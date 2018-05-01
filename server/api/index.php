<?php
header('Access-Control-Allow-Origin: *'); 
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');
header('Content-Type: application/json');
$requestObj = array();
$requestObj["method"] = $_SERVER['REQUEST_METHOD'];
$requestObj["path"] = explode('/', trim($_SERVER['PATH_INFO'],'/'));
$requestObj["POST_JSONObj"] = json_decode(file_get_contents('php://input'),true);
$requestObj["GET_Params"] = $_GET;
$requestObj["SESSION_Params"] = $_SESSION;
echo json_encode($requestObj);
?>
