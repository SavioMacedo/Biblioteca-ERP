<?php

require_once "iPage.php";

class daoEmprestimo implements iPage
{

    public function remover($source)
    {
        try {
            $statement = Conexao::getInstance()->prepare("DELETE FROM tb_emprestimo WHERE tb_usuario_idtb_usuario = :idUsuario AND tb_exemplar_idtb_exemplar = :idExemplar");
            $statement->bindValue(":idUsuario", $source->getUsuario());
            $statement->bindValue(":idExemplar", $source->getExemplar());
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
            $preStatement = Conexao::getInstance()->prepare("SELECT * from tb_emprestimo WHERE tb_usuario_idtb_usuario = :idUsuario AND tb_exemplar_idtb_exemplar = :idExemplar and dataDevolucao = '0000-00-00 00:00:00'");
            
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
                dataDevolucao, tb_usuario_idtb_usuario, tb_exemplar_idtb_exemplar, dataPrevista) VALUES (:observacao,:dataDevolucao, :idUsuario, :idExemplar, :dataPrevista)");
            }

            $exemplarDao = new daoExemplar();
            $exemplar = $exemplarDao->FirstOrDefault($source->getExemplar());

            $usuarioDao = new daoUsuario();
            $usuario = $usuarioDao->FirstOrDefault($source->getUsuario());

            $dataDevolucao = new DateTime();

            switch($exemplar->podeCircular)
            {
                case "S":
                    if($usuario->tipo == "1" || $usuario->tipo == "2")
                        $dataDevolucao->modify('+10 days');
                    else
                        $dataDevolucao->modify('+15 days');
                    break;
                case "N":
                    $dataDevolucao->modify('+1 day');
                default:
                    break;
            }

            $dataDevolucao = $dataDevolucao->date;

            $statement->bindValue(":dataPrevista", $dataDevolucao);
            $statement->bindvalue(":dataDevolucao", $source->getDataDevolucao());
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
            $statement = Conexao::getInstance()->prepare("SELECT tb_usuario_idtb_usuario, tb_exemplar_idtb_exemplar, dataEmprestimo, observacao, dataDevolucao FROM tb_emprestimo WHERE tb_usuario_idtb_usuario = :idUsuario and tb_exemplar_idtb_exemplar = :idExemplar");
            
            $statement->bindValue(":idUsuario", $source->getUsuario());
            $statement->bindValue(":idExemplar", $source->getExemplar());

            if ($statement->execute()) {
                $rs = $statement->fetch(PDO::FETCH_OBJ);
                $source->setDataEmprestimo($rs->dataEmprestimo);
                $source->setObservacao($rs->observacao);
                $source->setDataDevolucao($rs->dataDevolucao);
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
        $sql = "SELECT concat(tb_usuario_idtb_usuario, '-', tb_exemplar_idtb_exemplar) `tb_usuario_idtb_usuario-tb_exemplar_idtb_exemplar`, (select nomeUsuario from tb_usuario where idtb_usuario = tb_usuario_idtb_usuario) nomeUsuario, tb_exemplar_idtb_exemplar, (select titulo from tb_livro, tb_exemplar where idtb_exemplar = tb_exemplar_idtb_exemplar and tb_livro_idtb_livro = idtb_livro) titulo, dataEmprestimo, observacao, dataDevolucao, dataPrevista FROM tb_emprestimo";
        $statement = Conexao::getInstance()->prepare($sql);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_OBJ);
    }

    public function getGraficoData()
    {
        $sql = "select
        date_format(emprestimo.dataEmprestimo, '%b') as Mes,
       count(1) as quantidadeMes
   from
       tb_emprestimo emprestimo
   where
       emprestimo.dataEmprestimo between date_add(sysdate(), INTERVAL -3 MONTH) AND SYSDATE()
   group by date_format(emprestimo.dataEmprestimo, '%b')";

        $statement = Conexao::getInstance()->prepare($sql);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_OBJ);
    }

    public function getGraficoDataCategoria()
    {
        $sql = "select
        categoria.nomeCategoria,
        count(1) as quantidadeMes
    from
        tb_emprestimo emprestimo,
        tb_exemplar exemplar,
        tb_livro livro,
        tb_categoria categoria
    where
        emprestimo.dataEmprestimo between date_add(sysdate(), INTERVAL -3 MONTH) AND SYSDATE() and
        emprestimo.tb_exemplar_idtb_exemplar = exemplar.idtb_exemplar and
        livro.idtb_livro = exemplar.tb_livro_idtb_livro and
        livro.tb_categoria_idtb_categoria = categoria.idtb_categoria
    group by categoria.nomeCategoria";
    
        $statement = Conexao::getInstance()->prepare($sql);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_OBJ);
    }

    public function getGraficoEmprestadoReservado()
    {
        $sql = "select
        date_format(emprestimo.dataEmprestimo, '%b') as Mes,
        count(1) as quantidadeMes
    from
        tb_emprestimo emprestimo,
        tb_reserva reserva,
        tb_exemplar exemplar,
        tb_livro livro,
        tb_categoria categoria
    where
        emprestimo.dataEmprestimo between date_add(sysdate(), INTERVAL -1 MONTH) AND SYSDATE() and
        emprestimo.tb_exemplar_idtb_exemplar = exemplar.idtb_exemplar and
        livro.idtb_livro = exemplar.tb_livro_idtb_livro and
        livro.tb_categoria_idtb_categoria = categoria.idtb_categoria and
        reserva.tb_exemplar_idtb_exemplar = emprestimo.tb_exemplar_idtb_exemplar
    group by categoria.nomeCategoria";
    
        $statement = Conexao::getInstance()->prepare($sql);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_OBJ);
    }

}