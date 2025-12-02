<?php
require_once 'config/Database.php';
require_once 'models/Hero.php';
require_once 'models/ItemRepository.php';

class HeroViewModel {
    private $heroModel; // Seharusnya ada repo Hero sendiri, tapi kita simplifikasi pake Model lgsg utk contoh
    private $itemRepo;
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->itemRepo = new ItemRepository($this->db);
    }

    // Fungsi Utama: Mengambil Data Hero + Menghitung Final Stats
    public function getHeroProfile($heroId) {
        // 1. Ambil Data Hero Mentah (Base Stats)
        $query = "SELECT * FROM heroes WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $heroId);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$row) return null;
        $hero = new Hero($row);

        // 2. Ambil Inventory Hero
        $inventoryData = $this->itemRepo->getInventoryByHero($heroId);

        // 3. LOGIC: Kalkulasi Statistik (Base + Equipment)
        // Kita clone stats hero biar gak ngerubah data asli database
        $finalStr = $hero->base_str;
        $finalAgi = $hero->base_agi;
        $finalInt = $hero->base_int;
        $bonusAttack = 0;
        
        $equippedItems = [];
        $bagItems = [];

        foreach ($inventoryData as $data) {
            $item = $data['item'];      // Object (Weapon/Accessory/Consumable)
            $isEquipped = $data['is_equipped'];

            // Pisahkan mana yg dipake, mana yg di tas
            if ($isEquipped) {
                $equippedItems[] = $item;

                // KALKULASI STATS BERDASARKAN TIPE CLASS
                // Check if Accessory -> Nambah Stats Utama
                if ($item instanceof Accessory) {
                    $finalStr += $item->bonus_str;
                    $finalAgi += $item->bonus_agi;
                    $finalInt += $item->bonus_int;
                }
                // Check if Weapon -> Nambah Attack
                elseif ($item instanceof Weapon) {
                    $bonusAttack += $item->attack_power;
                }
            } else {
                $bagItems[] = $data; // Simpan item tas
            }
        }

        // 4. LOGIC: Derived Stats (Rumus RPG)
        // Misal: 1 STR = 20 HP, 1 INT = 15 Mana
        $maxHp = $finalStr * 20;
        $maxMana = $finalInt * 15;
        $maxXp = 100 + ($hero->level - 1) * 150;
        // Total Attack = (STR * 2) + Weapon Damage
        $totalAttack = ($finalStr * 2) + $bonusAttack; 

        // 5. Packing Data untuk View (Data Matang)
        return [
            'hero_name' => $hero->name,
            'job' => $hero->job_class,
            'level' => $hero->level,
            'xp_current' => $hero->xp,      
            'xp_max' => $maxXp,
            'gold' => $hero->gold,
            'stats' => [
                'STR' => $finalStr . " (Base: {$hero->base_str})",
                'AGI' => $finalAgi . " (Base: {$hero->base_agi})",
                'INT' => $finalInt . " (Base: {$hero->base_int})"
            ],
            'raw_hp' => $hero->current_hp,
            'raw_mana' => $hero->current_mana,
            'attributes' => [
                'HP' => "{$hero->current_hp} / {$maxHp}",
                'Mana' => "{$hero->current_mana} / {$maxMana}",
                'Attack' => $totalAttack
            ],
            'equipment_list' => $equippedItems, // Array of Objects
            'bag_list' => $bagItems
        ];
    }
}
?>