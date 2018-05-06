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
			]);
		do_response(201);
		break;
	default:
		break;
	}

$resource_id = $path[3];
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
					"value" => $post_json["name"],
					"type" => SQLITE3_TEXT,
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
			]);
		do_response(200);
		break;
	case "DELETE":
		do_check_auth();
		break;
	default:
		break;
	}

if ($path[4] === "file")
	switch ($method) {
	case "GET":
		do_check_auth();
		break;
	case "POST":
		do_check_auth();
		break;
	case "DELETE":
		do_check_auth();
		break;
	default:
		break;
	}