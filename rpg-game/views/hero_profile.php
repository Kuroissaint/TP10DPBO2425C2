<!DOCTYPE html>
<html lang="id">
<head>
    <title>RPG Profile</title>
    <link rel="stylesheet" href="css/style.css">
    
    </style>
</head>
<body>
<div style="position: absolute; top: 20px; right: 20px;">
        <a href="index.php?action=shop" style="background: #e67e22; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold;">
            ⚙️ Buy Items (Admin)
        </a>
        <a href="index.php?action=admin" style="background: #e67e22; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold;">
            ⚙️ Manage Items (Admin)
        </a>
    </div>
    <div class="container">
        <div class="card hero-section">
            <div class="avatar">🧙‍♂️</div>
            <h2><?= $profile['hero_name'] ?></h2>
            <p style="color: #aaa;"><?= $profile['job'] ?> - Lv. <?= $profile['level'] ?></p>
            
            <hr style="border-color: #444">

            <div class="stat-row">
                <span>HP</span> <span class="hp-text"><?= $profile['attributes']['HP'] ?></span>
            </div>
            <div class="stat-row">
                <span>Mana</span> <span class="mana-text"><?= $profile['attributes']['Mana'] ?></span>
            </div>
            <div class="stat-row">
                <span>⚔️ Attack Power</span> <span class="atk-text"><?= $profile['attributes']['Attack'] ?></span>
            </div>

            <br>
            <h4 style="text-align: left; margin-bottom: 10px;">Main Stats</h4>
            <?php foreach($profile['stats'] as $key => $val): ?>
                <div class="stat-row">
                    <span><?= $key ?></span> <span><?= $val ?></span>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="card inv-section">
            <h3>🛡️ Equipped Items (Sedang Dipakai)</h3>
            <?php if(empty($profile['equipment_list'])): ?>
                <p style="color:#777"><i>Hero ini telanjang (tidak pakai item).</i></p>
            <?php else: ?>
                <?php foreach($profile['equipment_list'] as $item): ?>
                    <div class="item-box type-<?= $item->type ?>">
                        <div>
                            <strong><?= $item->name ?></strong>
                            <span class="badge"><?= $item->type ?></span>
                        </div>
                        <div style="font-size: 0.9em; color: #ccc;">
                            <?= $item->getDetails() ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <h3 style="margin-top: 30px;">🎒 Backpack (Di Tas)</h3>
            <?php foreach($profile['bag_list'] as $bag): ?>
                <div class="item-box type-Consumable">
                    <div>
                        <strong><?= $bag['item']->name ?></strong> 
                        <span style="font-size:0.8em">x<?= $bag['quantity'] ?></span>
                    </div>
                    <button style="background: #2ecc71; border:none; padding:5px 10px; cursor:pointer;">Use</button>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

</body>
</html>