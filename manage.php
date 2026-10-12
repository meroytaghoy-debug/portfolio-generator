<?php
require_once 'includes/db.php';

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $pdo->prepare("DELETE FROM portfolios WHERE id = ?")->execute([(int)$_POST['delete_id']]);
    header('Location: manage.php?deleted=1');
    exit;
}
if (isset($_GET['deleted'])) { $msg = 'Portfolio deleted.'; }

$rows = $pdo->query("SELECT id, full_name, email, template_choice, created_at FROM portfolios ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
$names = [1 => 'Simple', 2 => 'Modern', 3 => 'Creative'];

$pageTitle = 'Manage Portfolios';
include 'includes/header.php';
?>
<div class="card">
  <h1>Manage Portfolios</h1>

  <?php if ($msg): ?>
    <div style="background:#d3f9d8;color:#2b8a3e;padding:12px;border-radius:6px;margin-bottom:16px;"><?= e($msg) ?></div>
  <?php endif; ?>

  <p><a class="btn" href="form.php">+ Create New Portfolio</a></p>

  <?php if (!$rows): ?>
    <p>No portfolios yet. Click "Create New Portfolio" to start.</p>
  <?php else: ?>
    <?php foreach ($rows as $r): ?>
      <div class="row-card" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;">
        <div>
          <strong><?= e($r['full_name']) ?></strong><br>
          <small><?= e($r['email']) ?> &middot; Template: <?= e($names[$r['template_choice']] ?? 'Simple') ?></small>
        </div>
        <div>
          <a class="btn btn-small" href="preview.php?id=<?= (int)$r['id'] ?>">View</a>
          <a class="btn btn-small btn-secondary" href="form.php?id=<?= (int)$r['id'] ?>">Edit</a>
          <a class="btn btn-small btn-secondary" href="templates.php?id=<?= (int)$r['id'] ?>">Template</a>
          <form method="post" style="display:inline;" onsubmit="return confirm('Delete this portfolio? This cannot be undone.');">
            <input type="hidden" name="delete_id" value="<?= (int)$r['id'] ?>">
            <button type="submit" class="btn btn-small btn-danger">Delete</button>
          </form>
        </div>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>
<?php include 'includes/footer.php'; ?>