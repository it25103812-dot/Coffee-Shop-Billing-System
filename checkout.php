<?php
require_once 'config/db.php'; require_once 'includes/auth.php'; require_login();
if ($_SERVER['REQUEST_METHOD']!=='POST') { header('Location: billing.php'); exit; }
verify_csrf();
$cart=json_decode($_POST['cart_json']??'[]',true);
$method=$_POST['payment_method']??'cash'; $paid=(float)($_POST['amount_paid']??0);
if (!is_array($cart)||!count($cart)||!in_array($method,['cash','card','online'],true)) exit('Invalid order. Please return to billing.');
try {
 $pdo->beginTransaction(); $validated=[]; $total=0;
 $find=$pdo->prepare('SELECT id,name,price FROM products WHERE id=? AND is_active=1');
 foreach($cart as $line) {
  $id=(int)($line['id']??0); $qty=(int)($line['quantity']??0);
  if($qty<1||$qty>99) throw new RuntimeException('Invalid quantity.');
  $find->execute([$id]); $p=$find->fetch(); if(!$p) throw new RuntimeException('A selected product is no longer available.');
  $lineTotal=(float)$p['price']*$qty; $total+=$lineTotal;
  $validated[]=['id'=>$id,'name'=>$p['name'],'price'=>(float)$p['price'],'quantity'=>$qty,'line_total'=>$lineTotal];
 }
 if($paid+0.0001<$total) throw new RuntimeException('Amount received is less than the bill total.');
 $invoice='BB-'.date('Ymd').'-'.strtoupper(bin2hex(random_bytes(3)));
 $s=$pdo->prepare("INSERT INTO orders(invoice_no,user_id,subtotal,total,amount_paid,change_due,payment_method,status) VALUES(?,?,?,?,?,?,?,'PAID')");
 $s->execute([$invoice,$_SESSION['user_id'],$total,$total,$paid,$paid-$total,$method]); $orderId=(int)$pdo->lastInsertId();
 $line=$pdo->prepare('INSERT INTO order_items(order_id,product_id,product_name,unit_price,quantity,line_total) VALUES(?,?,?,?,?,?)');
 foreach($validated as $v) $line->execute([$orderId,$v['id'],$v['name'],$v['price'],$v['quantity'],$v['line_total']]);
 $pay=$pdo->prepare('INSERT INTO payments(order_id,method,amount,status) VALUES(?,?,?,?)'); $pay->execute([$orderId,$method,$paid,'COMPLETED']);
 $pdo->commit(); header('Location: receipt.php?id='.$orderId); exit;
} catch(Throwable $e) {
 if($pdo->inTransaction()) $pdo->rollBack();
 $message=$e instanceof RuntimeException?$e->getMessage():'Could not save sale. Please try again.';
}
?>
<!doctype html><html><head><meta charset="utf-8"><link rel="stylesheet" href="assets/css/style.css"><title>Checkout error</title></head><body class="auth-page"><section class="auth-card"><h1>Sale not completed</h1><div class="error"><?= h($message??'Unknown error') ?></div><a class="btn full" href="billing.php">Return to billing</a></section></body></html>
