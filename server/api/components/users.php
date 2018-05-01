<?php

if (count($requestObj["path"]) == 1)
    switch ($requestObj["method"]) {
        // POST /users
        case "POST":

            break;
        // GET /users
        case "GET":

            break;
        default:
            do_error(
                405,
                ERROR_HTTP_405);
            break;
    }
else {
//TODO: Subpath parsing
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
