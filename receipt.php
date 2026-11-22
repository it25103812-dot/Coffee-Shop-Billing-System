<?php
require_once 'config/db.php'; require_once 'includes/auth.php'; require_login();
$id=(int)($_GET['id']??0); $s=$pdo->prepare("SELECT o.*,u.full_name cashier FROM orders o JOIN users u ON u.id=o.user_id WHERE o.id=? AND o.status='PAID'"); $s->execute([$id]); $order=$s->fetch();
if(!$order) { http_response_code(404); exit('Receipt not found.'); }
$s=$pdo->prepare('SELECT * FROM order_items WHERE order_id=?'); $s->execute([$id]); $items=$s->fetchAll();
$pageTitle='Receipt'; require 'includes/header.php';
?>
<section class="receipt-wrap"><div class="receipt panel"><div class="receipt-head"><div class="logo-large">☕</div><h2>Brew & Bean Coffee</h2><p>Thank you for stopping by!</p><span class="eyebrow">CUSTOMER RECEIPT</span></div>
<div class="receipt-meta"><span>Invoice<strong><?= h($order['invoice_no']) ?></strong></span><span>Date<strong><?= h(date('d M Y, h:i A',strtotime($order['created_at']))) ?></strong></span><span>Cashier<strong><?= h($order['cashier']) ?></strong></span></div>
<table><thead><tr><th>Item</th><th>Qty</th><th class="right">Amount</th></tr></thead><tbody><?php foreach($items as $item): ?><tr><td><?= h($item['product_name']) ?><small class="receipt-small">LKR <?= number_format((float)$item['unit_price'],2) ?> each</small></td><td><?= (int)$item['quantity'] ?></td><td class="right">LKR <?= number_format((float)$item['line_total'],2) ?></td></tr><?php endforeach; ?></tbody></table>
<div class="receipt-totals"><div><span>Subtotal</span><strong>LKR <?= number_format((float)$order['subtotal'],2) ?></strong></div><div class="grand-total"><span>Total</span><strong>LKR <?= number_format((float)$order['total'],2) ?></strong></div><div><span>Paid (<?= h(ucfirst($order['payment_method'])) ?>)</span><strong>LKR <?= number_format((float)$order['amount_paid'],2) ?></strong></div><div><span>Change</span><strong>LKR <?= number_format((float)$order['change_due'],2) ?></strong></div></div>
<p class="receipt-thanks">Made with care. Enjoy your coffee ☕</p></div><div class="receipt-actions"><button class="btn" onclick="window.print()">Print receipt</button><a class="btn btn-light" href="billing.php">New bill</a><a class="btn btn-light" href="sales.php">Sales history</a></div></section>
<?php require 'includes/footer.php'; ?>
