<?php
/**
 * Created by PhpStorm.
 * User: signalng
 * Date: 5/1/18
 * Time: 5:27 PM
 */

const ERROR_PASSWORD_NOT_MATCH = [401, "Password not match."];
const ERROR_PARAMETER_FAULT = [400, "Parameters not correct."];
const ERROR_HTTP_METHOD_NOT_ALLOWED = [405, "Method not allowed."];
const ERROR_HTTP_PATH_404 = [404, "Path does not exist."];
const ERROR_HTTP_FILE_404 = [404, "File does not exist."];
const ERROR_HTTP_USER_404 = [404, "User does not exist."];
const ERROR_USER_ALREADY_EXIST = [400, "User already exist."];
const ERROR_HTTP_RESOURCE_404 = [404, "Resource requested does not exist."];
const ERROR_UNEXPECTED = [404, "Unexpected error."];
const ERROR_USER_NOT_LOGGEDIN = [401, "User not logged in."];
const ERROR_USER_NOT_MATCH = [401, "User not match."];
const ERROR_CAPTCHA_NOT_PASSED = [401, "Captcha not passed."];
const ERROR_USER_LOGGEDIN = [400, "User logged in."];
const ERROR_SQL_FAIL = [500, "SQL Error!"];
const ERROR_NOT_IMPLEMENTED = [500, "Feature not implemented yet!"];
const ERROR_UPLOAD_FILE_FAILED = [500, "Upload file failed."];