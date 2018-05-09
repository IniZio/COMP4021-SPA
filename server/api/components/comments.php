<?php

if (count($path) == 3)
	switch ($method) {
	case "GET":
		do_check_auth();
		$resources = do_sqlite3_prepared_statement(
			"
			SELECT 
				id, 
				author_user_id, 
				content, 
				created_timestamp, 
				course_id
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
		if (!is_string($post_json["content"])) {
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
				last_edited_timestamp,
				course_id)
			VALUES (
				:author_user_id,
				:content,
				:created_timestamp,
				:last_edited_timestamp,
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
					"value" => $created_timestamp,
					"type" => SQLITE3_INTEGER,
				],
				[
					"param" => ":last_edited_timestamp",
					"value" => $created_timestamp,
					"type" => SQLITE3_INTEGER,
				],
				[
					"param" => ":course_id",
					"value" => $course_id,
					"type" => SQLITE3_INTEGER,
				],
			],
			true);
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
	case "PUT":
		do_check_auth();
		unset($comment["id"]);
		unset($comment["author_user_id"]);
		unset($comment["created_timestamp"]);
		unset($comment["course_id"]);

		if (is_string($post_json["content"])) {
			$comment["last_edited_timestamp"] = time();
			$comment["content"] = $post_json["content"];
			do_sqlite3_prepared_statement(
				"
			UPDATE Comments 
			SET 
				content=:content, 
				last_edited_timestamp=:last_edited_timestamp
			WHERE id=:id",
				[
					[
						"param" => ":content",
						"value" => $comment["content"],
						"type" => SQLITE3_TEXT,
					],
					[
						"param" => ":last_edited_timestamp",
						"value" => $comment["last_edited_timestamp"],
						"type" => SQLITE3_INTEGER,
					],
					[
						"param" => ":id",
						"value" => $comment_id,
						"type" => SQLITE3_INTEGER,
					],
				],
				true);
		}
		do_response(200);
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
			],
			true
		);
		do_response(200);
		break;
	default:
		error(ERROR_HTTP_METHOD_NOT_ALLOWED);
		break;
	}
