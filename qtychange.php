<?php
include "connection.php";

if (isset($_POST["id"]) && isset($_POST["qty"])) {
    $id = $_POST["id"];
    $qty = $_POST["qty"];

    // Validate input to ensure it's an integer greater than or equal to zero
    if (!is_numeric($qty) || $qty < 0) {
        echo "Error: Invalid quantity.";
        exit();
    }

    
    $cart = Database::Search("SELECT * FROM `cart` WHERE `id` = '" . $id . "'");
    $cartnum = $cart->num_rows;

    if ($cartnum > 0) {
        $cartdata = $cart->fetch_assoc();

        $batch = Database::Search("SELECT * FROM `batch` WHERE `batch_code` = '" . $cartdata["batch_batch_code"] . "'");
        $batch_data = $batch->fetch_assoc();
        if ($qty > $batch_data["batch_qty"]) {
            echo "Error: Not enough stock available. Maximum available: " . $batch_data["batch_qty"];
        } elseif ($qty == 0) {
            echo "Error: Quantity cannot be zero.";
        } else {
            Database::IUD("UPDATE `cart` SET `qty` = '" . $qty . "' WHERE `id` = '" . $id . "'");
            echo "UPDATED";
        }
    } else {
        echo "Error: Cart item not found.";
    }
} else {
    echo "Error: Missing required parameters.";
}
?>
