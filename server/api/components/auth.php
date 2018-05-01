<?php

switch ($requestObj["method"]) {
    case "POST":
        if (
            !is_null($requestObj["POST_JSONObj"]["username"]) &&
            !is_null($requestObj["POST_JSONObj"]["password"])
        ) {
            $sqlStmt = $db->prepare(
                "SELECT * FROM Users WHERE username=:username");
            $sqlStmt->bindValue(
                ":username",
                $requestObj["POST_JSONObj"]["username"]);
            $sqlResult = $sqlStmt->execute();

            $userEntry = $sqlResult->fetchArray(SQLITE3_ASSOC);

            $sqlResult->finalize();
            $sqlStmt->close();

            if (password_verify(
                $requestObj["POST_JSONObj"]["password"],
                $userEntry["hashed_password"])) {
                $_SESSION["user"] = $userEntry;
                do_response(200,$responseObj);
            }
            else
                do_error(
                    401,
                    PASSWORD_NOT_MATCH
                );

        } else {
            do_error(
                401,
                ERROR_PARAMETER_FAULT);
        }
        break;
    case "DELETE":
        $_SESSION["user"] = null;
        $responseObj ["success"] = true;
        http_response_code(200);
        break;
    default:
        do_error(
            404,
            ERROR_HTTP_METHOD_404);
        break;
}