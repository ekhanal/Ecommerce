<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:user_login.php');
};

if(isset($_POST['delete'])){
   $cart_id = $_POST['cart_id'];
   $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE id = ?");
   $delete_cart_item->execute([$cart_id]);
}

if(isset($_GET['delete_all'])){
   $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
   $delete_cart_item->execute([$user_id]);
   header('location:cart.php');
}

if(isset($_POST['update_qty'])){
   $cart_id = $_POST['cart_id'];
   $qty = $_POST['qty'];
   $qty = filter_var($qty, FILTER_SANITIZE_STRING);

   // Check if the requested quantity exceeds the available stock
   $select_cart_item = $conn->prepare("SELECT * FROM `cart` WHERE id = ?");
   $select_cart_item->execute([$cart_id]);
   if ($select_cart_item->rowCount() > 0) {
       $fetch_cart_item = $select_cart_item->fetch(PDO::FETCH_ASSOC);
       $product_id = $fetch_cart_item['pid'];
       $select_product = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
       $select_product->execute([$product_id]);
       if ($select_product->rowCount() > 0) {
           $product = $select_product->fetch(PDO::FETCH_ASSOC);
           if ($qty > $product['quantity']) {
               $message[] = 'Quantity out of stock. The available stock is ' . $product['quantity'];
           } else {
               $update_qty = $conn->prepare("UPDATE `cart` SET quantity = ? WHERE id = ?");
               $update_qty->execute([$qty, $cart_id]);
               $message[] = 'Cart quantity updated';
           }
       }
   }
}
if (isset($_POST['proceed_to_checkout'])) {
    $select_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
    $select_cart_items->execute([$user_id]);
    
    // Begin a database transaction
    $conn->beginTransaction();

    try {
        while ($cart_item = $select_cart_items->fetch(PDO::FETCH_ASSOC)) {
            $product_id = $cart_item['pid'];
            $quantity = $cart_item['quantity'];
            
            // Insert an order record for the user's purchase
            $insert_order = $conn->prepare("INSERT INTO `orders` (user_id, product_id, quantity) VALUES (?, ?, ?)");
            $insert_order->execute([$user_id, $product_id, $quantity]);
            
            // Deduct the purchased quantity from the product's stock
            $update_stock = $conn->prepare("UPDATE `products` SET quantity = quantity - ? WHERE id = ?");
            $update_stock->execute([$quantity, $product_id]);
        }
        
        // Delete all cart items for the user
        $delete_cart_items = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
        $delete_cart_items->execute([$user_id]);
        
        // Commit the transaction
        $conn->commit();
        
        $message[] = 'Order placed successfully!';
    } catch (Exception $e) {
        // Rollback the transaction in case of an error
        $conn->rollback();
        $message[] = 'An error occurred. Please try again later.';
    }
}


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping cart</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css?v=<?php echo Time()?>">

</head>

<body>

    <?php include 'components/user_header.php'; ?>

    <section class="products shopping-cart">

        <h3 class="heading">Shopping cart</h3>

        <div class="box-container">

            <?php
      $grand_total = 0;
      $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
      $select_cart->execute([$user_id]);
      if($select_cart->rowCount() > 0){
         while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
   ?>
            <form action="" method="post" class="box">
                <input type="hidden" name="cart_id" value="<?= $fetch_cart['id']; ?>">
                <a href="quick_view.php?pid=<?= $fetch_cart['pid']; ?>" class="fas fa-eye"></a>
                <img src="uploaded_img/<?= $fetch_cart['image']; ?>" alt="">
                <div class="name"><?= $fetch_cart['name']; ?></div>
                <div class="flex">
                    <div class="price">Rs <?= $fetch_cart['price']; ?>/-</div>
                    <input type="number" name="qty" class="qty" min="1" max="99"
                        onkeypress="if(this.value.length == 2) return false;" value="<?= $fetch_cart['quantity']; ?>">
                    <button type="submit" class="fas fa-edit" name="update_qty">Up</button>
                </div>
                <div class="sub-total"> Sub total :
                    <span>Rs <?= $sub_total = ($fetch_cart['price'] * $fetch_cart['quantity']); ?>/-</span>
                </div>
                <input type="submit" value="Delete item" onclick="return confirm('Delete this from cart?');"
                    class="delete-btn" name="delete">
            </form>
            <?php
   $grand_total += $sub_total;
      }
   }else{
      echo '<p class="empty">Your cart is empty</p>';
   }
   ?>
        </div>

        <div class="cart-total">
            <p>Grand total : <span>Rs <?= $grand_total; ?>/-</span></p>
            <a href="shop.php" class="option-btn">Continue shopping</a>
            <a href="cart.php?delete_all" class="delete-btn <?= ($grand_total > 1)?'':'disabled'; ?>"
                onclick="return confirm('Delete all from cart?');">Delete all item</a>
            <a href="checkout.php" class="btn <?= ($grand_total > 1)?'':'disabled'; ?>">
            <button type="submit" name="submit" class="btn btn-primary">PROCCED TO CHEKOUT</button>
            </a>
        </div>

    </section>













    <?php include 'components/footer.php'; ?>

    <script src="js/script.js"></script>

</body>

</html>