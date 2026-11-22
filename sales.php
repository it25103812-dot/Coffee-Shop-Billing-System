<?php
require_once 'config/db.php'; require_once 'includes/auth.php'; require_login();
$from=$_GET['from']??date('Y-m-01'); $to=$_GET['to']??date('Y-m-d');
$s=$pdo->prepare("SELECT o.*,u.full_name cashier FROM orders o JOIN users u ON u.id=o.user_id WHERE o.status='PAID' AND DATE(o.created_at) BETWEEN ? AND ? ORDER BY o.id DESC"); $s->execute([$from,$to]); $orders=$s->fetchAll();
$sum=0; foreach($orders as $o) $sum+=(float)$o['total'];
$pageTitle='Sales History'; require 'includes/header.php';
?>
<section class="panel"><div class="panel-heading"><div><span class="eyebrow">TRANSACTION REPORT</span><h2>Sales history</h2></div><div class="report-total"><small>Total revenue</small><strong>LKR <?= number_format($sum,2) ?></strong></div></div>
<form class="filter-row" method="get"><label>From<input type="date" name="from" value="<?= h($from) ?>"></label><label>To<input type="date" name="to" value="<?= h($to) ?>"></label><button class="btn">Filter</button></form>
<div class="table-wrap"><table><thead><tr><th>Invoice</th><th>Date</th><th>Cashier</th><th>Payment</th><th class="right">Total</th><th></th></tr></thead><tbody>
<?php foreach($orders as $o): ?><tr><td><strong><?= h($o['invoice_no']) ?></strong></td><td><?= h(date('d M Y, h:i A',strtotime($o['created_at']))) ?></td><td><?= h($o['cashier']) ?></td><td><?= h(ucfirst($o['payment_method'])) ?></td><td class="right">LKR <?= number_format((float)$o['total'],2) ?></td><td><a href="receipt.php?id=<?= (int)$o['id'] ?>">Receipt</a></td></tr><?php endforeach; ?>
<?php if(!$orders): ?><tr><td colspan="6" class="empty">No sales found for this date range.</td></tr><?php endif; ?>
</tbody></table></div></section>
<?php require 'includes/footer.php'; ?>
