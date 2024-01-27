<?php

session_start();

include_once 'database.php';

$database = new Database();

$db = $database->getDatabase();
$stmt = $db->prepare('SELECT * FROM moves WHERE id = '.$_SESSION['last_move']);
$stmt->execute();
$result = $stmt->get_result()->fetch_array();
$_SESSION['last_move'] = $result[5];
set_state($result[6]);
header('Location: index.php');
