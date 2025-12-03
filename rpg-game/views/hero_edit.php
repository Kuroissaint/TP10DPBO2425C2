<?php $title = "Edit Hero"; include 'views/templates/header.php'; ?>

<div class="form-container">
    <div class="text-center mb-20">
        <h2 class="mb-0">✏️ Edit Hero</h2>
        <p class="text-muted small">Ubah takdir pahlawanmu.</p>
    </div>
    
    <form action="index.php?action=update_hero" method="POST">
        <input type="hidden" name="id" value="<?= $hero->id ?>">

        <label>Hero Name</label>
        <input type="text" name="name" required value="<?= $hero->name ?>" class="w-100">

        <label>Job Class</label>
        <select name="job_class" required class="w-100">
            <option value="Warrior" <?= $hero->job_class == 'Warrior' ? 'selected' : '' ?>>⚔️ Warrior</option>
            <option value="Mage" <?= $hero->job_class == 'Mage' ? 'selected' : '' ?>>🧙‍♂️ Mage</option>
            <option value="Assassin" <?= $hero->job_class == 'Assassin' ? 'selected' : '' ?>>🗡️ Assassin</option>
        </select>

        <div class="alert-box alert-error small mt-20 text-left">
            <strong>⚠️ Perhatian:</strong><br>
            Mengubah Job Class akan mereset Base Stats (STR/AGI/INT) hero ini sesuai job barunya!
        </div>

        <button type="submit" class="btn btn-green w-100 mt-20">Update Hero</button>
        
        <div class="text-center mt-20">
            <a href="index.php?action=home" class="link-muted">&laquo; Batal</a>
        </div>
    </form>
</div>

<?php include 'views/templates/footer.php'; ?>