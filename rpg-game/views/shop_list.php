<?php 
$title = "Item Shop"; 
include 'views/templates/header.php'; 
?>

<div class="card" style="margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center;">
    <div>
        <h2 style="margin:0">Welcome, Traveler!</h2>
        <p style="margin:0; color:var(--text-muted)">Beli perlengkapan terkuat di sini.</p>
    </div>
    <div style="text-align: right;">
        <span style="color:var(--text-muted); font-size:0.9em">YOUR BALANCE</span>
        <div class="text-gold" style="font-size: 1.8em;">💰 <?= number_format($currentHeroGold) ?></div>    </div>
</div>

<?php if(isset($_GET['msg'])): ?>
    <div style="background: var(--green); color:black; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-weight:bold; text-align:center;">
        <?= htmlspecialchars($_GET['msg']) ?>
    </div>
<?php endif; ?>

<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 20px;">
    <?php foreach ($allItems as $item): ?>
        
        <div class="card" style="display: flex; flex-direction: column; justify-content: space-between;">
            <div style="text-align: center; margin-bottom: 15px;">
                <div style="font-size: 3rem; margin-bottom: 10px;">
                    <?= $item->type == 'Weapon' ? '⚔️' : ($item->type == 'Accessory' ? '💍' : '🧪') ?>
                </div>
                <h3 style="font-size: 1.1rem; margin-bottom: 5px;"><?= $item->name ?></h3>
                <span class="badge"><?= $item->type ?></span>
            </div>
            
            <div style="background: #111; padding: 10px; border-radius: 6px; font-size: 0.85em; color: #ccc; margin-bottom: 15px; text-align: center;">
                <?= $item->getDetails() ?>
            </div>

            <div style="margin-top: auto;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                    <span style="color:#aaa; font-size:0.9em">Price</span>
                    <span class="text-gold" style="font-weight:bold">🪙 <?= number_format($item->price) ?></span>
                </div>

                <?php if ($currentHeroGold >= $item->price): ?>
                    <a href="index.php?action=buy&item_id=<?= $item->id ?>" class="btn btn-green" style="width: 100%; text-align: center; box-sizing: border-box;">
                        BUY
                    </a>
                <?php else: ?>
                    <button class="btn btn-disabled" style="width: 100%;">
                        Mahal Banget
                    </button>
                <?php endif; ?>
            </div>
        </div>

    <?php endforeach; ?>
</div>

<?php include 'views/templates/footer.php'; ?>