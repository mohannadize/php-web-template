<?php
session_start();
$GLOBALS['title'] = 'project_title';

// ------------------------GENERAL----------------------------

function bytes_to_human($bytes) {
    $mapping = ["B","KB","MB","GB","TB"];
    $counter = 0;
    while ((+$bytes / 1024) > 1) {
        $bytes = +$bytes / 1024;
        $counter++;
    }
    $bytes = round($bytes,2);
    return "$bytes $mapping[$counter]";
}

function generateRandomString($length = 6, $letters = '1234567890QWERTYUOPASDFGHJKLZXCVBNM1234567890qwertyuiopasdfghjkzxcvbnm')
{
    $s = '';
    $lettersLength = strlen($letters) - 1;

    for ($i = 0; $i < $length; $i++) {
        $s .= $letters[rand(0, $lettersLength)];
    }

    return $s;
}

// ------------------------Login----------------------------


function check_login($db)
{
    $title = $GLOBALS['title'];
    if (!isset($_SESSION[$title])) return false;
    $session = $_SESSION[$title];
    if (isset($session['loggedin']) && $session['loggedin'] == true) {
        if (isset($session['LAST_ACTIVITY']) && (time() - $session['LAST_ACTIVITY'] > 60000)) {
            session_unset();
            session_destroy();
            return false;
        } else {
            $_SESSION[$title]['LAST_ACTIVITY'] = time();
            return $session;
        }
    }
    return false;
}

function login_user($title, $data, $db)
{
    $username = trim($db->escape_value($data['username']));
    preg_replace("/[^A-Za-z0-9-_]/", "", $username);
    $password = trim($data['password']);

    $result = $db->query("SELECT u.`name`, u.`id`,u.`username`,u.`password`,u.`role`,u.`store`,r.`admin` from users u INNER JOIN roles r on u.`role` = r.`id` WHERE (email='$username' OR username='$username') AND `deactivated`='0'");

    if ($db->num_rows($result)) {
        $result = $db->fetch_array($result);

        if ($password == $result['password']) {
            $_SESSION[$title]['loggedin'] = true;
            $_SESSION[$title]['id'] = $result['id'];
            $_SESSION[$title]['name'] = $result['name'];
            $_SESSION[$title]['username'] = $result['username'];
            $_SESSION[$title]['role'] = $result['role'];
            $_SESSION[$title]['admin'] = (int) $result['admin'];
            $_SESSION[$title]['store'] = $result['store'];

            return true;
        } else {
            return false;
        };
    } else {
        return false;
    }

    return false;
}

function sample_function() {
    $json = [
        'status' => 0,
    ];

    $json['status'] = 1;
    $json['message'] = 'Hurray!';
    return $json;
}