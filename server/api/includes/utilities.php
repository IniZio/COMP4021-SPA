<?php

function cleanup_exit()
{
    global $db;
    $db->close();
    exit();
}

function is_authed()
{
    return !is_null($_SESSION["user"]);
}

function do_check_auth()
{
    if (!is_authed()) {
        error(ERROR_USER_NOT_LOGGEDIN);
    }
}

function do_response($response_code, $responseObj = null)
{
    http_response_code($response_code);
    if (is_array($responseObj)) {
        header('Content-Type: application/json');
        echo json_encode($responseObj);
    }
    cleanup_exit();
}

function error($error)
{
    if (!is_array($error) ||
        !is_integer($error[0]) ||
        !is_string($error[1])) {
        do_error(500, "");
    }
    do_error($error[0], $error[1]);
}

function do_error($response_code, $error_message, $responseObj = array())
{
    if (is_array($responseObj)) {
        if (is_string($error_message))
            $responseObj["Error"] = $error_message;
        else
            $responseObj["Error"] = "Unknown error.";
    }
    do_response($response_code, $responseObj);
}

function do_sqlite3_prepared_statement($statement, $values, $no_return = false)
{
    global $db;
    $sqlStmt = $db->prepare($statement);
    foreach ($values as $value) {
        $sqlStmt->bindValue($value["param"], $value["value"], $value["type"]);
    }
    $sqlResult = $sqlStmt->execute();
    if ($sqlResult === false) {
        do_error(500, $db->lastErrorMsg());
    }
    $results = array();
    if (!$no_return) {
        $resultRow = null;
        while (($resultRow = $sqlResult->fetchArray(SQLITE3_ASSOC)) !== false) {
            array_push($results, $resultRow);
        }
    } else
        $results = null;
    $sqlResult->finalize();
    $sqlStmt->close();
    return $results;
}


$method = $_SERVER['REQUEST_METHOD'];
$path = explode('/', trim($_SERVER['PATH_INFO'], '/'));
$post_json = json_decode(file_get_contents('php://input'), true);

$db = new SQLite3("./data.db");

define("WEBROOT", dirname(dirname(__DIR__))."/");
define("FILEDIR", WEBROOT."files/");