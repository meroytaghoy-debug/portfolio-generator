<?php
require_once 'includes/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$error = '';

$p = ['full_name' => '', 'profile_picture' => '', 'email' => '', 'contact_number' => '', 'address' => '', 'about_me' => ''];
$saved = ['education' => [], 'skills' => [], 'projects' => [], 'experience' => [], 'social_links' => []];

// If editing, load the existing data
if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM portfolios WHERE id = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) { header('Location: manage.php'); exit; }
    $p = $row;
    foreach (array_keys($saved) as $table) {
        $stmt = $pdo->prepare("SELECT * FROM $table WHERE portfolio_id = ? ORDER BY id");
        $stmt->execute([$id]);
        $saved[$table] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

// When the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $p['full_name']      = trim($_POST['full_name'] ?? '');
    $p['email']          = trim($_POST['email'] ?? '');
    $p['contact_number'] = trim($_POST['contact_number'] ?? '');
    $p['address']        = trim($_POST['address'] ?? '');
    $p['about_me']       = trim($_POST['about_me'] ?? '');

    foreach (array_keys($saved) as $table) {
        $saved[$table] = (isset($_POST[$table]) && is_array($_POST[$table])) ? array_values($_POST[$table]) : [];
    }

    // Profile picture (stored in the database as text)
    if (!empty($_FILES['photo']['name'])) {
        if ($_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
            $error = 'Photo upload failed. Please try again.';
        } elseif ($_FILES['photo']['size'] > 1048576) {
            $error = 'Photo must be 1 MB or smaller.';
        } else {
            $info = getimagesize($_FILES['photo']['tmp_name']);
            $mime = $info['mime'] ?? '';
            $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!in_array($mime, $allowed)) {
                $error = 'Photo must be a JPG, PNG, GIF or WEBP image.';
            } else {
                $p['profile_picture'] = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($_FILES['photo']['tmp_name']));
            }
        }
    }

    if ($error === '' && $p['full_name'] === '') {
        $error = 'Full name is required.';
    }

    if ($error === '') {
        $defs = [
            'education'    => ['school', 'degree', 'year_range'],
            'skills'       => ['skill_name'],
            'projects'     => ['title', 'description', 'project_link'],
            'experience'   => ['company', 'position', 'year_range', 'description'],
            'social_links' => ['platform', 'url'],
        ];
        try {
            $pdo->beginTransaction();
            $saveId = $id;

            if ($saveId) {
                $stmt = $pdo->prepare("UPDATE portfolios SET full_name=?, profile_picture=?, email=?, contact_number=?, address=?, about_me=? WHERE id=?");
                $stmt->execute([$p['full_name'], $p['profile_picture'], $p['email'], $p['contact_number'], $p['address'], $p['about_me'], $saveId]);
                foreach (array_keys($defs) as $table) {
                    $pdo->prepare("DELETE FROM $table WHERE portfolio_id = ?")->execute([$saveId]);
                }
            } else {
                $stmt = $pdo->prepare("INSERT INTO portfolios (full_name, profile_picture, email, contact_number, address, about_me) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$p['full_name'], $p['profile_picture'], $p['email'], $p['contact_number'], $p['address'], $p['about_me']]);
                $saveId = (int)$pdo->lastInsertId();
            }

            foreach ($defs as $table => $cols) {
                foreach ($saved[$table] as $row) {
                    if (!is_array($row)) continue;
                    $values = [];
                    $hasData = false;
                    foreach ($cols as $c) {
                        $v = trim($row[$c] ?? '');
                        if ($v !== '') $hasData = true;
                        $values[] = $v;
                    }
                    if (!$hasData) continue;
                    $sql = "INSERT INTO $table (portfolio_id, " . implode(', ', $cols) . ") VALUES (?" . str_repeat(', ?', count($cols)) . ")";
                    $pdo->prepare($sql)->execute(array_merge([$saveId], $values));
                }
            }

            $pdo->commit();
            header('Location: templates.php?id=' . $saveId);
            exit;
        } catch (PDOException $ex) {
            $pdo->rollBack();
            $error = 'Could not save. Please try again.';
        }
    }
}

$pageTitle = $id ? 'Edit Portfolio' : 'Create Portfolio';
include 'includes/header.php';
?>

<div class="card">
  <h1><?= $id ? 'Edit Portfolio' : 'Create Portfolio' ?></h1>

  <?php if ($error): ?>
    <div class="error"><?= e($error) ?></div>
  <?php endif; ?>

  <form method="post" enctype="multipart/form-data">

    <h2>Personal Information</h2>

    <label>Full Name *</label>
    <input type="text" name="full_name" value="<?= e($p['full_name']) ?>" required>

    <label>Profile Picture (JPG/PNG, max 1 MB)</label>
    <?php if ($p['profile_picture']): ?>
      <img class="preview-img" src="<?= e($p['profile_picture']) ?>" alt="Current photo">
    <?php endif; ?>
    <input type="file" name="photo" accept="image/*">

    <label>Email</label>
    <input type="email" name="email" value="<?= e($p['email']) ?>">

    <label>Contact Number</label>
    <input type="text" name="contact_number" value="<?= e($p['contact_number']) ?>">

    <label>Address</label>
    <input type="text" name="address" value="<?= e($p['address']) ?>">

    <label>About Me</label>
    <textarea name="about_me" rows="4"><?= e($p['about_me']) ?></textarea>

    <h2>Education</h2>
    <div id="education"></div>
    <button type="button" class="btn btn-secondary btn-small" onclick="addRow('education')">+ Add Education</button>

    <h2>Skills</h2>
    <div id="skills"></div>
    <button type="button" class="btn btn-secondary btn-small" onclick="addRow('skills')">+ Add Skill</button>

    <h2>Projects</h2>
    <div id="projects"></div>
    <button type="button" class="btn btn-secondary btn-small" onclick="addRow('projects')">+ Add Project</button>

    <h2>Work Experience</h2>
    <div id="experience"></div>
    <button type="button" class="btn btn-secondary btn-small" onclick="addRow('experience')">+ Add Experience</button>

    <h2>Social Media / Website Links</h2>
    <div id="social_links"></div>
    <button type="button" class="btn btn-secondary btn-small" onclick="addRow('social_links')">+ Add Link</button>

    <p style="margin-top:24px;">
      <button type="submit" class="btn">Save &amp; Choose Template</button>
      <a class="btn btn-secondary" href="manage.php">Cancel</a>
    </p>
  </form>
</div>

<script>
const sections = {
  education: [
    {k: 'school', l: 'School'},
    {k: 'degree', l: 'Degree / Course'},
    {k: 'year_range', l: 'Years (e.g. 2022 - 2026)'}
  ],
  skills: [
    {k: 'skill_name', l: 'Skill'}
  ],
  projects: [
    {k: 'title', l: 'Project Title'},
    {k: 'description', l: 'Description', area: true},
    {k: 'project_link', l: 'Project Link'}
  ],
  experience: [
    {k: 'company', l: 'Company'},
    {k: 'position', l: 'Position'},
    {k: 'year_range', l: 'Years'},
    {k: 'description', l: 'Description', area: true}
  ],
  social_links: [
    {k: 'platform', l: 'Platform (e.g. LinkedIn)'},
    {k: 'url', l: 'Link (URL)'}
  ]
};

const saved = <?= json_encode($saved, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
let counter = 0;

function addRow(section, values) {
  const i = counter++;
  const row = document.createElement('div');
  row.className = 'row-card';

  sections[section].forEach(function (f) {
    const label = document.createElement('label');
    label.textContent = f.l;
    const input = document.createElement(f.area ? 'textarea' : 'input');
    if (f.area) { input.rows = 3; } else { input.type = 'text'; }
    input.name = section + '[' + i + '][' + f.k + ']';
    input.value = (values && values[f.k]) ? values[f.k] : '';
    row.appendChild(label);
    row.appendChild(input);
  });

  const remove = document.createElement('button');
  remove.type = 'button';
  remove.className = 'btn btn-danger btn-small';
  remove.textContent = 'Remove';
  remove.style.marginTop = '10px';
  remove.onclick = function () { row.remove(); };
  row.appendChild(remove);

  document.getElementById(section).appendChild(row);
}

Object.keys(sections).forEach(function (s) {
  if (saved[s].length === 0) {
    addRow(s);
  } else {
    saved[s].forEach(function (v) { addRow(s, v); });
  }
});
</script>

<?php include 'includes/footer.php'; ?>