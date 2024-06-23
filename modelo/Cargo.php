<?php
// Inclui o arquivo Banco.php, que provavelmente contém funcionalidades relacionadas ao banco de dados
require_once ("modelo/Banco.php");

// Definição da classe Cargo, que implementa a interface JsonSerializable
class Cargo implements JsonSerializable
{
    // Propriedades privadas da classe
    private $idCargo;
    private $nomeCargo;

    // Método necessário pela interface JsonSerializable para serialização do objeto para JSON
    public function jsonSerialize()
    {
        // Cria um objeto stdClass para armazenar os dados do cargo
        $objetoResposta = new stdClass();
        // Define as propriedades do objeto com os valores das propriedades da classe
        $objetoResposta->idCargo = $this->idCargo;
        $objetoResposta->nomeCargo = $this->nomeCargo;

        // Retorna o objeto para serialização
        return $objetoResposta;
    }

    // Método para criar um novo cargo no banco de dados
    public function create()
    {

        $conexao = Banco::getConexao(); // Obtém a conexão com o banco de dados
        // Define a consulta SQL para inserir um novo cargo
        $SQL = "INSERT INTO cargo (nomeCargo) VALUES (?);";
        $prepareSQL = $conexao->prepare($SQL);       // Prepara a consulta
        // Verifica se a preparação da consulta foi bem-sucedida
        if (!$prepareSQL) {
            throw new Exception("Erro ao preparar a consulta SQL: " . $conexao->error);
        }
        // Define o parâmetro da consulta com o nome do cargo
        $prepareSQL->bind_param("s", $this->nomeCargo);
        $executou = $prepareSQL->execute(); // Executa a consulta
        // Verifica se a execução da consulta foi bem-sucedida
        if (!$executou) {
            throw new Exception("Erro ao executar a consulta SQL: " . $prepareSQL->error);
        }
        $idCadastrado = $conexao->insert_id; // Obtém o ID do cargo inserido
        // Define o ID do cargo na instância atual da classe
        $this->setIdCargo($idCadastrado);

        // Retorna se a operação foi executada com sucesso
        return $executou;
    }


    // Método para excluir um cargo do banco de dados
    public function delete()
    {
        // Obtém a conexão com o banco de dados
        $conexao = Banco::getConexao();
        // Define a consulta SQL para excluir um cargo pelo ID
        $SQL = "delete from cargo where idCargo=?;";
        // Prepara a consulta
        $prepareSQL = $conexao->prepare($SQL);
        // Define o parâmetro da consulta com o ID do cargo
        $prepareSQL->bind_param("i", $this->idCargo);
        // Executa a consulta
        return $prepareSQL->execute();
    }

    // Método para atualizar os dados de um cargo no banco de dados
    public function update()
    {
        // Obtém a conexão com o banco de dados
        $conexao = Banco::getConexao();
        // Define a consulta SQL para atualizar o nome do cargo pelo ID
        $SQL = "update cargo set nomeCargo = ? where idCargo=?";
        // Prepara a consulta
        $prepareSQL = $conexao->prepare($SQL);
        // Define os parâmetros da consulta com o novo nome do cargo e o ID do cargo
        $prepareSQL->bind_param("si", $this->nomeCargo, $this->idCargo);
        // Executa a consulta
        $executou = $prepareSQL->execute();
        // Retorna se a operação foi executada com sucesso
        return $executou;
    }

    // Método para verificar se um cargo já existe no banco de dados
    public function isCargo()
    {
        // Obtém a conexão com o banco de dados
        $conexao = Banco::getConexao();
        // Define a consulta SQL para contar quantos cargos possuem o mesmo nome
        $SQL = "SELECT COUNT(*) AS qtd FROM cargo WHERE nomeCargo =?;";
        // Prepara a consulta
        $prepareSQL = $conexao->prepare($SQL);
        // Define o parâmetro da consulta com o nome do cargo
        $prepareSQL->bind_param("s", $this->nomeCargo);
        // Executa a consulta
        $executou = $prepareSQL->execute();

        // Obtém o resultado da consulta
        $matrizTuplas = $prepareSQL->get_result();

        // Extrai o objeto da tupla
        $objTupla = $matrizTuplas->fetch_object();
        // Retorna se a quantidade de cargos encontrados é maior que zero
        return $objTupla->qtd > 0;

    }

    /*
        public function readAll()
        {
            $SQL = "Select * from cargo order by nomeCargo";
            $prepareSQL = Banco::getConexao()->prepare($SQL);    // Prepara a consulta

            $prepareSQL->execute();  // Executa a consulta
            
            $matrizTuplas = $prepareSQL->get_result(); // Obtém o resultado da consulta
            $vetorCargos = array();// Inicializa um vetor para armazenar os cargos
            $i = 0;
            while ($tupla = $matrizTuplas->fetch_object()) {
                if($tupla->idCargo==1 || $tupla->idCargo== 2) {
                $vetorCargos[$i] = new Cargo();
                $vetorCargos[$i]->setIdCargo($tupla->idCargo);
                $vetorCargos[$i]->setNomeCargo($tupla->nomeCargo);
                $i++;
                }
            }
            // Retorna o vetor com os cargos encontrados
            return $vetorCargos;
        }
    */
    // Método para ler todos os cargos do banco de dados
    /*
    public function readAll()
    {
        // Obtém a conexão com o banco de dados
        $conexao = Banco::getConexao();
        // Define a consulta SQL para selecionar todos os cargos ordenados por nome
        $SQL = "Select * from cargo order by nomeCargo";
        // Prepara a consulta
        $prepareSQL = $conexao->prepare($SQL);
        // Executa a consulta
        $executou = $prepareSQL->execute();
        // Obtém o resultado da consulta
        $matrizTuplas = $prepareSQL->get_result();
        // Inicializa um vetor para armazenar os cargos
        $vetorCargos = array();
        $i = 0;
        // Itera sobre as tuplas do resultado
        while ($tupla = $matrizTuplas->fetch_object()) {
            // Cria uma nova instância de Cargo para cada tupla encontrada
            $vetorCargos[$i] = new Cargo();
            // Define o ID e o nome do cargo na instância
            $vetorCargos[$i]->setIdCargo($tupla->idCargo);
            $vetorCargos[$i]->setNomeCargo($tupla->nomeCargo);
            $i++;
        }
        // Retorna o vetor com os cargos encontrados
        return $vetorCargos;
    }
    */
    
    public function readAll()
    {
        // Obtém a conexão com o banco de dados
        $conexao = Banco::getConexao();
        // Define a consulta SQL para selecionar todos os cargos ordenados por nome
        $SQL = "Select * from cargo order by nomeCargo";
        // Prepara a consulta
        $prepareSQL = $conexao->prepare($SQL);
        // Executa a consulta
        $executou = $prepareSQL->execute();
        // Obtém o resultado da consulta
        $matrizTuplas = $prepareSQL->get_result();
        $matrizTuplas = $matrizTuplas->fetch_all(MYSQLI_ASSOC);

        return $matrizTuplas;
    }
    

    public function readByPage($pagina)
    {
        // Definir o número de itens por página
        $itensPorPagina = 5;
        // Calcular o início dos registros com base na página atual
        $inicio = ($pagina - 1) * $itensPorPagina;

        // Define a consulta SQL para selecionar os registros da página atual
        $SQL = "SELECT * FROM cargo ORDER BY nomeCargo LIMIT ?, ?";
        // Prepara a consulta
        $prepareSQL = Banco::getConexao()->prepare($SQL);
        // Vincula os parâmetros da consulta (início e número de itens por página)
        $prepareSQL->bind_param('ii', $inicio, $itensPorPagina);
        // Executa a consulta
        $executou = $prepareSQL->execute();
        // Obtém o resultado da consulta
        $matrizTuplas = $prepareSQL->get_result();
        // Transforma o resultado em um array associativo
        $matrizTuplas = $matrizTuplas->fetch_all(MYSQLI_ASSOC);
        return $matrizTuplas;
    }
    // Método para ler um cargo do banco de dados com base no ID
    public function readByID()
    {
        // Obtém a conexão com o banco de dados
        $conexao = Banco::getConexao();
        // Define a consulta SQL para selecionar um cargo pelo ID
        $SQL = "SELECT idCargo, nomeCargo FROM cargo WHERE idCargo=?;";
        // Prepara a consulta
        $prepareSQL = $conexao->prepare($SQL);
        // Define o parâmetro da consulta com o ID do cargo
        $prepareSQL->bind_param("i", $this->idCargo);
        // Executa a consulta
        $executou = $prepareSQL->execute();
        // Obtém o resultado da consulta
        $matrizTuplas = $prepareSQL->get_result();
        // Transforma o resultado em um array associativo
        $matrizTuplas = $matrizTuplas->fetch_all(MYSQLI_ASSOC);
        return $matrizTuplas;
    }

    // Método getter para idCargo
    public function getIdCargo()
    {
        return $this->idCargo;
    }

    // Método setter para idCargo
    public function setIdCargo($idCargo)
    {
        $this->idCargo = $idCargo;

        return $this;
    }

    // Método getter para nomeCargo
    public function getNomeCargo()
    {
        return $this->nomeCargo;
    }

    // Método setter para nomeCargo
    public function setNomeCargo($nameCargo)
    {
        $this->nomeCargo = $nameCargo;

        return $this;
    }
}

?>