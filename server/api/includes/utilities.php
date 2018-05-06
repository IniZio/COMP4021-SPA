<?php

function cleanup_exit()
{
	global $db;
	$db->close();
	exit();
}

function is_authed()
{
	return isset($_SESSION["user"]);
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

$db = null;
if (file_exists(WEBROOT . "api/data.db"))
	$db = new SQLite3(WEBROOT . "api/data.db");
else {
	$db = new SQLite3(WEBROOT . "api/data.db");
	$db->exec(
		"
CREATE TABLE Comments
(
  id                INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  author_user_id    INTEGER                           NOT NULL,
  content           TEXT                              NOT NULL,
  created_timestamp INTEGER                           NOT NULL,
  course_id         INTEGER                           NOT NULL
);
CREATE UNIQUE INDEX Comments_id_uindex
  ON Comments (id);
CREATE INDEX Comments_author_user_id_index
  ON Comments (author_user_id);
CREATE INDEX Comments_course_id_index
  ON Comments (course_id);



CREATE TABLE Courses
(
  id         INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  name       TEXT                              NOT NULL,
  descrption TEXT
);
CREATE UNIQUE INDEX Courses_id_uindex
  ON Courses (id);
CREATE UNIQUE INDEX Courses_name_uindex
  ON Courses (name);



CREATE TABLE Files
(
  id           INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  file_name    TEXT                              NOT NULL,
  content_type TEXT                              NOT NULL
);
CREATE UNIQUE INDEX Files_id_uindex
  ON Files (id);
CREATE UNIQUE INDEX Files_file_name_uindex
  ON Files (file_name);



CREATE TABLE Resources
(
  id           INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  name         TEXT                              NOT NULL,
  course_id    INTEGER                           NOT NULL,
  description  TEXT,
  type         TEXT                              NOT NULL,
  file_id      INTEGER,
  text_content TEXT
);
CREATE UNIQUE INDEX Resources_id_uindex
  ON Resources (id);
CREATE INDEX Resources_name_index
  ON Resources (name);
CREATE INDEX Resources_course_id_index
  ON Resources (course_id);
CREATE INDEX Resources_type_index
  ON Resources (type);



CREATE TABLE Users
(
  id              INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  username        TEXT                              NOT NULL,
  hashed_password TEXT                              NOT NULL,
  picture_file_id INTEGER,
  first_name      TEXT,
  last_name       TEXT,
  email           TEXT,
  major           TEXT,
  year            INTEGER,
  status          TEXT
);
CREATE UNIQUE INDEX Users_Auth_id_uindex
  ON Users (id);
CREATE UNIQUE INDEX Users_Auth_username_uindex
  ON Users (username);
CREATE UNIQUE INDEX Users_email_uindex
  ON Users (email);
CREATE INDEX Users_major_index
  ON Users (major);
CREATE INDEX Users_year_index
  ON Users (year);
CREATE INDEX Users_status_index
  ON Users (status);"
	);
}

const accepted_image_mime = [
	"image/bmp",
	"image/gif",
	"image/jpeg",
	"image/png",
	"image/svg+xml",
];