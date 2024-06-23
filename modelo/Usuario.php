<?php
include "Banco.php";
class Usuario implements JsonSerializable
{
    private $idUsuario;
    private $nome;
    private $email;
    private $senha;
    private $banco;

    /*
        Quando for chamado o json_decode($objUsuario)
        o método  jsonSerialize() será chamado
        Um json no formato definido em  jsonSerialize()
        será criado
    */
    public function jsonSerialize()
    {
        $json = array();
        $json['idUsuario'] = $this->getIdUsuario();
        $json['nome'] = $this->getNome();
        $json['email'] = $this->getEmail();
        return $json;
    }

    /*
        método chamado pelo arquivo index.php
        quando é recebido um post para cadastro
        de novo usuário.
    */
    public function create()
    {
        //Instancia a classe banco
        $this->banco = new Banco();
        //cria um hash da senha usando o algoritmo md5
        $this->senha = md5($this->senha);
        //prepara a instrução de insert no banco
        $stmt = $this->banco->getConexao()->prepare("insert into usuario (nome, email, senha) values (?,?,?)");
        //substitui os "s" pelos interrogações e valore de variáveis.
        $stmt->bind_param("sss", $this->nome, $this->email, $this->senha);
        //execura a instrução no banco de dados.
        $resposta = $stmt->execute();
        //retorna o id do registro que foi inserido no banco.
        $idCadastrado = $this->banco->getConexao()->insert_id;
        //passa para a instancia da classe o id que foi cadastrado
        $this->setIdUsuario($idCadastrado);
        return $resposta;
    }

    public function usuarioExiste(){
        $this->banco = new Banco();  //faz uma instância de banco  
        //cria um hash utilizando o algoritmo md5 para a senha
        //prepara a instrução de select no banco
        $sql = "select count(*) as qtd from usuario where email = ?";
        $stmt = $this->banco->getConexao()->prepare($sql);
        //víncula os parametros substituindo os "?" pelos valores correspondentes.
        $stmt->bind_param("s", $this->email);
        $stmt->execute();  //executa a  instrução sql no sgbd
        //recupera os dados referentes a execução do sql
        $resultado = $stmt->get_result();
        //estrutura de repetição que passa por todos os dados
        while ($linha = $resultado->fetch_object()) {
            //verifica se qtd é igual a 1.
            //igual a 1 significa que existe 1 usuario
            //com o email e senha fornecido. 
            if ($linha->qtd == 1) {
                return true;
            }
        }
        return false;
    }

    /*
        método chamado pelo arquivo index.php
        quando é recebido um Delete para exclusão 
        de usuário.
    */
    public function delete()
    {
        $this->banco = new Banco();  //Instancia a classe banco
        //prepara a instrução de delete no banco
        $stmt = $this->banco->getConexao()->prepare("delete from usuario where idUsuario = ?");
        //substitui o ? pelo "i"(inteiro) que corresponde ao idUsuario
        $stmt->bind_param("i", $this->idUsuario);
        //retorna verdadeiro se a instrução foi executa com sucesso no sgbd
        return $stmt->execute(); //executa a instrução no sgbd
    }
    /*
        método chamado pelo arquivo index.php
        quando é recebido um PUT para atualização 
        de usuário.
    */
    public function update(){   
        $this->banco = new Banco(); //Instancia a classe banco   
        $this->senha = md5($this->senha);  //cria um hash da senha usando o algoritmo md5
        //prepara a instrução de update no banco
        $stmt = $this->banco->getConexao()->prepare("update usuario 
                                    set nome=?, 
                                    email=?,
                                    senha=?
                                    where idusuario = ?");
        //substitui os "s,i" pelos interrogações e valores de variáveis.
        $stmt->bind_param("sssi", $this->nome, $this->email, $this->senha, $this->idUsuario);
        return $stmt->execute();  //executa a instrução no sgbd
    }

    /*
        método chamado pelo arquivo index.php
        quando é recebido um GET com id do Usuario
        para retornar dados do usuário com id corespondente.
    */
    public function read(){
        
        $this->banco = new Banco(); //Instancia a classe banco
        //prepara a instrução de select no banco
        $stmt = $this->banco->getConexao()->prepare("select *  from usuario where idUsuario=?");
        //substitui os "i" pelo interrogação e valor da variável.
        $stmt->bind_param("i", $this->idUsuario);
        
        $stmt->execute(); //executa a instrução no sgbd
        $resultado = $stmt->get_result(); //recupera os dados referentes a execução da instrução.
        //cria um vetor de objetos com os do usuário do id.
        $usuario= array();
        while ($linha = $resultado->fetch_object()) {
            //é utilizado apenas o indice zero pois 
            //só deve existir um único usuário com o id especifico.
            $usuario[0] = new Usuario();
            $usuario[0]->setIdUsuario($linha->idUsuario);
            $usuario[0]->setNome($linha->nome);
            $usuario[0]->setEmail($linha->email);
        }
        return   $usuario; //retorna um vetor com os dados do usuário.
    }
    
    // método chamado pelo arquivo index.php quando é recebido um GET sem o id do Usuario para retornar dados de todos os usuario.
    public function readAll(){ 
        $this->banco = new Banco(); //Instancia a classe banco
        //prepara a instrução de select no banco
        $stmt = $this->banco->getConexao()->prepare("select *  from usuario ");
        $stmt->execute();  //executa a instrução no sgbd
        $resultado = $stmt->get_result();  //recupera os dados referentes a execução da instrução.
        $usuario = array(); $i = 0;
        //cria um vetor com os dados de todos os usuários
        while ($linha = $resultado->fetch_object()) {
            
            $usuario[$i] = new Usuario(); //instância um novo usuário em cada posição do vetor
            //recupera os dados que vieram do sgbd  e faz o "set" dos dados
            $usuario[$i]->setIdUsuario($linha->idUsuario);
            $usuario[$i]->setNome($linha->nome);
            $usuario[$i]->setEmail($linha->email);
            $i++;
        }
        return   $usuario; //retorna um vetor de usuários
    }

    //método que verifica se o usuário e senha estão corretos
    public function verificarUsuarioSenha(){   
        $this->banco = new Banco(); //faz uma instância de banco   
        $this->senha = md5($this->senha); //cria um hash utilizando o algoritmo md5 para a senha
        $sql = "select count(*) as qtd ,nome,idUsuario  from usuario where email = ? and senha =?";
        $stmt = $this->banco->getConexao()->prepare($sql);
        $stmt->bind_param("ss", $this->email, $this->senha);
        $stmt->execute();  //executa a  instrução sql no sgbd
        $resultado = $stmt->get_result();   //recupera os dados referentes a execução do sql
        //estrutura de repetição que passa por todos os dados
        while ($linha = $resultado->fetch_object()) { 
            //verifica se qtd é igual a 1, significa que existe 1 usuario om o email e senha fornecido.
            if ($linha->qtd == 1) {
                $this->setIdUsuario($linha->idUsuario);
                $this->setNome($linha->nome);
                return true;
            }
        }
        return false;
    }

    /**
     * Get the value of idUsuario
     */
    public function getIdUsuario()
    {
        return $this->idUsuario;
    }

    /**
     * Set the value of idUsuario
     *
     * @return  self
     */
    public function setIdUsuario($idUsuario)
    {
        $this->idUsuario = $idUsuario;

        return $this;
    }

    /**
     * Get the value of nome
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Set the value of nome
     *
     * @return  self
     */
    public function setNome($nome)
    {
        $this->nome = $nome;

        return $this;
    }

    /**
     * Get the value of email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of senha
     */
    public function getSenha()
    {
        return $this->senha;
    }

    /**
     * Set the value of senha
     *
     * @return  self
     */
    public function setSenha($senha)
    {
        $this->senha = $senha;

        return $this;
    }
}
