<?php

require_once '../../config/Database.php';
require_once '../../controllers/CancelOrderController.php';

$db = Database::getConnection();
$controller = new CancelOrderController($db);

try {
  $controller->cancel();
  header("Location: ../../index.php?success=1");
  exit;
} catch (Exception $e) {
  error_log("DB Error " . $e->getMessage());

  $message = urlencode($e->getMessage());
  header("Location: ../../index.php?error=$message");
  exit;
}