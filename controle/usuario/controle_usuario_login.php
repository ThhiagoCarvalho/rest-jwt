<?php
use Firebase\JWT\MeuTokenJWT;
require_once "modelo/Usuario.php";
require_once "modelo/MeuTokenJWT.php";

$jsonRecebido = file_get_contents('php://input');
$objJson = json_decode($jsonRecebido);
$objFuncionario = new Usuario();
$objFuncionario->setEmail($objJson->email);
$objFuncionario->setSenha($objJson->senha);
$objResposta = array();
if ($objFuncionario->verificarUsuarioSenha() == true) {
    $tokenJWT = new MeuTokenJWT();
    $novoToken = $tokenJWT->gerarToken(json_encode($objFuncionario));
    $objResposta['status'] = 'true';
    $objResposta['msg'] = "Login efetuado com sucesso";
    $objResposta['token'] = $novoToken;
} else {
    $objResposta['status'] = 'false';
    $objResposta['msg'] = "Login inválido";
}
echo json_encode($objResposta);
?>