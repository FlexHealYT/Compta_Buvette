<?php
header('Content-Type: application/json');
$variables = include 'variables.php';
echo json_encode($variables);
?>
