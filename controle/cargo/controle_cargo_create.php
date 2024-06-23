<?php
// Inclui a classe Cargo que contêm funcionalidades relacionadas ao banco de dados e aos cargos
require_once ("modelo/Cargo.php");
header("Content-Type: application/json");
// Cria um novo objeto para armazenar a resposta
$objResposta = new stdClass();
// Cria um novo objeto da classe Cargo
$objCargo = new Cargo();
// Obtém os dados enviados por meio de uma requisição POST em formato JSON
$textoRecebido = file_get_contents("php://input");
try {
    // Decodifica os dados JSON recebidos em um objeto PHP ou interrompe o script se o formato estiver incorreto
    $objJson = json_decode($textoRecebido);
    if ($objJson === null && json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Formato JSON inválido');
    }
    // Define o nome do cargo recebido do JSON no objeto Cargo
    $objCargo->setNomeCargo($objJson->cargo->nomeCargo);
    // Verifica se o nome do cargo está vazio
    if ($objCargo->getNomeCargo() == "") {
        throw new Exception("O nome nao pode ser vazio: ");
    }
    if (strlen($objCargo->getNomeCargo()) < 3) {
        throw new Exception("o nome nao pode ser menor do que 3 caracteres");
    }
    if (strlen($objCargo->getNomeCargo()) > 32) {
        throw new Exception("o nome nao pode ser maior do que 32 caracteres");
    }
    if ($objCargo->isCargo() == true) {
        throw new Exception("Ja existe um cargo cadastrado com o nome: " . $objCargo->getNomeCargo());
    }
    $objCargo->create(); //tenta criar um novo cargo
    $objResposta->status = true;
    $objResposta->msg = "cadastrado com sucesso";
    $objResposta->novoCargo = $objCargo;
    header("HTTP/1.1 201");
} catch (Exception $e) {
    $objResposta->status = false;
    $objResposta->msg = $e->getMessage();
}
// Converte o objeto resposta em JSON e o imprime na saída
echo json_encode($objResposta);
