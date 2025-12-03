<?php
require_once 'Item.php';

class Consumable extends Item {
    public $recover_hp;
    public $recover_mana;

    public function __construct($row) {
        parent::__construct($row);
        $this->recover_hp = $row['recover_hp'];
        $this->recover_mana = $row['recover_mana'];
    }

    public function getDetails() {
        $eff = [];
        if ($this->recover_hp > 0) $eff[] = "HP +{$this->recover_hp}";
        if ($this->recover_mana > 0) $eff[] = "Mana +{$this->recover_mana}";
        return "🧪 " . implode(", ", $eff);
    }
}
?>