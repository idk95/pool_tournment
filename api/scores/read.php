<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
  
include_once '../config/database.php';
include_once '../objects/scores.php';
  
$database = new Database();
$db = $database->getConnection();
  
$scores = new Scores($db);

$stmt = $scores->read();
$num = $stmt->rowCount();
  
if($num>0){
  
    $scores_arr=array();
    $scores_arr["records"]=array();
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
  
        $scores_item = array(
            "matches_id" => $matches_id,
            "people_id" => $people_id,
            "ball" => $ball
        );
  
        array_push($scores_arr["records"], $scores_item);
    }
  
    http_response_code(200);
    echo json_encode($scores_arr);
}
  
else{
    http_response_code(404);
    echo json_encode(array("message" => "No scores found."));
}
?>