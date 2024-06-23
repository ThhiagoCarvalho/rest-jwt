<?php
// Inclui a classe Funcionario, que  contém funcionalidades relacionadas a funcionários
require_once ("modelo/Funcionario.php");

// Cria um novo objeto para armazenar a resposta
$objResposta = new stdClass();
// Cria um novo objeto da classe Funcionario
$funcionario = new Funcionario();
// Define o ID do funcionário com base na variável $idFuncionario (presumivelmente definida anteriormente)

$funcionario->setIdFuncionario($idFuncionario);

// Verifica se a exclusão do funcionário foi bem-sucedida
if ($funcionario->delete() == true) {
    // Define o código de status da resposta como 204 (No Content)
    header("HTTP/1.1 204");
} else {
    // Define o código de status da resposta como 200 (OK)
    header("HTTP/1.1 200");
    // Define o tipo de conteúdo da resposta como JSON
    header("Content-Type: application/json");
    // Define os atributos do objeto resposta para indicar que ocorreu um erro na exclusão do funcionário
    $objResposta->status = false;
    $objResposta->cod = 1;
    $objResposta->msg = "Erro ao excluir funcionario";
    // Converte o objeto resposta em JSON e o imprime na saída
    echo json_encode($objResposta);
}
?>
