<?php
// Inclui as classes Banco, Cargo e Funcionario, que provavelmente contêm funcionalidades relacionadas ao banco de dados, cargos e funcionários
require_once ("modelo/Banco.php");
require_once ("modelo/Cargo.php");
require_once ("modelo/Funcionario.php");

// Obtém o conteúdo do corpo da requisição HTTP
$textoRecebido = file_get_contents("php://input");
// Decodifica o JSON recebido em um objeto PHP, ou interrompe o script caso o formato seja incorreto
$objJson = json_decode($textoRecebido) or die('{"msg":"formato incorreto"}');

// Cria um novo objeto para armazenar a resposta
$objResposta = new stdClass();

// Cria um novo objeto da classe Cargo
$funcionario = new Cargo();

// Define o ID do cargo do funcionário com base no JSON recebido
$funcionario->setIdCargo($objJson->funcionario->Cargo_idCargo);

// Cria um novo objeto da classe Funcionario
$objetoFuncionario = new Funcionario();

// Define o ID do funcionário com base na variável $idFuncionario (presumivelmente definida anteriormente)
$objetoFuncionario->setIdFuncionario($idFuncionario);
// Define o nome do funcionário com base no JSON recebido
$objetoFuncionario->setNomeFuncionario($objJson->funcionario->nomeFuncionario);
// Define o email do funcionário com base no JSON recebido
$objetoFuncionario->setEmail($objJson->funcionario->email);
// Define a senha do funcionário com base no JSON recebido
$objetoFuncionario->setSenha($objJson->funcionario->senha);
// Define se o funcionário recebe vale transporte com base no JSON recebido
$objetoFuncionario->setRecebeValeTransporte($objJson->funcionario->recebeValeTransporte);

// Define o cargo do funcionário com base no objeto Cargo criado anteriormente
$objetoFuncionario->setCargo($funcionario);

// Verifica se o nome do funcionário está vazio
if ($objetoFuncionario->getNomeFuncionario() == "") {
    // Define os atributos da resposta indicando erro
    $objResposta->cod = 1;
    $objResposta->status = false;
    $objResposta->msg = "o nome nao pode ser vazio";
}
// Verifica se o nome do funcionário possui menos de 3 caracteres
else if (strlen($objetoFuncionario->getNomeFuncionario()) < 3) {
    // Define os atributos da resposta indicando erro
    $objResposta->cod = 2;
    $objResposta->status = false;
    $objResposta->msg = "o nome nao pode ser menor do que 3 caracteres";
} else {
    // Tenta atualizar os dados do funcionário no banco de dados
    if ($objetoFuncionario->update() == true) {
        // Define os atributos da resposta indicando sucesso
        $objResposta->cod = 4;
        $objResposta->status = true;
        $objResposta->msg = "Atualizado com sucesso";
        // Inclui os dados do cargo atualizado na resposta
        $objResposta->cargoAtualizado = $funcionario;
    } else {
        // Define os atributos da resposta indicando erro
        $objResposta->cod = 5;
        $objResposta->status = false;
        $objResposta->msg = "Erro ao atualizar funcionário";
    }
}
// Define o código de status da resposta como 200 (OK)
header("HTTP/1.1 200");
// Define o tipo de conteúdo da resposta como JSON
header("Content-Type: application/json");
// Converte o objeto resposta em JSON e o imprime na saída
echo json_encode($objResposta);
?>