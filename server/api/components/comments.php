<?php

if (count($path) == 3)
	switch ($method) {
	case "GET":
		break;
	case "POST":
		break;
	default:
		break;
	}

$comment_id = $path[3];
$comment = get_resource_by_id($comment_id, "Comments");

if (count($path) == 4)
	switch ($method) {
	case "GET":
		break;
	case "PUT":
		break;
	case "DELETE":
		break;
	default:
		break;
	}