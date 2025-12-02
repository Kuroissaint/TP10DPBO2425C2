<?php
// index.php

require_once 'config/Database.php';
require_once 'models/ItemRepository.php';
require_once 'viewmodels/HeroViewModel.php'; 
require_once 'models/HeroRepository.php';

// Koneksi DB
$db = (new Database())->getConnection();
$itemRepo = new ItemRepository($db);
$heroVM = new HeroViewModel();
$heroRepo = new HeroRepository($db);

// Router
$action = $_GET['action'] ?? 'profile'; // Default lari ke Profile

switch ($action) {

    // --- HALAMAN UTAMA: HERO PROFILE ---
    case 'profile':
        // Kita hardcode Hero ID 1 (Arthur)
        $profile = $heroVM->getHeroProfile(1);
        
        // Kita butuh view khusus profile
        include 'views/hero_profile.php'; 
        break;

    // --- HALAMAN ADMIN: LIST ITEM ---
    case 'admin':
        $allItems = $itemRepo->getAllItems();
        include 'views/item_list.php';
        break;

    // --- HALAMAN ADMIN: CREATE FORM ---
    case 'create':
        include 'views/item_form.php';
        break;

    // --- LOGIC: SIMPAN ITEM ---
    case 'store':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $price = $_POST['price'];
            $type = $_POST['type'];

            if ($type == 'Weapon') {
                $itemRepo->createWeapon($name, $price, 'sword.png', $_POST['attack'], $_POST['element']);
            } elseif ($type == 'Consumable') {
                $itemRepo->createConsumable($name, $price, 'potion.png', $_POST['hp'], $_POST['mana']);
            } elseif ($type == 'Accessory') { // <--- TAMBAHAN LOGIC
                // Ambil inputan STR, AGI, INT (kasih nilai 0 kalau kosong biar gak error)
                $str = $_POST['str'] ?: 0;
                $agi = $_POST['agi'] ?: 0;
                $int = $_POST['int'] ?: 0;

                $itemRepo->createAccessory($name, $price, 'ring.png', $str, $agi, $int);
            }
            header("Location: index.php?action=admin"); // Balik ke Admin
        }

    // --- HALAMAN SHOP ---
    case 'shop':
        // Kita butuh data Hero (buat cek duit) DAN data semua Item (buat etalase)
        $currentHero = $heroRepo->getHeroById(1); // Hardcode Arthur dulu
        $allItems = $itemRepo->getAllItems();
        
        include 'views/shop_list.php';
        break;

    // --- PROSES BELI BARANG ---
    case 'buy':
        $itemId = $_GET['item_id'];
        $heroId = 1; // Hardcode Arthur

        // Panggil Logic Transaksi Sakti
        $resultMessage = $heroRepo->buyItem($heroId, $itemId);

        // Balik ke Shop dengan pesan
        header("Location: index.php?action=shop&msg=" . urlencode($resultMessage));
        
        break;

    // --- LOGIC: HAPUS ITEM ---
    case 'delete':
        $id = $_GET['id'];
        $itemRepo->deleteItem($id);
        header("Location: index.php?action=admin"); // Balik ke Admin
        break;

    default:
        echo "404 Not Found";
        break;
}
?>