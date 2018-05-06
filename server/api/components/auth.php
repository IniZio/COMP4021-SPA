<?php

switch ($method) {
case "POST":
	if (is_authed())
		error(ERROR_USER_LOGGEDIN);
	if (
		is_string($post_json["username"]) &&
		is_string($post_json["password"])
	) {
		$userEntries = do_sqlite3_prepared_statement(
			"
			SELECT * 
			FROM Users 
			WHERE username=:username",
			[[
				"param" => ":username",
				"value" => $post_json["username"],
				"type" => SQLITE3_TEXT]]
		);

		if (!isset($userEntries[0])) {
			error(ERROR_HTTP_USER_404);
		}

		$userEntry = $userEntries[0];

		if (password_verify(
			$post_json["password"],
			$userEntry["hashed_password"])) {
			$_SESSION["user"] = $userEntry;
			do_response(201);
		}
		else {
			error(ERROR_PASSWORD_NOT_MATCH);
		}

	}
	else {
		error(ERROR_PARAMETER_FAULT);
	}
	break;
case "DELETE":
	do_check_auth();
	$_SESSION["user"] = null;
	do_response(200, null);
	break;
case "PUT":
	if (is_authed())
		error(ERROR_USER_LOGGEDIN);
	if (
		is_string($post_json["username"]) &&
		is_string($post_json["password"]) &&
		is_string($post_json["reset_key"]) &&
		$post_json["reset_key"] === "master_reset_passwd"
	) {
		do_sqlite3_prepared_statement(
			"
			UPDATE Users 
			SET hashed_password=:hashed_password 
			WHERE username=:username",
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
			]
		);
		do_response(200);
	}
	else
		error(ERROR_PASSWORD_NOT_MATCH);
	break;
default:
	error(ERROR_HTTP_METHOD_NOT_ALLOWED);
	break;
}