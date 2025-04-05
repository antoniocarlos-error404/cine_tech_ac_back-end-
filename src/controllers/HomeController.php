<?php 
namespace src\controllers;

use core\Controller;
use src\models\Filme;

class HomeController extends Controller {

    public function index() {
        echo json_encode(['message' => 'teste feito para funcionar antonio!']);
    }

    public function listarFilmesPorCategoria($categoria) {
        $filmeModel = new Filme();
        $filmes = $filmeModel->getFilmesFromDatabase($categoria);

        header('Content-Type: application/json');
        echo json_encode($filmes);
    }

    public function buscarFilmePorTitulo($titulo) {
        $filmeModel = new Filme();
        $filmes = $filmeModel->getFilmesFromDatabaseByTitulo($titulo);

        header('Content-Type: application/json');
        echo json_encode($filmes);
    }

    public function listarCategorias() {
        $filmeModel = new Filme();
        $categorias = $filmeModel->getCategoriasFromDatabase();

        header('Content-Type: application/json');
        echo json_encode($categorias);
    }

    public function listarFilmes() {
        $filmeModel = new Filme();
        $filmes = $filmeModel->getFilmesFromDatabase();

        header('Content-Type: application/json');
        echo json_encode($filmes);
    }

    public function cadastrarFilme() {
        header('Content-Type: application/json');

        if (!isset($_FILES['capa'])) {
            echo json_encode(['status' => 'error', 'message' => 'A capa do filme é obrigatória.']);
            return;
        }

        $titulo = $_POST['titulo'] ?? '';
        $sinopse = $_POST['sinopse'] ?? '';
        $trailer = $_POST['link_trailer'] ?? '';
        $genero_id = $_POST['genero_id'] ?? '';
        $data_lancamento = $_POST['data_lancamento'] ?? '';
        $duracao = $_POST['duracao'] ?? '';

        if (empty($titulo) || empty($genero_id) || empty($data_lancamento)) {
            echo json_encode(['status' => 'error', 'message' => 'Campos obrigatórios estão vazios.']);
            return;
        }

        $capa = $_FILES['capa'];
        $uploadDir = __DIR__ . '/../../uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $uploadFile = $uploadDir . basename($capa['name']);

        $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($imageFileType, $allowedTypes)) {
            echo json_encode(['status' => 'error', 'message' => 'Formato de imagem inválido. Use JPG, JPEG, PNG ou GIF.']);
            return;
        }

        if (move_uploaded_file($capa['tmp_name'], $uploadFile)) {
            $filmeModel = new Filme();
            $result = $filmeModel->inserirFilme(
                $titulo,
                $sinopse,
                $trailer,
                basename($capa['name']),
                $genero_id,
                $data_lancamento,
                $duracao
            );

            echo json_encode($result ? 
                ['status' => 'success', 'message' => 'Filme cadastrado com sucesso!'] : 
                ['status' => 'error', 'message' => 'Erro ao cadastrar o filme.']
            );
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Erro ao fazer upload da imagem.']);
        }
    }

    public function deletarFilme($id) {
        header('Content-Type: application/json');

        if (!is_numeric($id)) {
            echo json_encode(['status' => 'error', 'message' => 'ID inválido.']);
            return;
        }

        $filmeModel = new Filme();
        $result = $filmeModel->deletarFilme($id);

        echo json_encode($result ? 
            ['status' => 'success', 'message' => 'Filme deletado com sucesso!'] : 
            ['status' => 'error', 'message' => 'Filme não encontrado ou já deletado.']
        );
    }

    public function atualizarFilme($id) {
        header('Content-Type: application/json');

        if (empty($_POST['titulo'])) {
            echo json_encode(['status' => 'error', 'message' => 'O título do filme é obrigatório.']);
            return;
        }

        $titulo = $_POST['titulo'] ?? '';
        $sinopse = $_POST['sinopse'] ?? '';
        $trailer = $_POST['link_trailer'] ?? '';
        $genero_id = $_POST['genero_id'] ?? '';
        $data_lancamento = $_POST['data_lancamento'] ?? '';
        $duracao = $_POST['duracao'] ?? '';
        $capa = null;

        if (isset($_FILES['capa']) && $_FILES['capa']['error'] === UPLOAD_ERR_OK) {
            $capaFile = $_FILES['capa'];
            $uploadDir = __DIR__ . '/../../uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $uploadFile = $uploadDir . basename($capaFile['name']);

            $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array($imageFileType, $allowedTypes)) {
                echo json_encode(['status' => 'error', 'message' => 'Formato de imagem inválido. Use JPG, JPEG, PNG ou GIF.']);
                return;
            }

            if (!move_uploaded_file($capaFile['tmp_name'], $uploadFile)) {
                echo json_encode(['status' => 'error', 'message' => 'Erro ao fazer upload da imagem.']);
                return;
            }

            $capa = basename($capaFile['name']);
        }

        $filmeModel = new Filme();
        $result = $filmeModel->atualizarFilme(
            $id,
            $titulo,
            $sinopse,
            $trailer,
            $capa,
            $genero_id,
            $data_lancamento,
            $duracao
        );

        echo json_encode($result ? 
            ['status' => 'success', 'message' => 'Filme atualizado com sucesso!'] : 
            ['status' => 'error', 'message' => 'Erro ao atualizar o filme.']
        );
    }

    public function buscarFilme($args)
    {
        header('Content-Type: application/json');
    
        $id = $args['id'] ?? null;
    
        if (!is_numeric($id)) {
            echo json_encode(['status' => 'error', 'message' => 'ID inválido.']);
            return;
        }
    
        $filmeModel = new Filme();
        $filme = $filmeModel->buscarFilmePorId($id);
    
        echo json_encode($filme ? $filme : ['status' => 'error', 'message' => 'Filme não encontrado.']);
    }
    
}
