<?php

require_once '../../config/Database.php';

$db = Database::getConnection();

$name = $_POST['category-name'];
$tax = $_POST['category-tax'];

$stmt = $db->prepare("INSERT INTO categories (name, tax) VALUES (:name, :tax)");
$stmt->execute(["name" => $name, "tax" => $tax]);

header("Location: ../../categories.php");
exit;