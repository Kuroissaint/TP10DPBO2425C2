<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'RPG Game' ?></title> <link rel="stylesheet" href="css/style.css">
</head>
<body>

<nav class="navbar">
        <div class="nav-brand">⚔️ RPG SYSTEM</div>
        <div class="nav-links">
            
            <a href="index.php?action=home" style="color: #e74c3c;">
                🔄 Switch Hero
            </a>

            <a href="index.php?action=profile" class="<?= ($_GET['action']??'') == 'profile' ? 'active' : '' ?>">
                Hero Profile
            </a>
            <a href="index.php?action=shop" class="<?= ($_GET['action']??'') == 'shop' ? 'active' : '' ?>">
                Item Shop
            </a>
            <a href="index.php?action=admin" class="<?= ($_GET['action']??'') == 'admin' ? 'active' : '' ?>">
                Admin Panel
            </a>
        </div>
    </nav>

    <div class="container" style="margin-top: 20px;">