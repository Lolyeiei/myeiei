<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Content-Type: application/json; charset=UTF-8");
require_once 'connect.php';
$postdata = file_get_contents("php://input");
@$request = json_decode($postdata);
@$ApiKey = $request->ApiKey;
    $bind = array();
    $products = array('status'=>false);
    $query = 'DELETE From products Where ';
    $n = 0;
    if(isset($request->products_id)){
        $query .= 'products_id=:products_id';
        $bind['products_id'] = $request->products_id;
        $n = 1;
    }
    $products_delete = $pdo->prepare($query);
    $products_delete->execute($bind);
    if($products_delete->rowCount()>0){
        $products['status'] = true;
    }
    echo json_encode($products);   
?>