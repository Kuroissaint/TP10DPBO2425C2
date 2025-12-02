<!DOCTYPE html>
<html>
<head>
    <title>Game Admin Panel</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

   <div style="display: flex; justify-content: space-between; align-items: center;">
        <h2>🎒 Master Data Items</h2>
        
        <a href="index.php?action=profile" style="background: #3498db; color: white; padding: 8px 15px; text-decoration: none; border-radius: 4px;">
            🎮 Back to Game Profile
        </a>
    </div>

    <hr>
    
    <a href="index.php?action=create" class="btn btn-green">+ Tambah Item Baru</a>

    <table>
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
                    <td>
                        <span class="badge"><?= $item->type ?></span>
                    </td>
                    <td>Rp <?= number_format($item->price) ?></td>
                    <td style="color: #aaa;">
                        <?= $item->getDetails() ?>
                    </td>
                    <td>
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

</body>
</html>