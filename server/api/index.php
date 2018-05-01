<?php

/*
 * How to add a new route?
 *
 * Add a php file under /components,
 * Add a case under switch, with value as the path,
 * include_once your php file in the case,
 * in your php file, you can use global $requestObj, $responseObj and $db.
 *
 * If you need anything other than json response, set $responseObj as null,
 * and then do your own output and content_type header.
 */

session_start();

include_once "./includes/utilities.php";
include_once "./includes/languages/en_US.php";

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

$db = new SQLite3("../data.db");

switch ($requestObj["path"][0]){
    case "users":
        include "components/users.php";
        break;
    case "auth":
        include "components/auth.php";
        break;
    case "courses":
        include "components/courses.php";
        break;
    default:
        do_error(
            404,
            ERROR_HTTP_PATH_404);
        break;
}

cleanup_exit();

