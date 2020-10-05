<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
  
// include database and object files
include_once '../config/core.php';
include_once '../config/database.php';
include_once '../objects/matches.php';
  
// instantiate database and matches object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$matches = new Matches($db);
  
// get keywords
$keywords=isset($_GET["s"]) ? $_GET["s"] : "";
  
$stmt = $matches->search($keywords);
$num = $stmt->rowCount();
  
if($num>0){

    $matches_arr=array();
    $matches_arr["records"]=array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
  
        $matches_item=array(
            "id" => $id,
            "name" => $name,
            "description" => html_entity_decode($description),
            "price" => $price,
            "category_id" => $category_id,
            "category_name" => $category_name
        );
  
        array_push($matches_arr["records"], $matches_item);
    }
  
    // set response code - 200 OK
    http_response_code(200);
    echo json_encode($matches_arr);
}
  
else{
    http_response_code(404);
    echo json_encode(array("message" => "No matches found."));
}
?>