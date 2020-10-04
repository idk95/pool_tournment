<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
include_once '../config/database.php';
include_once '../objects/matches.php';
  
$database = new Database();
$db = $database->getConnection();
  
$matches = new Matches($db);
  
$data = json_decode(file_get_contents("php://input"));
  
$matches->solids_id = $data->person_1;
$matches->stripes_id = $data->person_2;
$matches->date = $data->date;

if($matches->create()){
    http_response_code(201);
    echo json_encode(array("message" => "Match was created."));
}

else{
    http_response_code(503);
    echo json_encode(array("message" => "Unable to create matches."));
}
?>