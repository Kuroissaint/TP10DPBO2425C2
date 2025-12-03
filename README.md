# TP10DPBO2425C2

## Janji

Saya Nafis Asyakir Anjar dengan NIM 2407915 mengerjakan Tugas Praktikum 10 pada Mata Kuliah Desain dan Pemrograman Berorientasi Objek (DPBO) untuk keberkahan-Nya maka saya tidak melakukan kecurangan seperti yang telah dispesifikasikan. Aamiin.

## Struktur Folder

Berikut adalah struktur direktori dari implementasi arsitektur MVVM (Model-View-ViewModel) dengan pola Repository pada proyek ini:

```text
rpg-game/
├── config/
│   └── Database.php            # Konfigurasi koneksi PDO Database MySQL
├── css/
│   └── style.css               # Styling tampilan (Dark Theme)
├── models/                     # Layer Model (Entitas & Logika Bisnis)
│   ├── Item.php                # Abstract Class untuk semua Item
│   ├── Weapon.php              # Child Class untuk Senjata
│   ├── Accessory.php           # Child Class untuk Aksesoris
│   ├── Consumable.php          # Child Class untuk Potion/Makanan
│   ├── Hero.php                # Class Object (POJO) Hero
│   ├── HeroRepository.php      # Logika Bisnis & Query Database Hero (Gameplay)
│   └── ItemRepository.php      # Logika Bisnis & Query Database Item (CRUD)
├── viewmodels/                 # Layer ViewModel (Penyiapan Data untuk View)
│   ├── AdminViewModel.php      # Menyiapkan data untuk panel Admin
│   ├── HeroViewModel.php       # Menyiapkan profil hero & kalkulasi stats
│   └── ShopViewModel.php       # Menyiapkan data katalog toko & gold hero
├── views/                      # Layer View (Tampilan UI)
│   ├── templates/              # Potongan layout (Header & Footer)
│   ├── hero_form.php           # Form pembuatan Hero baru
│   ├── hero_list.php           # Halaman pemilihan Hero (Home)
│   ├── hero_profile.php        # Halaman status, inventory, & aksi Hero
│   ├── item_edit.php           # Form edit item (Admin)
│   ├── item_form.php           # Form tambah item baru (Admin)
│   ├── item_list.php           # Tabel manajemen item (Admin)
│   └── shop_list.php           # Halaman Toko (Shop)
├── index.php                   # Main Entry Point & Routing (Switch Case)
└── sql                         # File Query DDL Database
```
# 🗄️ Desain Database & Fitur Proyek

<img width="730" height="613" alt="image" src="https://github.com/user-attachments/assets/eec62493-1e9d-4b34-b0af-d4b76956b24b" />

Proyek ini menggunakan relasi database dengan konsep **Inheritance** pada tabel `items` dan **Many-to-Many** pada `inventory`.

---

## 📄 Tabel: `heroes`
Menyimpan data statistik utama karakter pemain.

| Atribut       | Tipe Data | Keterangan                                  |
|--------------|-----------|----------------------------------------------|
| id           | INT       | Primary Key, Auto Increment                  |
| name         | VARCHAR   | Nama Hero                                    |
| job_class    | VARCHAR   | Pekerjaan (Warrior / Mage / Assassin)        |
| gold         | INT       | Mata uang dalam game                         |
| level        | INT       | Level karakter (default 1)                   |
| xp           | INT       | Experience Point saat ini                    |
| base_str     | INT       | Statistik dasar Strength                     |
| base_agi     | INT       | Statistik dasar Agility                      |
| base_int     | INT       | Statistik dasar Intelligence                 |
| current_hp   | INT       | Nyawa saat ini                                |
| current_mana | INT       | Mana saat ini                                 |

---

## 📄 Tabel: `items` (Parent)
Tabel induk untuk semua jenis barang.

| Atribut    | Tipe Data | Keterangan                         |
|-----------|-----------|-------------------------------------|
| id        | INT       | Primary Key                         |
| name      | VARCHAR   | Nama Item                           |
| type      | VARCHAR   | Tipe (Weapon, Accessory, Consumable)|
| price     | INT       | Harga Beli                          |
| image_url | VARCHAR   | Path gambar                         |

---

## 📄 Tabel Anak: `weapons`, `accessories`, `consumables`
Tabel spesifik yang berelasi **One-to-One** dengan `items`.

| Tabel        | Atribut Tambahan                               |
|--------------|------------------------------------------------|
| weapons      | `attack_power`, `element`                      |
| accessories  | `bonus_str`, `bonus_agi`, `bonus_int`          |
| consumables  | `recover_hp`, `recover_mana`                   |

---

## 📄 Tabel: `inventory`
Menghubungkan Hero dengan Item (**Many-to-Many**).

| Atribut   | Tipe Data | Keterangan                                |
|-----------|-----------|--------------------------------------------|
| hero_id   | INT       | Foreign Key ke `heroes`                    |
| item_id   | INT       | Foreign Key ke `items`                     |
| is_equipped | BOOLEAN | Status pemakaian (0 = Tas, 1 = Dipakai)   |
| quantity  | INT       | Jumlah barang (Stackable)                 |

---

## 🚀 Fitur & Implementasi CRUD

Berikut adalah rincian operasi CRUD (Create, Read, Update, Delete) yang terjadi pada setiap tabel dalam fitur aplikasi:

---

### 1️⃣ Manajemen Hero (`heroes`)

| Operasi | Implementasi |
|--------|--------------|
| **Create** | Pembuatan karakter baru melalui formulir **Create New Hero**, menyimpan nama, job class, dan statistik awal. |
| **Read** | - Menampilkan daftar hero di halaman **Home** untuk dipilih.<br>- Menampilkan detail statistik lengkap (Attributes) di halaman **Profile**. |
| **Update** | - Perubahan **gold, xp, level, dan base_stats** setelah fitur **Adventure**.<br>- **gold berkurang** ketika melakukan **Buy Item**.<br>- **current_hp** dan **current_mana bertambah** setelah **Use Potion**. |
| **Delete** | Menghapus karakter hero secara permanen dari daftar slot. |

---

### 2️⃣ Manajemen Item / Admin Panel (`items` & tabel anak)

| Operasi | Implementasi |
|--------|--------------|
| **Create** | Admin membuat item baru (Weapon / Accessory / Consumable) melalui **form dinamis** — menyimpan data ke tabel `items` (induk) dan tabel anak sesuai tipe. |
| **Read** | - Menampilkan katalog barang di halaman **Shop (User View)**.<br>- Menampilkan daftar master data item di **Admin Panel (Admin View)**. |
| **Update** | Admin dapat mengedit informasi item seperti **Nama**, **Harga**, atau **Efek Statistik** (Attack Power, Bonus STR, Recover HP, dll.). |
| **Delete** | Admin menghapus item dari database. Menggunakan **Cascade Delete** agar data di tabel anak ikut terhapus otomatis. |

---

### 3️⃣ Inventory System (`inventory`)

| Operasi | Implementasi |
|--------|--------------|
| **Create** | Menambahkan baris baru di tabel `inventory` saat hero **membeli item** yang belum dimilikinya. |
| **Read** | Menampilkan daftar barang milik hero di halaman **Profile**, dikelompokkan menjadi **Equipment (dipakai)** dan **Backpack (disimpan)**. |
| **Update** | - Mengubah `is_equipped = 1` saat user melakukan **Equip**.<br>- **Menambah quantity** jika membeli item yang sudah dimiliki (stack).<br>- **Mengurangi quantity** saat item Consumable digunakan. |
| **Delete** | Menghapus baris inventory secara otomatis saat `quantity = 0` setelah item digunakan. |

---


---

## 🔄 Alur Program

### 🧭 Routing (`index.php`)
User mengakses aplikasi dengan `?action=...` (contoh `?action=shop`).  
Routing menentukan logika mana yang dijalankan.

### 🧠 ViewModel
- Mengambil data mentah dari Repository.
- Menggabungkan/mengolah data (contoh: Hero + Item).
- Mengembalikan array siap render.

### 🗃 Repository
- Operasi database menggunakan **PDO**.
- Menggunakan **Factory Pattern** untuk membuat objek `Weapon`, `Accessory`, atau `Consumable`.

### 🎨 View
- Template HTML pada folder `views/` untuk menampilkan data ke pengguna.

---

## 📸 Dokumentasi

https://github.com/user-attachments/assets/e6d7ed05-4aab-41f5-9cb0-b23453e1d3d2


---


