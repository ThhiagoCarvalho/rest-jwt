<?php
// Inclui as classes Banco, Funcionario e Cargo, que contêm funcionalidades relacionadas ao banco de dados, funcionários e cargos
require_once ("modelo/Banco.php");
require_once ("modelo/Funcionario.php");
require_once ("modelo/Cargo.php");

// Obtém os dados enviados por meio de uma requisição POST em formato JSON
$textoRecebido = file_get_contents("php://input");
// Decodifica os dados JSON recebidos em um objeto PHP ou interrompe o script se o formato estiver incorreto
$objJson = json_decode($textoRecebido) or die('{"msg":"formato incorreto"}');

// Cria um novo objeto para armazenar a resposta
$objResposta = new stdClass();
// Cria um novo objeto da classe Funcionario
$funcionario = new Funcionario();

// Define os atributos do funcionário com base nos dados recebidos do JSON
$funcionario->setNomeFuncionario($objJson->funcionario->nomeFuncionario);
$funcionario->setEmail($objJson->funcionario->email);
$funcionario->setSenha($objJson->funcionario->senha);
$funcionario->setRecebeValeTransporte($objJson->funcionario->recebeValeTransporte);
// Define o ID do cargo do funcionário com base nos dados recebidos do JSON
$funcionario->getCargo()->setIdCargo($objJson->funcionario->Cargo_idCargo);

// Verifica se o nome do funcionário está vazio
if ($funcionario->getNomeFuncionario() == '') {
    $objResposta->cod = 1;
    $objResposta->status = false;
    $objResposta->msg = "o nome nao pode ser vazio";
} 
// Verifica se o nome do funcionário tem menos de 3 caracteres
elseif (strlen($funcionario->getNomeFuncionario()) < 3) {
    $objResposta->cod = 2;
    $objResposta->status = false;
    $objResposta->msg = "o nome nao pode ser menor do que 3 caracteres";
} 
// Verifica se o email do funcionário está vazio
elseif ($funcionario->getEmail() == '') {
    $objResposta->cod = 3;
    $objResposta->status = false;
    $objResposta->msg = "o email nao pode ser vazio";
} 
// Verifica se já existe um funcionário cadastrado com o mesmo email
elseif ($funcionario->isFuncionario() == true) {
    $objResposta->cod = 4;
    $objResposta->status = false;
    $objResposta->msg = "ja existe funcionário cadastrado com o email indicado";
} 
// Se todas as condições anteriores forem atendidas, tenta cadastrar o novo funcionário
else {
    // Verifica se o cadastro do funcionário foi bem-sucedido
    if ($funcionario->create() == true) {
        $objResposta->cod = 4;
        $objResposta->status = true;
        $objResposta->msg = "cadastrado com sucesso";
        $objResposta->novoFuncionario = $funcionario;
    } 
    // Se houver erro no cadastro do funcionário, define a mensagem de erro
    else {
        $objResposta->cod = 5;
        $objResposta->status = false;
        $objResposta->msg = "Erro ao cadastrar funcionario";
    }
}

// Define o tipo de conteúdo da resposta como JSON
header("Content-Type: application/json");

// Define o código de status da resposta como 201 (Created) se o cadastro foi bem-sucedido, caso contrário, como 200 (OK)
if ($objResposta->status == true) {
    header("HTTP/1.1 201");
} else {
    header("HTTP/1.1 200");
}

// Converte o objeto resposta em JSON e o imprime na saída
echo json_encode($objResposta);

?>
