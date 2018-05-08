<?php

if (count($path) == 1)
	switch ($method) {
	case "GET":
		do_check_auth();
		if (!isset($_GET["search"]) || !is_string($_GET["search"])) {
			$_GET["search"] = "%";
		}
		else
			$_GET["search"] = "%" . str_replace("%", "[%]", $_GET["search"]) . "%";
		if (!isset($_GET["sortby"]) || !is_string($_GET["sortby"]))
			$_GET["sortby"] = "code";
		if (!isset($_GET["pagesize"]) || !is_numeric($_GET["pagesize"]))
			$_GET["pagesize"] = "5";
		if (!isset($_GET["page"]) || !is_numeric($_GET["page"]))
			$_GET["page"] = "0";

		$sqlRet = do_sqlite3_prepared_statement(
			"
			SELECT * 
			FROM Courses 
			WHERE 
				(name LIKE :search) OR 
				(description LIKE :search) OR
				(code LIKE :search) OR 
				(summary LIKE :search) OR 
				(professor LIKE :search)
			ORDER BY :sortby ASC LIMIT :pagesize OFFSET :skip",
			[
				[
					"param" => ":search",
					"value" => $_GET["search"],
					"type" => SQLITE3_TEXT,
				],
				[
					"param" => ":sortby",
					"value" => $_GET["sortby"],
					"type" => SQLITE3_TEXT,
				],
				[
					"param" => ":pagesize",
					"value" => (int)$_GET["pagesize"],
					"type" => SQLITE3_INTEGER,
				],
				[
					"param" => ":skip",
					"value" => ((int)$_GET["page"]) * ((int)$_GET["pagesize"]),
					"type" => SQLITE3_INTEGER,
				],
			]);
		do_response(200, $sqlRet);
		break;
	case "POST":
		do_check_auth();
		if (is_string($post_json["name"])) {
			if (!is_string($post_json["description"])) {
				$post_json["description"] = null;
			}
			if (!is_string($post_json["code"])) {
				$post_json["code"] = null;
			}
			if (!is_string($post_json["summary"])) {
				$post_json["summary"] = null;
			}
			if (!is_string($post_json["professor"])) {
				$post_json["professor"] = null;
			}
			do_sqlite3_prepared_statement(
				"
				INSERT INTO Courses (name, description, code, summary, professor) 
				VALUES (:name, :description, :code, :summary, :professor)",
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
						"param" => ":code",
						"value" => $post_json["code"],
						"type" => SQLITE3_TEXT,
					],
					[
						"param" => ":summary",
						"value" => $post_json["summary"],
						"type" => SQLITE3_TEXT,
					],
					[
						"param" => ":professor",
						"value" => $post_json["professor"],
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


$course_id = (int)$path[1];
$course = get_resource_by_id($course_id, "Courses");

if (count($path) === 2)
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
		if (!is_string($post_json["code"])) {
			$post_json["code"] = null;
		}
		if (!is_string($post_json["summary"])) {
			$post_json["summary"] = null;
		}
		if (!is_string($post_json["professor"])) {
			$post_json["professor"] = null;
		}
		do_sqlite3_prepared_statement(
			"
			UPDATE Courses 
			SET 
				name=:name, 
				description=:description,
				code=:code,
				summary=:summary,
				professor=:professor 
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
					"param" => ":code",
					"value" => $post_json["code"],
					"type" => SQLITE3_TEXT,
				],
				[
					"param" => ":summary",
					"value" => $post_json["summary"],
					"type" => SQLITE3_TEXT,
				],
				[
					"param" => ":professor",
					"value" => $post_json["professor"],
					"type" => SQLITE3_TEXT,
				],
				[
					"param" => ":id",
					"value" => $course_id,
					"type" => SQLITE3_INTEGER,
				],
			],
			true
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
			],
			true);
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