<?php
// Inclui as classes Cargo contêm funcionalidades relacionadas ao banco de dados e aos cargos
require_once ("modelo/Cargo.php");

// Obtém o nome temporário do arquivo CSV enviado pelo formulário HTML
$nomeArquivo = $_FILES["variavelArquivo"]["tmp_name"];

// Abre o arquivo CSV no modo de leitura
$ponteiroArquivo = fopen($nomeArquivo, "r");

// Loop que lê cada linha do arquivo CSV
$qtdCargos = 0;
$objCargo = array();
while (($linhaArguivo = fgetcsv($ponteiroArquivo, 1000, ";")) !== false) {
    // Converte os valores da linha para UTF-8, caso necessário
    $linhaArguivo = array_map("utf8_encode", $linhaArguivo);

    // Cria um novo objeto da classe Cargo
    $objCargo[$qtdCargos] = new Cargo();

    // Define o nome do cargo recebido da coluna zero do arquivo csv
    $objCargo[$qtdCargos]->setNomeCargo($linhaArguivo[0]);

    // Chama o método para criar o cargo no banco de dados
    if ($objCargo[$qtdCargos]->create() == true) {
        $qtdCargos++;
    }
}
$objResposta = new stdClass();
$objResposta->status = true;
$objResposta->msg = "Cargos cadastrados com sucesso";
$objResposta->cadastrados = $objCargo;
$objResposta->totalCargos = $qtdCargos;
echo json_encode($objResposta);

?>