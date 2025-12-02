<?php
require_once 'config/Database.php';
require_once 'Weapon.php';
require_once 'Accessory.php';
require_once 'Consumable.php';

class ItemRepository {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllItems() {
        // QUERY SAKTI: Menggabungkan semua tabel anak
        // Kita pakai LEFT JOIN agar semua data terambil
        $query = "
            SELECT 
                i.*, 
                w.attack_power, w.element,
                a.bonus_str, a.bonus_agi, a.bonus_int,
                c.recover_hp, c.recover_mana
            FROM items i
            LEFT JOIN weapons w ON i.id = w.item_id
            LEFT JOIN accessories a ON i.id = a.item_id
            LEFT JOIN consumables c ON i.id = c.item_id
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        $items = [];

        // FACTORY LOGIC: Mengubah baris DB menjadi Objek yang Tepat
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $itemObj = null;

            switch ($row['type']) {
                case 'Weapon':
                    $itemObj = new Weapon($row);
                    break;
                case 'Accessory':
                    $itemObj = new Accessory($row);
                    break;
                case 'Consumable':
                    $itemObj = new Consumable($row);
                    break;
            }

            if ($itemObj) {
                $items[] = $itemObj;
            }
        }

        return $items;
    }

    public function getInventoryByHero($heroId) {
        $query = "
            SELECT 
                inv.is_equipped, inv.quantity,
                i.*,                           
                w.attack_power, w.element,     
                a.bonus_str, 
                a.bonus_agi, 
                a.bonus_int,  -- <--- TAMBAH a.bonus_int
                c.recover_hp, 
                c.recover_mana            -- <--- TAMBAH c.recover_mana
            FROM inventory inv
            JOIN items i ON inv.item_id = i.id
            LEFT JOIN weapons w ON i.id = w.item_id
            LEFT JOIN accessories a ON i.id = a.item_id
            LEFT JOIN consumables c ON i.id = c.item_id
            WHERE inv.hero_id = :hero_id
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':hero_id', $heroId);
        $stmt->execute();
        
        $inventoryList = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Gunakan Factory Logic yang sama kayak tadi
            $itemObj = null;
            
            // Cek Type untuk bikin Object yang sesuai
            switch ($row['type']) {
                case 'Weapon': $itemObj = new Weapon($row); break;
                case 'Accessory': $itemObj = new Accessory($row); break;
                case 'Consumable': $itemObj = new Consumable($row); break;
            }

            if ($itemObj) {
                // Kita bungkus item + status inventory-nya
                // Biar tau item ini lagi dia pakai atau enggak
                $inventoryList[] = [
                    'item' => $itemObj,         // Object (Weapon/Accessory)
                    'is_equipped' => $row['is_equipped'],
                    'quantity' => $row['quantity']
                ];
            }
        }

        return $inventoryList;
    }
    // --- CREATE WEAPON ---
    public function createWeapon($name, $price, $img, $atk, $element) {
        try {
            // 1. Mulai Transaksi (Kunci Database)
            $this->conn->beginTransaction();

            // 2. Insert ke Bapak (Tabel ITEMS)
            $sql1 = "INSERT INTO items (name, type, price, image_url) VALUES (?, 'Weapon', ?, ?)";
            $stmt1 = $this->conn->prepare($sql1);
            $stmt1->execute([$name, $price, $img]);
            
            // 3. Ambil ID yang baru aja dibuat
            $lastId = $this->conn->lastInsertId();

            // 4. Insert ke Anak (Tabel WEAPONS)
            $sql2 = "INSERT INTO weapons (item_id, attack_power, element) VALUES (?, ?, ?)";
            $stmt2 = $this->conn->prepare($sql2);
            $stmt2->execute([$lastId, $atk, $element]);

            // 5. Kalau lancar, simpan permanen
            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            // 6. Kalau error, batalkan semua (Rollback)
            $this->conn->rollBack();
            echo "Gagal membuat senjata: " . $e->getMessage();
            return false;
        }
    }

    // --- CREATE CONSUMABLE ---
    public function createConsumable($name, $price, $img, $hp, $mana) {
        try {
            $this->conn->beginTransaction();

            // Insert Items
            $stmt = $this->conn->prepare("INSERT INTO items (name, type, price, image_url) VALUES (?, 'Consumable', ?, ?)");
            $stmt->execute([$name, $price, $img]);
            $lastId = $this->conn->lastInsertId();

            // Insert Consumables
            $stmt2 = $this->conn->prepare("INSERT INTO consumables (item_id, recover_hp, recover_mana) VALUES (?, ?, ?)");
            $stmt2->execute([$lastId, $hp, $mana]);

            $this->conn->commit();
        } catch (Exception $e) {
            $this->conn->rollBack();
        }
    }

    // --- CREATE ACCESSORY ---
    public function createAccessory($name, $price, $img, $str, $agi, $int) {
        try {
            $this->conn->beginTransaction();

            // 1. Insert ke Items
            $stmt = $this->conn->prepare("INSERT INTO items (name, type, price, image_url) VALUES (?, 'Accessory', ?, ?)");
            $stmt->execute([$name, $price, $img]);
            $lastId = $this->conn->lastInsertId();

            // 2. Insert ke Accessories
            $stmt2 = $this->conn->prepare("INSERT INTO accessories (item_id, bonus_str, bonus_agi, bonus_int) VALUES (?, ?, ?, ?)");
            $stmt2->execute([$lastId, $str, $agi, $int]);

            $this->conn->commit();
        } catch (Exception $e) {
            $this->conn->rollBack();
            echo "Error: " . $e->getMessage();
        }
    }

    // --- DELETE (Gampang banget karena Cascade) ---
    public function deleteItem($id) {
        // Cukup hapus bapaknya, anaknya ikut hilang otomatis
        $stmt = $this->conn->prepare("DELETE FROM items WHERE id = ?");
        $stmt->execute([$id]);
    }
}
?>