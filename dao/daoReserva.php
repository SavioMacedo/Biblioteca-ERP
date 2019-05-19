<?php

require_once "iPage.php";

class daoReserva implements iPage
{

    public function remover($source)
    {
        try {
            $statement = Conexao::getInstance()->prepare("DELETE FROM tb_reserva WHERE tb_usuario_idtb_usuario = :idUsuario AND tb_exemplar_idtb_exemplar = :idExemplar");
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
            $preStatement = Conexao::getInstance()->prepare("SELECT * from tb_reserva WHERE tb_usuario_idtb_usuario = :idUsuario AND tb_exemplar_idtb_exemplar = :idExemplar and date_format(dataReserva, '%d/%m/%Y') = :dataReserva");
            
            $dataReserva = date('d/m/Y', $source->getDataEmprestimo());

            $preStatement->bindValue(":idUsuario", $source->getUsuario());
            $preStatement->bindValue(":idExemplar", $source->getExemplar());
            $preStatement->bindValue(":dataReserva", $dataReserva);

            if ($preStatement->execute()) {
                if ($preStatement->rowCount() > 0) {
                      $existe = true;
                }
            } else {
                throw new PDOException("<script> alert('Não foi possível executar a declaração pré SQL !'); </script>");
            }

            if ($existe) {
                $statement = Conexao::getInstance()->prepare("UPDATE tb_reserva
                                                                           SET observacao = :observacao,
                                                                           dataLimite = :dataLimite
                                                                         WHERE tb_usuario_idtb_usuario = :idUsuario
                                                                           AND tb_exemplar_idtb_exemplar = :idExemplar
                                                                           and dataReserva = :dataReserva");
                $statement->bindValue(":dataReserva", $source->getDataEmprestimo());
            } else {
                $statement = Conexao::getInstance()->prepare("INSERT INTO tb_reserva (observacao,
                dataLimite, tb_usuario_idtb_usuario, tb_exemplar_idtb_exemplar) VALUES (:observacao,:dataLimite, :idUsuario, :idExemplar)");
            }

            $statement->bindValue(":dataLimite", $source->getDataLimite());
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
            $statement = Conexao::getInstance()->prepare("SELECT tb_usuario_idtb_usuario, tb_exemplar_idtb_exemplar, dataReserva, observacao, dataLimite FROM tb_reserva WHERE tb_usuario_idtb_usuario = :idUsuario and tb_exemplar_idtb_exemplar = :idExemplar");
            
            $statement->bindValue(":idUsuario", $source->getUsuario());
            $statement->bindValue(":idExemplar", $source->getExemplar());

            if ($statement->execute()) {
                $rs = $statement->fetch(PDO::FETCH_OBJ);
                $source->setDataReserva($rs->dataReserva);
                $source->setObservacao($rs->observacao);
                $source->setDataLimite($rs->dataLimite);
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
        $sql = "SELECT concat(tb_usuario_idtb_usuario, '-', tb_exemplar_idtb_exemplar) `tb_usuario_idtb_usuario-tb_exemplar_idtb_exemplar`, (select nomeUsuario from tb_usuario where idtb_usuario = tb_usuario_idtb_usuario) nomeUsuario, tb_exemplar_idtb_exemplar, (select titulo from tb_livro, tb_exemplar where idtb_exemplar = tb_exemplar_idtb_exemplar and tb_livro_idtb_livro = idtb_livro) titulo, dataReserva, observacao, dataLimite FROM tb_reserva where
        dataLimite >= sysdate()";
        $statement = Conexao::getInstance()->prepare($sql);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_OBJ);
    }

    public function getFiltered($source)
    {
        $sql = "SELECT concat(tb_usuario_idtb_usuario, '-', tb_exemplar_idtb_exemplar) `tb_usuario_idtb_usuario-tb_exemplar_idtb_exemplar`, (select nomeUsuario from tb_usuario where idtb_usuario = tb_usuario_idtb_usuario) nomeUsuario, tb_exemplar_idtb_exemplar, (select titulo from tb_livro, tb_exemplar where idtb_exemplar = tb_exemplar_idtb_exemplar and tb_livro_idtb_livro = idtb_livro) titulo, dataReserva, observacao, dataLimite FROM tb_reserva where
        tb_usuario_idtb_usuario = IF(:tb_usuario_idtb_usuario='', tb_usuario_idtb_usuario, :tb_usuario_idtb_usuario) and
        tb_exemplar_idtb_exemplar = IF(:tb_exemplar_idtb_exemplar='', tb_exemplar_idtb_exemplar, :tb_exemplar_idtb_exemplar) and
        dataLimite <= IF(:dataLimite='', dataLimite, :dataLimite)
        ";
        
        $statement = Conexao::getInstance()->prepare($sql);
        $statement->bindValue(":tb_usuario_idtb_usuario", $source->getUsuario());
        $statement->bindValue(":tb_exemplar_idtb_exemplar", $source->getExemplar());
        $statement->bindValue(":dataLimite", $source->getDataLimite());
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_OBJ);
    }

    public function getGraficoData()
    {
        $sql = "select
        date_format(reserva.dataReserva, '%b') as Mes,
       count(1) as quantidadeMes
   from
       tb_reserva reserva
   where
       reserva.dataReserva between date_add(sysdate(), INTERVAL -3 MONTH) AND SYSDATE()
   group by date_format(reserva.dataReserva, '%b')";

        
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
        tb_reserva reserva,
        tb_exemplar exemplar,
        tb_livro livro,
        tb_categoria categoria
    where
        reserva.dataReserva between date_add(sysdate(), INTERVAL -3 MONTH) AND SYSDATE() and
        reserva.tb_exemplar_idtb_exemplar = exemplar.idtb_exemplar and
        livro.idtb_livro = exemplar.tb_livro_idtb_livro and
        livro.tb_categoria_idtb_categoria = categoria.idtb_categoria
    group by categoria.nomeCategoria";
    
        $statement = Conexao::getInstance()->prepare($sql);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_OBJ);
    }

}