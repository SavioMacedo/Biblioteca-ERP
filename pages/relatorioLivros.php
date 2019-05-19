<?php

require_once "./dao/daoLivro.php";
require_once "./modelo/livro.php";
require_once "./db/Conexao.php";

$object = new daoLivro();
$dadosGrid = array();

// Verificar se foi enviando dados via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    $dataInicio = (isset($_POST["dataInicio"]) && $_POST["dataInicio"] != null) ? $_POST["dataInicio"] : "";
    $dataFim = (isset($_POST["dataFim"]) && $_POST["dataFim"] != null) ? $_POST["dataFim"] : "";
}

if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "consulta")
{
    $dadosGrid = $object->getFilteredRelatory($dataInicio, $dataFim);
}

?>

    <div class='content' xmlns="http://www.w3.org/1999/html">
        <div class='container-fluid'>
            <div class='row'>
                <div class='col-md-12'>
                    <div class='card'>
                        <div class='header'>
                            <h4 class='title'>Relatório de locação/Reserva de Livros</h4>
                            <p class='category'>Buscar período</p>
                        </div>
                        <div class='content table-responsive'>
                            <form action="?page=relatorioLivros&act=consulta&id=" method="POST" name="form1" id="form1">

                                <Label>De</Label>
                                <input class="form-control" type="date" size="50" name="dataInicio" value="<?php
                                // Preenche o nome no campo nome com um valor "value"
                                echo (isset($dataInicio) && ($dataInicio != null || $dataInicio != "")) ? $dataInicio : '';
                                ?>" />
                                <br/>

                                <Label>até</Label>
                                <input class="form-control" type="date" size="50" name="dataFim" value="<?php
                                // Preenche o nome no campo nome com um valor "value"
                                echo (isset($dataFim) && ($dataFim != null || $dataFim != "")) ? $dataFim : '';
                                ?>" />
                                <br/>


                                <input class="btn btn-success" type="submit" id="submitForm" value="Consultar">
                                <hr>
                            </form>
                            <?php
                                echo (isset($msg) && ($msg != null || $msg != "")) ? $msg : '';
                                //chamada a paginação
                                $parameter = [
                                    ["Titulo", "titulo"],
                                    ["Exemplar", "idtb_exemplar"],
                                    ["Ano de Publicação", "ano"],
                                    ["Editora do Livro", "editora"],
                                    ["Locação Exemplar", "locacaoExemplar"],
                                    ["Reserva Exemplar", "reservaExemplar"]
                                ];
                                //chamada a paginação
                                Functions::constructGrid($dadosGrid, $parameter, $page);
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>