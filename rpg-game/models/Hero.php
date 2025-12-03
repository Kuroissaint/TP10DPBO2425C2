<?php
class Hero {
    public $id;
    public $name;
    public $job_class;
    public $gold;
    public $level;
    public $xp;

    // Stats
    public $base_str;
    public $base_agi;
    public $base_int;
    
    // State
    public $current_hp;
    public $max_hp;
    public $current_mana;  
    public $max_mana;       

    // --- PERBAIKAN: KONSTANTA RUMUS ---
    const HP_PER_STR = 20;
    const MANA_PER_INT = 15;
    const XP_BASE = 100;
    const XP_PER_LEVEL = 150;

    public function __construct($row) {
        $this->id = $row['id'];
        $this->name = $row['name'];
        $this->job_class = $row['job_class'];
        $this->gold = $row['gold'] ?? 0;
        $this->level = $row['level'] ?? 1;
        $this->xp = $row['xp'] ?? 0;
        
        $this->base_str = $row['base_str'];
        $this->base_agi = $row['base_agi'];
        $this->base_int = $row['base_int'];
        
        $this->current_hp = $row['current_hp'];
        $this->max_hp = $row['max_hp'];
        $this->current_mana = $row['current_mana'] ?? 0; 
        $this->max_mana = $row['max_mana'] ?? 0;
    }

    // --- PERBAIKAN: METHOD CALCULATION ---
    public static function calculateMaxHp(int $str): int {
        return $str * self::HP_PER_STR;
    }

    public static function calculateMaxMana(int $int): int {
        return $int * self::MANA_PER_INT;
    }

    public static function calculateReqXp(int $level): int {
        return self::XP_BASE + ($level - 1) * self::XP_PER_LEVEL;
    }
}
?>