<?php
// Inclui as classes Banco e Cargo, que provavelmente contêm funcionalidades relacionadas ao banco de dados e aos cargos
require_once ("modelo/Banco.php");
require_once ("modelo/Cargo.php");

// Definição da classe Funcionario, que implementa a interface JsonSerializable
class Funcionario implements JsonSerializable
{
    // Propriedades privadas da classe
    private $idFuncionario;
    private $nomeFuncionario;
    private $email;
    private $senha;
    private $recebeValeTransporte;
    private $cargo;

    // Construtor da classe
    public function __construct()
    {
        // Inicializa a propriedade $cargo com um novo objeto da classe Cargo
        $this->cargo = new Cargo();
    }

    // Método necessário pela interface JsonSerializable para serialização do objeto para JSON
    public function jsonSerialize()
    {
        // Cria um objeto stdClass para armazenar os dados do funcionário
        $respostaPadrao = new stdClass();
        $respostaPadrao->idFuncionario = $this->idFuncionario;
        $respostaPadrao->nomeFuncionario = $this->nomeFuncionario;
        $respostaPadrao->email = $this->email;
        // A senha não é incluída na serialização por motivos de segurança
        //$respostaPadrao->senha = $this->senha;
        $respostaPadrao->recebeValeTransporte = $this->recebeValeTransporte;
        $respostaPadrao->idCargo = $this->cargo->getIdCargo();
        $respostaPadrao->nomeCargo = $this->cargo->getNomeCargo();
        return $respostaPadrao;
    }

    // Método para criar um novo funcionário no banco de dados
    public function create()
    {
        // Obtém a conexão com o banco de dados
        $conexao = Banco::getConexao();
        // Define a consulta SQL para inserir um novo funcionário
        $SQL = "insert into Funcionario (nomeFuncionario, email, senha, recebeValeTransporte,Cargo_idCargo) values(?,?,?,?,?)";
        // Prepara a consulta
        $prepararSQL = $conexao->prepare($SQL);
        // Obtém o ID do cargo associado ao funcionário
        $idCargo = $this->cargo->getIdCargo();
        // Define os parâmetros da consulta com os dados do funcionário e o ID do cargo
        $prepararSQL->bind_param("sssii", $this->nomeFuncionario, $this->email, $this->senha, $this->recebeValeTransporte, $idCargo);
        // Executa a consulta
        $executar = $prepararSQL->execute();
        // Obtém o ID do funcionário cadastrado
        $idCadastrado = $conexao->insert_id;
        // Define o ID do funcionário na instância atual da classe
        $this->setIdFuncionario($idCadastrado);
        // Fecha a consulta
        $prepararSQL->close();
        // Retorna se a operação foi executada com sucesso
        return $executar;
    }

    //método que verifica se o usuário e senha estão corretos
    public function verificarUsuarioSenha()
    {
        // Obtém a conexão com o banco de dados
        $conexao = Banco::getConexao();
               
        $SQL = "SELECT COUNT(*) AS qtd, idFuncionario,nomeFuncionario,email,idCargo, nomeCargo 
        from funcionario join cargo on cargo_idCargo = idCargo WHERE email=? and senha =MD5(?)";
        
        $prepararSQL =  $conexao->prepare($SQL);

        $prepararSQL->bind_param("ss", $this->email, $this->senha);
         //executa a  instrução sql no sgbd
        $prepararSQL->execute(); 
        //recupera os dados referentes a execução do sql
        $matrizTuplas = $prepararSQL->get_result();   
        //estrutura de repetição que passa por todos os dados
        while ($tupla = $matrizTuplas->fetch_object()) {
            //verifica se qtd é igual a 1, significa que existe 1 usuario om o email e senha fornecido.
            if ($tupla->qtd == 1) {
                $this->setIdFuncionario($tupla->idFuncionario);
                $this->setNomeFuncionario($tupla->nomeFuncionario);
                $this->setEmail($tupla->email);
                $this->getCargo()->setIdCargo($tupla->idCargo);
                $this->getCargo()->setNomeCargo($tupla->nomeCargo);
                return true;
            }
        }
        return false;
    }

    // Método para criar um novo funcionário no banco de dados
    public function createFromCSV()
    {
        // Obtém a conexão com o banco de dados
        $conexao = Banco::getConexao();
        // Define a consulta SQL para inserir um novo funcionário
        $SQL = "INSERT into Funcionario 
        (nomeFuncionario, email, senha, recebeValeTransporte,Cargo_idCargo) 
        VALUES(?,?,?,?,(SELECT idCargo FROM cargo WHERE nomeCargo = ? ))";
        // Prepara a consulta
        $prepararSQL = $conexao->prepare($SQL);
        // Obtém o ID do cargo associado ao funcionário
        $nomeCargo = $this->cargo->getNomeCargo();
        // Define os parâmetros da consulta com os dados do funcionário e o ID do cargo
        $prepararSQL->bind_param(
            "sssis",
            $this->nomeFuncionario,
            $this->email,
            $this->senha,
            $this->recebeValeTransporte,
            $nomeCargo
        );
        // Executa a consulta
        $executar = $prepararSQL->execute();
        // Obtém o ID do funcionário cadastrado
        $idCadastrado = $conexao->insert_id;
        // Define o ID do funcionário na instância atual da classe
        $this->setIdFuncionario($idCadastrado);
        // Fecha a consulta
        $prepararSQL->close();
        // Retorna se a operação foi executada com sucesso
        return $executar;
    }

    // Método para verificar se um funcionário já existe no banco de dados
    public function isFuncionario()
    {
        // Obtém a conexão com o banco de dados
        $conexao = Banco::getConexao();
        // Define a consulta SQL para contar quantos funcionários possuem o mesmo e-mail
        $SQL = "SELECT count(*) qtd FROM funcionario WHERE email=?";
        // Prepara a consulta
        $prepararSQL = $conexao->prepare($SQL);
        // Define o parâmetro da consulta com o e-mail do funcionário
        $prepararSQL->bind_param("s", $this->email);
        // Executa a consulta
        $executar = $prepararSQL->execute();
        // Obtém o resultado da consulta
        $matrizTuplas = $prepararSQL->get_result();
        // Extrai o objeto da tupla
        $tupla = $matrizTuplas->fetch_object();
        // Fecha a consulta
        $prepararSQL->close();
        // Retorna se a quantidade de funcionários encontrados é maior que zero
        return $tupla->qtd > 0;
    }

    // Método para atualizar os dados de um funcionário no banco de dados
    public function update()
    {
        // Obtém a conexão com o banco de dados
        $conexao = Banco::getConexao();
        // Define a consulta SQL para atualizar os dados do funcionário
        $SQL = "update Funcionario set nomeFuncionario=?, email=?, senha=?,recebeValeTransporte=?, Cargo_idCargo=? where idFuncionario=?";
        // Prepara a consulta
        $prepararSQL = $conexao->prepare($SQL);
        // Obtém o ID do cargo associado ao funcionário
        $idCargo = $this->getCargo()->getIdCargo();
        // Define os parâmetros da consulta com os novos dados do funcionário e o ID do cargo
        $prepararSQL->bind_param("sssiii", $this->nomeFuncionario, $this->email, $this->senha, $this->recebeValeTransporte, $idCargo, $this->idFuncionario);
        // Executa a consulta
        $executar = $prepararSQL->execute();
        // Fecha a consulta
        $prepararSQL->close();
        // Retorna se a operação foi executada com sucesso
        return $executar;
    }

    // Método para excluir um funcionário do banco de dados
    public function delete()
    {
        // Obtém a conexão com o banco de dados
        $conexao = Banco::getConexao();
        // Define a consulta SQL para excluir um funcionário pelo ID
        $SQL = "delete from Funcionario where idFuncionario = ?";
        // Prepara a consulta
        $prepararSQL = $conexao->prepare($SQL);
        // Define o parâmetro da consulta com o ID do funcionário
        $prepararSQL->bind_param("i", $this->idFuncionario);
        // Executa a consulta
        $executou = $prepararSQL->execute();
        // Fecha a consulta
        $prepararSQL->close();
        // Retorna se a operação foi executada com sucesso
        return $executou;
    }

    // Método para obter os dados de um funcionário pelo ID
    public function readById()
    {
        // Obtém a conexão com o banco de dados
        $conexao = Banco::getConexao();
        // Define a consulta SQL para obter os dados de um funcionário pelo ID
        $SQL = "SELECT * FROM funcionario JOIN cargo ON funcionario.Cargo_idCargo= cargo.idCargo WHERE idFuncionario=?; ";
        // Prepara a consulta
        $prepararSQL = $conexao->prepare($SQL);
        // Define o parâmetro da consulta com o ID do funcionário
        $prepararSQL->bind_param("i", $this->idFuncionario);
        // Executa a consulta
        $executou = $prepararSQL->execute();
        // Obtém o resultado da consulta
        $matrizTuplas = $prepararSQL->get_result();
        // Inicializa um contador
        $i = 0;
        // Inicializa um array para armazenar os funcionários encontrados
        $funcionario[0] = new Funcionario();
        // Itera sobre as tuplas retornadas
        while ($tupla = $matrizTuplas->fetch_object()) {
            // Define os dados do funcionário na instância atual da classe
            $funcionario[0]->setIdFuncionario($tupla->idFuncionario);
            $funcionario[0]->setNomeFuncionario($tupla->nomeFuncionario);
            $funcionario[0]->setEmail($tupla->email);
            $funcionario[0]->setSenha($tupla->senha);
            $funcionario[0]->setRecebeValeTransporte($tupla->recebeValeTransporte);

            // Cria um novo objeto da classe Cargo e define seus dados
            $cargo = new Cargo();
            $cargo->setIdCargo($tupla->idCargo);
            $cargo->setNomeCargo($tupla->nomeCargo);

            // Define o cargo do funcionário
            $funcionario[0]->setCargo($cargo);
        }
        // Retorna o array contendo os funcionários encontrados
        return $funcionario;
    }

    // Método para obter todos os funcionários
    public function readAll()
    {
        // Obtém a conexão com o banco de dados
        $conexao = Banco::getConexao();
        // Define a consulta SQL para obter todos os funcionários e seus cargos
        $SQL = "SELECT * FROM funcionario JOIN cargo ON funcionario.Cargo_idCargo= cargo.idCargo  order by nomeFuncionario";
        // Prepara a consulta
        $prepararSQL = $conexao->prepare($SQL);
        // Executa a consulta
        $executou = $prepararSQL->execute();
        // Obtém o resultado da consulta
        $matrizTuplas = $prepararSQL->get_result();
        // Inicializa um contador
        $i = 0;
        // Inicializa um array para armazenar os funcionários encontrados
        $funcionarios = array();
        // Itera sobre as tuplas retornadas
        while ($tupla = $matrizTuplas->fetch_object()) {
            // Cria um novo objeto da classe Funcionario e define seus dados
            $funcionarios[$i] = new Funcionario();
            $funcionarios[$i]->setIdFuncionario($tupla->idFuncionario);
            $funcionarios[$i]->setNomeFuncionario($tupla->nomeFuncionario);
            $funcionarios[$i]->setEmail($tupla->email);
            $funcionarios[$i]->setSenha($tupla->senha);
            $funcionarios[$i]->setRecebeValeTransporte($tupla->recebeValeTransporte);

            // Cria um novo objeto da classe Cargo e define seus dados
            $cargo = new Cargo();
            $cargo->setIdCargo($tupla->idCargo);
            $cargo->setNomeCargo($tupla->nomeCargo);

            // Define o cargo do funcionário
            $funcionarios[$i]->setCargo($cargo);
            // Incrementa o contador
            $i++;
        }
        // Retorna o array contendo os funcionários encontrados
        return $funcionarios;
    }

    // Método getter para idFuncionario
    public function getIdFuncionario()
    {
        return $this->idFuncionario;
    }

    // Método setter para idFuncionario
    public function setIdFuncionario($idFuncionario)
    {
        $this->idFuncionario = $idFuncionario;
        return $this;
    }

    // Método getter para nomeFuncionario
    public function getNomeFuncionario()
    {
        return $this->nomeFuncionario;
    }

    // Método setter para nomeFuncionario
    public function setNomeFuncionario($nomeFuncionario)
    {
        $this->nomeFuncionario = $nomeFuncionario;
        return $this;
    }

    // Método getter para email
    public function getEmail()
    {
        return $this->email;
    }

    // Método setter para email
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    // Método getter para senha
    public function getSenha()
    {
        return $this->senha;
    }

    // Método setter para senha
    public function setSenha($senha)
    {
        $this->senha = $senha;
        return $this;
    }

    // Método getter para recebeValeTransporte
    public function getRecebeValeTransporte()
    {
        return $this->recebeValeTransporte;
    }

    // Método setter para recebeValeTransporte
    public function setRecebeValeTransporte($recebeValeTransporte)
    {
        $this->recebeValeTransporte = $recebeValeTransporte;
        return $this;
    }

    // Método getter para cargo
    public function getCargo()
    {
        return $this->cargo;
    }

    // Método setter para cargo
    public function setCargo($cargo)
    {
        $this->cargo = $cargo;
        return $this;
    }
}
?>