<?php

if (count($path) == 3)
	switch ($method) {
	case "GET":
		do_check_auth();
		$resources = do_sqlite3_prepared_statement(
			"
			SELECT (
				id, 
				name, 
				course_id, 
				description, 
				type, 
				text_content) 
			FROM Resources 
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
			!is_string($post_json["name"])) {
			error(ERROR_PARAMETER_FAULT);
		}
		do_sqlite3_prepared_statement(
			"
			INSERT INTO Resources (
				name, 
				course_id, 
				description, 
				type, 
				file_id, 
				text_content)
			VALUES (
				:name, 
				:course_id, 
				:description, 
				:type, 
				null, 
				:text_content)",
			[
				[
					"param" => ":name",
					"value" => $post_json["name"],
					"type" => SQLITE3_TEXT,
				],
				[
					"param" => ":course_id",
					"value" => $post_json["course_id"],
					"type" => SQLITE3_INTEGER,
				],
				[
					"param" => ":description",
					"value" => $post_json["description"],
					"type" => SQLITE3_TEXT,
				],
				[
					"param" => ":type",
					"value" => $post_json["type"],
					"type" => SQLITE3_TEXT,
				],
				[
					"param" => ":text_content",
					"value" => $post_json["text_content"],
					"type" => SQLITE3_TEXT,
				],
			],
			true);
		do_response(201);
		break;
	default:
		error(ERROR_HTTP_METHOD_NOT_ALLOWED);
		break;
	}

$resource_id = (int)$path[3];
$resource = get_resource_by_id($resource_id, "Resources");

if (count($path) == 4)
	switch ($method) {
	case "GET":
		do_check_auth();
		unset($resource["file_id"]);
		do_response(200, $resource);
		break;
	case "PUT":
		do_check_auth();
		unset($resource["id"]);
		unset($resource["course_id"]);
		unset($resource["file_id"]);
		if (is_string($post_json["name"])) {
			$resource["name"] = $post_json["name"];
		}
		if (is_string($post_json["description"])) {
			$resource["description"] = $post_json["description"];
		}
		if (is_string($post_json["type"])) {
			$resource["type"] = $post_json["type"];
		}
		if (is_string($post_json["text_content"])) {
			$resource["text_content"] = $post_json["text_content"];
		}

		do_sqlite3_prepared_statement(
			"
			UPDATE Resources 
			SET 
				name=:name, 
				description=:description, 
				type=:type, 
				text_content=:text_content 
			WHERE id=:id",
			[
				[
					"param" => ":name",
					"value" => $resource["name"],
					"type" => SQLITE3_TEXT,
				],
				[
					"param" => ":description",
					"value" => $resource["description"],
					"type" => SQLITE3_TEXT,
				],
				[
					"param" => ":type",
					"value" => $resource["type"],
					"type" => SQLITE3_TEXT,
				],
				[
					"param" => ":text_content",
					"value" => $resource["text_content"],
					"type" => SQLITE3_TEXT,
				],
				[
					"param" => ":id",
					"value" => $resource_id,
					"type" => SQLITE3_INTEGER,
				],
			],
			true);
		do_response(200);
		break;
	case "DELETE":
		do_check_auth();
		do_sqlite3_prepared_statement(
			"DELETE FROM Resources WHERE id=:id",
			[
				[
					"param" => ":id",
					"value" => $resource_id,
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

if ($path[4] === "file")
	switch ($method) {
	case "GET":
		do_check_auth();
		if ($resource["file_id"] === null) {
			error(ERROR_HTTP_FILE_404);
		}
		$fileEntries = do_sqlite3_prepared_statement(
			"SELECT file_name, content_type FROM Files WHERE id=:id",
			[
				[
					"param" => ":id",
					"value" => $resource["file_id"],
					"type" => SQLITE3_INTEGER,
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
				["file" => "Post a file with a form, with file input " .
					"field id 'file'."]);
		}
		if ($_FILES["file"]["error"] !== UPLOAD_ERR_OK) {
			error(ERROR_UPLOAD_FILE_FAILED);
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
			UPDATE Resources 
			SET file_id=:file_id 
			WHERE id=:resource_id",
			[
				[
					"param" => ":file_id",
					"value" => $sqlRet[0]["id"],
					"type" => SQLITE3_INTEGER,
				],
				[
					"param" => ":resource_id",
					"value" => $resource_id,
					"type" => SQLITE3_INTEGER,
				],
			],
			true
		);
		do_response(201);
		break;
	case "DELETE":
		do_check_auth();
		$sqlRet = do_sqlite3_prepared_statement(
			"SELECT id,file_name FROM Files WHERE id=:file_id",
			[
				[
					"param" => ":file_id",
					"value" => $resource["file_id"],
					"type" => SQLITE3_INTEGER,
				],
			]
		);
		if (!isset($sqlRet[0])) {
			error(ERROR_UNEXPECTED);
		}
		do_sqlite3_prepared_statement(
			"UPDATE Resources SET file_id=null WHERE id=:id",
			[
				[
					"param" => ":id",
					"value" => $resource_id,
					"type" => SQLITE3_INTEGER,
				],
			],
			true
		);
		do_sqlite3_prepared_statement(
			"DELETE FROM Files WHERE id=:file_id",
			[
				[
					"param" => ":file_id",
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