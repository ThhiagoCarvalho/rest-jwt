<?php
use Firebase\JWT\MeuTokenJWT;
require_once "modelo/Usuario.php";
require_once "modelo/MeuTokenJWT.php";

  //recupera o header contendo o token de autorização
  $headers     = apache_request_headers();
  //instância a classe TokenJWT()
  $tokenJWT = new MeuTokenJWT();
  //verifica se o token é valido ou não
  if ($tokenJWT->validarToken($headers) == true) {
   $objFuncionario = new Usuario();
   $objFuncionario->setIdUsuario($v1);
   $objResposta['status'] = $objFuncionario->delete();
   $objResposta['msg'] = "excluído com sucesso";
   echo json_encode($objResposta);
 } else {
   $objResposta['status'] = false;
   $objResposta['msg'] = 'Usuário não logado';
   echo json_encode($objResposta);
 }

?>