<?php
require_once 'config/db.php'; require_once 'includes/auth.php'; require_login();
$products=$pdo->query('SELECT id,name,category,price FROM products WHERE is_active=1 ORDER BY category,name')->fetchAll();
$pageTitle='New Bill'; require 'includes/header.php';
?>
<form method="post" action="checkout.php" id="checkout-form">
<input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
<div class="billing-layout"><section class="panel"><div class="panel-heading"><div><span class="eyebrow">MENU</span><h2>Choose your items</h2></div><input id="product-search" class="search-input" placeholder="Search menu..."></div>
<div class="product-grid" id="product-grid">
<?php foreach($products as $p): ?><button type="button" class="product-tile" data-id="<?= (int)$p['id'] ?>" data-name="<?= h($p['name']) ?>" data-category="<?= h($p['category']) ?>" data-price="<?= h((string)$p['price']) ?>"><span class="tile-icon"><?= str_contains(strtolower($p['category']),'cake')||str_contains(strtolower($p['category']),'food')?'🍰':'☕' ?></span><strong><?= h($p['name']) ?></strong><small><?= h($p['category']) ?></small><b>LKR <?= number_format((float)$p['price'],2) ?></b><span class="add-symbol">＋</span></button><?php endforeach; ?>
<?php if(!$products): ?><p class="empty">No active products. Add products first.</p><?php endif; ?>
</div></section>
<aside class="panel cart-panel"><div class="panel-heading"><div><span class="eyebrow">CURRENT ORDER</span><h2>Order summary</h2></div><span class="tag" id="item-count">0 items</span></div>
<div id="cart-items" class="cart-items"><p class="empty">Select products to start an order.</p></div>
<div class="cart-total"><div><span>Subtotal</span><strong id="subtotal">LKR 0.00</strong></div><div class="total-line"><span>Total</span><strong id="total">LKR 0.00</strong></div></div>
<label>Payment method<select name="payment_method" required><option value="cash">Cash</option><option value="card">Card</option><option value="online">Online transfer</option></select></label>
<label>Amount received (LKR)<input type="number" id="amount-paid" name="amount_paid" min="0" step="0.01" placeholder="Enter amount received" required></label>
<div class="change-row"><span>Change</span><strong id="change">LKR 0.00</strong></div>
<button class="btn full" id="complete-sale" disabled>Complete sale →</button><p class="muted small">The bill is saved only after payment is completed.</p>
</aside></div></form>
<script>window.COFFEE_PRODUCTS = <?= json_encode($products, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT) ?>;</script>
<?php require 'includes/footer.php'; ?>
