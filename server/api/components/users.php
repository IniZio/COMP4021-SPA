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
				do_sqlite3_prepared_statement("
								INSERT INTO Users 
									(username, hashed_password) 
								VALUES 
									(:username, :hashed_password);",
					[
						array(
							"param" => ":username",
							"value" => $post_json["username"],
							"type" => SQLITE3_TEXT),
						array(
							"param" => ":hashed_password",
							"value" => password_hash($post_json["password"], PASSWORD_DEFAULT),
							"type" => SQLITE3_TEXT
						)
					],
					true);

				$userEntries = do_sqlite3_prepared_statement("
								SELECT id 
                    			FROM Users 
                    			WHERE username=:username;",
					[
						array(
							"param" => ":username",
							"value" => $post_json["username"],
							"type" => SQLITE3_TEXT)
					]);
				$responseObj = array();
				$responseObj["id"] = $userEntries[0]["id"];

				do_response(200, $responseObj);
			} else
				error(ERROR_PARAMETER_FAULT);
			break;
		// GET /users
		case "GET":
			do_check_auth();
			do_response(
				200,
				$_SESSION["user"]);
			break;
		// PUT /users
		case "PUT":
			break;
		default:
			error(ERROR_HTTP_METHOD_NOT_ALLOWED);
			break;
	}

$user_id = (int)$path[1];
if (count($path) == 2)
	switch ($method) {
		// PUT /users/{id}
		case "PUT":
			do_check_auth();
			if ($user_id == $_SESSION["user"]["id"]) {
				do_sqlite3_prepared_statement("
								UPDATE Users 
								SET 
									first_name=:first_name,
									last_name=:last_name,
									email=:email, 
									hashed_password=:hashed_password,
									major=:major,
									year=:year, 
									status=:status 
								WHERE id=:id;",
					[
						array(
							"param" => ":first_name",
							"value" => $post_json["first_name"],
							"type" => SQLITE3_TEXT),
						array(
							"param" => ":last_name",
							"value" => $post_json["last_name"],
							"type" => SQLITE3_TEXT),
						array(
							"param" => ":email",
							"value" => $post_json["email"],
							"type" => SQLITE3_TEXT),
						array(
							"param" => ":hashed_password",
							"value" => password_hash($post_json["password"], PASSWORD_DEFAULT),
							"type" => SQLITE3_TEXT
						),
						array(
							"param" => ":major",
							"value" => $post_json["major"],
							"type" => SQLITE3_TEXT),
						array(
							"param" => ":year",
							"value" => $post_json["year"],
							"type" => SQLITE3_INTEGER),
						array(
							"param" => ":status",
							"value" => $post_json["status"],
							"type" => SQLITE3_TEXT),
						array(
							"param" => ":id",
							"value" => $user_id,
							"type" => SQLITE3_INTEGER),
					],
					true
				);
				$_SESSION["user"] = do_sqlite3_prepared_statement(
					"SELECT * FROM Users WHERE id=:id",
					[array(
						"param" => ":id",
						"value" => $user_id,
						"type" => SQLITE3_INTEGER)]
				)[0];
				do_response(200);
			} else
				error(ERROR_USER_NOT_MATCH);
			break;
		// GET /users/{id}
		case "GET":
			do_check_auth();
			if ($user_id == $_SESSION["user"]["id"])
				do_response(
					200,
					$_SESSION["user"]);
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
			if ($_SESSION["user"]["picture_file_id"] === null) {
				error(ERROR_HTTP_FILE_404);
			}
			$fileEntries = do_sqlite3_prepared_statement(
				"SELECT file_name, content_type FROM Files WHERE id=:id",
				[
					array(
						"param" => ":id",
						"value" => $_SESSION["user"]["picture_file_id"],
						"type" => SQLITE3_TEXT
					)
				]
			);
			if (count($fileEntries) < 1) {
				error(ERROR_HTTP_FILE_404);
			}
			if (file_exists(FILEDIR . $fileEntries[0]["file_name"])) {
				header('Content-Type: ' . $fileEntries[0]["content_type"]);
				readfile(FILEDIR . $fileEntries[0]["file_name"]);
			} else
				error(ERROR_HTTP_FILE_404);
			exit();
			break;
		case "POST":
			error(ERROR_NOT_IMPLEMENTED);
			break;
		default:
			error(ERROR_HTTP_METHOD_NOT_ALLOWED);
			break;
	}
}