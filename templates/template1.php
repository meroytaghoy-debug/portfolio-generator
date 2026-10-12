<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($p['full_name']) ?> - Portfolio</title>
<style>
body { margin: 0; font-family: Georgia, 'Times New Roman', serif; color: #222; background: #fff; line-height: 1.6; }
.wrap { max-width: 760px; margin: 0 auto; padding: 40px 20px; }
header { text-align: center; border-bottom: 2px solid #222; padding-bottom: 24px; margin-bottom: 28px; }
header img { width: 120px; height: 120px; border-radius: 50%; object-fit: cover; margin-bottom: 12px; }
h1 { margin: 0; font-size: 2.2rem; letter-spacing: 1px; }
.contact { color: #555; font-size: .95rem; margin-top: 6px; }
h2 { font-size: 1.1rem; text-transform: uppercase; letter-spacing: 2px; border-bottom: 1px solid #ccc; padding-bottom: 4px; margin-top: 32px; }
.item { margin-bottom: 14px; }
.meta { color: #666; font-size: .9rem; }
a { color: #1a4d8f; }
ul.list { list-style: none; padding: 0; display: flex; flex-wrap: wrap; gap: 6px 22px; }
@media (max-width: 600px) { h1 { font-size: 1.7rem; } }
</style>
</head>
<body>
<?= $toolbar ?>
<div class="wrap">

  <header>
    <?php if ($p['profile_picture']): ?>
      <img src="<?= e($p['profile_picture']) ?>" alt="Profile photo">
    <?php endif; ?>
    <h1><?= e($p['full_name']) ?></h1>
    <?php $contact = array_filter([$p['email'], $p['contact_number'], $p['address']]); ?>
    <?php if ($contact): ?>
      <div class="contact"><?= e(implode('  |  ', $contact)) ?></div>
    <?php endif; ?>
  </header>

  <?php if ($p['about_me']): ?>
    <h2>About Me</h2>
    <p><?= nl2br(e($p['about_me'])) ?></p>
  <?php endif; ?>

  <?php if ($education): ?>
    <h2>Education</h2>
    <?php foreach ($education as $ed): ?>
      <div class="item">
        <strong><?= e($ed['school']) ?></strong>
        <div class="meta"><?= e($ed['degree']) ?> <?= $ed['year_range'] ? '(' . e($ed['year_range']) . ')' : '' ?></div>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>

  <?php if ($skills): ?>
    <h2>Skills</h2>
    <ul class="list">
      <?php foreach ($skills as $s): ?><li><?= e($s['skill_name']) ?></li><?php endforeach; ?>
    </ul>
  <?php endif; ?>

  <?php if ($experience): ?>
    <h2>Work Experience</h2>
    <?php foreach ($experience as $x): ?>
      <div class="item">
        <strong><?= e($x['position']) ?></strong> at <?= e($x['company']) ?>
        <div class="meta"><?= e($x['year_range']) ?></div>
        <div><?= nl2br(e($x['description'])) ?></div>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>

  <?php if ($projects): ?>
    <h2>Projects</h2>
    <?php foreach ($projects as $pr): ?>
      <div class="item">
        <strong><?= e($pr['title']) ?></strong>
        <div><?= nl2br(e($pr['description'])) ?></div>
        <?php if ($pr['project_link']): ?>
          <a href="<?= e(safeUrl($pr['project_link'])) ?>" target="_blank" rel="noopener">View project</a>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>

  <?php if ($links): ?>
    <h2>Links</h2>
    <ul class="list">
      <?php foreach ($links as $l): ?>
        <li><a href="<?= e(safeUrl($l['url'])) ?>" target="_blank" rel="noopener"><?= e($l['platform'] ?: $l['url']) ?></a></li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>

</div>
</body>
</html>