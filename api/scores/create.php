<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
include_once '../config/database.php';
include_once '../objects/scores.php';
  
$database = new Database();
$db = $database->getConnection();
  
$scores = new Scores($db);

$data = json_decode(file_get_contents("php://input"));

$scores->matches_id = $data->match_id;
$scores->people_id = $data->people_id;
$scores->ball = $data->ball;

// create the scores
if($scores->create()){
    http_response_code(201);
    echo json_encode(array("message" => "Score was created."));
}

else{
    http_response_code(503);
    echo json_encode(array("message" => "Unable to create."));
}
?>