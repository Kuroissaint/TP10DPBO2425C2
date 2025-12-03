<?php
require_once 'Item.php';

class Weapon extends Item {
    public $attack_power;
    public $element;

    public function __construct($row) {
        parent::__construct($row); // Serahkan id, name, dll ke Bapak
        
        // Data spesifik dari tabel 'weapons'
        $this->attack_power = $row['attack_power'];
        $this->element = $row['element'];
    }

    public function getDetails() {
        return "⚔️ ATK: {$this->attack_power} ({$this->element})";
    }
}
?>