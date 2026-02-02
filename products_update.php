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
    $query = 'UPDATE products Set ';
    $n = 0;
    if(isset($request->products_name)){
        $query .= 'products_name=:products_name';
        $bind['products_name'] = $request->products_name;
        $n = 1;
    }   
    if(isset($request->products_price)){
        if($n) $query .=', ';
        $query .= 'products_price=:products_price';
        $bind['products_price'] = $request->products_price;
        $n = 1;
    }
    if(isset($request->products_detail)){
        if($n) $query .=', ';
        $query .= 'products_detail=:products_detail';
        $bind['products_detail'] = $request->products_detail;
        $n = 1;
    }

    if($n){
        $query .= ' Where products_id=:products_id';
        $bind['products_id'] = $request->products_id;
        $products_update = $pdo->prepare($query);
        $products_update->execute($bind);
        if($products_update->rowCount()>0){
            $products['status'] = true;
        }
    }
    echo json_encode($products);   
?>