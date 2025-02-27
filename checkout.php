<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:user_login.php');
};

if(isset($_POST['order'])){
    $hasOutOfStockProduct = false;

    foreach (json_decode($_POST["cart_data"]) as $data) {
        $product = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
        $product->execute([$data->pid]);
        $productData = $product->fetch(PDO::FETCH_ASSOC);
        if (count($productData) && $data->quantity > $productData["quantity"]) {
            $hasOutOfStockProduct = true;

            $message[] = 'Out of stock!';
        } else {
            $remainingQuantity = $productData["quantity"] - $data->quantity;
            $updateData = $conn->prepare("UPDATE `products` SET quantity = ? WHERE id = ?");
            $updateData->execute([$remainingQuantity, $productData["id"]]);
        }
    }

    if (!$hasOutOfStockProduct) {
        $name = $_POST['name'];
        $name = filter_var($name, FILTER_SANITIZE_STRING);
        $number = $_POST['number'];
        $number = filter_var($number, FILTER_SANITIZE_STRING);
        $email = $_POST['email'];
        $email = filter_var($email, FILTER_SANITIZE_STRING);
        $method = $_POST['method'];
        $method = filter_var($method, FILTER_SANITIZE_STRING);
        $address =  $_POST['flat'] .', '. $_POST['street'] .', '. $_POST['city'] .', '. $_POST['state'];
        $address = filter_var($address, FILTER_SANITIZE_STRING);
        $total_products = $_POST['total_products'];
        $total_price = $_POST['total_price'];
     
        $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
        $check_cart->execute([$user_id]);
     
        if($check_cart->rowCount() > 0){
     
           $insert_order = $conn->prepare("INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price) VALUES(?,?,?,?,?,?,?,?)");
           $insert_order->execute([$user_id, $name, $number, $email, $method, $address, $total_products, $total_price]);
     
           $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
           $delete_cart->execute([$user_id]);
     
           $message[] = 'Order placed successfully!';
        }else{
           $message[] = 'Your cart is empty';
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css?v=<?php echo Time()?>">

</head>

<body>

    <?php include 'components/user_header.php'; ?>

    <section class="checkout-orders">

        <form action="" method="POST">

            <h3>Your orders</h3>

            <div class="display-orders">
                <?php
         $grand_total = 0;
         $cart_items[] = '';
         $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
         $select_cart->execute([$user_id]);
         $cart_data = [];
         if($select_cart->rowCount() > 0){
            while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
                $cart_data[] = [
                    "pid" => $fetch_cart["pid"],
                    "quantity" => $fetch_cart["quantity"],
                ];
               $cart_items[] = $fetch_cart['name'].' ('.$fetch_cart['price'].' x '. $fetch_cart['quantity'].') - ';
               $total_products = implode($cart_items);
               $grand_total += ($fetch_cart['price'] * $fetch_cart['quantity']);
      ?>
                <p> <?= $fetch_cart['name']; ?>
                    <span>(<?= 'RS'.$fetch_cart['price'].'/- x '. $fetch_cart['quantity']; ?>)</span>
                </p>
                <?php
            }
         }else{
            echo '<p class="empty">Your cart is empty!</p>';
         }
      ?>
                <input type="hidden" name="cart_data" value="<?php echo htmlentities(json_encode($cart_data)); ?>">
                <input type="hidden" name="total_products" value="<?= $total_products; ?>">
                <input type="hidden" name="total_price" value="<?= $grand_total; ?>" value="">
                <div class="grand-total">Grand total : <span>Rs <?= $grand_total; ?>/-</span></div>
            </div>

            <h3>Place your orders</h3>

            <div class="flex">
                <div class="inputBox">
                    <span>Name :</span>
                    <input type="text" name="name" placeholder="Name" class="box" maxlength="20" required>
                </div>
                <div class="inputBox">
                    <span>Phone no. :</span>
                    <input type="number" name="number" placeholder="Number" class="box" min="0" max="9999999999"
                        onkeypress="if(this.value.length == 10) return false;" required>
                </div>
                <div class="inputBox">
                    <span>Email :</span>
                    <input type="email" name="email" placeholder="Email" class="box" maxlength="50" required>
                </div>
                <div class="inputBox">
                    <span>Payment method :</span>
                    <select name="method" class="box" required>
                        <option value="cash on delivery">Cash on delivery</option>
                        <option value="credit card">Credit card</option>
                        <option value="esewa">Esewa</option>
                        <option value="khalti">Khalti</option>
                    </select>
                </div>
                <div class="inputBox">
                    <span>State:</span>
                    <select name="state" class="box" required>
                        <option value="">Select State</option>
                        <option value="Koshi">Koshi</option>
                        <option value="Madhes">Madhes</option>
                        <option value="Bagmati">Bagmati</option>
                        <option value="Gandaki">Gandaki</option>
                        <option value="Lumbini">Lumbini</option>
                        <option value="Karnali">Karnali</option>
                        <option value="Sudurpachim">Sudurpachim</option>
                    </select>
                </div>
                <div class="inputBox">
                    <span>City :</span>
                    <input type="text" name="city" placeholder="e.g. kathmandu" class="box" maxlength="50" required>
                </div>
                <div class="inputBox">
                    <span>Address line 01 :</span>
                    <input type="text" name="flat" placeholder="e.g.Tole" class="box" maxlength="50" required>
                </div>
                <div class="inputBox">
                    <span>Address line 02 :</span>
                    <input type="text" name="street" placeholder="e.g. Area" class="box" maxlength="50" required>
                </div>



                <!-- <div class="inputBox">
            <span>country :</span>
            <input type="text" name="country" placeholder="e.g. India" class="box" maxlength="50" required>
         </div> -->
                <!-- <div class="inputBox">
            <span>pin code :</span>
            <input type="number" min="0" name="pin_code" placeholder="e.g. 123456" min="0" max="999999" onkeypress="if(this.value.length == 6) return false;" class="box" required>
         </div> -->
            </div>

            <input type="submit" name="order" class="btn <?= ($grand_total > 1)?'':'disabled'; ?>" value="Place order">

        </form>

    </section>













    <?php include 'components/footer.php'; ?>

    <script src="js/script.js"></script>

</body>

</html>