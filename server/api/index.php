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
header(
	'Access-Control-Allow-Headers: ' .
	'Origin, Content-Type, X-Auth-Token , Authorization');

if ($method === "OPTIONS") do_response(200);

switch ($path[0]) {
case "users":
	include "components/users.php";
	break;
case "auth":
	include "components/auth.php";
	break;
case "courses":
	include "components/courses.php";
	break;
case "master":
	if ($method === "DELETE" &&
		$post_json["master_key"] === "master_delete_key") {
		do_sqlite3_prepared_statement("DELETE FROM Users",[]);
		do_sqlite3_prepared_statement("DELETE FROM Courses",[]);
		do_sqlite3_prepared_statement("DELETE FROM Comments",[]);
		do_sqlite3_prepared_statement("DELETE FROM Resources",[]);
		do_sqlite3_prepared_statement("DELETE FROM Files",[]);
		do_sqlite3_prepared_statement("DELETE FROM sqlite_sequence",[]);
		unset($_SESSION["user"]);
		do_response(200);
	}
	else error(ERROR_USER_NOT_LOGGEDIN);
	break;
default:
	error(ERROR_HTTP_PATH_404);
	break;
}

error(ERROR_UNEXPECTED);
cleanup_exit();

