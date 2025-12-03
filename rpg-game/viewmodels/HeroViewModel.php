<?php
require_once 'config/Database.php';
require_once 'models/Hero.php';
require_once 'models/ItemRepository.php';

class HeroViewModel {
    private $db;
    private $itemRepo;

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
        $finalStr = $hero->base_str;
        $finalAgi = $hero->base_agi;
        $finalInt = $hero->base_int;
        
        // --- FIX: Inisialisasi variabel ini agar tidak "Undefined" ---
        $bonusAttack = 0; 
        
        $equippedItems = [];
        $bagItems = [];

        foreach ($inventoryData as $data) {
            $item = $data['item'];      
            $isEquipped = $data['is_equipped'];

            if ($isEquipped) {
                $equippedItems[] = $item;

                // Cek tipe item untuk update stats
                if ($item instanceof Accessory) {
                    $finalStr += $item->bonus_str;
                    $finalAgi += $item->bonus_agi;
                    $finalInt += $item->bonus_int;
                }
                elseif ($item instanceof Weapon) {
                    // --- FIX: Tambahkan logic ini agar $bonusAttack terisi ---
                    $bonusAttack += $item->attack_power;
                }
            } else {
                $bagItems[] = $data; 
            }
        }

        // 4. LOGIC: Derived Stats (Pakai Rumus dari Model Hero)
        $maxHp = Hero::calculateMaxHp($finalStr);
        $maxMana = Hero::calculateMaxMana($finalInt);
        $maxXp = Hero::calculateReqXp($hero->level);

        // --- FIX: Hitung Total Attack setelah loop selesai ---
        $totalAttack = ($finalStr * 2) + $bonusAttack; 

        // 5. Packing Data untuk View
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
                'Attack' => $totalAttack // Variable ini sekarang sudah aman
            ],
            'equipment_list' => $equippedItems,
            'bag_list' => $bagItems
        ];
    }
}
?>