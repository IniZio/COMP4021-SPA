<?php

if (count($path) == 1)
	switch ($method) {
		case "GET":
			break;
		case "POST":
			break;
		default:
			error(ERROR_HTTP_METHOD_NOT_ALLOWED);
			break;
	}

$course_id = $path[1];

if (count($path) == 2)
	switch ($method) {
		case "GET":
			break;
		case "PUT":
			break;
		case "DELETE":
			break;
		default:
			error(ERROR_HTTP_METHOD_NOT_ALLOWED);
			break;
	}

switch ($path[2]){
	case "resources":
		include "resources.php";
		break;
	case "comments":
		include "comments.php";
		break;
	default:
		error(ERROR_HTTP_PATH_404);
		break;
}