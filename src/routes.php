<?php
use core\Router;

$router = new Router();

$router->get('/', 'HomeController@index');
$router->get('/sobre', 'HomeController@sobre');
$router->get('/sobre/{id}', 'HomeController@sobreP');




$router->get('/filme/existe/{id}', 'HomeController@filmeExiste');

$router->post('/cadastrar-filme', 'HomeController@cadastrarFilme');

$router->get('/listar-filme', 'HomeController@listarFilmes');//

$router->delete('/deletar-filme/{id}', 'HomeController@deletarFilme');

$router->put('/atualizar-filme/{id}', 'HomeController@atualizarFilme');

$router->get('/filme/{id}', 'HomeController@buscarFilme');

$router->get('/filme/ListarCategorias', 'HomeController@ListarCategorias');

$router->get('/filmes/categoria/{categoria}', 'HomeController@listarFilmesPorCategoria');

$router->get('/categorias', 'HomeController@listarCategorias');

$router->get('/filmes/buscar/{titulo}', 'HomeController@buscarFilmePorTitulo');
