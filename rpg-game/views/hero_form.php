<?php $title = "Create New Hero"; include 'views/templates/header.php'; ?>

<div class="form-container">
    <div class="text-center mb-20">
        <h2 class="mb-0">✨ Summon New Hero</h2>
        <p class="text-muted small">Tentukan takdir pahlawan barumu.</p>
    </div>
    
    <form action="index.php?action=store_hero" method="POST">
        
        <label>Hero Name</label>
        <input type="text" name="name" required placeholder="Ex: Aragorn" class="w-100">

        <label>Job Class</label>
        <select name="job_class" required class="w-100">
            <option value="Warrior">⚔️ Warrior (High STR)</option>
            <option value="Mage">🧙‍♂️ Mage (High INT)</option>
            <option value="Assassin">🗡️ Assassin (High AGI)</option>
        </select>

        <div class="alert-box alert-info small mt-20 text-left">
            <strong>Info Job:</strong><br>
            • Warrior: Kuat fisik, HP tebal.<br>
            • Mage: Ahli sihir, Mana banyak.<br>
            • Assassin: Cepat, serangan kritis.
        </div>

        <button type="submit" class="btn btn-green w-100 mt-20">Summon Hero</button>
        
        <div class="text-center mt-20">
            <a href="index.php?action=home" class="link-muted">&laquo; Batal</a>
        </div>
    </form>
</div>

<?php include 'views/templates/footer.php'; ?>