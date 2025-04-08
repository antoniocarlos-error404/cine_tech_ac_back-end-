<?php
session_start();
require '../vendor/autoload.php';
require '../src/routes.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') exit(0);

$method = $_SERVER['REQUEST_METHOD'];
$uri = rawurldecode($_SERVER['REQUEST_URI']);
$basePath = '/backend/public';

$homeController = new \src\controllers\HomeController();

switch (true) {

    // Página inicial
    case ($method === 'GET' && $uri === "$basePath/"):
        $homeController->index();
        break;

    // Listar todos os filmes
    case ($method === 'GET' && $uri === "$basePath/listar-filme"):
        $homeController->listarFilmes();
        break;

    // Listar categorias
    case ($method === 'GET' && $uri === "$basePath/categorias"):
        $homeController->listarCategorias();
        break;

    // Buscar filme por ID
    case ($method === 'GET' && preg_match("%^$basePath/filme/(\d+)$%", $uri, $matches)):
        $homeController->buscarFilme((int)$matches[1]);
        break;

    // Buscar filmes por categoria
    case ($method === 'GET' && preg_match("%^$basePath/filmes/categoria/([^/]+)$%", $uri, $matches)):
        $homeController->listarFilmesPorCategoria($matches[1]);
        break;

    // Buscar filme por título (rota alternativa)
    case ($method === 'GET' && preg_match("%^$basePath/filmes/buscar/(.+)$%", $uri, $matches)):
        $homeController->buscarFilmePorTitulo($matches[1]);
        break;

    // Cadastrar novo filme
    case ($method === 'POST' && $uri === "$basePath/cadastrar-Filme"):
        $homeController->cadastrarFilme();
        break;

    // Atualizar filme (PUT ou POST)
    case (($method === 'PUT' || $method === 'POST') && preg_match("%^$basePath/atualizar-filme/(\d+)$%", $uri, $matches)):
        $homeController->atualizarFilme((int)$matches[1]);
        break;

    // Deletar filme
    case ($method === 'DELETE' && preg_match("%^$basePath/deletar-filme/(\d+)$%", $uri, $matches)):
        $homeController->deletarFilme((int)$matches[1]);
        break;

    // Rota não encontrada
    default:
        http_response_code(404);
        echo json_encode([
            'status' => 'error',
            'message' => 'Rota não encontrada',
            'requested_uri' => $uri,
            'available_routes' => [
                "$basePath/",
                "$basePath/listar-filme",
                "$basePath/categorias",
                "$basePath/filmes/categoria/{categoria}",
                "$basePath/filmes/buscar/{titulo}",
                "$basePath/filme/{id}",
                "$basePath/cadastrar-Filme",
                "$basePath/atualizar-filme/{id}",
                "$basePath/deletar-filme/{id}",
            ]
        ]);
        break;
}
