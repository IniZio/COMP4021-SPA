<?php

if (count($path) == 1)
	switch ($method) {
		// POST /users
	case "POST":
		if (is_authed()) {
			error(ERROR_USER_LOGGEDIN);
		}
		if (is_string($post_json["username"]) &&
			is_string($post_json["password"])) {
			if (!is_string($post_json["first_name"]))
				unset($post_json["first_name"]);
			if (!is_string($post_json["last_name"]))
				unset($post_json["last_name"]);
			$sqlRet = do_sqlite3_prepared_statement(
				"
				INSERT INTO Users
					(username, hashed_password, first_name, last_name)
				VALUES
					(:username, :hashed_password, :first_name, :last_name);",
				[
					[
						"param" => ":username",
						"value" => $post_json["username"],
						"type" => SQLITE3_TEXT,
					],
					[
						"param" => ":hashed_password",
						"value" => password_hash(
							$post_json["password"], PASSWORD_DEFAULT),
						"type" => SQLITE3_TEXT,
					],
					[
						"param" => ":first_name",
						"value" => $post_json["first_name"],
						"type" => SQLITE3_TEXT,
					],
					[
						"param" => ":last_name",
						"value" => $post_json["last_name"],
						"type" => SQLITE3_TEXT,
					],
				],
				true,
				true);
			if ($sqlRet[0] != 0) {
				switch ($sqlRet[0]) {
				case 19:
					error(ERROR_USER_ALREADY_EXIST);
					break;
				default:
					error(ERROR_UNEXPECTED);
					break;
				}
			}

			$userEntries = do_sqlite3_prepared_statement(
				"
				SELECT *
				FROM Users
				WHERE username=:username;",
				[
					[
						"param" => ":username",
						"value" => $post_json["username"],
						"type" => SQLITE3_TEXT],
				]);
			$userEntry = $userEntries[0];
			unset($userEntry["hashed_password"]);

			$_SESSION["user"] = $userEntry;
			$responseObj = [];
			$responseObj["user"] = $userEntry;
			$responseObj["id"] = $userEntries[0]["id"];

			do_response(201, $responseObj);
		}
		else
			error(ERROR_PARAMETER_FAULT);
		break;
		// GET /users
	case "GET":
		do_check_auth();
		do_response(
			200,
			do_sanitize_user_info($_SESSION["user"]));
		break;
	default:
		error(ERROR_HTTP_METHOD_NOT_ALLOWED);
		break;
	}

$user_id = (int)$path[1];
$user = get_resource_by_id($user_id, "Users");

if (count($path) == 2)
	switch ($method) {
		// PUT /users/{id}
	case "PUT":
		do_check_auth();

		if ($user_id == $_SESSION["user"]["id"]) {
			$post_json["first_name"] =
				is_string($post_json["first_name"]) ?
					$post_json["first_name"] :
					$user["first_name"];

			$post_json["last_name"] =
				is_string($post_json["last_name"]) ?
					$post_json["last_name"] :
					$user["last_name"];

			$post_json["email"] =
				is_string($post_json["email"]) ?
					$post_json["email"] :
					$user["email"];

			$post_json["major"] =
				is_string($post_json["major"]) ?
					$post_json["major"] :
					$user["major"];

			$post_json["year"] =
				is_string($post_json["year"]) ?
					$post_json["year"] :
					$user["year"];

			$post_json["status"] =
				is_string($post_json["status"]) ?
					$post_json["status"] :
					$user["status"];


			do_sqlite3_prepared_statement(
				"
				UPDATE Users
				SET
					first_name=:first_name,
					last_name=:last_name,
					email=:email,
					major=:major,
					year=:year,
					status=:status
				WHERE id=:id;",
				[
					[
						"param" => ":first_name",
						"value" => $post_json["first_name"],
						"type" => SQLITE3_TEXT,
					],
					[
						"param" => ":last_name",
						"value" => $post_json["last_name"],
						"type" => SQLITE3_TEXT,
					],
					[
						"param" => ":email",
						"value" => $post_json["email"],
						"type" => SQLITE3_TEXT,
					],
					[
						"param" => ":major",
						"value" => $post_json["major"],
						"type" => SQLITE3_TEXT,
					],
					[
						"param" => ":year",
						"value" => $post_json["year"],
						"type" => SQLITE3_TEXT,
					],
					[
						"param" => ":status",
						"value" => $post_json["status"],
						"type" => SQLITE3_TEXT,
					],
					[
						"param" => ":id",
						"value" => $user_id,
						"type" => SQLITE3_INTEGER,
					],
				],
				true
			);
			if (isset($post_json["password"]) && is_string(
					$post_json["password"])) {
				do_sqlite3_prepared_statement(
					"
					UPDATE Users
					SET hashed_password=:hashed_password
					WHERE id=:id",
					[
						[
							"param" => ":hashed_password",
							"value" => password_hash(
								$post_json["password"], PASSWORD_DEFAULT),
							"type" => SQLITE3_TEXT,
						],
						[
							"param" => ":id",
							"value" => $user_id,
							"type" => SQLITE3_INTEGER,
						],
					],
					true);
			}
			$userEntry = do_sqlite3_prepared_statement(
				"SELECT * FROM Users WHERE id=:id",
				[[
					"param" => ":id",
					"value" => $user_id,
					"type" => SQLITE3_INTEGER]]
			)[0];
			unset($userEntry["hashed_password"]);

			$_SESSION["user"] = $userEntry;
			$responseObj = [];
			$responseObj["user"] = $userEntry;
			do_response(200, $responseObj);
		}
		else
			error(ERROR_USER_NOT_MATCH);
		break;
		// GET /users/{id}
	case "GET":
		do_check_auth();
		if ($user_id == $_SESSION["user"]["id"]) {
			$responseObj = [];
			$responseObj["user"] = do_sanitize_user_info($_SESSION["user"]);
			do_response(200, $responseObj);
		}
		else
			error(ERROR_USER_NOT_MATCH);
		break;
	default:
		error(ERROR_HTTP_METHOD_NOT_ALLOWED);
		break;
	}


if (count($path) == 3 &&
	$path[2] === "picture") {
	switch ($method) {
	case "GET":
		do_check_auth();
		if ($_SESSION["user"]["picture_file_id"] === null) {
			error(ERROR_HTTP_FILE_404);
		}
		$fileEntries = do_sqlite3_prepared_statement(
			"SELECT file_name, content_type FROM Files WHERE id=:id",
			[
				[
					"param" => ":id",
					"value" => $_SESSION["user"]["picture_file_id"],
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
			UPDATE Users
			SET picture_file_id=:pic_id
			WHERE id=:user_id",
			[
				[
					"param" => ":pic_id",
					"value" => $sqlRet[0]["id"],
					"type" => SQLITE3_INTEGER,
				],
				[
					"param" => ":user_id",
					"value" => $_SESSION["user"]["id"],
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
			"UPDATE Users SET picture_file_id=null WHERE id=:id",
			[
				[
					"param" => ":id",
					"value" => $_SESSION["user"]["id"],
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
