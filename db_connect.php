<?php
// db_connect.php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=fancydress_rental", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>