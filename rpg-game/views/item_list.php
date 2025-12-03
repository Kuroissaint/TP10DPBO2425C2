<?php 
$title = "Admin Panel"; 
include 'views/templates/header.php'; 
?>

<div class="flex-between-center">
    <h2>🎒 Master Data Items</h2>
    <a href="index.php?action=create" class="btn btn-green">+ Tambah Item Baru</a>
</div>

<table class="table-admin">
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
                <td class="text-muted"><?= $item->getDetails() ?></td>
                <td>
                    <a href="index.php?action=edit_item&id=<?= $item->id ?>" class="btn btn-blue btn-sm">Edit</a>
                    <a href="index.php?action=delete&id=<?= $item->id ?>" class="btn btn-red btn-sm" onclick="return confirm('Yakin hapus item ini?');">Hapus</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include 'views/templates/footer.php'; ?>