<?php
/**
 * Created by PhpStorm.
 * User: tassio
 * Date: 2019-03-16
 * Time: 15:39
 */

require_once "iPage.php";

class daoLivro implements iPage
{

    public function remover($source)
    {
        try {
            $statement = Conexao::getInstance()->prepare("DELETE FROM tb_livro WHERE idtb_livro = :id");
            $statement->bindValue(":id", $source->getIdLivro());
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
            if ($source->getIdLivro() != "") {
                $statement = Conexao::getInstance()->prepare("UPDATE tb_livro SET titulo=:titulo, isbn=:isbn, edicao=:edicao, ano=:ano, upload=:upload, tb_editora_idtb_editora=:idEditora, tb_categoria_idtb_categoria=:idCategoria WHERE idtb_livro = :id;");
                $statement->bindValue(":id", $source->getIdLivro());
            } else {
                $statement = Conexao::getInstance()->prepare("INSERT INTO tb_livro (titulo, isbn, edicao, ano, upload, tb_editora_idtb_editora, tb_categoria_idtb_categoria) VALUES (:titulo, :isbn, :edicao, :ano, :upload, :idEditora, :idCategoria)");
            }

            $statement->bindValue(":titulo", $source->getTitulo());
            $statement->bindValue(":isbn", $source->getIsbn());
            $statement->bindValue(":edicao", $source->getEdicao());
            $statement->bindValue(":ano", $source->getAno());
            $statement->bindValue(":upload", $source->getUpload());
            $statement->bindValue(":idEditora", $source->getEditora());
            $statement->bindValue(":idCategoria", $source->getCategoria());

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
            $statement = Conexao::getInstance()->prepare("SELECT idtb_livro, titulo, isbn, edicao, ano, upload, tb_editora_idtb_editora, tb_categoria_idtb_categoria FROM tb_livro WHERE idtb_livro = :id");
            $statement->bindValue(":id", $source->getIdLivro());
            if ($statement->execute()) {
                $rs = $statement->fetch(PDO::FETCH_OBJ);
                $source->setIdLivro($rs->idtb_livro);
                $source->setTitulo($rs->titulo);
                $source->setAno($rs->ano);
                $source->setCategoria($rs->tb_categoria_idtb_categoria);
                $source->setEditora($rs->tb_editora_idtb_editora);
                $source->setUpload($rs->upload);
                $source->setEdicao($rs->edicao);
                $source->setIsbn($rs->isbn);
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
        $sql = "SELECT idtb_livro, titulo, isbn, edicao, ano, upload, (select nomeEditora from tb_editora where idtb_editora = tb_editora_idtb_editora) editora, (select nomeCategoria from tb_categoria where idtb_categoria = tb_categoria_idtb_categoria) categoria FROM tb_livro";
        $statement = Conexao::getInstance()->prepare($sql);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_OBJ);
    }
}