<?php
$db = new mysqli('localhost', 'root', '', 'r57_php') or die("Error!!");
$db->set_charset('utf8');
if ($db->connect_errno) {
    printf('unable to connect to server/db: <br/> %s', $db->connect_error);
    exit();
}
