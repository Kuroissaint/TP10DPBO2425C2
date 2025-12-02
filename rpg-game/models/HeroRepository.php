<?php
require_once 'config/Database.php';
require_once 'models/Hero.php';

class HeroRepository {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // CREATE
    public function createHero($name, $job, $str, $agi, $int) {
        $query = "INSERT INTO heroes (name, job_class, base_str, base_agi, base_int, current_hp, max_hp, current_mana, max_mana) 
                  VALUES (:name, :job, :str, :agi, :int, :hp, :hp, :mana, :mana)";
        
        // Auto hitung HP/Mana awal biar gak null
        $hp = $str * 20;
        $mana = $int * 15;

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
    public function buyItem($heroId, $itemId) {
        try {
            $this->conn->beginTransaction(); // Mulai Transaksi

            // 1. Ambil Data Hero (Cek Uang)
            $stmtHero = $this->conn->prepare("SELECT gold FROM heroes WHERE id = ?");
            $stmtHero->execute([$heroId]);
            $heroGold = $stmtHero->fetchColumn();

            // 2. Ambil Data Item (Cek Harga)
            $stmtItem = $this->conn->prepare("SELECT price FROM items WHERE id = ?");
            $stmtItem->execute([$itemId]);
            $itemPrice = $stmtItem->fetchColumn();

            // 3. VALIDASI: Cukup gak duitnya?
            if ($heroGold < $itemPrice) {
                // Kalau kurang, lempar Error!
                throw new Exception("Uang tidak cukup! (Butuh: $itemPrice, Punya: $heroGold)");
            }

            // 4. Kurangi Uang Hero
            $newGold = $heroGold - $itemPrice;
            $stmtUpdate = $this->conn->prepare("UPDATE heroes SET gold = ? WHERE id = ?");
            $stmtUpdate->execute([$newGold, $heroId]);

            // 5. Masukkan Barang ke Inventory
            // Cek dulu udah punya belum? Kalau udah, update quantity. Kalau belum, insert.
            // (Disini kita bikin simpel: Insert baru / abaikan quantity dulu biar gampang)
            $stmtInv = $this->conn->prepare("INSERT INTO inventory (hero_id, item_id, is_equipped, quantity) VALUES (?, ?, 0, 1)");
            $stmtInv->execute([$heroId, $itemId]);

            $this->conn->commit(); // Simpan Permanen
            return "Berhasil membeli item! Sisa uang: $newGold";

        } catch (Exception $e) {
            $this->conn->rollBack(); // Batalkan semua kalau error
            return "Gagal: " . $e->getMessage();
        }
    }
}
?>