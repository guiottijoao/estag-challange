<?php

require_once '../../config/Database.php';
require_once '../../controllers/ProductController.php';

$db = Database::getConnection();
$controller = new ProductController($db);

$name = $_POST['name'];
$amount = $_POST['amount'];
$price = $_POST['price'];
$categoryId = $_POST['category-code'];

try {
  $controller->store($_POST);
  header("Location: ../../products.php?success=1");
  exit;
} catch (Exception $e) {
  error_log("DB Error: " . $e->getMessage());

  $message = urlencode($e->getMessage());
  header("Location: ../../products.php?error=$message");
  exit;
}