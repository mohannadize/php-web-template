<?php

include 'functions/functions.php';
include 'functions/database.php';

$logged_in = false && check_login($db);

$json = [
    "status" => 0
];

if (empty($_POST))  $_POST = json_decode(file_get_contents("php://input"), true);
if ($_SERVER['REQUEST_METHOD'] != "POST" || !$logged_in || !isset($_POST['action'])) {
    $json['message'] = "Permission denied";
} else {
    switch ($_POST['action']) {
        case 'sample_action':
            $json = sample_function();
            break;
        default:
            header("location: .");
            break;
    }
}

header("content-type: application/json");
echo json_encode($json);
