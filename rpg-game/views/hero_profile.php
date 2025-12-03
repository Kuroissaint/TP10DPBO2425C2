<?php 
$title = "Hero Profile"; 
include 'views/templates/header.php'; 
?>

<?php if(isset($_GET['msg'])): ?>
    <div class="alert-box alert-info">
        <?= htmlspecialchars($_GET['msg']) ?>
    </div>
<?php endif; ?>

<div class="profile-container">
    
    <div class="card hero-section text-center">
        
        <div class="hero-action-area">
            <?php if ($profile['raw_mana'] <= 0): ?>
                <button class="btn btn-disabled w-100 mb-10">🛑 EXHAUSTED (Mana 0)</button>
                <p class="text-muted small">Minum Mana Potion dulu!</p>
            <?php else: ?>
                <a href="index.php?action=adventure" class="btn btn-red w-100 mb-10">🔥 GO ADVENTURE!</a>
                <p class="text-muted small">Risk: HP & Mana, Reward: Gold</p>
            <?php endif; ?>
        </div>

        <div class="avatar">🧙‍♂️</div>
        <h2><?= $profile['hero_name'] ?></h2>
        <p class="text-muted"><?= $profile['job'] ?> • Lv. <?= $profile['level'] ?></p>

        <div class="mb-20">
            <div class="stat-header text-muted">
                <span>EXP</span> <span><?= $profile['xp_current'] ?> / <?= $profile['xp_max'] ?></span>
            </div>
            <div class="stat-bar-container bg-dark-grey">
                <?php $percentXp = ($profile['xp_current'] / $profile['xp_max']) * 100; ?>
                <div class="stat-bar-fill" style="width: <?= $percentXp ?>%; background: var(--accent);"></div>
            </div>
        </div>

        <div class="hero-gold-box">
            <span class="hero-gold-text">💰 <?= number_format($profile['gold'] ?? 0) ?> Gold</span>
        </div>

        <div class="stat-row-group">
            <div class="stat-header">
                <span>HP</span> <span><?= $profile['attributes']['HP'] ?></span>
            </div>
            <div class="stat-bar-container">
                <?php 
                    $hpParts = explode(' / ', $profile['attributes']['HP']);
                    $percentHp = ($hpParts[0] / $hpParts[1]) * 100;
                ?>
                <div class="stat-bar-fill bar-hp" style="width: <?= $percentHp ?>%;"></div>
            </div>
        </div>

        <div class="stat-row-group">
            <div class="stat-header">
                <span>Mana</span> <span><?= $profile['attributes']['Mana'] ?></span>
            </div>
            <div class="stat-bar-container">
                <?php 
                    $manaParts = explode(' / ', $profile['attributes']['Mana']);
                    $percentMana = ($manaParts[0] / $manaParts[1]) * 100;
                ?>
                <div class="stat-bar-fill bar-mana" style="width: <?= $percentMana ?>%;"></div>
            </div>
        </div>
        
        <h4 class="text-left text-muted uppercase small mb-10">Attributes</h4>
        <?php foreach($profile['stats'] as $key => $val): ?>
            <div class="stat-row">
                <span class="text-muted"><?= $key ?></span> 
                <strong><?= $val ?></strong>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="card inv-section">
        <h3 class="border-bottom pb-10">🛡️ Equipment</h3>
        
        <?php if(empty($profile['equipment_list'])): ?>
            <div class="empty-state-box">Belum ada item yang dipakai</div>
        <?php else: ?>
            <?php foreach($profile['equipment_list'] as $item): ?>
                <div class="item-box type-<?= $item->type ?>">
                    <div>
                        <div class="font-bold"><?= $item->name ?></div>
                        <span class="badge"><?= $item->type ?></span>
                    </div>
                    <div class="text-right text-muted small">
                        <?= $item->getDetails() ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <h3 class="mt-20 border-bottom pb-10">🎒 Backpack</h3>
        <?php if(empty($profile['bag_list'])): ?>
             <p class="text-muted">Tas kosong.</p>
        <?php else: ?>
            <?php foreach($profile['bag_list'] as $bag): ?>
                <?php 
                    $itemType = $bag['item']->type;
                    $cssClass = 'type-' . $itemType; 
                ?>
                <div class="item-box <?= $cssClass ?>">
                    <div class="flex-align-center gap-10">
                        <div class="item-icon-box">
                            <?= $itemType == 'Weapon' ? '⚔️' : ($itemType == 'Accessory' ? '💍' : '🧪') ?>
                        </div>
                        <div>
                            <strong><?= $bag['item']->name ?></strong> 
                            <div class="text-muted small">
                                <?= $bag['quantity'] > 1 ? "Qty: {$bag['quantity']}" : $itemType ?>
                            </div>
                        </div>
                    </div>
                    
                    <?php if ($itemType == 'Consumable'): ?>
                        <a href="index.php?action=use_item&item_id=<?= $bag['item']->id ?>" class="btn btn-green btn-sm">Use</a>
                    <?php else: ?>
                        <a href="index.php?action=equip&item_id=<?= $bag['item']->id ?>" class="btn btn-blue btn-sm">Equip</a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php include 'views/templates/footer.php'; ?>