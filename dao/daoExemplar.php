<?php
/**
 * Created by PhpStorm.
 * User: tassio
 * Date: 2019-03-16
 * Time: 15:39
 */

require_once "iPage.php";

class daoExemplar implements iPage
{

    public function remover($source)
    {
        try {
            $statement = Conexao::getInstance()->prepare("DELETE FROM tb_exemplar WHERE idtb_exemplar = :id");
            $statement->bindValue(":id", $source->getIdExemplar());
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
            if ($source->getIdExemplar() != "") {
                $statement = Conexao::getInstance()->prepare("UPDATE tb_exemplar SET tb_livro_idtb_livro =:idLivro, tipoExemplar =:tipoExemplar WHERE idtb_exempl = :id;");
                $statement->bindValue(":id", $source->getIdExemplar());
            } else {
                $statement = Conexao::getInstance()->prepare("INSERT INTO tb_exemplar (tb_livro_idtb_livro, tipoExemplar) VALUES (:idLivro, :tipoExemplar)");
            }

            $statement->bindValue(":idLivro", $source->getLivro());
            $statement->bindValue(":tipoExemplar", $source->getTipoLivro());

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
            $statement = Conexao::getInstance()->prepare("SELECT idtb_exemplar, tb_livro_idtb_livro FROM tb_exemplar WHERE idtb_exemplar = :id");
            $statement->bindValue(":id", $source->getIdExemplar());
            if ($statement->execute()) {
                $rs = $statement->fetch(PDO::FETCH_OBJ);
                $source->setIdExemplar($rs->idtb_exemplar);
                $source->setLivro($rs->tb_livro_idtb_livro);
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
        $sql = "SELECT idtb_exemplar, (select titulo from tb_livro where idtb_livro = tb_livro_idtb_livro) titulo FROM tb_exemplar";
        $statement = Conexao::getInstance()->prepare($sql);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_OBJ);
    }
}