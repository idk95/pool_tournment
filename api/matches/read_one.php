<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

include_once '../config/database.php';
include_once '../objects/matches.php';

$database = new Database();
$db = $database->getConnection();

$matches = new Matches($db);
  
$data = json_decode(file_get_contents("php://input"));
$matches->id = $data->id;

if($row = $matches->readOne()){
    extract($row);
    $matches_arr = array(
        "id" => $id,
        "solids_id" => $solids_id,
        "solids_name" => $solids_name,
        "stripes_id" => $stripes_id,
        "stripes_name" => $stripes_name,
        "date" => $date,
        "winner" => $winner,
        "absencent" => $absencent,
        "solids_left" => $solids_left,
        "stripes_left" => $stripes_left,
        "stripes_points" => $stripes_points,
        "solids_points" => $solids_points
    );

    http_response_code(200);
    echo json_encode($matches_arr);
}
  
else{
    http_response_code(404);
    echo json_encode(array("message" => "Matches does not exist."));
}
?>