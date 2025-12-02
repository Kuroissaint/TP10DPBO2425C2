<?php
// index.php

// 1. Mulai Session (Wajib paling atas)
session_start();

// 2. Load Konfigurasi
require_once 'config/Database.php';

// 3. Load ViewModels (Otak Tampilan)
require_once 'viewmodels/HeroViewModel.php'; 
require_once 'viewmodels/ShopViewModel.php';  // <--- BARU (Refactoring)
require_once 'viewmodels/AdminViewModel.php'; // <--- BARU (Refactoring)

// 4. Load Repositories (Otak Logika/Database)
// Kita masih butuh ini untuk menangani aksi POST/Logic game
require_once 'models/ItemRepository.php';
require_once 'models/HeroRepository.php';

// 5. Inisialisasi Database & Object
$db = (new Database())->getConnection();
$heroRepo = new HeroRepository($db);
$itemRepo = new ItemRepository($db);

$heroVM = new HeroViewModel();
$shopVM = new ShopViewModel();   // <--- BARU
$adminVM = new AdminViewModel(); // <--- BARU

// 6. Router & Session Check
$action = $_GET['action'] ?? 'home';

// Logic: Kalau belum pilih hero, tendang ke 'home' (kecuali lagi di halaman create/select)
if (!isset($_SESSION['hero_id']) && !in_array($action, ['home', 'select_hero', 'create_hero', 'store_hero', 'delete_hero'])) {
    header("Location: index.php?action=home");
    exit;
}

// Ambil ID Hero yang sedang main
$activeHeroId = $_SESSION['hero_id'] ?? null;

// ==========================================
//          SWITCH CONTROLLER
// ==========================================
switch ($action) {

    // --- CASE 1: HERO SELECTION (Login) ---
    case 'home':
        // Masih pakai Repo langsung gak masalah untuk list sederhana
        $heroes = $heroRepo->getAllHeroes();
        include 'views/hero_list.php';
        break;

    case 'select_hero':
        $_SESSION['hero_id'] = $_GET['id'];
        header("Location: index.php?action=profile");
        break;

    // --- CASE 2: GAMEPLAY PAGES (Pakai ViewModel) ---
    
    case 'profile':
        $profile = $heroVM->getHeroProfile($activeHeroId);
        include 'views/hero_profile.php'; 
        break;

    case 'shop':
        // Controller gak perlu tau cara ambil gold/item, terima beres aja
        $data = $shopVM->getShopData($activeHeroId);
        
        // Kita pecah array-nya biar di View variabelnya gampang dipanggil
        $items = $data['items']; 
        $currentHeroGold = $data['hero_gold']; 
        
        include 'views/shop_list.php';
        break;

    // --- CASE 3: ADMIN PAGES (Pakai ViewModel) ---

    case 'admin':
        // BERUBAH: Pake AdminViewModel
        $allItems = $adminVM->getItemList();
        include 'views/item_list.php';
        break;

    case 'edit_item':
        // BERUBAH: Pake AdminViewModel
        $item = $adminVM->getEditData($_GET['id']);
        if ($item) {
            include 'views/item_edit.php';
        } else {
            echo "Item tidak ditemukan!";
        }
        break;

    case 'create':
        include 'views/item_form.php';
        break;

    // --- CASE 4: GAME ACTIONS (Tetap Pakai Repository) ---
    // Karena ini mengubah data (bukan cuma nampilin), kita pakai Repo langsung
    
    case 'adventure':
        $result = $heroRepo->adventure($activeHeroId);
        header("Location: index.php?action=profile&msg=" . urlencode($result['msg']));
        break;

    case 'buy':
        $itemId = $_GET['item_id'];
        $msg = $heroRepo->buyItem($activeHeroId, $itemId);
        header("Location: index.php?action=shop&msg=" . urlencode($msg));
        break;

    case 'use_item':
        $itemId = $_GET['item_id'];
        $msg = $heroRepo->useItem($activeHeroId, $itemId);
        header("Location: index.php?action=profile&msg=" . urlencode($msg));
        break;

    case 'equip':
        $itemId = $_GET['item_id'];
        $msg = $heroRepo->equipItem($activeHeroId, $itemId);
        header("Location: index.php?action=profile&msg=" . urlencode($msg));
        break;

    // --- CASE 5: ADMIN ACTIONS (CRUD) ---
    
    case 'store':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $price = $_POST['price'];
            $type = $_POST['type'];

            // Validasi Input Sederhana
            if ($type == 'Weapon') {
                $itemRepo->createWeapon($name, $price, 'sword.png', $_POST['attack'], $_POST['element']);
            } elseif ($type == 'Consumable') {
                $itemRepo->createConsumable($name, $price, 'potion.png', $_POST['hp'], $_POST['mana']);
            } elseif ($type == 'Accessory') {
                $str = $_POST['str'] ?: 0;
                $agi = $_POST['agi'] ?: 0;
                $int = $_POST['int'] ?: 0;
                $itemRepo->createAccessory($name, $price, 'ring.png', $str, $agi, $int);
            }
            header("Location: index.php?action=admin");
        }
        break;

    case 'update_item':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $type = $_POST['type'];
            $name = $_POST['name'];
            $price = $_POST['price'];

            if ($type == 'Weapon') {
                $itemRepo->updateWeapon($id, $name, $price, $_POST['attack'], $_POST['element']);
            } elseif ($type == 'Accessory') {
                $itemRepo->updateAccessory($id, $name, $price, $_POST['str'], $_POST['agi'], $_POST['int']);
            } elseif ($type == 'Consumable') {
                $itemRepo->updateConsumable($id, $name, $price, $_POST['hp'], $_POST['mana']);
            }
            header("Location: index.php?action=admin");
        }
        break;

    case 'delete':
        $id = $_GET['id'];
        $itemRepo->deleteItem($id);
        header("Location: index.php?action=admin");
        break;

    default:
        echo "404 Not Found";
        break;
}
?>