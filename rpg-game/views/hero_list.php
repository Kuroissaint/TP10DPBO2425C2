<?php $title = "Select Your Hero"; include 'views/templates/header.php'; ?>

<div class="container">
    <div class="flex-between-center mb-20">
        <h2>🦸‍♂️ Select Your Hero</h2>
        <a href="index.php?action=create_hero" class="btn btn-green">+ Create New Hero</a>
    </div>

    <div class="grid-responsive-lg">
        <?php foreach ($heroes as $hero): ?>
            <div class="card card-hero-action">
                <div class="flex-align-center gap-10">
                    <div class="avatar">
                        <?= $hero['job_class'] == 'Mage' ? '🧙‍♂️' : '⚔️' ?>
                    </div>
                    <div>
                        <h3 class="mb-0"><?= $hero['name'] ?></h3>
                        <p class="text-muted mb-0 small">
                            <?= $hero['job_class'] ?> • Gold: <?= number_format($hero['gold']) ?>
                        </p>
                    </div>
                </div>
                
                <hr class="border-dark my-15">

                <div class="flex-between-center gap-10">
                    <a href="index.php?action=select_hero&id=<?= $hero['id'] ?>" class="btn btn-blue w-100 text-center">
                        Play
                    </a>
                    <a href="index.php?action=edit_hero&id=<?= $hero['id'] ?>" class="btn btn-orange">
                        ✏️
                    </a>
                    <a href="index.php?action=delete_hero&id=<?= $hero['id'] ?>" class="btn btn-red" onclick="return confirm('Permanently delete this hero?');">
                        🗑️
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include 'views/templates/footer.php'; ?>