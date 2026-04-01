<?php
session_start();
include 'db_connect.php';
if(!isset($_SESSION['user_id'])) exit(json_encode(['status'=>'error','message'=>'Not logged in']));
$user_id=$_SESSION['user_id'];
$post_id=intval($_POST['post_id']);
$stmt=$conn->prepare("DELETE FROM posts WHERE post_id=? AND user_id=?");
$stmt->bind_param("ii",$post_id,$user_id);
$stmt->execute();
echo json_encode(['status'=>'success']);
?>