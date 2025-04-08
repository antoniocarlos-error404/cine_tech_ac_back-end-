<?php
namespace src\models;

use core\Database;
use PDO;

class Filme extends Database {
    private $pdo;

    public function __construct() {
        $this->pdo = $this-> getInstance(); // Assuming getConnection() is a method in Database class
    }

    // =========================
    // Inserir filme
    // =========================
 // =========================
// Inserir filme
// =========================
public function inserirFilme($titulo, $sinopse, $trailer, $capa, $categoria, $lancamento, $duracao) {
    $sql = "INSERT INTO filmes (titulo, sinopse, trailer, capa, categoria, lancamento, duracao) 
            VALUES (:titulo, :sinopse, :trailer, :capa, :categoria, :lancamento, :duracao)";
    
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute([
        ':titulo'     => $titulo,
        ':sinopse'    => $sinopse,
        ':trailer'    => $trailer,
        ':capa'       => $capa,
        ':categoria'  => $categoria,
        ':lancamento' => $lancamento,
        ':duracao'    => $duracao
    ]);
}


    // =========================
    // Listar todos os filmes ou por categoria
    // =========================
    public function getFilmesFromDatabase($categoria = null) {
        if ($categoria) {
            $sql = "SELECT * FROM filmes WHERE categoria = :categoria ORDER BY id DESC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':categoria', $categoria);
        } else {
            $sql = "SELECT * FROM filmes ORDER BY id DESC";
            $stmt = $this->pdo->prepare($sql);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // =========================
    // Buscar filme por título
    // =========================
    public function getFilmesFromDatabaseByTitulo($titulo) {
        $sql = "SELECT * FROM filmes WHERE titulo LIKE :titulo ORDER BY id DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':titulo', '%' . $titulo . '%');
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // =========================
    // Buscar filme por ID
    // =========================
    public function buscarFilmePorId($id) {
        $sql = "SELECT * FROM filmes WHERE id = :id LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // =========================
    // Atualizar filme
    // =========================
   // =========================
// Atualizar filme
// =========================
public function atualizarFilme($id, $titulo, $sinopse, $trailer, $capa, $categoria, $lancamento, $duracao) {
    // Define os campos a serem atualizados
    $campos = [
        'titulo'     => $titulo,
        'sinopse'    => $sinopse,
        'trailer'    => $trailer,
        'categoria'  => $categoria,
        'lancamento' => $lancamento,
        'duracao'    => $duracao
    ];

    // Só atualiza a capa se for enviada
    if ($capa) {
        $campos['capa'] = $capa;
    }

    $sets = [];
    foreach ($campos as $key => $value) {
        $sets[] = "$key = :$key";
    }

    $sql = "UPDATE filmes SET " . implode(', ', $sets) . " WHERE id = :id";
    $stmt = $this->pdo->prepare($sql);

    $campos['id'] = $id;

    return $stmt->execute($campos);
}


    // =========================
    // Deletar filme
    // =========================
    public function deletarFilme($id) {
        $sql = "DELETE FROM filmes WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
