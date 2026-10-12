<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($p['full_name']) ?> - Portfolio</title>
<style>
* { box-sizing: border-box; }
body { margin: 0; font-family: 'Trebuchet MS', Arial, sans-serif; background: #12121c; color: #e8e8f0; }
.layout { display: grid; grid-template-columns: 320px 1fr; min-height: 100vh; }
.side { background: linear-gradient(180deg, #ff3d81, #7b2ff7); padding: 40px 24px; text-align: center; color: #fff; }
.side img { width: 150px; height: 150px; border-radius: 50%; object-fit: cover; border: 5px solid #fff; }
.side h1 { margin: 14px 0 6px; font-size: 1.8rem; }
.side p { margin: 4px 0; font-size: .95rem; word-break: break-word; }
.side a { display: block; color: #fff; margin: 8px 0; font-weight: bold; }
.content { padding: 40px 36px; }
h2 { color: #ff3d81; text-transform: uppercase; letter-spacing: 3px; font-size: 1rem; margin: 30px 0 14px; }
h2:first-child { margin-top: 0; }
.timeline { border-left: 3px solid #7b2ff7; padding-left: 22px; margin-left: 6px; }
.tl-item { position: relative; margin-bottom: 22px; }
.tl-item::before { content: ''; position: absolute; left: -31px; top: 6px; width: 14px; height: 14px; border-radius: 50%; background: #ff3d81; }
.tl-item small { color: #a9a9c4; }
.skill { display: inline-block; border: 2px solid #7b2ff7; padding: 6px 14px; border-radius: 4px; margin: 4px; transform: rotate(-1deg); }
.content a { color: #ff7eb3; }
@media (max-width: 760px) { .layout { grid-template-columns: 1fr; } .content { padding: 28px 18px; } }
</style>
</head>
<body>
<?= $toolbar ?>

<div class="layout">
  <aside class="side">
    <?php if ($p['profile_picture']): ?>
      <img src="<?= e($p['profile_picture']) ?>" alt="Profile photo">
    <?php endif; ?>
    <h1><?= e($p['full_name']) ?></h1>
    <?php if ($p['email']): ?><p><?= e($p['email']) ?></p><?php endif; ?>
    <?php if ($p['contact_number']): ?><p><?= e($p['contact_number']) ?></p><?php endif; ?>
    <?php if ($p['address']): ?><p><?= e($p['address']) ?></p><?php endif; ?>
    <div style="margin-top:20px;">
      <?php foreach ($links as $l): ?>
        <a href="<?= e(safeUrl($l['url'])) ?>" target="_blank" rel="noopener"><?= e($l['platform'] ?: $l['url']) ?></a>
      <?php endforeach; ?>
    </div>
  </aside>

  <section class="content">

    <?php if ($p['about_me']): ?>
      <h2>About Me</h2>
      <p><?= nl2br(e($p['about_me'])) ?></p>
    <?php endif; ?>

    <?php if ($skills): ?>
      <h2>Skills</h2>
      <?php foreach ($skills as $s): ?><span class="skill"><?= e($s['skill_name']) ?></span><?php endforeach; ?>
    <?php endif; ?>

    <?php if ($experience): ?>
      <h2>Experience</h2>
      <div class="timeline">
        <?php foreach ($experience as $x): ?>
          <div class="tl-item">
            <strong><?= e($x['position']) ?></strong> &mdash; <?= e($x['company']) ?><br>
            <small><?= e($x['year_range']) ?></small>
            <div><?= nl2br(e($x['description'])) ?></div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <?php if ($education): ?>
      <h2>Education</h2>
      <div class="timeline">
        <?php foreach ($education as $ed): ?>
          <div class="tl-item">
            <strong><?= e($ed['school']) ?></strong><br>
            <?= e($ed['degree']) ?><br>
            <small><?= e($ed['year_range']) ?></small>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <?php if ($projects): ?>
      <h2>Projects</h2>
      <div class="timeline">
        <?php foreach ($projects as $pr): ?>
          <div class="tl-item">
            <strong><?= e($pr['title']) ?></strong>
            <div><?= nl2br(e($pr['description'])) ?></div>
            <?php if ($pr['project_link']): ?>
              <a href="<?= e(safeUrl($pr['project_link'])) ?>" target="_blank" rel="noopener">View project</a>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

  </section>
</div>
</body>
</html>