<?php
$headers = apache_request_headers();
$is_ajax = (isset($headers['X-Requested-Width']) && $headers['X-Requested-Width'] == 'XMLHttpRequest');
if (!$is_ajax) {
    die('only ajax allowed');
    exit;
}
require "database2.php";
$psku = $POST['psku'];
$pn = $POST['productname'];
$pp = $POST['productprice'];
$insertQuery = "insert into products values (NULL,'{$psku}','{$pn}','$pp')";

$db->query($insertQuery);
if ($db->affected_rows) {
    $success = true;
    $message = "Successfully inserted with id : " . $db->insert_id;;
} else {
    $success = false;
    $message = "Error inserting data into product table";;
}

echo json_encode(['success' => $success, 'message' => $message]);
$db->close();
