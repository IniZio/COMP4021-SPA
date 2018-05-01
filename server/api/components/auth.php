<?php
/**
 * Created by PhpStorm.
 * User: signalng
 * Date: 5/1/18
 * Time: 2:28 PM
 */

switch ($requestObj["method"]){
    case "POST":
        if (
            !is_null($requestObj["POST_JSONObj"]["username"])&&
            !is_null($requestObj["POST_JSONObj"]["password"])
        ){
            $responseObj ["success"] = true;
            $responseObj ["token"] = "masterKey02135468910346501749";
            http_response_code(200);
            header('Content-Type: application/json');
        }
        else{
            $responseObj ["success"] = false;
            http_response_code(401);
            header('Content-Type: application/json');
        }
        break;
    case "DELETE":
        $responseObj ["success"] = true;
        http_response_code(200);
        header('Content-Type: application/json');
        break;
    default:
        http_response_code(404);
        header('Content-Type: application/json');
        exit();
        break;
}