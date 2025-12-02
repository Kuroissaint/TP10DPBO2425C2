<?php 
// 1. SET JUDUL & PANGGIL HEADER (PENTING!)
$title = "Hero Profile"; 
include 'views/templates/header.php'; 
?>

<?php if(isset($_GET['msg'])): ?>
    <div style="background: #333; border: 1px solid var(--accent); color: white; padding: 15px; border-radius: 8px; margin-bottom: 20px; text-align: center;">
        <?= htmlspecialchars($_GET['msg']) ?>
    </div>
<?php endif; ?>

<div class="profile-container">
    
    <div class="card hero-section">
        <div style="margin: 20px 0;">
        <?php if ($profile['raw_mana'] <= 0): ?>
                <button class="btn btn-disabled" style="width: 100%; padding: 15px; font-size: 1.1em; background: #555; border: 1px solid #777; color: #aaa;">
                    🛑 EXHAUSTED (Mana 0)
                </button>
                <p style="font-size: 0.8em; color: #e74c3c; margin-top: 5px;">Minum Mana Potion dulu!</p>
            <?php else: ?>
                <a href="index.php?action=adventure" class="btn btn-red" style="width: 100%; box-sizing: border-box; padding: 15px; font-size: 1.1em;">
                    🔥 GO ADVENTURE!
                </a>
                <p style="font-size: 0.8em; color: #888; margin-top: 5px;">Risk: HP & Mana, Reward: Gold</p>
            <?php endif; ?>
        </div>

        <div class="avatar">🧙‍♂️</div>
        <h2><?= $profile['hero_name'] ?></h2>
        <p style="color: var(--text-muted);"><?= $profile['job'] ?> • Lv. <?= $profile['level'] ?></p>
        <div style="margin-bottom: 20px;">
            <div style="display:flex; justify-content:space-between; font-size:0.8em; color:#aaa;">
                <span>EXP</span> 
                <span><?= $profile['xp_current'] ?> / <?= $profile['xp_max'] ?></span>
            </div>
            <div class="stat-bar-container" style="height: 6px; background: #444;">
                <?php 
                    $percentXp = ($profile['xp_current'] / $profile['xp_max']) * 100;
                ?>
                <div class="stat-bar-fill" style="width: <?= $percentXp ?>%; background: var(--accent); box-shadow: 0 0 10px var(--accent);"></div>
            </div>
        </div>
        <div style="background: #111; padding: 10px; border-radius: 8px; margin: 20px 0;">
            <div class="text-gold" style="font-size: 1.2rem;">💰 <?= number_format($profile['gold'] ?? 0) ?> Gold</div>
        </div>

        <div style="text-align:left; margin-bottom:15px;">
            <div style="display:flex; justify-content:space-between; font-size:0.9em">
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

        <div style="text-align:left; margin-bottom:20px;">
            <div style="display:flex; justify-content:space-between; font-size:0.9em">
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
        
        <h4 style="text-align: left; color:#666; text-transform:uppercase; font-size:0.8rem; margin-bottom: 10px;">Attributes</h4>
        <?php foreach($profile['stats'] as $key => $val): ?>
            <div class="stat-row">
                <span style="color:#aaa"><?= $key ?></span> 
                <strong><?= $val ?></strong>
            </div>
        <?php endforeach; ?>
        <div class="stat-row">
            <span style="color:#aaa">Attack</span> 
            <strong style="color: var(--red); font-size:1.1em"><?= $profile['attributes']['Attack'] ?></strong>
        </div>
    </div>

    <div class="card inv-section">
        <h3 style="border-bottom: 1px solid #333; padding-bottom:10px;">🛡️ Equipment</h3>
        
        <?php if(empty($profile['equipment_list'])): ?>
            <div style="padding: 20px; text-align: center; color: #555; border: 2px dashed #333; border-radius: 8px;">
                Belum ada item yang dipakai
            </div>
        <?php else: ?>
            <?php foreach($profile['equipment_list'] as $item): ?>
                <div class="item-box type-<?= $item->type ?>">
                    <div>
                        <div style="font-weight:bold; font-size:1.1em"><?= $item->name ?></div>
                        <span class="badge"><?= $item->type ?></span>
                    </div>
                    <div style="text-align:right; font-size: 0.9em; color: #ccc;">
                        <?= $item->getDetails() ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <h3 style="margin-top: 30px; border-bottom: 1px solid #333; padding-bottom:10px;">🎒 Backpack</h3>
        <?php if(empty($profile['bag_list'])): ?>
             <p style="color:#555">Tas kosong.</p>
        <?php else: ?>
            <?php foreach($profile['bag_list'] as $bag): ?>
                
                <?php 
                    $itemType = $bag['item']->type;
                    $cssClass = 'type-' . $itemType; // type-Weapon, type-Consumable
                ?>

                <div class="item-box <?= $cssClass ?>">
                    <div style="display:flex; align-items:center; gap:10px;">
                        <div style="background:#333; width:40px; height:40px; display:flex; align-items:center; justify-content:center; border-radius:5px;">
                            <?= $itemType == 'Weapon' ? '⚔️' : ($itemType == 'Accessory' ? '💍' : '🧪') ?>
                        </div>
                        <div>
                            <strong><?= $bag['item']->name ?></strong> 
                            <div style="font-size:0.8em; color:#aaa">
                                <?= $bag['quantity'] > 1 ? "Qty: {$bag['quantity']}" : $itemType ?>
                            </div>
                        </div>
                    </div>
                    
                    <?php if ($itemType == 'Consumable'): ?>
                        <a href="index.php?action=use_item&item_id=<?= $bag['item']->id ?>" class="btn btn-green" style="padding: 5px 15px; font-size:0.8em">
                            Use
                        </a>
                    <?php else: ?>
                        <a href="index.php?action=equip&item_id=<?= $bag['item']->id ?>" class="btn btn-blue" style="padding: 5px 15px; font-size:0.8em">
                            Equip
                        </a>
                    <?php endif; ?>
                </div>

            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php include 'views/templates/footer.php'; ?>