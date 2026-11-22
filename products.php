<?php
require_once 'config/db.php'; require_once 'includes/auth.php'; require_login();
$error='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
 verify_csrf(); $action=$_POST['action']??'';
 if ($action==='save') {
  $id=(int)($_POST['id']??0); $name=trim($_POST['name']??''); $category=trim($_POST['category']??''); $price=(float)($_POST['price']??0);
  if ($name==='' || $category==='' || $price<=0) $error='Enter a product name, category and price above zero.';
  else if ($id) { $s=$pdo->prepare('UPDATE products SET name=?,category=?,price=?,is_active=? WHERE id=?'); $s->execute([$name,$category,$price,isset($_POST['is_active'])?1:0,$id]); }
  else { $s=$pdo->prepare('INSERT INTO products(name,category,price,is_active) VALUES(?,?,?,1)'); $s->execute([$name,$category,$price]); }
 } elseif ($action==='toggle') { $s=$pdo->prepare('UPDATE products SET is_active=1-is_active WHERE id=?'); $s->execute([(int)$_POST['id']]); }
 header('Location: products.php'); exit;
}
$edit=null;
if (isset($_GET['edit'])) { $s=$pdo->prepare('SELECT * FROM products WHERE id=?'); $s->execute([(int)$_GET['edit']]); $edit=$s->fetch(); }
$items=$pdo->query('SELECT * FROM products ORDER BY category,name')->fetchAll();
$pageTitle='Products'; require 'includes/header.php';
?>
<div class="two-col">
<section class="panel"><div class="panel-heading"><div><span class="eyebrow">MENU CATALOGUE</span><h2><?= $edit?'Edit product':'Add a product' ?></h2></div></div>
<?php if($error): ?><div class="error"><?= h($error) ?></div><?php endif; ?>
<form method="post" class="form-grid"><input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>"><input type="hidden" name="action" value="save"><input type="hidden" name="id" value="<?= (int)($edit['id']??0) ?>">
<label class="wide">Product name<input name="name" value="<?= h($edit['name']??'') ?>" placeholder="e.g. Cappuccino" required></label>
<label>Category<input name="category" value="<?= h($edit['category']??'Coffee') ?>" placeholder="Coffee" required></label>
<label>Price (LKR)<input type="number" name="price" min="0.01" step="0.01" value="<?= h(isset($edit['price'])?(string)$edit['price']:'') ?>" required></label>
<?php if($edit): ?><label class="wide check-label"><input type="checkbox" name="is_active" <?= $edit['is_active']?'checked':'' ?>> Available for sale</label><?php endif; ?>
<div class="wide actions"><button class="btn"><?= $edit?'Save changes':'Add product' ?></button><?php if($edit): ?><a class="btn btn-light" href="products.php">Cancel</a><?php endif; ?></div></form></section>
<section class="panel"><div class="panel-heading"><div><span class="eyebrow">MENU SUMMARY</span><h2><?= count($items) ?> products</h2></div></div><p class="muted">Deactivate a product to hide it from the billing screen without deleting past sales records.</p></section>
</div>
<section class="panel"><div class="panel-heading"><h2>All products</h2></div><div class="table-wrap"><table><thead><tr><th>Product</th><th>Category</th><th>Price</th><th>Status</th><th>Actions</th></tr></thead><tbody>
<?php foreach($items as $p): ?><tr><td><strong><?= h($p['name']) ?></strong></td><td><?= h($p['category']) ?></td><td>LKR <?= number_format((float)$p['price'],2) ?></td><td><span class="tag <?= $p['is_active']?'tag-green':'tag-muted' ?>"><?= $p['is_active']?'Active':'Hidden' ?></span></td><td class="actions"><a href="products.php?edit=<?= (int)$p['id'] ?>">Edit</a><form method="post"><input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>"><input type="hidden" name="action" value="toggle"><input type="hidden" name="id" value="<?= (int)$p['id'] ?>"><button class="link-button"><?= $p['is_active']?'Deactivate':'Activate' ?></button></form></td></tr><?php endforeach; ?>
</tbody></table></div></section>
<?php require 'includes/footer.php'; ?>
