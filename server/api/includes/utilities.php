<?php

function cleanup_exit(){
    global $db;
    $db->close();
    exit();
}

function do_response($response_code, $responseObj)
{
    http_response_code($response_code);
    if (is_array($responseObj)){
        header('Content-Type: application/json');
        echo json_encode($responseObj);
    }
    cleanup_exit();
}

function do_error($response_code, $error_message, $responseObj = array()){
    if (is_array($responseObj)){
        if (is_string($error_message))
            $responseObj["Error"] = $error_message;
        else
            $responseObj["Error"] = "Unknown error.";
    }
    do_response($response_code, $responseObj);
}