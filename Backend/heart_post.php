<?php
session_start();
include 'db_connect.php';
if(!isset($_SESSION['user_id'])) exit(json_encode(['status'=>'error','message'=>'Not logged in']));
$user_id = $_SESSION['user_id'];
$post_id = intval($_POST['post_id']);
if($post_id<=0) exit(json_encode(['status'=>'error','message'=>'Invalid post']));

$stmt=$conn->prepare("SELECT heart_id FROM post_hearts WHERE post_id=? AND user_id=?");
$stmt->bind_param("ii",$post_id,$user_id);
$stmt->execute();
$res=$stmt->get_result();
if($res->num_rows>0){
    $stmt_del=$conn->prepare("DELETE FROM post_hearts WHERE post_id=? AND user_id=?");
    $stmt_del->bind_param("ii",$post_id,$user_id);
    $stmt_del->execute();
    $action='unliked';
}else{
    $stmt_ins=$conn->prepare("INSERT INTO post_hearts (post_id,user_id) VALUES (?,?)");
    $stmt_ins->bind_param("ii",$post_id,$user_id);
    $stmt_ins->execute();
    $action='liked';
}
$stmt_count=$conn->prepare("SELECT COUNT(*) as total FROM post_hearts WHERE post_id=?");
$stmt_count->bind_param("i",$post_id);
$stmt_count->execute();
$total_hearts=$stmt_count->get_result()->fetch_assoc()['total'];
echo json_encode(['status'=>'success','action'=>$action,'total_hearts'=>$total_hearts]);
?>