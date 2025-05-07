<?php
$host = 'localhost'; 
$dbname = 'botica_nova_salud'; 
$user = 'postgres'; 
$password = '123456'; 
$port = '5432'; 

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password";
    $pdo = new PDO($dsn);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>