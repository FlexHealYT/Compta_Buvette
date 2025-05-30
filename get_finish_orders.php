<?php
$filename = "finishOrders.txt";

if (!file_exists($filename)) {
    file_put_contents($filename, '0');
}
$content = file_get_contents($filename);
echo $content;

if (isset($_GET['id'])) {
    file_put_contents($filename, ", " . $_GET['id'], FILE_APPEND);
}
?>