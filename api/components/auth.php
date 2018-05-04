<?php

switch ($method) {
    case "POST":
        if (is_authed())
            error(ERROR_USER_LOGGEDIN);
        if (
            is_string($post_json["username"]) &&
            is_string($post_json["password"])
        ) {
            $userEntry = do_sqlite3_prepared_statement("
							SELECT * 
							FROM Users 
							WHERE username=:username",
                [array(
                    "param" => ":username",
                    "value" => $post_json["username"],
                    "type" => SQLITE3_TEXT)]
            )[0];

            if (password_verify(
                    $post_json["password"],
                    $userEntry["hashed_password"])) {
                $_SESSION["user"] = $userEntry;
                do_response(200, null);
            } else{
                error(ERROR_PASSWORD_NOT_MATCH);
            }

        } else {
            error(ERROR_PARAMETER_FAULT);
        }
        break;
    case "DELETE":
        do_check_auth();
        $_SESSION["user"] = null;
        do_response(200, null);
        break;
    default:
        error(ERROR_HTTP_METHOD_NOT_ALLOWED);
        break;
}