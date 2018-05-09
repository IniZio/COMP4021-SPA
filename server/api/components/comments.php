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
				last_edited_timestamp,
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
				picture_url,
				created_timestamp,
				last_edited_timestamp,
				course_id)
			VALUES (
				:author_user_id,
				:content,
				:picture_url,
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
					"param" => ":picture_url",
					"value" => $post_json["picture_url"],
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
		$comments = do_sqlite3_prepared_statement(
			"
					SELECT * FROM Comments WHERE created_timestamp=:ts AND content=:content",
			[
				[
					"param" => ":created_timestamp",
					"value" => $created_timestamp,
					"type" => SQLITE3_INTEGER,
				],
				[
					"param" => ":content",
					"value" => $post_json["content"],
					"type" => SQLITE3_TEXT,
				],
			]);
		if (count($comments) > 0)
			do_response(201, $comments[0]);
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
			$comment["picture_url"] = $post_json["picture_url"];
			do_sqlite3_prepared_statement(
				"
			UPDATE Comments 
			SET 
				content=:content, 
				last_edited_timestamp=:last_edited_timestamp,
				picture_url=:picture_url
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
						"param" => ":picture_url",
						"value" => $comment["picture_url"],
						"type" => SQLITE3_TEXT,
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


if (count($path) == 5 &&
	$path[4] === "picture") {
	switch ($method) {
	case "GET":
		do_check_auth();
		if ($comment["picture_file_id"] === null) {
			error(ERROR_HTTP_FILE_404);
		}
		$fileEntries = do_sqlite3_prepared_statement(
			"SELECT file_name, content_type FROM Files WHERE id=:id",
			[
				[
					"param" => ":id",
					"value" => $comment["picture_file_id"],
					"type" => SQLITE3_TEXT,
				],
			]
		);
		if (count($fileEntries) < 1) {
			error(ERROR_HTTP_FILE_404);
		}
		if (file_exists(FILEDIR . $fileEntries[0]["file_name"])) {
			header('Content-Type: ' . $fileEntries[0]["content_type"]);
			readfile(FILEDIR . $fileEntries[0]["file_name"]);
		}
		else
			error(ERROR_HTTP_FILE_404);
		exit();
		break;
	case "POST":
		do_check_auth();
		if (!isset($_FILES["file"])) {
			error(
				ERROR_PARAMETER_FAULT,
				["file" => "Post a picture with a form, with file input " .
					"field id 'file'."]);
		}
		if ($_FILES["file"]["error"] !== UPLOAD_ERR_OK) {
			error(ERROR_UPLOAD_FILE_FAILED);
		}
		if (!in_array($_FILES["file"]["type"], accepted_image_mime)) {
			error(
				ERROR_PARAMETER_FAULT,
				["file" => "Image format not accepted."]);
		}
		$file_name = "";
		do {
			$file_name = date("ymdHis") . $_FILES["file"]["name"];
			$sqlRet = do_sqlite3_prepared_statement(
				"
				INSERT INTO Files (file_name, content_type)
				VALUES (:file_name, :content_type)",
				[
					[
						"param" => ":file_name",
						"value" => $file_name,
						"type" => SQLITE3_TEXT,
					],
					[
						"param" => ":content_type",
						"value" => $_FILES["file"]["type"],
						"type" => SQLITE3_TEXT,
					],
				],
				true, true);
		} while ($sqlRet !== null);
		if (!move_uploaded_file(
			$_FILES["file"]["tmp_name"], FILEDIR . $file_name)) {
			error(ERROR_UPLOAD_FILE_FAILED);
		}
		$sqlRet = do_sqlite3_prepared_statement(
			"SELECT id FROM Files WHERE file_name=:file_name",
			[
				[
					"param" => ":file_name",
					"value" => $file_name,
					"type" => SQLITE3_TEXT,
				],
			]
		);
		if (!isset($sqlRet[0]["id"])) {
			error(ERROR_UNEXPECTED);
		}
		do_sqlite3_prepared_statement(
			"
			UPDATE Comments
			SET picture_file_id=:pic_id
			WHERE id=:comment_id",
			[
				[
					"param" => ":pic_id",
					"value" => $sqlRet[0]["id"],
					"type" => SQLITE3_INTEGER,
				],
				[
					"param" => ":comment_id",
					"value" => $comment_id,
					"type" => SQLITE3_INTEGER,
				],
			],
			true
		);
		$_SESSION["user"]["picture_file_id"] = $sqlRet[0]["id"];
		do_response(201);
		break;
	case "DELETE":
		do_check_auth();
		$sqlRet = do_sqlite3_prepared_statement(
			"SELECT id,file_name FROM Files WHERE id=:pic_id",
			[
				[
					"param" => ":pic_id",
					"value" => $_SESSION["user"]["picture_file_id"],
					"type" => SQLITE3_INTEGER,
				],
			]
		);
		if (!isset($sqlRet[0])) {
			error(ERROR_UNEXPECTED);
		}
		$_SESSION["user"]["picture_file_id"] = null;
		do_sqlite3_prepared_statement(
			"UPDATE Comments SET picture_file_id=null WHERE id=:id",
			[
				[
					"param" => ":id",
					"value" => $comment_id,
					"type" => SQLITE3_INTEGER,
				],
			],
			true
		);
		do_sqlite3_prepared_statement(
			"DELETE FROM Files WHERE id=:pic_id",
			[
				[
					"param" => ":pic_id",
					"value" => $sqlRet[0]["id"],
					"type" => SQLITE3_INTEGER,
				],
			],
			true
		);
		unlink(FILEDIR . $sqlRet[0]["file_name"]);
		do_response(200);
		break;
	default:
		error(ERROR_HTTP_METHOD_NOT_ALLOWED);
		break;
	}
}