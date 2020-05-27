<?php
require "./functions/database.php";
require "./functions/functions.php";

$logged_in = false && check_login($db);

if (isset($_GET['a'])) {
  $action = $_GET['a'];
} else {
  $action = 'index';
}

switch ($action) {
  case "index":
    $page = "./components/home.php";
    break;
  default:
    $page = "./components/404.php";
    break;
}

require('functions/render.php');