<?php
// models/Hero.php

class Hero {
    public $id;
    public $name;
    public $job_class;
    public $gold;
    
    // Stats
    public $base_str;
    public $base_agi;
    public $base_int;
    
    // State HP
    public $current_hp;
    public $max_hp;

    // State MANA (YANG KEMARIN KELUPAAN)
    public $current_mana;  
    public $max_mana;       

    public function __construct($row) {
        $this->id = $row['id'];
        $this->name = $row['name'];
        $this->job_class = $row['job_class'];
        $this->gold = $row['gold'] ?? 0;
        
        $this->base_str = $row['base_str'];
        $this->base_agi = $row['base_agi'];
        $this->base_int = $row['base_int'];
        
        $this->current_hp = $row['current_hp'];
        $this->max_hp = $row['max_hp'];

        // Assign nilai Mana (TAMBAHKAN INI JUGA)
        // Pastikan pakai isset() atau operator coalescing (??) biar aman kalau datanya kosong
        $this->current_mana = $row['current_mana'] ?? 0; 
        $this->max_mana = $row['max_mana'] ?? 0;
    }
}
?>