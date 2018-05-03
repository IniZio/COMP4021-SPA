<?php

if (count($path) == 1)
    switch ($method) {
        // POST /users
        case "POST":
            if (is_string($post_json["username"]) &&
                is_string($post_json["password"])) {
                do_sqlite3_prepared_statement(
                    "INSERT INTO Users (username, hashed_password) VALUES (:username, :hashed_password);",
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

                $userEntries = do_sqlite3_prepared_statement(
                    "SELECT id FROM Users where username=:username;",
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
        default:
            error(ERROR_HTTP_METHOD_NOT_ALLOWED);
            break;
    }

$user_id = $path[1];
switch ($method) {
    // PUT /users/{id}
    case "PUT":

        break;
    // GET /users/{id}
    case "GET":

        break;
    default:
        error(ERROR_HTTP_METHOD_NOT_ALLOWED);
        break;
}

