<?php
require_once 'includes/db.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT id, full_name, template_choice FROM portfolios WHERE id = ?");
$stmt->execute([$id]);
$p = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$p) { header('Location: manage.php'); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $t = (int)($_POST['template'] ?? 1);
    if ($t < 1 || $t > 3) { $t = 1; }
    $pdo->prepare("UPDATE portfolios SET template_choice = ? WHERE id = ?")->execute([$t, $id]);
    header('Location: preview.php?id=' . $id);
    exit;
}

$names = [
    1 => ['Simple',   'Clean and professional. One column, easy to read.'],
    2 => ['Modern',   'Cards, sections and colorful visual elements.'],
    3 => ['Creative', 'Dark theme with a side panel and timeline layout.'],
];

$pageTitle = 'Choose Template';
include 'includes/header.php';
?>
<style>
.tpl-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; }
.tpl { background: #fff; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,.1); overflow: hidden; text-align: center; padding-bottom: 18px; }
.tpl-thumb { height: 140px; margin-bottom: 14px; }
.t1 { background: repeating-linear-gradient(#fff 0 14px, #e5e5e5 14px 16px); border-bottom: 4px solid #222; }
.t2 { background: linear-gradient(135deg, #4f46e5, #06b6d4); }
.t3 { background: linear-gradient(180deg, #ff3d81, #7b2ff7); }
.tpl h3 { margin: 6px 0; }
.tpl p { margin: 0 14px 14px; color: #555; }
.current { color: #2b8a3e; font-weight: bold; margin-bottom: 8px; }
</style>

<div class="card">
  <h1>Choose a Template</h1>
  <p>Portfolio for <strong><?= e($p['full_name']) ?></strong>. Pick one of the 3 designs.</p>

  <div class="tpl-grid">
    <?php foreach ($names as $n => $info): ?>
      <div class="tpl">
        <div class="tpl-thumb t<?= $n ?>"></div>
        <h3><?= e($info[0]) ?></h3>
        <p><?= e($info[1]) ?></p>
        <?php if ((int)$p['template_choice'] === $n): ?>
          <div class="current">Currently selected</div>
        <?php endif; ?>
        <form method="post">
          <input type="hidden" name="template" value="<?= $n ?>">
          <button type="submit" class="btn">Select &amp; Preview</button>
        </form>
      </div>
    <?php endforeach; ?>
  </div>

  <p style="margin-top:20px;">
    <a class="btn btn-secondary" href="form.php?id=<?= (int)$id ?>">Back to Edit</a>
    <a class="btn btn-secondary" href="manage.php">Manage Portfolios</a>
  </p>
</div>
<?php include 'includes/footer.php'; ?>