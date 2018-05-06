<?php

if (count($path) == 3)
	switch ($method) {
	case "GET":
		$resources = do_sqlite3_prepared_statement(
			"SELECT (id, name, description, type, text_content) FROM Resources WHERE course_id=:course_id",
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
		break;
	case "PUT":
		break;
	case "DELETE":
		break;
	default:
		break;
	}

if ($path[4] === "file")
	switch ($method) {
	case "GET":
		break;
	case "POST":
		break;
	case "DELETE":
		break;
	default:
		break;
	}