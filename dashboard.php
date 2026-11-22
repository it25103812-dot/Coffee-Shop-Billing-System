<?php
require_once 'config/db.php'; require_once 'includes/auth.php'; require_login();
$pageTitle = 'Dashboard';
$today = $pdo->query("SELECT COUNT(*) orders_count, COALESCE(SUM(total),0) revenue FROM orders WHERE DATE(created_at)=CURDATE() AND status='PAID'")->fetch();
$products = (int)$pdo->query('SELECT COUNT(*) FROM products WHERE is_active=1')->fetchColumn();
$recent = $pdo->query("SELECT id, invoice_no, total, payment_method, created_at FROM orders WHERE status='PAID' ORDER BY id DESC LIMIT 6")->fetchAll();
require 'includes/header.php';
?>
<section class="stats-grid">
 <article class="stat-card"><span>Today's revenue</span><strong>LKR <?= number_format((float)$today['revenue'],2) ?></strong><small>Paid orders only</small></article>
 <article class="stat-card"><span>Today's orders</span><strong><?= (int)$today['orders_count'] ?></strong><small>Completed transactions</small></article>
 <article class="stat-card"><span>Active products</span><strong><?= $products ?></strong><small>Available for billing</small></article>
</section>
<section class="panel welcome-panel"><div><span class="eyebrow">READY FOR SERVICE?</span><h2>Make every cup count.</h2><p>Create a new customer bill, manage your menu, and keep track of sales from one place.</p><a class="btn" href="billing.php">＋ Create new bill</a></div><div class="coffee-art">☕</div></section>
<section class="panel"><div class="panel-heading"><div><span class="eyebrow">LATEST ACTIVITY</span><h2>Recent sales</h2></div><a href="sales.php">View all →</a></div>
<div class="table-wrap"><table><thead><tr><th>Invoice</th><th>Date</th><th>Payment</th><th class="right">Total</th></tr></thead><tbody>
<?php foreach ($recent as $r): ?><tr><td><strong><?= h($r['invoice_no']) ?></strong></td><td><?= h(date('d M Y, h:i A',strtotime($r['created_at']))) ?></td><td><span class="tag"><?= h(ucfirst($r['payment_method'])) ?></span></td><td class="right">LKR <?= number_format((float)$r['total'],2) ?></td></tr><?php endforeach; ?>
<?php if (!$recent): ?><tr><td colspan="4" class="empty">No sales yet. Your completed bills will appear here.</td></tr><?php endif; ?>
</tbody></table></div></section>
<?php require 'includes/footer.php'; ?>
