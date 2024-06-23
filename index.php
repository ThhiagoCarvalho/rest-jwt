<?php
// Inclui o arquivo Router.php, que provavelmente contém a definição da classe Router
require_once ("modelo/Router.php");

// Instancia um objeto da classe Router
$roteador = new Router();

// Define uma rota para a obtenção de todos os cargos
$roteador->get("/cargos", function () {
    // Requer o arquivo de controle responsável por obter todos os cargos
    require_once ("controle/cargo/controle_cargo_read_all.php");
});

// Define uma rota para a obtenção de um cargo específico pelo ID
$roteador->get("/cargos/(\d+)", function ($idCargo) {
    // Requer o arquivo de controle responsável por obter um cargo pelo ID
    require_once ("controle/cargo/controle_cargo_read_by_id.php");
});

// Define uma rota para a criação de um novo cargo
$roteador->post("/cargos", function () {
    // Requer o arquivo de controle responsável por criar um novo cargo
    require_once ("controle/cargo/controle_cargo_create.php");
});


// Define uma rota para a atualização de um cargo existente pelo ID
$roteador->put("/cargos/(\d+)", function ($idCargo) {
    // Requer o arquivo de controle responsável por atualizar um cargo pelo ID
    require_once ("controle/cargo/controle_cargo_update.php");
});

// Define uma rota para a exclusão de um cargo existente pelo ID
$roteador->delete("/cargos/(\d+)", function ($idCargo) {
    // Requer o arquivo de controle responsável por excluir um cargo pelo ID
    require_once ("controle/cargo/controle_cargo_delete.php");
});

// Define uma rota para recuparar uma pagina de cargos
$roteador->get("/cargos/(\d+)/pagina", function ($pagina) {
    // Requer o arquivo de controle responsável por excluir um cargo pelo ID
    require_once ("controle/cargo/controle_cargo_read_by_page.php");
});

// Define uma rota para enviar um arquivo CSV para cadastrar todos os cargos
$roteador->post("/cargos/csv", function () {
    // Requer o arquivo de controle responsável por processar o arquivo CSV e cadastrar os cargos
    require_once ("controle/cargo/controle_cargos_create_from_csv.php");
});

// Define uma rota para a obtenção de todos os funcionários
$roteador->get("/funcionarios", function () {
    // Requer o arquivo de controle responsável por obter todos os funcionários
    require_once ("controle/funcionario/controle_funcionario_read_all.php");
});

// Define uma rota para a obtenção de um funcionário específico pelo ID
$roteador->get("/funcionarios/(\d+)", function ($idFuncionario) {
    // Requer o arquivo de controle responsável por obter um funcionário pelo ID
    require_once ("controle/funcionario/controle_funcionario_read_by_id.php");
});

// Define uma rota para a criação de um novo funcionário
$roteador->post("/logar", function () {
    // Requer o arquivo de controle responsável por criar um novo funcionário
    require_once ("controle/funcionario/controle_funcionario_logar.php");
});

// Define uma rota para a criação de um novo funcionário
$roteador->post("/funcionarios", function () {
    // Requer o arquivo de controle responsável por criar um novo funcionário
    require_once ("controle/funcionario/controle_funcionario_create.php");
});

// Define uma rota para a atualização de um funcionário existente pelo ID
$roteador->put("/funcionarios/(\d+)", function ($idFuncionario) {
    // Requer o arquivo de controle responsável por atualizar um funcionário pelo ID
    require_once ("controle/funcionario/controle_funcionario_update.php");
});

// Define uma rota para a exclusão de um funcionário existente pelo ID
$roteador->delete("/funcionarios/(\d+)", function ($idFuncionario) {
    // Requer o arquivo de controle responsável por excluir um funcionário pelo ID
    require_once ("controle/funcionario/controle_funcionario_delete.php");
});

// Define uma rota para enviar um arquivo CSV para cadastrar todos os cargos
$roteador->post("/funcionarios/csv", function () {
    // Requer o arquivo de controle responsável por processar o arquivo CSV e cadastrar os cargos
    require_once ("controle/funcionario/controle_funcionario_create_from_csv.php");
});

// Executa o roteador para lidar com as requisições
$roteador->run();
?>