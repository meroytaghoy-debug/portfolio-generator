<?php
require_once __DIR__ . '/db.php';
$pageTitle = $pageTitle ?? 'Portfolio Generator';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($pageTitle) ?></title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<nav class="navbar">
  <a class="brand" href="index.php">Portfolio Generator</a>
  <div class="nav-links">
    <a href="index.php">Home</a>
    <a href="form.php">Create</a>
    <a href="manage.php">Manage</a>
  </div>
</nav>
<main class="container">