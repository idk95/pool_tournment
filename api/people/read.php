<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
  
include_once '../config/database.php';
include_once '../objects/people.php';
  
$database = new Database();
$db = $database->getConnection();
  
$people = new People($db);

$stmt = $people->read();
$num = $stmt->rowCount();
  
if($num>0){
  
    $people_arr=array();
    $people_arr["records"]=array();
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
  
        $people_item = array(
            "id" => $id,
            "name" => $name,
            "points" => $points,
            "balls_left" => $balls_left
        );
  
        array_push($people_arr["records"], $people_item);
    }
  
    http_response_code(200);
    echo json_encode($people_arr);
}
  
else{
    http_response_code(404);
    echo json_encode(array("message" => "No people found."));
}
?>