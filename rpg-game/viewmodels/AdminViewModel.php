<?php
require_once 'config/Database.php';
require_once 'models/ItemRepository.php';

class AdminViewModel {
    private $db;
    private $itemRepo;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->itemRepo = new ItemRepository($this->db);
    }

    public function getItemList() {
        return $this->itemRepo->getAllItems();
    }
    
    public function getEditData($itemId) {
        return $this->itemRepo->getItemById($itemId);
    }
}
?>