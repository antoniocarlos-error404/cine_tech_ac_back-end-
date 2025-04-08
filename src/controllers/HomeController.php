<?php 
namespace src\controllers;

use core\Controller;
use src\models\Filme;

class HomeController extends Controller {

    // Página de teste
    public function index() {
        echo json_encode(['message' => 'API funcionando corretamente!']);
    }

    // Listar todos os filmes
    public function listarFilmes() {
        $filmes = (new Filme())->getFilmesFromDatabase();
        $this->json($filmes);
    }

    // Listar filmes por categoria
    public function listarFilmesPorCategoria($categoria) {
        $filmes = (new Filme())->getFilmesFromDatabase($categoria);
        $this->json($filmes);
    }

    // Buscar filme por título
    public function buscarFilmePorTitulo($titulo) {
        $filmes = (new Filme())->getFilmesFromDatabaseByTitulo($titulo);
        $this->json($filmes);
    }

    // Buscar filme por ID
    public function buscarFilme($id) {
        if (!is_numeric($id)) {
            return $this->json(['status' => 'error', 'message' => 'ID inválido.']);
        }

        $filme = (new Filme())->buscarFilmePorId($id);
        $this->json($filme ?: ['status' => 'error', 'message' => 'Filme não encontrado.']);
    }

    // Listar categorias fixas
    public function listarCategorias() {
        $categorias = ["Ação", "Terror", "Drama", "Ficção", "Romance"];
        $this->json($categorias);
    }

    // Cadastrar filme (com upload de imagem)
    public function cadastrarFilme() {
        if (!isset($_FILES['capa'])) {
            return $this->json(['status' => 'error', 'message' => 'A capa do filme é obrigatória.']);
        }

        $titulo     = $_POST['titulo'] ?? '';
        $sinopse    = $_POST['sinopse'] ?? '';
        $trailer    = $_POST['trailer'] ?? '';
        $categoria  = $_POST['categoria'] ?? '';
        $lancamento = $_POST['lancamento'] ?? null;
        $duracao    = $_POST['duracao'] ?? null;

        $uploadDir = __DIR__ . '/../../uploads/';
        $capa = $_FILES['capa'];
        $fileName = basename($capa['name']);
        $uploadPath = $uploadDir . $fileName;

        $ext = strtolower(pathinfo($uploadPath, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($ext, $allowed)) {
            return $this->json(['status' => 'error', 'message' => 'Formato de imagem inválido.']);
        }

        if (!move_uploaded_file($capa['tmp_name'], $uploadPath)) {
            return $this->json(['status' => 'error', 'message' => 'Erro ao fazer upload da imagem.']);
        }

        $result = (new Filme())->inserirFilme(
            $titulo, $sinopse, $trailer, $fileName, $categoria, $lancamento, $duracao
        );

        $this->json($result
            ? ['status' => 'success', 'message' => 'Filme cadastrado com sucesso!']
            : ['status' => 'error', 'message' => 'Erro ao cadastrar o filme.']);
    }

    // Atualizar filme
    public function atualizarFilme($id) {
        if (empty($_POST['titulo'])) {
            return $this->json(['status' => 'error', 'message' => 'O título é obrigatório.']);
        }

        $titulo     = $_POST['titulo'];
        $sinopse    = $_POST['sinopse'] ?? '';
        $trailer    = $_POST['trailer'] ?? '';
        $categoria  = $_POST['categoria'] ?? '';
        $lancamento = $_POST['lancamento'] ?? null;
        $duracao    = $_POST['duracao'] ?? null;
        $capaNome   = null;

        if (isset($_FILES['capa']) && $_FILES['capa']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../uploads/';
            $capa = $_FILES['capa'];
            $fileName = basename($capa['name']);
            $uploadPath = $uploadDir . $fileName;

            $ext = strtolower(pathinfo($uploadPath, PATHINFO_EXTENSION));
            if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
                return $this->json(['status' => 'error', 'message' => 'Formato de imagem inválido.']);
            }

            if (!move_uploaded_file($capa['tmp_name'], $uploadPath)) {
                return $this->json(['status' => 'error', 'message' => 'Erro ao fazer upload da imagem.']);
            }

            $capaNome = $fileName;
        }

        $result = (new Filme())->atualizarFilme(
            $id,
            $titulo,
            $sinopse,
            $trailer,
            $capaNome,
            $categoria,
            $lancamento,
            $duracao
        );

        $this->json($result
            ? ['status' => 'success', 'message' => 'Filme atualizado com sucesso!']
            : ['status' => 'error', 'message' => 'Erro ao atualizar o filme.']);
    }

    // Deletar filme
    public function deletarFilme($id) {
        if (!is_numeric($id)) {
            return $this->json(['status' => 'error', 'message' => 'ID inválido.']);
        }

        $result = (new Filme())->deletarFilme($id);
        $this->json($result
            ? ['status' => 'success', 'message' => 'Filme deletado com sucesso!']
            : ['status' => 'error', 'message' => 'Filme não encontrado ou já deletado.']);
    }

    // Método auxiliar para retornar JSON
    private function json($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}
