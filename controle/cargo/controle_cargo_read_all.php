<?php
use Firebase\JWT\MeuTokenJWT;

// Inclui as classe Cargo que contêm funcionalidades relacionadas ao banco de dados e aos cargos
require_once ("modelo/Cargo.php");
require_once ("modelo/MeuTokenJWT.php");
$headers = apache_request_headers();
$authorization = $headers["Authorization"];


$objMeuToken = new MeuTokenJWT();
$validouToken = $objMeuToken->validarToken($authorization);


if ($validouToken == false) {
    $objResposta = new stdClass();
    $objResposta->cod = 2;
    // Define o status da resposta como verdadeiro
    $objResposta->status = false;
    // Define a mensagem de sucesso
    $objResposta->msg = "Token Inválido";
    //print_r($objMeuToken->getPayload());

    header("HTTP/1.1 401");
    // Define o tipo de conteúdo da resposta como JSON
    header("Content-Type: application/json");
    // Converte o objeto resposta em JSON e o imprime na saída
    echo json_encode($objResposta);
    exit();
}



// Cria um novo objeto para armazenar a resposta
$objResposta = new stdClass();
// Cria um novo objeto da classe Cargo
$objCargo = new Cargo();

// Obtém todos os cargos do banco de dados
$vetor = $objCargo->readAll();

// Define o código de resposta como 1
$objResposta->cod = 1;
// Define o status da resposta como verdadeiro
$objResposta->status = true;
// Define a mensagem de sucesso
$objResposta->msg = "executado com sucesso";
// Define o vetor de cargos na resposta
$objResposta->cargos = $vetor;

// Define o código de status da resposta como 200 (OK)
header("HTTP/1.1 200");
// Define o tipo de conteúdo da resposta como JSON
header("Content-Type: application/json");
// Converte o objeto resposta em JSON e o imprime na saída
echo json_encode($objResposta);

?>