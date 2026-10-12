<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($p['full_name']) ?> - Portfolio</title>
<style>
* { box-sizing: border-box; }
body { margin: 0; font-family: 'Segoe UI', Arial, sans-serif; background: #eef1f8; color: #1c2333; }
.hero { background: linear-gradient(135deg, #4f46e5, #06b6d4); color: #fff; padding: 50px 20px 70px; }
.hero-in { max-width: 1000px; margin: 0 auto; display: flex; align-items: center; gap: 28px; flex-wrap: wrap; }
.hero img { width: 140px; height: 140px; border-radius: 20px; object-fit: cover; border: 4px solid rgba(255,255,255,.6); }
.hero h1 { margin: 0 0 6px; font-size: 2.4rem; }
.chips span { display: inline-block; background: rgba(255,255,255,.2); padding: 4px 12px; border-radius: 20px; margin: 4px 6px 0 0; font-size: .9rem; }
.main { max-width: 1000px; margin: -40px auto 40px; padding: 0 20px; }
.card { background: #fff; border-radius: 16px; padding: 24px; margin-bottom: 20px; box-shadow: 0 6px 18px rgba(30,40,90,.1); }
.card h2 { margin: 0 0 14px; color: #4f46e5; font-size: 1.2rem; }
.grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 16px; }
.mini { background: #f5f7ff; border-left: 4px solid #06b6d4; border-radius: 10px; padding: 14px; }
.mini small { color: #667; }
.pill { display: inline-block; background: #e0e7ff; color: #3730a3; padding: 6px 14px; border-radius: 20px; margin: 4px 6px 4px 0; font-size: .9rem; text-decoration: none; }
a { color: #4f46e5; }
</style>
</head>
<body>
<?= $toolbar ?>

<div class="hero">
  <div class="hero-in">
    <?php if ($p['profile_picture']): ?>
      <img src="<?= e($p['profile_picture']) ?>" alt="Profile photo">
    <?php endif; ?>
    <div>
      <h1><?= e($p['full_name']) ?></h1>
      <?php $contact = array_filter([$p['email'], $p['contact_number'], $p['address']]); ?>
      <div class="chips">
        <?php foreach ($contact as $c): ?><span><?= e($c) ?></span><?php endforeach; ?>
      </div>
    </div>
  </div>
</div>

<div class="main">

  <?php if ($p['about_me']): ?>
    <div class="card"><h2>About Me</h2><p><?= nl2br(e($p['about_me'])) ?></p></div>
  <?php endif; ?>

  <?php if ($skills): ?>
    <div class="card"><h2>Skills</h2>
      <?php foreach ($skills as $s): ?><span class="pill"><?= e($s['skill_name']) ?></span><?php endforeach; ?>
    </div>
  <?php endif; ?>

  <?php if ($experience): ?>
    <div class="card"><h2>Work Experience</h2>
      <div class="grid">
        <?php foreach ($experience as $x): ?>
          <div class="mini">
            <strong><?= e($x['position']) ?></strong><br>
            <?= e($x['company']) ?><br>
            <small><?= e($x['year_range']) ?></small>
            <p><?= nl2br(e($x['description'])) ?></p>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  <?php endif; ?>

  <?php if ($education): ?>
    <div class="card"><h2>Education</h2>
      <div class="grid">
        <?php foreach ($education as $ed): ?>
          <div class="mini">
            <strong><?= e($ed['school']) ?></strong><br>
            <?= e($ed['degree']) ?><br>
            <small><?= e($ed['year_range']) ?></small>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  <?php endif; ?>

  <?php if ($projects): ?>
    <div class="card"><h2>Projects</h2>
      <div class="grid">
        <?php foreach ($projects as $pr): ?>
          <div class="mini">
            <strong><?= e($pr['title']) ?></strong>
            <p><?= nl2br(e($pr['description'])) ?></p>
            <?php if ($pr['project_link']): ?>
              <a href="<?= e(safeUrl($pr['project_link'])) ?>" target="_blank" rel="noopener">View project</a>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  <?php endif; ?>

  <?php if ($links): ?>
    <div class="card"><h2>Find Me Online</h2>
      <?php foreach ($links as $l): ?>
        <a class="pill" href="<?= e(safeUrl($l['url'])) ?>" target="_blank" rel="noopener"><?= e($l['platform'] ?: $l['url']) ?></a>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

</div>
</body>
</html>