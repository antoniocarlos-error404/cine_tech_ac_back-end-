<?php
// Inicia a sessão para gerenciar variáveis de sessão
session_start();

// Importa as dependências do projeto
require '../vendor/autoload.php'; // Autoload do Composer
require '../src/routes.php'; // Importa as rotas do sistema

/**
 * Configurações de CORS (Cross-Origin Resource Sharing)
 * Permite que o backend aceite requisições de diferentes domínios
 */
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json");

// Responde a requisições OPTIONS para CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

$method = $_SERVER['REQUEST_METHOD'];
$uri = rawurldecode($_SERVER['REQUEST_URI']);

// Log de requisição (para debug)
error_log("Requisição recebida: $method $uri");

// Instancia o controller principal
$homeController = new \src\controllers\HomeController();

// Novo base path
$basePath = '/cine_tech_ac/public';

// Roteamento atualizado
switch (true) {
    case ($method === 'GET' && $uri === "$basePath/"):
        $homeController->index();
        break;

    case ($method === 'GET' && $uri === "$basePath/listar-filme"):
        $homeController->listarFilmes();
        break;

    case ($method === 'GET' && preg_match("%^$basePath/filmes/categoria/([^/]+)$%i", $uri, $matches)):
        $categoria = rawurldecode($matches[1]);
        $homeController->listarFilmesPorCategoria($categoria);
        break;

    case ($method === 'GET' && preg_match("%^$basePath/filmes/titulo/(.+)$%", $uri, $matches)):
        $titulo = rawurldecode($matches[1]);
        $homeController->buscarFilmePorTitulo($titulo);
        break;

    case ($method === 'GET' && $uri === "$basePath/categorias"):
        $homeController->listarCategorias();
        break;

    case ($method === 'POST' && $uri === "$basePath/cadastrar-filme"):
        $homeController->cadastrarFilme();
        break;

    case ($method === 'GET' && preg_match("%^$basePath/filme/(\d+)$%", $uri, $matches)):
        $id = (int)$matches[1];
        $homeController->buscarFilme($id);
        break;

    case ($method === 'DELETE' && preg_match("%^$basePath/deletar-filme/(\d+)$%", $uri, $matches)):
        $id = (int)$matches[1];
        $homeController->deletarFilme($id);
        break;

    case ($method === 'PUT' && preg_match("%^$basePath/atualizar-filme/(\d+)$%", $uri, $matches)):
        $id = (int)$matches[1];
        $homeController->atualizarFilme($id);
        break;

    default:
        header("HTTP/1.1 404 Not Found");
        echo json_encode([
            'status' => 'error',
            'message' => 'Rota não encontrada',
            'requested_uri' => $uri,
            'available_routes' => [
                "$basePath/",
                "$basePath/categorias",
                "$basePath/filmes/categoria/{categoria}",
                "$basePath/listar-filme",
                "$basePath/filme/{id}",
                "$basePath/filmes/titulo/{titulo}",
                "$basePath/cadastrar-filme",
                "$basePath/deletar-filme/{id}",
                "$basePath/atualizar-filme/{id}"
            ]
        ]);
        break;
}