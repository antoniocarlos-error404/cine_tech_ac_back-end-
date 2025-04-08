# 🎬 CineTech AC - Backend

Este é o backend do projeto **CineTech AC**, desenvolvido em PHP com arquitetura MVC, utilizando Composer para autoload e organização do código.

---

## 🚀 Instalação

Você pode clonar este repositório **ou** baixar o `.zip`.

```bash
git clone https://github.com/antoniocarlos-error404/cine_tech_ac_back-end-.git

Após baixar ou clonar:

Navegue até a pasta do projeto no terminal:
cd cine_tech_ac_back-end-
Execute o Composer para instalar as dependências:

bash
Copiar
Editar
composer install

⚙️ Configuração
As configurações estão localizadas em:

css
Copiar
Editar
src/Config.php
Você deve configurar corretamente a constante BASE_DIR de acordo com o caminho da sua pasta public. Exemplo:

php
Copiar
Editar
const BASE_DIR = '/cine_tech_ac_back-end-/public';
Além disso, configure também os dados do banco de dados (host, user, password, dbname) nesse mesmo arquivo.

🧪 Uso
Você deve acessar a pasta public do projeto via navegador:

pgsql
Copiar
Editar
http://localhost/cine_tech_ac_back-end-/public
💡 O ideal é criar um alias ou virtual host no Apache/Nginx que aponte direto para a pasta public.

🧱 Exemplo de Model
Os Models do sistema ficam em src/models. Aqui vai um exemplo simples:

php
Copiar
Editar
<?php
namespace src\models;

use \core\Model;

class Usuario extends Model {
    // Métodos de acesso à tabela 'usuarios' aqui
}
📁 Estrutura Padrão
pgsql
Copiar
Editar
cine_tech_ac_back-end-/
├── public/
├── src/
│   ├── controllers/
│   ├── models/
│   └── Config.php
├── vendor/
└── composer.json
👨‍💻 
