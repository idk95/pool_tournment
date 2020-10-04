<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
  
// include database and object files
include_once '../config/database.php';
include_once '../objects/people.php';

$database = new Database();
$db = $database->getConnection();

$people = new People($db);

$people->id = isset($_GET['id']) ? $_GET['id'] : die();
$people->readOne();
  
if($people->name!=null){
    $result = array(
        "id" =>  $people->id,
        "name" => $people->name,
        "points" => $people->points
    );
    
    http_response_code(200);
    echo json_encode($result);
}
  
else{
    http_response_code(404);
    echo json_encode(array("message" => "People does not exist."));
}
?>