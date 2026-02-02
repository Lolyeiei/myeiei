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
    $query = 'INSERT Into products(products_name, products_price, products_detail) Value (:products_name, :products_price, :products_detail)';
    $bind = array(
        ':products_name'=>$request->products_name,
        ':products_price'=>$request->products_price,
        ':products_detail'=>$request->products_detail);

    $products_insert = $pdo->prepare($query);
    $products_insert->execute($bind);
    if($products_insert->rowCount()>0){
        $products['status'] = true;
        $products['products_id'] = $pdo->lastInsertId();
    }
    echo json_encode($products);

?>