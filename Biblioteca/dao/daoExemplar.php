<?php

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
            if ($source->getIdtbUsuario() != "") {
                $statement = Conexao::getInstance()->prepare("UPDATE tb_exemplar 
                                                                 SET tb_livro_idtb_livro = :idLivro
                                                                   , tipoExemplar = :tipo
                                                               WHERE idtb_exemplar = :id;");
                $statement->bindValue(":id", $source->getIdExemplar());
            } else {
                $statement = Conexao::getInstance()->prepare("INSERT 
                                                                INTO tb_exemplar (tb_livro_idtb_livro
                                                                                , tipoExemplar) 
                                                              VALUES (:idLivro
                                                                     , :tipo)");
            }
            $statement->bindValue(":idLivro", $source->getIdLivro());
            $statement->bindValue(":tipo", $source->getTipoLivro());
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
            $statement = Conexao::getInstance()->prepare("SELECT idtb_exemplar
                                                               , tb_livro_idtb_livro
                                                               , tipoExemplar
                                                            FROM tb_exemplar
                                                           WHERE idtb_exemplar = :id");
            $statement->bindValue(":id", $source->getIdtbUsuario());
            if ($statement->execute()) {
                $rs = $statement->fetch(PDO::FETCH_OBJ);
                $source->setIdExemplar($rs->idtb_exemplar);
                $source->setIdLivro($rs->tb_livro_idtb_livro);
                $source->setTipoLivro($rs->tipoExemplar);
                return $source;
            } else {
                throw new PDOException("<script> alert('Não foi possível executar a declaração SQL !'); </script>");
            }
        } catch (PDOException $erro) {
            return "Erro: " . $erro->getMessage();
        }
    }

    public function tabelapaginada()
    {
        //endereço atual da página
        $endereco = $_SERVER ['PHP_SELF'];
        /* Constantes de configuração */
        define('QTDE_REGISTROS', 2);
        define('RANGE_PAGINAS', 3);
        /* Recebe o número da página via parâmetro na URL */
        $pagina_atual = (isset($_GET['page']) && is_numeric($_GET['page'])) ? $_GET['page'] : 1;
        /* Calcula a linha inicial da consulta */
        $linha_inicial = ($pagina_atual - 1) * QTDE_REGISTROS;
        /* Instrução de consulta para paginação com MySQL */
        $sql = "SELECT idtb_exemplar
                     , tb_livro_idtb_livro
                     , tipoExemplar
                  FROM tb_exemplar
                 LIMIT {$linha_inicial}, " . QTDE_REGISTROS;
        $statement = Conexao::getInstance()->prepare($sql);
        $statement->execute();
        $dados = $statement->fetchAll(PDO::FETCH_OBJ);
        /* Conta quantos registos existem na tabela */
        $sqlContador = "SELECT COUNT(*) AS total_registros FROM tb_exemplar";
        $statement = Conexao::getInstance()->prepare($sqlContador);
        $statement->execute();
        $valor = $statement->fetch(PDO::FETCH_OBJ);
        /* Idêntifica a primeira página */
        $primeira_pagina = 1;
        /* Cálcula qual será a última página */
        $ultima_pagina = ceil($valor->total_registros / QTDE_REGISTROS);
        /* Cálcula qual será a página anterior em relação a página atual em exibição */
        $pagina_anterior = ($pagina_atual > 1) ? $pagina_atual - 1 : 0;
        /* Cálcula qual será a pŕoxima página em relação a página atual em exibição */
        $proxima_pagina = ($pagina_atual < $ultima_pagina) ? $pagina_atual + 1 : 0;
        /* Cálcula qual será a página inicial do nosso range */
        $range_inicial = (($pagina_atual - RANGE_PAGINAS) >= 1) ? $pagina_atual - RANGE_PAGINAS : 1;
        /* Cálcula qual será a página final do nosso range */
        $range_final = (($pagina_atual + RANGE_PAGINAS) <= $ultima_pagina) ? $pagina_atual + RANGE_PAGINAS : $ultima_pagina;
        /* Verifica se vai exibir o botão "Primeiro" e "Pŕoximo" */
        $exibir_botao_inicio = ($range_inicial < $pagina_atual) ? 'mostrar' : 'esconder';
        /* Verifica se vai exibir o botão "Anterior" e "Último" */
        $exibir_botao_final = ($range_final > $pagina_atual) ? 'mostrar' : 'esconder';
        if (!empty($dados)):
            echo "
     <table class='table table-striped table-bordered'>
     <thead>
       <tr style='text-transform: uppercase;' class='active'>
        <th style='text-align: center; font-weight: bolder;'>ID</th>
        <th style='text-align: center; font-weight: bolder;'>Livro</th>
        <th style='text-align: center; font-weight: bolder;'>Tipo</th>
        <th style='text-align: center; font-weight: bolder;' colspan='2'>Ações</th>
       </tr>
     </thead>
     <tbody>";
            foreach ($dados as $source):
                echo "<tr>
        <td style='text-align: center'>$source->idtb_exemplar</td>
        <td style='text-align: center'>$source->tb_livro_idtb_livro</td>
        <td style='text-align: center'>$source->tipoExemplar</td>
        <td style='text-align: center'><a href='?act=upd&id=$source->idtb_exemplar' title='Alterar'><i class='ti-reload'></i></a></td>
        <td style='text-align: center'><a href='?act=del&id=$source->idtb_exemplar' title='Remover'><i class='ti-close'></i></a></td>
       </tr>";
            endforeach;
            echo "
</tbody>
    </table>
     <div class='box-paginacao' style='text-align: center'>
       <a class='box-navegacao  $exibir_botao_inicio' href='$endereco?page=$primeira_pagina' title='Primeira Página'> Primeira  |</a>
       <a class='box-navegacao  $exibir_botao_inicio' href='$endereco?page=$pagina_anterior' title='Página Anterior'> Anterior  |</a>
";
            /* Loop para montar a páginação central com os números */
            for ($i = $range_inicial; $i <= $range_final; $i++):
                $destaque = ($i == $pagina_atual) ? 'destaque' : '';
                echo "<a class='box-numero $destaque' href='$endereco?page=$i'> ( $i ) </a>";
            endfor;
            echo "<a class='box-navegacao $exibir_botao_final' href='$endereco?page=$proxima_pagina' title='Próxima Página'>| Próxima  </a>
                  <a class='box-navegacao $exibir_botao_final' href='$endereco?page=$ultima_pagina'  title='Última Página'>| Última  </a>
     </div>";
        else:
            echo "<p class='bg-danger'>Nenhum registro foi encontrado!</p>
     ";
        endif;
    }

}