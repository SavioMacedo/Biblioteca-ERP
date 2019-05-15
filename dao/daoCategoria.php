<?php
/**
 * Created by PhpStorm.
 * User: tassio
 * Date: 2019-03-16
 * Time: 15:39
 */

require_once "iPage.php";

class daoCategoria implements iPage
{

    public function remover($source)
    {
        try {
            $statement = Conexao::getInstance()->prepare("DELETE FROM tb_categoria WHERE idtb_categoria = :id");
            $statement->bindValue(":id", $source->getIdCategoria());
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
            if ($source->getIdCategoria() != "") {
                $statement = Conexao::getInstance()->prepare("UPDATE tb_categoria SET nomeCategoria=:nome WHERE idtb_categoria = :id;");
                $statement->bindValue(":id", $source->getIdCategoria());
            } else {
                $statement = Conexao::getInstance()->prepare("INSERT INTO tb_categoria (nomeCategoria) VALUES (:nome)");
            }
            $statement->bindValue(":nome", $source->getNomeCategoria());
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
            $statement = Conexao::getInstance()->prepare("SELECT idtb_categoria, nomeCategoria FROM tb_categoria WHERE idtb_categoria = :id");
            $statement->bindValue(":id", $source->getIdCategoria());
            if ($statement->execute()) {
                $rs = $statement->fetch(PDO::FETCH_OBJ);
                $source->setIdCategoria($rs->idtb_categoria);
                $source->setNomeCategoria($rs->nomeCategoria);
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
        $sql = "SELECT idtb_categoria, `nomeCategoria` FROM tb_categoria";
        $statement = Conexao::getInstance()->prepare($sql);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_OBJ);
    }
}