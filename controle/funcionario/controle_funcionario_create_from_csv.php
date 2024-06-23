<?php
// Inclui as classes Cargo e Funcionario, que provavelmente contêm funcionalidades relacionadas a cargos e funcionários
require_once ("modelo/Cargo.php");
require_once ("modelo/Funcionario.php");

// Obtém o nome temporário do arquivo CSV enviado pelo formulário HTML
$nomeArquivo = $_FILES["variavelArquivo"]["tmp_name"];

// Abre o arquivo CSV no modo de leitura
$ponteiroArquivo = fopen($nomeArquivo, "r");

// Variáveis para armazenar a quantidade de cargos e funcionários cadastrados
$qtdCargos = 0;
$qtdFuncionarios = 0;

// Vetores para armazenar objetos de cargos e funcionários
$funcionario = array();
$funcionarios = array();

// Loop que lê cada linha do arquivo CSV
while (($linhaArquivo = fgetcsv($ponteiroArquivo, 1000, ";")) !== false) {
    // Converte os valores da linha para UTF-8, caso necessário
    $linhaArquivo = array_map("utf8_encode", $linhaArquivo);

    // Verifica se o cargo já existe no vetor $cargos
    $cargoExistente = false;
    foreach ($funcionario as $objCargo) {
        if ($objCargo->getNomeCargo() == $linhaArquivo[4]) {
            $cargoExistente = true;
        }
    }
    // Se o cargo não existir, cria um novo objeto Cargo e adiciona ao vetor $cargos
    if ($cargoExistente == false) {
        $funcionario[$qtdCargos] = new Cargo();
        $funcionario[$qtdCargos]->setNomeCargo($linhaArquivo[4]);
        // Verifica se o cargo foi criado com sucesso no banco de dados
        if ($funcionario[$qtdCargos]->create() == true) {
            $qtdCargos++;
        }
    }

    // Cria um novo objeto Funcionario e define seus atributos com base nos dados do arquivo CSV
    $funcionarios[$qtdFuncionarios] = new Funcionario();
    $funcionarios[$qtdFuncionarios]->setNomeFuncionario($linhaArquivo[0]);
    $funcionarios[$qtdFuncionarios]->setEmail($linhaArquivo[1]);
    $funcionarios[$qtdFuncionarios]->setSenha($linhaArquivo[2]);
    // Verifica se o funcionário recebe vale transporte e atribui 1 se sim, 0 se não
    $funcionarios[$qtdFuncionarios]->setRecebeValeTransporte(($linhaArquivo[3] == "Sim" || $linhaArquivo[3] == "sim") ? 1 : 0);
    // Define o cargo do funcionário
    $funcionarios[$qtdFuncionarios]->getCargo()->setNomeCargo($linhaArquivo[4]);
    // Verifica se o funcionário foi criado com sucesso no banco de dados
    if ($funcionarios[$qtdFuncionarios]->createFromCSV() == true) {
        $qtdFuncionarios++;
    }
}

// Cria um objeto stdClass para a resposta em JSON
$objResposta = new stdClass();
$objResposta->status = true;
$objResposta->msg = "Cadastrados com sucesso";
$objResposta->cargosCadastrados = $funcionario;
$objResposta->totalCargos = $qtdCargos;
$objResposta->totalFuncionarios = $qtdFuncionarios;
$objResposta->FuncionariosCadastrados = $funcionarios;
// Converte a resposta para JSON e a imprime
echo json_encode($objResposta);
?>