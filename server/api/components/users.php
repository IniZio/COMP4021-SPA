<?php

switch ($requestObj["method"]) {
    case "POST":

        break;
    default:
        do_error(
            405,
            ERROR_HTTP_405);
        break;
}