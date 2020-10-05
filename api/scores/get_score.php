<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
  
// include database and object files
include_once '../config/database.php';
include_once '../objects/scores.php';

$database = new Database();
$db = $database->getConnection();

$scores = new Scores($db);

$scores->matches_id = $_GET['matches_id'];

$stmt = $scores->getScore();
$num = $stmt->rowCount();
  
if($num>0){
  
    $scores_arr=array();
    $scores_arr["records"]=array();
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
  
        $score_item = array(
            "matches_id" =>  $matches_id,
            "people_id" => $people_id,
            "ball" => $ball,
            "name" => $name
        );
  
        array_push($scores_arr["records"], $score_item);
    }
  
    http_response_code(200);
    echo json_encode($scores_arr);
}

else{
    http_response_code(404);
    echo json_encode(array("message" => "Scores does not exist."));
}
?>