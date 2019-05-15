<?php
/**
 * Created by PhpStorm.
 * User: tassio
 * Date: 2019-03-16
 * Time: 15:39
 */

require_once "iPage.php";

class daoAutor implements iPage
{

    public function remover($source)
    {
        try {
            $statement = Conexao::getInstance()->prepare("DELETE FROM tb_autores WHERE idtb_autores = :id");
            $statement->bindValue(":id", $source->getIdtbAutores());
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
            if ($source->getIdtbAutores() != "") {
                $statement = Conexao::getInstance()->prepare("UPDATE tb_autores SET nomeAutor=:nome WHERE idtb_autores = :id;");
                $statement->bindValue(":id", $source->getIdtbAutores());
            } else {
                $statement = Conexao::getInstance()->prepare("INSERT INTO tb_autores (nomeAutor) VALUES (:nome)");
            }
            $statement->bindValue(":nome", $source->getNomeAutor());
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
            $statement = Conexao::getInstance()->prepare("SELECT idtb_autores, nomeAutor FROM tb_autores WHERE idtb_autores = :id");
            $statement->bindValue(":id", $source->getIdtbAutores());
            if ($statement->execute()) {
                $rs = $statement->fetch(PDO::FETCH_OBJ);
                $source->setIdtbAutores($rs->idtb_autores);
                $source->setNomeAutor($rs->nomeAutor);
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
        $sql = "SELECT idtb_autores, nomeAutor FROM tb_autores";
        $statement = Conexao::getInstance()->prepare($sql);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_OBJ);
    }

}