<?php
namespace src\models;

use core\Database;
use core\Model;
use PDO;
use PDOException;

class Filme extends Model {
    public $id;
    public $titulo;
    public $sinopse;
    public $trailer;
    public $capa;
    public $genero_id;
    public $data_lancamento;
    public $duracao;

    public function salvar() {
        $pdo = Database::getInstance();
        $sql = "INSERT INTO filmes (titulo, genero_id, sinopse, trailer, data_lancamento, duracao, capa)
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $this->titulo,
            $this->genero_id,
            $this->sinopse,
            $this->trailer,
            $this->data_lancamento,
            $this->duracao,
            $this->capa
        ]);

        $this->id = $pdo->lastInsertId();
        return $this->id;
    }

    public function getFilmesFromDatabase($genero_id = null) {
        $sql = "SELECT 
                    f.id, f.titulo, f.sinopse, f.trailer, f.capa, f.data_lancamento, f.duracao,
                    g.nome as genero
                FROM filmes f
                LEFT JOIN generos g ON f.genero_id = g.id";
        
        if ($genero_id) {
            $sql .= " WHERE f.genero_id = :genero_id";
        }

        try {
            $pdo = Database::getInstance();
            $stmt = $pdo->prepare($sql);

            if ($genero_id) {
                $stmt->bindValue(':genero_id', $genero_id, PDO::PARAM_INT);
            }

            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $filmes = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($filmes as &$filme) {
                    $filme['capa'] = 'http://localhost/cine_tech_ac/uploads/' . $filme['capa'];
                }

                return $filmes;
            } else {
                return [];
            }
        } catch (PDOException $e) {
            die("Erro na consulta ao banco de dados: " . $e->getMessage());
        }
    }

    public function getFilmesFromDatabaseByTitulo($titulo) {
        $sql = "SELECT 
                    f.id, f.titulo, f.sinopse, f.trailer, f.capa, f.data_lancamento, f.duracao,
                    g.nome as genero
                FROM filmes f
                LEFT JOIN generos g ON f.genero_id = g.id
                WHERE f.titulo LIKE :titulo";

        try {
            $pdo = Database::getInstance();
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':titulo', '%' . $titulo . '%', PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $filmes = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($filmes as &$filme) {
                    $filme['capa'] = 'http://localhost/cine_tech_ac/uploads/' . $filme['capa'];
                }

                return $filmes;
            } else {
                return [];
            }
        } catch (PDOException $e) {
            die("Erro na consulta ao banco de dados: " . $e->getMessage());
        }
    }

    public function getCategoriasFromDatabase() {
        $sql = "SELECT id, nome FROM generos";

        try {
            $pdo = Database::getInstance();
            $stmt = $pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Erro ao listar categorias: " . $e->getMessage());
        }
    }

    public function listarFilmesPorCategoria($genero_id) {
        return $this->getFilmesFromDatabase($genero_id);
    }

    public function inserirFilme($titulo, $sinopse, $trailer, $capa, $genero_id, $data_lancamento, $duracao) {
        $sql = "INSERT INTO filmes (titulo, sinopse, trailer, capa, genero_id, data_lancamento, duracao) 
                VALUES (:titulo, :sinopse, :trailer, :capa, :genero_id, :data_lancamento, :duracao)";

        try {
            $pdo = Database::getInstance();
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':titulo', $titulo);
            $stmt->bindValue(':sinopse', $sinopse);
            $stmt->bindValue(':trailer', $trailer);
            $stmt->bindValue(':capa', $capa);
            $stmt->bindValue(':genero_id', $genero_id);
            $stmt->bindValue(':data_lancamento', $data_lancamento);
            $stmt->bindValue(':duracao', $duracao);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erro ao cadastrar filme: " . $e->getMessage());
            return false;
        }
    }

    public function deletarFilme($id) {
        $sql = "DELETE FROM filmes WHERE id = :id";

        try {
            $pdo = Database::getInstance();
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Erro ao deletar filme: " . $e->getMessage());
            return false;
        }
    }

    public function buscarFilmePorId($id) {
        $sql = "SELECT 
                    f.*, g.nome as genero
                FROM filmes f
                LEFT JOIN generos g ON f.genero_id = g.id
                WHERE f.id = :id";

        try {
            $pdo = Database::getInstance();
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $filme = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($filme) {
                $filme['capa'] = 'http://localhost/cine_tech_ac/uploads/' . $filme['capa'];
            }

            return $filme;
        } catch (PDOException $e) {
            error_log("Erro ao buscar filme: " . $e->getMessage());
            return false;
        }
    }

    public function atualizarFilme($id, $titulo, $sinopse, $trailer, $capa, $genero_id, $data_lancamento, $duracao) {
        $sql = "UPDATE filmes 
                SET titulo = :titulo, sinopse = :sinopse, trailer = :trailer, capa = :capa, genero_id = :genero_id, data_lancamento = :data_lancamento, duracao = :duracao 
                WHERE id = :id";

        try {
            $pdo = Database::getInstance();
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':titulo', $titulo);
            $stmt->bindValue(':sinopse', $sinopse);
            $stmt->bindValue(':trailer', $trailer);
            $stmt->bindValue(':capa', $capa);
            $stmt->bindValue(':genero_id', $genero_id);
            $stmt->bindValue(':data_lancamento', $data_lancamento);
            $stmt->bindValue(':duracao', $duracao);
            return $stmt->execute();
        } catch (PDOException $e) {
            die("Erro ao atualizar filme: " . $e->getMessage());
        }
    }

    public function filmeExiste($id) {
        $sql = "SELECT id FROM filmes WHERE id = :id";
        try {
            $pdo = Database::getInstance();
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Erro ao verificar existência do filme: " . $e->getMessage());
            return false;
        }
    }
}
