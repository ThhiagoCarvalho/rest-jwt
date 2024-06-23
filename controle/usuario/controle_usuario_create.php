<?php
require_once "modelo/Usuario.php";
require_once "modelo/MeuTokenJWT.php";

$jsonRecebido = file_get_contents('php://input');

$objJson = json_decode($jsonRecebido);

$objUsuario = new Usuario();   


$objUsuario->setNome($objJson->nome);
$objUsuario->setEmail($objJson->email);
$objUsuario->setSenha($objJson->senha);

if ($objUsuario->usuarioExiste() == false) {

    $objResposta['status'] = $objUsuario->create();
    $objResposta['msg'] = 'cadastrado com sucesso';
    $objResposta['dados'] = $objUsuario;
} else {
    $objResposta['status'] = false;
    $objResposta['msg'] = 'já existe um usuário com o e-mail fornecido';
    $objResposta['dados'] = $objUsuario;
}

echo json_encode($objResposta);

?>