<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Content-Type: application/json; charset=UTF-8");
require_once 'connect.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$products_id = $request->products_id ?? null;

    try {
        $query = 'SELECT * FROM `products` WHERE 1=1';
        $bind = [];        
        if (!empty($products_id)) {
            $query .= " AND products_id = :products_id";
            $bind[':products_id'] = $products_id;
        }  
        $query .= " ORDER BY products_id DESC";        
        $sites_query = $pdo->prepare($query);
        $sites_query->execute($bind);        
        $sites = [];
        
        if (!empty($products_id)) {
            $sites = $sites_query->fetch(PDO::FETCH_ASSOC);
        } else {
            $sites = $sites_query->fetchAll(PDO::FETCH_ASSOC);
        }        
        echo json_encode($sites);        
    } catch (PDOException $e) {
        // Log error สำหรับ debugging
        error_log("Database error: " . $e->getMessage());
        echo json_encode(["error" => true, "message" => "Database connection failed"]);
        exit();
    }

?>