<?php $title = "Select Your Hero"; include 'views/templates/header.php'; ?>

<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2>🦸‍♂️ Select Your Hero</h2>
        <a href="index.php?action=create_hero" class="btn btn-green">+ Create New Hero</a>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
        <?php foreach ($heroes as $hero): ?>
            <div class="card" style="position: relative;">
                <div style="display: flex; gap: 15px;">
                    <div class="avatar" style="width: 60px; height: 60px; font-size: 30px;">
                        <?= $hero['job_class'] == 'Mage' ? '🧙‍♂️' : '⚔️' ?>
                    </div>
                    <div>
                        <h3 style="margin: 0; font-size: 1.2rem;"><?= $hero['name'] ?></h3>
                        <p style="color: var(--text-muted); margin: 0; font-size: 0.9rem;">
                            <?= $hero['job_class'] ?> • Gold: <?= number_format($hero['gold']) ?>
                        </p>
                    </div>
                </div>
                
                <hr style="border-color: #333; margin: 15px 0;">

                <div style="display: flex; gap: 10px;">
                    <a href="index.php?action=select_hero&id=<?= $hero['id'] ?>" class="btn btn-blue" style="flex: 1; text-align: center;">
                        Play
                    </a>
                    <a href="index.php?action=edit_hero&id=<?= $hero['id'] ?>" class="btn btn-orange">
                        ✏️
                    </a>
                    <a href="index.php?action=delete_hero&id=<?= $hero['id'] ?>" class="btn btn-red" onclick="return confirm('Kill this hero permanently?');">
                        🗑️
                    </a>

                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include 'views/templates/footer.php'; ?>