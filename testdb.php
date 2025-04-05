<?php
require 'vendor/autoload.php'; // se usar composer
use core\Database;

try {
    $pdo = \core\Database::getInstance();
    echo "✅ Conexão com o banco de dados bem-sucedida!";
} catch (PDOException $e) {
    echo "❌ Erro na conexão: " . $e->getMessage();
}
