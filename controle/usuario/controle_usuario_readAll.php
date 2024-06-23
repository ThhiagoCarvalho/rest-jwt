<?php
use Firebase\JWT\MeuTokenJWT;

require_once "modelo/Usuario.php";
require_once "modelo/MeuTokenJWT.php";

//recupera o header contendo o token de autorização
$headers = apache_request_headers();
//instância a classe TokenJWT()
$objToken = new MeuTokenJWT();
//verifica se o token é valido ou não
if ($objToken->validarToken($headers) == true) {
    $objFuncionario = new Usuario();
    $objResposta['status'] = true;
    $objResposta['dados'] = $objFuncionario->readAll();
    echo json_encode($objResposta);
    exit;
} else {
    $objResposta['status'] = false;
    $objResposta['msg'] = 'Token inválido';
    echo json_encode($objResposta);
}

?>