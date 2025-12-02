<?php
abstract class Item {
    public $id;
    public $name;
    public $type;
    public $price;
    public $image_url;

    public function __construct($row) {
        $this->id = $row['id'];
        $this->name = $row['name'];
        $this->type = $row['type'];
        $this->price = $row['price'];
        $this->image_url = $row['image_url'];
    }

    // Setiap anak WAJIB punya method ini untuk deskripsi
    abstract public function getDetails();
}
?>