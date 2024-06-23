<?php
use Firebase\JWT\MeuTokenJWT;
require_once "modelo/Usuario.php";
require_once "modelo/MeuTokenJWT.php";

//recupera o header contendo o token de autorização
$headers = apache_request_headers();
//instância a classe TokenJWT()
$tokenJWT = new MeuTokenJWT();
//verifica se o token é valido ou não
if ($tokenJWT->validarToken($headers) == true) {
    $objFuncionario = new Usuario();
    //recupera os dados enviado no corpo da requisição.
    $jsonRecebido = file_get_contents('php://input');
    //converte os dados em um objeto json.
    $objJson = json_decode($jsonRecebido);
    //faz o set dos dados para update
    $objFuncionario->setIdUsuario($v1);
    $objFuncionario->setNome($objJson->nome);
    $objFuncionario->setEmail($objJson->email);
    $objFuncionario->setSenha($objJson->senha);
    $objResposta['status'] = $objFuncionario->update();
    $objResposta['msg'] = "Atualizado com sucesso";
    $objResposta['dados'] = $objFuncionario;
    echo json_encode($objResposta);
} else {
    $objResposta['status'] = false;
    $objResposta['msg'] = 'Usuário não logado';
    echo json_encode($objResposta);
}

?>