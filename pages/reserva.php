<?php

require_once "./dao/daoReserva.php";
require_once "./dao/daoExemplar.php";
require_once "./dao/daoUsuario.php";
require_once "./modelo/reserva.php";
require_once "./db/Conexao.php";

$object = new daoReserva();

$objectExemplar = new daoExemplar();

$objectEmprestimos = $object->getAll();
$objectEmprestimosExemplares = array();

$objectExemplar = $objectExemplar->getExemplaresDisponiveis();

$objectUsuario = new daoUsuario();
$objectUsuario = $objectUsuario->getAll();

$dadosGrid = $object->getAll();

// Verificar se foi enviando dados via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $idExemplar = (isset($_POST["idExemplar"]) && $_POST["idExemplar"] != null) ? $_POST["idExemplar"] : "";
    $idUsuario = (isset($_POST["idUsuario"]) && $_POST["idUsuario"] != null) ? $_POST["idUsuario"] : "";

    $dataEmprestimo = (isset($_POST["dataEmprestimo"]) && $_POST["dataEmprestimo"] != null) ? $_POST["dataEmprestimo"] : "";
    $dataLimite = (isset($_POST["dataLimite"]) && $_POST["dataLimite"] != null) ? $_POST["dataLimite"] : "";
    $observacao = (isset($_POST["observacao"]) && $_POST["observacao"] != null) ? $_POST["observacao"] : "";

} else if (!isset($id)) {

    // Se não se não foi setado nenhum valor para variável $id
    $id = (isset($_GET["id"]) && $_GET["id"] != null) ? $_GET["id"] : "";
    $id = explode('-', $id);
    
    if(count($id) > 1)
    {
        $idExemplar = $id[1];
        $idUsuario = $id[0];
    }
    else
    {
        $idExemplar = "";
        $idUsuario = "";
    }
    
    $dataEmprestimo = null;
    $observacao = null;
    $dataLimite = null;
}

if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "upd" && $idExemplar != "" && $idUsuario != "") {
    $reserva = new reserva($idUsuario, $idExemplar, "", "", "");
    $resultado = $object->atualizar($reserva);
    $idExemplar = $resultado->getExemplar();
    $dataLimite = $resultado->getDataDevolucao();
    $dataEmprestimo = $resultado->getDataEmprestimo();
    $observacao = $resultado->getObservacao();
    $idUsuario = $resultado->getUsuario();

}

if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "save" && $idExemplar != "" && $idExemplar != "" && $observacao != "" ) {
    $reserva = new reserva($idUsuario, $idExemplar, $dataEmprestimo, $dataLimite, $observacao);
    $msg = $object->salvar($reserva);
    $idExemplar = null;
    $dataEmprestimo = null;
    $dataLimite = null;
    $observacao = null;
    $idUsuario = null;

}
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "del" && $idUsuario != "" && $idExemplar != "") {
    $reserva = new reserva($idUsuario, $idExemplar, "", "", "");
    $msg = $object->remover($reserva);
    $idExemplar = null;
    $dataEmprestimo = null;
    $dataLimite = null;
    $observacao = null;
    $idUsuario = null;
}

if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "consulta")
{
    $reserva = new reserva($idUsuario, $idExemplar, "", $dataLimite, "");
    $dadosGrid = $object->getFiltered($reserva);
}

?>

    <div class='content' xmlns="http://www.w3.org/1999/html">
        <div class='container-fluid'>
            <div class='row'>
                <div class='col-md-12'>
                    <div class='card'>
                        <div class='header'>
                            <h4 class='title'>Reserva</h4>
                            <p class='category'>Lista de Reservas do Sistema</p>

                        </div>
                        <div class='content table-responsive'>
                            <form action="?page=reserva&act=save&id=" method="POST" id="form1" name="form1">

                                <div class='form-group'>
                                    <label for='acao'>Ação</label>
                                    <select class='form-control' name='acao' id='acao' onchange="formFunction(this)">
                                        <option value="save">Cadastrar</option>
                                        <option value="consulta">Consulta</option>
                                    </select>
                                </div>

                                <input type="hidden" name="dataEmprestimo" value="<?php
                                // Preenche o id no campo id com um valor "value"
                                echo (isset($dataEmprestimo) && ($dataEmprestimo != null || $dataEmprestimo != "")) ? $dataEmprestimo : time();
                                ?>"/>

                                <?php
                                    $selectedValue = (isset($idExemplar) && ($idExemplar != null || $idExemplar != "")) ? $idExemplar : '';
                                    Functions::DropDownFor($objectExemplar, "idExemplar", "idtb_exemplar", "titulo", "Exemplar do Livro", $selectedValue);
                                ?>

                                <?php
                                    $selectedValue = (isset($idUsuario) && ($idUsuario != null || $idUsuario != "")) ? $idUsuario : '';
                                    Functions::DropDownFor($objectUsuario, "idUsuario", "idtb_usuario", "nomeUsuario", "Usuario", $selectedValue);
                                ?>

                                <Label>Data Limite da Reserva</Label>
                                <input class="form-control" type="date" name="dataLimite" value="<?php
                                // Preenche o nome no campo nome com um valor "value"
                                echo (isset($dataLimite) && ($dataLimite != null || $dataLimite != "")) ? $dataLimite : '';
                                ?>"/>
                                <br/>

                                <Label>Observação</Label>
                                <input class="form-control" type="text" size="50" name="observacao" value="<?php
                                // Preenche o nome no campo nome com um valor "value"
                                echo (isset($observacao) && ($observacao != null || $observacao != "")) ? $observacao : '';
                                ?>"/>
                                <br/>
                                <input class="btn btn-success" type="submit" id="submitForm" value="REGISTRAR">
                                <hr>
                            </form>
                            <?php
                                echo (isset($msg) && ($msg != null || $msg != "")) ? $msg : '';
                                $parameter = [
                                    [null,"tb_usuario_idtb_usuario-tb_exemplar_idtb_exemplar"],
                                    ["Usuario","nomeUsuario"],
                                    ["Exemplar", "titulo"],
                                    ["Data de Reserva", "dataReserva"],
                                    ["Data Limite de Reserva", "dataLimite"],
                                    ["Observação", "observacao"]
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