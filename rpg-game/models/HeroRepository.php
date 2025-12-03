<?php
require_once 'config/Database.php';
require_once 'models/Hero.php';

class HeroRepository {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllHeroes() {
        $stmt = $this->conn->prepare("SELECT * FROM heroes");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // CREATE
    public function createHero($name, $job, $str, $agi, $int) {
        // PERBAIKAN: Pakai rumus dari Model
        $hp = Hero::calculateMaxHp($str);
        $mana = Hero::calculateMaxMana($int);

        $query = "INSERT INTO heroes (name, job_class, base_str, base_agi, base_int, current_hp, max_hp, current_mana, max_mana) 
                  VALUES (:name, :job, :str, :agi, :int, :hp, :hp, :mana, :mana)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            ':name' => $name, ':job' => $job, 
            ':str' => $str, ':agi' => $agi, ':int' => $int, 
            ':hp' => $hp, ':mana' => $mana
        ]);
    }
    // READ (Single)
    public function getHeroById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM heroes WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? new Hero($row) : null;
    }

    private function getRealMaxStats($heroId) {
        // 1. Ambil Base Stats
        $hero = $this->getHeroById($heroId);
        
        // 2. Hitung Bonus dari Equipment (Accessory)
        $stmt = $this->conn->prepare("
            SELECT SUM(a.bonus_str) as total_bonus_str, 
                   SUM(a.bonus_int) as total_bonus_int
            FROM inventory inv
            JOIN accessories a ON inv.item_id = a.item_id
            WHERE inv.hero_id = ? AND inv.is_equipped = 1
        ");
        $stmt->execute([$heroId]);
        $bonus = $stmt->fetch(PDO::FETCH_ASSOC);

        $totalStr = $hero->base_str + ($bonus['total_bonus_str'] ?? 0);
        $totalInt = $hero->base_int + ($bonus['total_bonus_int'] ?? 0);

        return [
            'real_max_hp' => $totalStr * 20,
            'real_max_mana' => $totalInt * 15
        ];
    }

    // UPDATE
    public function updateHero($id, $name, $job, $str, $agi, $int) {
        $query = "UPDATE heroes SET name=:name, job_class=:job, base_str=:str, base_agi=:agi, base_int=:int WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':name' => $name, ':job' => $job, ':str' => $str, ':agi' => $agi, ':int' => $int, ':id' => $id]);
    }

    // DELETE
    public function deleteHero($id) {
        $stmt = $this->conn->prepare("DELETE FROM heroes WHERE id = ?");
        $stmt->execute([$id]);
    }

    // BELI ITEM
    public function buyItem($heroId, $itemId) {
        try {
            $this->conn->beginTransaction();

            // 1. Cek Uang Hero
            $stmtHero = $this->conn->prepare("SELECT gold FROM heroes WHERE id = ?");
            $stmtHero->execute([$heroId]);
            $heroGold = $stmtHero->fetchColumn();

            // 2. Cek Harga Item
            $stmtItem = $this->conn->prepare("SELECT price FROM items WHERE id = ?");
            $stmtItem->execute([$itemId]);
            $itemPrice = $stmtItem->fetchColumn();

            // 3. Validasi Uang
            if ($heroGold < $itemPrice) {
                throw new Exception("Uang tidak cukup!");
            }

            // 4. Kurangi Gold
            $newGold = $heroGold - $itemPrice;
            $stmtUpdate = $this->conn->prepare("UPDATE heroes SET gold = ? WHERE id = ?");
            $stmtUpdate->execute([$newGold, $heroId]);

            // 5. CEK INVENTORY (LOGIC BARU ANTI-DUPLIKAT) 🛡️
            $stmtCek = $this->conn->prepare("SELECT quantity FROM inventory WHERE hero_id = ? AND item_id = ?");
            $stmtCek->execute([$heroId, $itemId]);
            $existingItem = $stmtCek->fetch(PDO::FETCH_ASSOC);

            if ($existingItem) {
                // Kalo udah punya, update Qty + 1
                $stmtInv = $this->conn->prepare("UPDATE inventory SET quantity = quantity + 1 WHERE hero_id = ? AND item_id = ?");
                $stmtInv->execute([$heroId, $itemId]);
            } else {
                // Kalo belum punya, Insert baru (Qty 1)
                $stmtInv = $this->conn->prepare("INSERT INTO inventory (hero_id, item_id, is_equipped, quantity) VALUES (?, ?, 0, 1)");
                $stmtInv->execute([$heroId, $itemId]);
            }

            $this->conn->commit();
            return "Berhasil membeli item! Sisa uang: $newGold";

        } catch (Exception $e) {
            $this->conn->rollBack();
            return "Gagal: " . $e->getMessage();
        }
    }

    public function adventure($heroId) {

        $hero = $this->getHeroById($heroId);

        // 1. CEK MANA DULU! (LOGIC BARU)
        if ($hero->current_mana <= 0) {
            return ["status" => "failed", "msg" => "🛑 Mana habis! Istirahat dulu atau minum potion."];
        }

        // 1. Randomize Hasil
        $goldGain = rand(500, 1000);
        $xpGain = rand(10, 1000);
        $hpLoss = rand(100, 500);
        $manaLoss = rand(50, 300);

        // 2. Ambil Status Hero Saat Ini
        $hero = $this->getHeroById($heroId);
        
        // Cek apakah HP cukup?
        if ($hero->current_hp <= $hpLoss) {
            return ["status" => "dead", "msg" => "💀 Hero mati saat bertualang! (HP Habis)"];
        }

        // 3. Update Database
        $currentXp = $hero->xp + $xpGain;
        $currentLevel = $hero->level;
        $reqXp = Hero::calculateReqXp($currentLevel);

        while ($currentXp >= $reqXp) {
            $currentXp -= $reqXp;
            $currentLevel++;
            
            $hero->base_str += 5;
            $hero->base_agi += 2;
            $hero->base_int += 2;
            
            // PERBAIKAN: Update Max HP/Mana pakai rumus Model
            $hero->max_hp = Hero::calculateMaxHp($hero->base_str);
            $hero->max_mana = Hero::calculateMaxMana($hero->base_int);

            $hero->current_hp = $hero->max_hp;
            $hero->current_mana = $hero->max_mana;
            
            $reqXp = Hero::calculateReqXp($currentLevel); // Update req untuk loop berikutnya
        }

        if ($levelUpMsg == "") {
            $newHp = $hero->current_hp - $hpLoss;
            $newMana = max(0, $hero->current_mana - $manaLoss);
        } else {
            $newHp = $hero->current_hp;   // Udah full heal
            $newMana = $hero->current_mana;
        }
        $newGold = $hero->gold + $goldGain;

        $sql = "UPDATE heroes SET 
                current_hp=?, current_mana=?, gold=?, 
                xp=?, level=?, 
                base_str=?, base_agi=?, base_int=?, max_hp=?, max_mana=?
                WHERE id=?";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            $newHp, $newMana, $newGold, 
            $currentXp, $currentLevel, 
            $hero->base_str, $hero->base_agi, $hero->base_int, $hero->max_hp, $hero->max_mana,
            $heroId
        ]);

        return [
            "status" => "success", 
            "msg" => "Adventure Selesai! Dapat $goldGain Gold, tapi luka -$hpLoss HP & lelah -$manaLoss Mana."
        ];
    }

    // --- BARU: Fitur Use Item (Consumable) ---
    public function useItem($heroId, $itemId) {
        try {
            $this->conn->beginTransaction();

            // 1. Cek Item
            $stmt = $this->conn->prepare("SELECT quantity FROM inventory WHERE hero_id=? AND item_id=?");
            $stmt->execute([$heroId, $itemId]);
            $qty = $stmt->fetchColumn();

            if (!$qty || $qty <= 0) throw new Exception("Item habis!");

            // 2. Ambil Efek Item
            $stmtInfo = $this->conn->prepare("SELECT c.recover_hp, c.recover_mana, i.name 
                                              FROM items i 
                                              JOIN consumables c ON i.id = c.item_id 
                                              WHERE i.id = ?");
            $stmtInfo->execute([$itemId]);
            $effect = $stmtInfo->fetch(PDO::FETCH_ASSOC);

            // 3. HITUNG REAL MAX HP (Base + Equipment)
            $realStats = $this->getRealMaxStats($heroId); // <--- INI KUNCINYA
            $hero = $this->getHeroById($heroId);

            // 4. Update Stats (Pakai Real Max, bukan Base Max)
            $newHp = min($realStats['real_max_hp'], $hero->current_hp + $effect['recover_hp']);
            $newMana = min($realStats['real_max_mana'], $hero->current_mana + $effect['recover_mana']);

            $updHero = $this->conn->prepare("UPDATE heroes SET current_hp=?, current_mana=? WHERE id=?");
            $updHero->execute([$newHp, $newMana, $heroId]);

            // 5. Kurangi Stok
            if ($qty > 1) {
                $updInv = $this->conn->prepare("UPDATE inventory SET quantity = quantity - 1 WHERE hero_id=? AND item_id=?");
                $updInv->execute([$heroId, $itemId]);
            } else {
                $delInv = $this->conn->prepare("DELETE FROM inventory WHERE hero_id=? AND item_id=?");
                $delInv->execute([$heroId, $itemId]);
            }

            $this->conn->commit();
            return "Gluk gluk! {$effect['name']} used. (HP sekarang: $newHp / {$realStats['real_max_hp']})";

        } catch (Exception $e) {
            $this->conn->rollBack();
            return "Gagal: " . $e->getMessage();
        }
    }

    public function equipItem($heroId, $itemId) {
        try {
            $this->conn->beginTransaction();

            // 1. Cek Tipe Item yang mau dipakai (Weapon/Accessory)
            $stmtType = $this->conn->prepare("SELECT type, name FROM items WHERE id = ?");
            $stmtType->execute([$itemId]);
            $item = $stmtType->fetch(PDO::FETCH_ASSOC);

            if (!$item) throw new Exception("Item tidak ditemukan!");
            
            // Validasi: Consumable gak bisa di-equip
            if ($item['type'] == 'Consumable') throw new Exception("Potion diminum, bukan dipakai!");

            // 2. COPO (Unequip) barang lama yang tipenya sama
            // Query sakti: "Set is_equipped=0 untuk semua barang milik hero ini yang tipenya X"
            // Kita pake subquery buat cari item dengan tipe yang sama di inventory hero
            $sqlUnequip = "UPDATE inventory inv
                           JOIN items i ON inv.item_id = i.id
                           SET inv.is_equipped = 0
                           WHERE inv.hero_id = ? AND i.type = ?";
            $stmtUnequip = $this->conn->prepare($sqlUnequip);
            $stmtUnequip->execute([$heroId, $item['type']]);

            // 3. PAKAI (Equip) barang baru
            $sqlEquip = "UPDATE inventory SET is_equipped = 1 WHERE hero_id = ? AND item_id = ?";
            $stmtEquip = $this->conn->prepare($sqlEquip);
            $stmtEquip->execute([$heroId, $itemId]);

            $this->conn->commit();
            return "Berhasil menggunakan {$item['name']}!";

        } catch (Exception $e) {
            $this->conn->rollBack();
            return "Gagal: " . $e->getMessage();
        }
    }

}
?>