<?php 
$title = "Admin Panel"; 
include 'views/templates/header.php'; 
?>

<div style="display: flex; justify-content: space-between; align-items: center;">
    <h2>🎒 Master Data Items</h2>
    <a href="index.php?action=create" class="btn btn-green">+ Tambah Item Baru</a>
</div>

<table style="width: 100%; margin-top: 20px;">
    <thead>
        <tr>
            <th>Name</th>
            <th>Type</th>
            <th>Price</th>
            <th>Details (Stats)</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($allItems as $item): ?>
            <tr>
                <td><?= $item->name ?></td>
                <td><span class="badge"><?= $item->type ?></span></td>
                <td>🪙 <?= number_format($item->price) ?></td>
                <td style="color: #aaa;"><?= $item->getDetails() ?></td>
                <td>
                <a href="index.php?action=edit_item&id=<?= $item->id ?>" class="btn btn-blue" style="font-size: 0.8em;">
                    Edit
                </a>
                    <a href="index.php?action=delete&id=<?= $item->id ?>" 
                        class="btn btn-red"
                        onclick="return confirm('Yakin hapus item ini?');">
                        Hapus
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include 'views/templates/footer.php'; ?>