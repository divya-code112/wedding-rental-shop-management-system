<?php
session_start();
include "../includes/db.php";

if(!isset($_SESSION['user_id'])) header("Location: login.php");

$user_id = intval($_SESSION['user_id']);

// Fetch cart
$cart_q = mysqli_query($conn, "
    SELECT c.*, p.price_per_day, p.deposit_amount, p.max_rental_days
    FROM cart c
    JOIN products p ON p.product_id = c.product_id
    WHERE c.user_id=$user_id
");

if(mysqli_num_rows($cart_q) == 0) die("Cart is empty");

$total_rent = 0;
$total_deposit = 0;
$delivery_date = null;
$return_date = null;

while($item = mysqli_fetch_assoc($cart_q)){
    $total_rent += $item['price_per_day'] * $item['rental_days'];
    $total_deposit += $item['deposit_amount'];
    if(!$delivery_date) $delivery_date = $item['start_date'];
    if(!$return_date) {
        $dt = new DateTime($item['start_date']);
        $dt->modify("+{$item['rental_days']} days");
        $return_date = $dt->format('Y-m-d');
    }
}
// Get delivery & return dates from cart
$dateRow = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT 
        MIN(start_date) AS delivery_date,
        MAX(return_date) AS return_due_date
    FROM cart
    WHERE user_id = $user_id
"));

mysqli_query($conn, "
    INSERT INTO orders (
        user_id,
        delivery_date,
        return_due_date,
        total_rent_amount,
        total_deposit,
        advance_amount,
        order_status,
        payment_status,
        order_date
    ) VALUES (
        $user_id,
        '{$dateRow['delivery_date']}',
        '{$dateRow['return_due_date']}',
        $total_rent,
        $total_deposit,
        $total_deposit,
        'pending',
        'pending',
        NOW()
    )
");

$order_id = mysqli_insert_id($conn);

// Insert order items
mysqli_data_seek($cart_q, 0);
while($item = mysqli_fetch_assoc($cart_q)){
    $item_total = $item['price_per_day'] * $item['rental_days'];
    mysqli_query($conn, "
        INSERT INTO order_items (
            order_id, product_id, size, rental_days, price_per_day, deposit_amount, total_price, total_deposit
        ) VALUES (
            $order_id, {$item['product_id']}, '{$item['size']}', {$item['rental_days']},
            {$item['price_per_day']}, {$item['deposit_amount']}, $item_total, {$item['deposit_amount']}
        )
    ");
}

// Redirect to deposit payment page
header("Location: payment.php?order_id=$order_id");
exit();
