<?php

include 'functions/functions.php';
include 'functions/database.php';

$logged_in = false && check_login($db);

$json = [
    'status' => 0
];


if (empty($_POST))  $_POST = json_decode(file_get_contents("php://input"), true);
if ($_SERVER['REQUEST_METHOD'] != "POST" || !$logged_in || !isset($_POST['r'])) {
    $json['message'] = "Permission denied";
} else {

    switch ($_POST['r']) {
        case "sample_request":
            $json['status'] = 1;
            $json['message'] = 'success!';
            break;
        default:
            $json['message'] = 'invalid request';
            break;
    }
}

header("content-type: application/json");
echo json_encode($json);
