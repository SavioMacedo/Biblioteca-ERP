<?php

require_once "iPage.php";

class daoEmprestimo implements iPage
{

    public function remover($source)
    {
        try {
            $statement = Conexao::getInstance()->prepare("DELETE FROM tb_emprestimo WHERE tb_usuario_idtb = :idUsuario AND tb_exemplar_idtb_exemplar = :idExemplar");
            $statement->bindValue(":idUsuario", $source->getUsuario()->getIdtbUsuario());
            $statement->bindValue(":idExemplar", $source->getExemplar()->getIdExemplar());
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
            $existe = false;
            $preStatement = Conexao::getInstance()->prepare("SELECT * from tb_emprestimo WHERE tb_usuario_idtb_usuario = :idUsuario AND tb_exemplar_idtb_exemplar = :idExemplar and dataDevolucao is null");
            
            $preStatement->bindValue(":idUsuario", $source->getUsuario());
            $preStatement->bindValue(":idExemplar", $source->getExemplar());

            if ($preStatement->execute()) {
                if ($preStatement->rowCount() > 0) {
                      $existe = true;
                }
            } else {
                throw new PDOException("<script> alert('Não foi possível executar a declaração pré SQL !'); </script>");
            }

            if ($existe) {
                $statement = Conexao::getInstance()->prepare("UPDATE tb_emprestimo
                                                                           SET observacao = :observacao,
                                                                           dataDevolucao = :dataDevolucao
                                                                         WHERE tb_usuario_idtb_usuario = :idUsuario
                                                                           AND tb_exemplar_idtb_exemplar = :idExemplar;");
            } else {
                $statement = Conexao::getInstance()->prepare("INSERT INTO tb_emprestimo (observacao,
                dataDevolucao, tb_usuario_idtb_usuario, tb_exemplar_idtb_exemplar) VALUES (:observacao,:dataDevolucao, :idUsuario, :idExemplar)");
            }

            $statement->bindValue(":dataDevolucao", $source->getDataDevolucao());
            $statement->bindValue(":observacao", $source->getObservacao());
            $statement->bindValue(":idUsuario", $source->getUsuario());
            $statement->bindValue(":idExemplar", $source->getExemplar());

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
            $statement = Conexao::getInstance()->prepare("SELECT idtb_editora, nomeEditora FROM tb_editora WHERE idtb_editora = :id");
            $statement->bindValue(":id", $source->getIdEditora());
            if ($statement->execute()) {
                $rs = $statement->fetch(PDO::FETCH_OBJ);
                $source->setIdtbEditores($rs->idtb_editora);
                $source->setNomeEditora($rs->NomeEditora);
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
        $sql = "SELECT concat(tb_usuario_idtb_usuario, tb_exemplar_idtb_exemplar) `tb_usuario_idtb_usuario-tb_exemplar_idtb_exempla`, (select nomeUsuario from tb_usuario where idtb_usuario = tb_usuario_idtb_usuario) nomeUsuario, (select titulo from tb_livro, tb_exemplar where idtb_exemplar = tb_exemplar_idtb_exemplar and tb_livro_idtb_livro = idtb_livro) titulo, dataEmprestimo, observacao, dataDevolucao FROM tb_emprestimo";
        $statement = Conexao::getInstance()->prepare($sql);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_OBJ);
    }

}