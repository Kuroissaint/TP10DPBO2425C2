<?php
require_once 'Item.php';

class Accessory extends Item {
    public $bonus_str;
    public $bonus_agi;
    public $bonus_int;

    public function __construct($row) {
        parent::__construct($row);
        $this->bonus_str = $row['bonus_str'];
        $this->bonus_agi = $row['bonus_agi'];
        $this->bonus_int = $row['bonus_int'];
    }

    public function getDetails() {
        $stats = [];
        if ($this->bonus_str > 0) $stats[] = "STR +{$this->bonus_str}";
        if ($this->bonus_agi > 0) $stats[] = "AGI +{$this->bonus_agi}";
        if ($this->bonus_int > 0) $stats[] = "INT +{$this->bonus_int}";
        return "💍 " . implode(", ", $stats);
    }
}
?>