<?php

use Firebase\JWT\MeuTokenJWT; //utiliza o pacote do JWT

require_once "modelo/Funcionario.php"; //importa o arquivo Funcionario.php e
require_once "modelo/MeuTokenJWT.php"; //importa o arquivo MeuTokenJWT.php

$jsonRecebido = file_get_contents('php://input'); //recupera o json
$objJson = json_decode($jsonRecebido); // converte o texto json para um objeto
$objFuncionario = new Funcionario(); // cria um objeto de funcionario

$objFuncionario->setEmail($objJson->funcionario->email); // passa o email para o objeto funcionario
$objFuncionario->setSenha($objJson->funcionario->senha);// passa a senha para o objeto funcionario

$objResposta = new stdClass();

//chama o método de verificação do usuário
if ($objFuncionario->verificarUsuarioSenha() == true) {
    $tokenJWT = new MeuTokenJWT();

    $objClaimsToken = new stdClass();
    //gera as claims para a criação do token
    $objClaimsToken->email = $objFuncionario->getEmail();
    $objClaimsToken->role = $objFuncionario->getCargo()->getNomeCargo();
    $objClaimsToken->name = $objFuncionario->getNomeFuncionario();
    $objClaimsToken->idFuncionario = $objFuncionario->getIdFuncionario();

    //chama o método que gera um novo token 
    //passa as claims para o método
    $novoToken = $tokenJWT->gerarToken($objClaimsToken);

    $objResposta->status = 'true';
    $objResposta->msg = 'Login efetuado com sucesso';
    $objResposta->token = $novoToken;
} else {
    $objResposta->status = 'false';
    $objResposta->msg = 'Login inválido';
}
//converte a resposta em um json
echo json_encode($objResposta);


?>