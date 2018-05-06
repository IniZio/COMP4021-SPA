<?php

if (count($path) == 1)
	switch ($method) {
	case "GET":
		do_check_auth();
		$sqlRet = do_sqlite3_prepared_statement(
			"SELECT * FROM Courses ORDER BY name ASC ",
			[]);
		do_response(200, $sqlRet);
		break;
	case "POST":
		do_check_auth();
		if (is_string($post_json["name"])) {
			if (!is_string($post_json["description"])) {
				$post_json["description"] = null;
			}
			do_sqlite3_prepared_statement(
				"
				INSERT INTO Courses (name, descrption) 
				VALUES (:name,:description)",
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
				],
				true);
			$sqlRet = do_sqlite3_prepared_statement(
				"SELECT id FROM Courses WHERE name=:name",
				[
					[
						"param" => ":name",
						"value" => $post_json["name"],
						"type" => SQLITE3_TEXT,
					],
				]
			);
			if (!isset($sqlRet[0]["id"])) {
				error(ERROR_UNEXPECTED);
			}
			do_response(201, ["id" => $sqlRet[0]["id"]]);
		}
		else error(ERROR_PARAMETER_FAULT);
		break;
	default:
		error(ERROR_HTTP_METHOD_NOT_ALLOWED);
		break;
	}

$course_id = $path[1];
$course = get_resource_by_id($course_id, "Courses");

if (count($path) == 2)
	switch ($method) {
	case "GET":
		do_check_auth();
		do_response(200, $course);
		break;
	case "PUT":
		do_check_auth();
		if (!is_string($post_json["name"])) {
			$post_json["name"] = $course["name"];
		}
		if (!is_string($post_json["description"])) {
			$post_json["description"] = $course["description"];
		}
		do_sqlite3_prepared_statement(
			"
			UPDATE Courses 
			SET name=:name, descrption=:description 
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
					"param" => ":id",
					"value" => $course_id,
					"type" => SQLITE3_INTEGER,
				],
			]
		);
		do_response(200);
		break;
	case "DELETE":
		do_check_auth();
		do_sqlite3_prepared_statement(
			"DELETE FROM Courses WHERE id=:id",
			[
				[
					"param" => ":id",
					"value" => $course_id,
					"type" => SQLITE3_INTEGER,
				],
			]);
		do_response(200);
		break;
	default:
		error(ERROR_HTTP_METHOD_NOT_ALLOWED);
		break;
	}

switch ($path[2]) {
case "resources":
	include "resources.php";
	break;
case "comments":
	include "comments.php";
	break;
default:
	error(ERROR_HTTP_PATH_404);
	break;
}