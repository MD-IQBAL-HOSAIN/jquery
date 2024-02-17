<?php
$headers = apache_request_headers();
$is_ajax = (isset($headers['X-Requested-Width']) && $headers['X-Requested-Width'] == 'XMLHttpRequest');
if (!$is_ajax) {
    die('only ajax allowed');
    exit;
}
require "database2.php";
$id = $POST['id'];
$psku = $POST['psku'];
$pn = $POST['productname'];
$pp = $POST['productprice'];
$updatetQuery = "update products set sku = '{$psku}', name = '{$pn}', price = '$pp') where id = '{$id}' limit 1";

$db->query($updatetQuery);
if ($db->affected_rows) {
    $success = true;
    $message = "Successfully updated product with id : " . $id;
} else {
    $success = false;
    $message = "Error updating data into products table";
}

echo json_encode(['success' => $success, 'message' => $message]);
$db->close();
