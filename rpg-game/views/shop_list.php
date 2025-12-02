<!DOCTYPE html>
<html>
<head>
    <title>Item Shop</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .shop-header { background: #2c3e50; padding: 20px; text-align: center; margin-bottom: 20px; border-radius: 8px; }
        .gold-display { color: #f1c40f; font-size: 1.5em; font-weight: bold; }
        .price-tag { color: #f1c40f; font-weight: bold; }
        
        /* Tombol Disabled (Gak punya duit) */
        .btn-disabled { background: #555; cursor: not-allowed; pointer-events: none; opacity: 0.6; }
    </style>
</head>
<body>

    <div class="shop-header">
        <h2>🛒 Black Market</h2>
        <div class="gold-display">💰 My Gold: <?= number_format($currentHero->gold) ?></div>
        <br>
        <a href="index.php?action=profile" class="btn btn-blue">🏠 Back to Profile</a>
    </div>

    <?php if(isset($_GET['msg'])): ?>
        <div style="background: #444; padding: 10px; margin-bottom: 20px; border: 1px solid #777; text-align: center;">
            <?= htmlspecialchars($_GET['msg']) ?>
        </div>
    <?php endif; ?>

    <div style="display: flex; flex-wrap: wrap; gap: 15px; justify-content: center;">
        <?php foreach ($allItems as $item): ?>
            
            <div class="card" style="width: 250px; text-align: center;">
                <h3><?= $item->name ?></h3>
                <span class="badge"><?= $item->type ?></span>
                
                <p class="price-tag">🪙 <?= number_format($item->price) ?></p>
                <p style="font-size: 0.9em; color: #aaa;"><?= $item->getDetails() ?></p>

                <?php if ($currentHero->gold >= $item->price): ?>
                    <a href="index.php?action=buy&item_id=<?= $item->id ?>" class="btn btn-green">
                        Beli Sekarang
                    </a>
                <?php else: ?>
                    <a href="#" class="btn btn-disabled">
                        Uang Kurang
                    </a>
                <?php endif; ?>
            </div>

        <?php endforeach; ?>
    </div>

</body>
</html>