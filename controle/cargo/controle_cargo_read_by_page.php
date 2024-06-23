<?php
// Inclui a classe Cargo, que contêm funcionalidades relacionadas ao banco de dados e aos cargos
require_once ("modelo/Cargo.php");

// Cria um novo objeto para armazenar a resposta
$objResposta = new stdClass();
// Cria um novo objeto da classe Cargo
$objetoCargo = new Cargo();

// Obtém todos os cargos do banco de dados
$vetor = $objetoCargo ->readByPage($pagina);

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
