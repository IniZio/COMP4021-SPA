<?php

if (count($requestObj["path"]) == 1)
    switch ($requestObj["method"]) {
        // POST /users
        case "POST":

            break;
        // GET /users
        case "GET":
            if (is_null($_SESSION["user"]))
                do_error(
                    401,
                    ERROR_USER_NOT_LOGGEDIN);
            else
                do_response(
                    200,
                    $_SESSION["user"]);
            break;
        default:
            do_error(
                405,
                ERROR_HTTP_405);
            break;
    }
else {
    $user_id = $requestObj["path"][1];
    switch ($requestObj["method"]) {
        // PUT /users/{id}
        case "PUT":

            break;
        // GET /users/{id}
        case "GET":

            break;
        default:
            do_error(
                405,
                ERROR_HTTP_405);
            break;
    }
}
