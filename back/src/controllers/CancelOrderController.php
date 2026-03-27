<?php

class CancelOrderController
{

  private $db;

  function __construct(PDO $db)
  {
    $this->db = $db;
  }

  public function cancel() {
    try {
      $active_order_stmt = $this->db->query("SELECT * FROM orders o WHERE o.status = 'open'");
      $order = $active_order_stmt->fetch(PDO::FETCH_ASSOC);
      $order_items_stmt = $this->db->query("SELECT * FROM order_item");
      $orderItems = $order_items_stmt->fetch(PDO::FETCH_ASSOC);
      if (!$order || !$orderItems) return;

      $delete_order_items_stmt = $this->db->prepare("DELETE FROM order_item o WHERE o.order_code = :order_code");
      $delete_order_items_stmt->execute([":order_code" => $order['code']]);
      
      $update_order_total_and_tax = $this->db->prepare("UPDATE orders o SET total = 0, tax = 0 WHERE  o.code = :order_code");
      $update_order_total_and_tax->execute([":order_code" => $order['code']]);
    } catch (Exception $e) {
      throw $e;
    }
  }
}
