<?php
/**
 * Created by PhpStorm.
 * User: tassio
 * Date: 2019-03-16
 * Time: 15:39
 */

require_once "iPage.php";

class daoUsuario implements iPage
{

    public function logar($email, $senha)
    {
        try {
            $statement = Conexao::getInstance()->prepare("SELECT idtb_usuario, nomeUsuario, tipo, email, senha FROM tb_usuario WHERE email = :email and senha = :senha");

            $statement->bindValue(":email", $email);
            $statement->bindValue(":senha", $senha);

            if ($statement->execute()) {
                $rs = $statement->fetch(PDO::FETCH_OBJ);
                
                if(!$rs)
                    return "Usuario e/ou Senha não encontrado.";

                $usuario = new Usuario($rs->idtb_usuario ,$rs->nomeUsuario, $rs->tipo, $rs->email, $rs->senha);
                
                return $usuario;
            } else {
                throw new PDOException("<script> alert('Não foi possível executar a declaração SQL !'); </script>");
            }
        } catch (PDOException $erro) {
            return "Erro: " . $erro->getMessage();
        }
    }

    public function remover($source)
    {
        try {
            $statement = Conexao::getInstance()->prepare("DELETE FROM tb_usuario WHERE idtb_usuario = :id");
            $statement->bindValue(":id", $source->getIdtbUsuario());
            if ($statement->execute()) {
                return "<script> alert('Registo foi excluído com êxito !'); </script>";
            } else {
                throw new PDOException("<script> alert('Não foi possível executar a declaração SQL !'); </script>");
            }
        } catch (PDOException $erro) {
            return "Erro: " . $erro->getMessage();
        }
    }

    public function salvar($source)
    {
        try {
            if ($source->getIdtbUsuario() != "") {
                $statement = Conexao::getInstance()->prepare("UPDATE tb_usuario SET nomeUsuario=:nome, tipo=:tipo, email=:email, senha=:senha WHERE idtb_usuario = :id;");
                $statement->bindValue(":id", $source->getIdtbUsuario());
            } else {
                $statement = Conexao::getInstance()->prepare("INSERT INTO tb_usuario (nomeUsuario, tipo, email, senha) VALUES (:nome, :tipo, :email, :senha)");
            }
            
            $statement->bindValue(":nome", $source->getNomeUsuario());
            $statement->bindValue(":tipo", $source->getTipo());
            $statement->bindValue(":email", $source->getEmail());
            $statement->bindValue(":senha", $source->getSenha());

            if ($statement->execute()) {
                if ($statement->rowCount() > 0) {
                    return "<script> alert('Dados cadastrados com sucesso !'); </script>";
                } else {
                    return "<script> alert('Erro ao tentar efetivar cadastro !'); </script>";
                }
            } else {
                throw new PDOException("<script> alert('Não foi possível executar a declaração SQL !'); </script>");
            }
        } catch (PDOException $erro) {
            return "Erro: " . $erro->getMessage();
        }
    }

    public function atualizar($source)
    {
        try {
            $statement = Conexao::getInstance()->prepare("SELECT nomeUsuario, tipo, email, senha FROM tb_usuario WHERE idtb_usuario = :id");

            $statement->bindValue(":id", $source->getIdtbUsuario());

            if ($statement->execute()) {
                $rs = $statement->fetch(PDO::FETCH_OBJ);
                
                $source->setNomeUsuario($rs->nomeUsuario);
                $source->setTipo($rs->tipo);
                $source->setEmail($rs->email);
                $source->setSenha($rs->senha);
                
                return $source;
            } else {
                throw new PDOException("<script> alert('Não foi possível executar a declaração SQL !'); </script>");
            }
        } catch (PDOException $erro) {
            return "Erro: " . $erro->getMessage();
        }
    }

    public function getAll()
    {
        $sql = "SELECT idtb_usuario, nomeUsuario, tipo, email, senha FROM tb_usuario";
        $statement = Conexao::getInstance()->prepare($sql);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_OBJ);
    }

    public function isHabilitado($idUsuario)
    {
        $sql = "SELECT count(1) as contador, tipo from tb_emprestimo, tb_usuario where tb_usuario_idtb_usuario = tbid_usuario and tb_usuario_idtb_usuario = :tb_usuario_idtb_usuario and dataDevolucao = '0000-00-00 00:00:00'";
        $statement = Conexao::getInstance()->prepare($sql);
        $statement->bindValue(":tb_usuario_idtb_usuario", $idUsuario);
        $statement->execute();
        $obj = $statement->fetchAll(PDO::FETCH_OBJ);

        if(count($obj) < 1)
            return true;

        switch($obj->tipo)
        {
            case "1":
                return ($obj->contador < 3);
            case "2":
                return ($obj->contador < 3);
            case "3":
                return ($obj->contador < 5);

            default:
                return false;
        }
    }

    public function FirstOrDefault($idUsuario = "")
    {
        try
        {
            $statement = Conexao::getInstance()->prepare("SELECT * from tb_usuario where idtb_usuario = if(:idtb_usuario='', idtb_usuario, :idtb_usuario) limit 1");
            $statement->bindValue(":idtb_usuario", $idUsuario);
            $statement->execute();
            $array = $statement->fetchAll(PDO::FETCH_OBJ);
            if(count($array) >= 1)
            {
                return $array[0];
            }
            else
            {
                return null;
            }
        }
        catch (PDOException $erro) 
        {
            return "Erro: " . $erro->getMessage();
        }
    }

}