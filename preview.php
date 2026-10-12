<?php
require_once 'includes/db.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM portfolios WHERE id = ?");
$stmt->execute([$id]);
$p = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$p) { header('Location: manage.php'); exit; }

function loadRows($pdo, $table, $id) {
    $stmt = $pdo->prepare("SELECT * FROM $table WHERE portfolio_id = ? ORDER BY id");
    $stmt->execute([$id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$education  = loadRows($pdo, 'education', $id);
$skills     = loadRows($pdo, 'skills', $id);
$projects   = loadRows($pdo, 'projects', $id);
$experience = loadRows($pdo, 'experience', $id);
$links      = loadRows($pdo, 'social_links', $id);

function safeUrl($u) {
    $u = trim((string)$u);
    if ($u === '') { return '#'; }
    if (!preg_match('#^https?://#i', $u)) { $u = 'https://' . $u; }
    return $u;
}

$t = (int)($_GET['t'] ?? $p['template_choice']);
if ($t < 1 || $t > 3) { $t = 1; }

$toolbar = '<div style="background:#111;color:#fff;padding:10px 16px;font-family:Arial,sans-serif;font-size:14px;display:flex;gap:16px;flex-wrap:wrap;align-items:center;">'
    . '<strong>Preview</strong>'
    . '<a style="color:#8ab4ff" href="templates.php?id=' . $id . '">Change template</a>'
    . '<a style="color:#8ab4ff" href="form.php?id=' . $id . '">Edit</a>'
    . '<a style="color:#8ab4ff" href="manage.php">Manage</a>'
    . '</div>';

include __DIR__ . '/templates/template' . $t . '.php';