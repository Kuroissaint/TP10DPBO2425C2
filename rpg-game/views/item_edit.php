<?php $title = "Edit Item"; include 'views/templates/header.php'; ?>

<div class="form-container">
    <h2>✏️ Edit Item: <?= $item->name ?></h2>
    
    <form action="index.php?action=update_item" method="POST">
        <input type="hidden" name="id" value="<?= $item->id ?>">
        <input type="hidden" name="type" value="<?= $item->type ?>">

        <label>Item Name</label>
        <input type="text" name="name" required value="<?= $item->name ?>">

        <label>Price</label>
        <input type="number" name="price" required value="<?= $item->price ?>">

        <label>Type</label>
        <input type="text" value="<?= $item->type ?>" disabled style="background: #333; color: #888;">

        <?php if ($item instanceof Weapon): ?>
            <div id="weaponInputs">
                <label>Attack Power</label>
                <input type="number" name="attack" value="<?= $item->attack_power ?>">
                <label>Element</label>
                <input type="text" name="element" value="<?= $item->element ?>">
            </div>
        <?php endif; ?>

        <?php if ($item instanceof Consumable): ?>
            <div id="consumableInputs">
                <label>Recover HP</label>
                <input type="number" name="hp" value="<?= $item->recover_hp ?>">
                <label>Recover Mana</label>
                <input type="number" name="mana" value="<?= $item->recover_mana ?>">
            </div>
        <?php endif; ?>

        <?php if ($item instanceof Accessory): ?>
            <div id="accessoryInputs">
                <label>Bonus STR</label>
                <input type="number" name="str" value="<?= $item->bonus_str ?>">
                <label>Bonus AGI</label>
                <input type="number" name="agi" value="<?= $item->bonus_agi ?>">
                <label>Bonus INT</label>
                <input type="number" name="int" value="<?= $item->bonus_int ?>">
            </div>
        <?php endif; ?>

        <button type="submit" class="btn btn-green">Update Item</button>
        <br><br>
        <a href="index.php?action=admin" style="color: #aaa;">Cancel</a>
    </form>
</div>

<?php include 'views/templates/footer.php'; ?>