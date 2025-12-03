<?php
require_once 'config/Database.php';
require_once 'models/ItemRepository.php';
require_once 'models/HeroRepository.php';

class ShopViewModel {
    private $db;
    private $itemRepo;
    private $heroRepo;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->itemRepo = new ItemRepository($this->db);
        $this->heroRepo = new HeroRepository($this->db);
    }

    public function getShopData($heroId) {
        // 1. Ambil Data Hero (Butuh Gold-nya)
        $hero = $this->heroRepo->getHeroById($heroId);

        // 2. Ambil Semua Item (Katalog Toko)
        $items = $this->itemRepo->getAllItems();

        // 3. Packing Data Siap Saji
        return [
            'hero_gold' => $hero->gold,
            'hero_name' => $hero->name,
            'items' => $items
        ];
    }
}
?>