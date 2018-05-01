<?php
session_start();

header('Access-Control-Allow-Origin: *'); 
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');

$requestObj = array();
$requestObj["method"] = $_SERVER['REQUEST_METHOD'];
$requestObj["path"] = explode('/', trim($_SERVER['PATH_INFO'],'/'));
$requestObj["POST_JSONObj"] = json_decode(file_get_contents('php://input'),true);
$requestObj["GET_Params"] = $_GET;
$requestObj["SESSION_Params"] = $_SESSION;

$responseObj = array();

switch ($requestObj["path"][0]){
    case "users":
        include_once "components/users.php";
        break;
    case "auth":
        include_once "components/auth.php";
        break;
    case "courses":
        include_once "components/courses.php";
        break;
    default:
        http_response_code(404);
        header('Content-Type: application/json');
        exit();
        break;
}

echo json_encode($responseObj);

