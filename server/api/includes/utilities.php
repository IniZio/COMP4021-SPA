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

function do_sanitize_user_info($user_obj)
{
	unset($user_obj["hashed_password"]);
	unset($user_obj["picture_file_id"]);
	return $user_obj;
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

function error($error, $responseObj = [])
{
	if (!is_array($error) ||
		!is_integer($error[0]) ||
		!is_string($error[1])) {
		do_error(500, "");
	}
	do_error($error[0], $error[1], $responseObj);
}

function do_error($response_code, $error_message, $responseObj = [])
{
	if (is_array($responseObj)) {
		if (is_string($error_message))
			$responseObj["Error"] = $error_message;
		else
			$responseObj["Error"] = "Unknown error.";
	}
	do_response($response_code, $responseObj);
}

function do_sqlite3_prepared_statement(
	$statement, $values, $no_return = false, $returnError = false)
{
	global $db;
	$sqlStmt = $db->prepare($statement);
	foreach ($values as $value) {
		$sqlStmt->bindValue($value["param"], $value["value"], $value["type"]);
	}
	$sqlResult = $sqlStmt->execute();
	if ($sqlResult === false) {
		if ($returnError) {
			$ret = [$db->lastErrorCode(), $db->lastErrorMsg()];
			$sqlStmt->close();
			return $ret;
		}
		$sqlStmt->close();
		do_error(500, $db->lastErrorMsg());
	}
	$results = [];
	if (!$no_return) {
		$resultRow = null;
		while (
			($resultRow = $sqlResult->fetchArray(SQLITE3_ASSOC)) !==
			false) {
			array_push($results, $resultRow);
		}
	}
	else
		$results = null;
	$sqlResult->finalize();
	$sqlStmt->close();
	return $results;
}

function get_resource_by_id($id, $resource_name)
{
	if (is_integer($id) &&
		is_string($resource_name)) {
		$sqlRet = do_sqlite3_prepared_statement(
			"SELECT * FROM " . $resource_name . " WHERE id=:id",
			[
				[
					"param" => ":id",
					"value" => $id,
					"type" => SQLITE3_INTEGER,
				],
			]);
		if (!isset($sqlRet[0])) {
			error(ERROR_HTTP_RESOURCE_404);
		}
		return $sqlRet[0];
	}
	return null;
}

$method = $_SERVER['REQUEST_METHOD'];
$path = explode('/', trim($_SERVER['PATH_INFO'], '/'));
$post_json = json_decode(file_get_contents('php://input'), true);

define("WEBROOT", dirname(dirname(__DIR__)) . "/");
define("FILEDIR", WEBROOT . "files/");

$db = new SQLite3(WEBROOT . "data.db");

const accepted_image_mime = [
	"image/bmp",
	"image/gif",
	"image/jpeg",
	"image/png",
	"image/svg+xml",
];