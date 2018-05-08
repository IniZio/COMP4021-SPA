<?php

if (count($path) == 3)
	switch ($method) {
	case "GET":
		do_check_auth();
		$resources = do_sqlite3_prepared_statement(
			"
			SELECT (
				id, 
				author_user_id, 
				content, 
				created_timestamp, 
				course_id) 
			FROM Comments 
			WHERE course_id=:course_id",
			[
				[
					"param" => ":course_id",
					"value" => $course_id,
					"type" => SQLITE3_INTEGER,
				],
			]
		);
		do_response(200, $resources);
		break;
	case "POST":
		do_check_auth();
		if (
			!is_integer($post_json["course_id"]) &&
			!is_string($post_json["content"])) {
			error(ERROR_PARAMETER_FAULT);
		}
		$created_timestamp = time();
		$author_user_id = $_SESSION["user"]["id"];
		do_sqlite3_prepared_statement(
			"
			INSERT INTO Comments (
				author_user_id, 
				content, 
				created_timestamp, 
				course_id)
			VALUES (
				:author_user_id, 
				:content, 
				:created_timestamp, 
				:course_id)",
			[
				[
					"param" => ":author_user_id",
					"value" => $author_user_id,
					"type" => SQLITE3_INTEGER,
				],
				[
					"param" => ":content",
					"value" => $post_json["content"],
					"type" => SQLITE3_TEXT,
				],
				[
					"param" => ":created_timestamp",
					"value" => $post_json["created_timestamp"],
					"type" => SQLITE3_INTEGER,
				],
				[
					"param" => ":course_id",
					"value" => $post_json["course_id"],
					"type" => SQLITE3_INTEGER,
				],
			]);
		do_response(201);
		break;
	default:
		error(ERROR_HTTP_METHOD_NOT_ALLOWED);
		break;
	}

$comment_id = (int)$path[3];
$comment = get_resource_by_id($comment_id, "Comments");

if (count($path) == 4)
	switch ($method) {
	case "GET":
		do_check_auth();
		do_response(200, $comment);
		break;
	case "DELETE":
		do_check_auth();
		do_sqlite3_prepared_statement(
			"DELETE FROM Comments WHERE id=:id",
			[
				[
					"param" => ":id",
					"value" => $comment_id,
					"type" => SQLITE3_INTEGER,
				],
			]
		);
		do_response(200);
		break;
	default:
		error(ERROR_HTTP_METHOD_NOT_ALLOWED);
		break;
	}