<?php
namespace src;

class Config {
    const BASE_DIR = '/cine_tech_ac/public'; // <- Verifique se esse caminho bate com seu projeto real!

    const DB_DRIVER = 'mysql';
    const DB_HOST = 'localhost';
    const DB_DATABASE = 'cine_tech_ac';
    const DB_USER = 'root'; // Aqui estava "CONST", deve ser "const"
    const DB_PASS = '';     // Aqui também

    const ERROR_CONTROLLER = 'ErrorController';
    const DEFAULT_ACTION = 'index';
}
