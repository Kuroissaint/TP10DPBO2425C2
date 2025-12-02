<?php
class Database {
    // Settingan Default Laragon
    private $host = "localhost";
    private $db_name = "rpg_game"; // Sesuai nama DB yang kamu buat di langkah 2
    private $username = "root";           // Default Laragon: root
    private $password = "";               // Default Laragon: kosong (tidak ada password)
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
?>