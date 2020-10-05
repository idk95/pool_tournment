<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// include database and object files
include_once '../config/database.php';
include_once '../objects/matches.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare matches object
$matches = new Matches($db);
  
// get id of matches to be edited
$data = json_decode(file_get_contents("php://input"));
  
// set ID property of matches to be edited
$matches->id = $data->id;
  
// set matches property values
$matches->stripes_id = $data->stripes_id;
$matches->solids_id = $data->solids_id;
$matches->winner = $data->winner;
$matches->absencent = $data->absencent;
  
// update the matches
if($matches->update()){
  
    // set response code - 200 ok
    http_response_code(200);
  
    // tell the user
    echo json_encode(array("message" => "Matches was updated."));
}
  
// if unable to update the matches, tell the user
else{
  
    // set response code - 503 service unavailable
    http_response_code(503);
  
    // tell the user
    echo json_encode(array("message" => "Unable to update matches."));
}
?>