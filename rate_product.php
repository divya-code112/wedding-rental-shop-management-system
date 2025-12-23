<?php
session_start();
include 'db.php';

if(!isset($_SESSION['user_id'])){
 echo json_encode(['success'=>false,'message'=>'Login required']);
 exit;
}

$userId=$_SESSION['user_id'];
$productId=intval($_POST['product_id']);
$rating=intval($_POST['rating']);

if($rating<1 || $rating>5){
 echo json_encode(['success'=>false,'message'=>'Invalid rating']);
 exit;
}

/* check existing */
$check=$conn->prepare("SELECT feedback_id FROM feedback WHERE user_id=? AND product_id=?");
$check->bind_param("ii",$userId,$productId);
$check->execute();
$res=$check->get_result();

if($res->num_rows){
 $q=$conn->prepare("UPDATE feedback SET rating=?,feedback_date=NOW() WHERE user_id=? AND product_id=?");
 $q->bind_param("iii",$rating,$userId,$productId);
}else{
 $q=$conn->prepare("INSERT INTO feedback(user_id,product_id,rating) VALUES(?,?,?)");
 $q->bind_param("iii",$userId,$productId,$rating);
}
$q->execute();

/* update avg */
$avg=$conn->prepare("
UPDATE products SET rating=(
 SELECT ROUND(AVG(rating),2) FROM feedback WHERE product_id=?
) WHERE product_id=?");
$avg->bind_param("ii",$productId,$productId);
$avg->execute();

echo json_encode(['success'=>true]);
