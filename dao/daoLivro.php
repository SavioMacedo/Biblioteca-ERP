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
        $sql = "SELECT idtb_livro, titulo, isbn, edicao, ano, upload, (select nomeEditora from tb_editora where idtb_editora = tb_editora_idtb_editora) editora, (select nomeCategoria from tb_categoria where idtb_categoria = tb_categoria_idtb_categoria) categoria, (select count(1) from tb_exemplar where tb_livro_idtb_livro = idtb_livro and podeCircular = 'S') as exemplarCircular, (select count(1) from tb_exemplar where tb_livro_idtb_livro = idtb_livro and podeCircular = 'N') as exemplarNaoCircular FROM tb_livro";
        
        $statement = Conexao::getInstance()->prepare($sql);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_OBJ);
    }

    public function getFiltered(livro $source)
    {
        $sql = "SELECT idtb_livro, titulo, isbn, edicao, ano, upload, (select nomeEditora from tb_editora where idtb_editora = tb_editora_idtb_editora) editora, (select nomeCategoria from tb_categoria where idtb_categoria = tb_categoria_idtb_categoria) categoria, (select count(1) from tb_exemplar where tb_livro_idtb_livro = idtb_livro and podeCircular = 'S') as exemplarCircular, (select count(1) from tb_exemplar where tb_livro_idtb_livro = idtb_livro and podeCircular = 'N') as exemplarNaoCircular FROM tb_livro where
        idtb_livro = if(:idtb_livro='',idtb_livro,:idtb_livro) and
        upper(titulo) like if(:titulo='', upper(titulo), upper(:titulo)) and 
        edicao like if(:edicao='', edicao, :edicao) and
        ano like if(:ano='', ano, :ano) and
        tb_editora_idtb_editora like if(:editora='', tb_editora_idtb_editora, :editora) and
        tb_categoria_idtb_categoria like if(:categoria='', tb_categoria_idtb_categoria, :categoria) 
        ";

        $statement = Conexao::getInstance()->prepare($sql);
        
        $statement->bindValue(":idtb_livro", $source->getIdLivro());
        $statement->bindValue(":titulo", $source->getTitulo());
        $statement->bindValue(":edicao", $source->getEdicao());
        $statement->bindValue(":ano", $source->getAno());
        $statement->bindValue(":editora", $source->getEditora());
        $statement->bindValue(":categoria", $source->getCategoria());
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_OBJ);
    }

    public function hasExemplares(livro $source)
    {
        $sql = "SELECT * FROM tb_exemplar where tb_livro_idtb_livro = :idLivro";
        $statement = Conexao::getInstance()->prepare($sql);
        $statement->bindValue(":idLivro", $source->getIdLivro());
        $statement->execute();
        $exemplares = $statement->fetchAll(PDO::FETCH_OBJ);

        return (count($exemplares) > 0);
    }

    public function getFilteredRelatory(string $dataInicio, string $dataFim)
    {
        $sql = "select
        livro.titulo,
        livro.ano,
        editora.nomeEditora as editora,
        exemplar.idtb_exemplar,
        (select count(1) from tb_reserva reserva where reserva.tb_exemplar_idtb_exemplar = exemplar.idtb_exemplar and date_format(reserva.dataReserva, '%d/%m/%Y') between :dataInicio and :dataFim) as reservaExemplar,
        (select count(1) from tb_emprestimo emprestimo where emprestimo.tb_exemplar_idtb_exemplar = exemplar.idtb_exemplar and date_format(emprestimo.dataEmprestimo, '%d/%m/%Y') between :dataInicio and :dataFim) as locacaoExemplar
    from
        tb_livro livro,
        tb_editora editora,
        tb_exemplar exemplar
    where
        editora.idtb_editora = livro.tb_editora_idtb_editora and
        exemplar.tb_livro_idtb_livro = livro.idtb_livro";

        $statement = Conexao::getInstance()->prepare($sql);

        $statement->bindValue(":dataInicio", $dataInicio);
        $statement->bindValue(":dataFim", $dataFim);

        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_OBJ);
    }
}