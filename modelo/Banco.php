<?php
// Definição da classe Banco
class Banco
{
    // Propriedades estáticas para armazenar informações de conexão com o banco de dados
    private static $HOST = '127.0.0.1';
    private static $USER = 'root';
    private static $PWD = '';
    private static $DB = 'aula_api_2024';
    private static $PORT = 3306;
    private static $CONEXAO = null;

    // Método privado para estabelecer uma conexão com o banco de dados
    private static function conectar()
    {
        // Desativar exibição de erros temporariamente
        error_reporting(0);
        try {
            // Tenta estabelecer uma nova conexão utilizando as informações fornecidas
            Banco::$CONEXAO = new mysqli(Banco::$HOST, Banco::$USER, Banco::$PWD, Banco::$DB, Banco::$PORT);
            if (Banco::$CONEXAO->connect_error) {
                throw new Exception("Erro ao conectar ao banco de dados: " . Banco::$CONEXAO->connect_error);
            }
        } catch (Exception $e) {
            // Em caso de qualquer outra exceção, trata normalmente
            $objResposta = new stdClass();
            $objResposta->cod = 1;
            $objResposta->erro = $e->getMessage();

            die(json_encode($objResposta));
        } catch (Error $e) {
            $objResposta = new stdClass();
            $objResposta->erro = $e->getMessage();
            echo json_encode($objResposta);
            die(json_encode($objResposta));
        }

    }

    // Método público para obter a conexão com o banco de dados
    public static function getConexao()
    {
        // Verifica se já existe uma conexão estabelecida
        if (Banco::$CONEXAO == null) {
            // Se não houver, estabelece uma nova conexão
            Banco::conectar();
        }
        // Retorna a conexão
        return Banco::$CONEXAO;
    }
}
?>