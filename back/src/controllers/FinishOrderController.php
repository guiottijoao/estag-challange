<?php

class FinishOrderController
{

  private $db;

  function __construct($db)
  {
    $this->db = $db;
  }

  private function discountStock(array $orderItem)
  {
    $productId = $orderItem['product_code'];
    $orderItemAmount = $orderItem['amount'];

    $this->db->query("UPDATE products p SET amount = amount - '$orderItemAmount' WHERE p.code = '$productId'");
  }

  public function finish()
  {
    try {
      $order_select_stmt = $this->db->query("SELECT * FROM orders o WHERE o.status = 'open'");
      $openOrder = $order_select_stmt->fetch(PDO::FETCH_ASSOC);
      if ($openOrder) {
        $openOrderId = $openOrder['code'];
        $order_item_select_stmt = $this->db->query("SELECT * FROM order_item oi WHERE oi.order_code = '$openOrderId'");
        $orderItems = $order_item_select_stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($orderItems) {
          foreach ($orderItems as $item) {
            $this->discountStock($item);
          }
          $open_order_update_stmt = $this->db->prepare("UPDATE orders SET status = 'closed'");
          $open_order_update_stmt->execute();
        }
      }
      throw new Exception("You dont have items in your order.");
    } catch (Exception $e) {
      throw $e;
    }
  }
}
