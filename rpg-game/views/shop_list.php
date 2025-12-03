<?php 
$title = "Item Shop"; 
include 'views/templates/header.php'; 
?>

<div class="card card-shop-header flex-between-center">
    <div>
        <h2 class="mb-0">Welcome, Traveler!</h2>
        <p class="text-muted mb-0">Beli perlengkapan terkuat di sini.</p>
    </div>
    <div class="text-right">
        <span class="text-muted small">YOUR BALANCE</span>
        <div class="text-gold" style="font-size: 1.8em;">💰 <?= number_format($currentHeroGold) ?></div>
    </div>
</div>

<?php if(isset($_GET['msg'])): ?>
    <div class="alert-box alert-success">
        <?= htmlspecialchars($_GET['msg']) ?>
    </div>
<?php endif; ?>

<div class="grid-responsive">
    <?php foreach ($allItems as $item): ?>
        
        <div class="card flex-col-between">
            <div class="text-center mb-15">
                <div class="item-emoji-lg">
                    <?= $item->type == 'Weapon' ? '⚔️' : ($item->type == 'Accessory' ? '💍' : '🧪') ?>
                </div>
                <h3 class="font-md mb-0"><?= $item->name ?></h3>
                <span class="badge"><?= $item->type ?></span>
            </div>
            
            <div class="shop-details-box">
                <?= $item->getDetails() ?>
            </div>

            <div class="mt-auto">
                <div class="flex-between-center mb-10">
                    <span class="text-muted small">Price</span>
                    <span class="price-tag">🪙 <?= number_format($item->price) ?></span>
                </div>

                <?php if ($currentHeroGold >= $item->price): ?>
                    <a href="index.php?action=buy&item_id=<?= $item->id ?>" class="btn btn-green w-100 text-center">
                        BUY
                    </a>
                <?php else: ?>
                    <button class="btn btn-disabled w-100">
                        Mahal Banget
                    </button>
                <?php endif; ?>
            </div>
        </div>

    <?php endforeach; ?>
</div>

<?php include 'views/templates/footer.php'; ?>