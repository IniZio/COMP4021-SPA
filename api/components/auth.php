<?php

switch ($requestObj["method"]) {
    case "POST":
        if (
            is_string($requestObj["POST_JSONObj"]["username"]) &&
            is_string($requestObj["POST_JSONObj"]["password"])
        ) {
            $userEntry = do_sqlite3_prepared_statement(
                "SELECT * FROM Users WHERE username=:username",
                [array(
                    "param" => ":username",
                    "value" => $requestObj["POST_JSONObj"]["username"],
                    "type" => SQLITE3_TEXT)]
            )[0];

            if (password_verify(
                    $requestObj["POST_JSONObj"]["password"],
                    $userEntry["hashed_password"])) {
                $_SESSION["user"] = $userEntry;
                do_response(200, null);
            } else{
                do_error(
                    401,
                    ERROR_PASSWORD_NOT_MATCH
                );
            }

        } else {
            do_error(
                400,
                ERROR_PARAMETER_FAULT);
        }
        break;
    case "DELETE":
        if (is_null($_SESSION["user"])) {
            do_error(
                401,
                ERROR_USER_NOT_LOGGEDIN
            );
        }
        $_SESSION["user"] = null;
        do_response(200, null);
        break;
    default:
        do_error(
            405,
            ERROR_HTTP_405);
        break;
}