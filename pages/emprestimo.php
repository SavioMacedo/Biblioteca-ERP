<?php

require_once "./dao/daoEmprestimo.php";
require_once "./dao/daoExemplar.php";
require_once "./dao/daoUsuario.php";
require_once "./modelo/emprestimo.php";
require_once "./db/Conexao.php";

$object = new daoEmprestimo();

$objectExemplar = new daoExemplar();
$objectExemplar = $objectExemplar->getAll();

$objectEmprestimos = $object->getAll();
$objectEmprestimosExemplares = array();

foreach($objectEmprestimos as $emprestimoTemp)
{
    if($emprestimoTemp->dataDevolucao == "0000-00-00 00:00:00")
        array_push($objectEmprestimosExemplares, $emprestimoTemp->tb_exemplar_idtb_exemplar);
}

foreach($objectEmprestimosExemplares as $idExemplar)
{
    for($i = 0; $i < count($objectExemplar); $i++)
    {
        if($objectExemplar[$i]->idtb_exemplar == $idExemplar)
            unset($objectExemplar[$i]);
    }

    $objectExemplar = array_values($objectExemplar);
}

$objectUsuario = new daoUsuario();
$dadosUsuario = $objectUsuario->getAll();

// Verificar se foi enviando dados via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $idExemplar = (isset($_POST["idExemplar"]) && $_POST["idExemplar"] != null) ? $_POST["idExemplar"] : "";
    $idUsuario = (isset($_POST["idUsuario"]) && $_POST["idUsuario"] != null) ? $_POST["idUsuario"] : "";

    $dataEmprestimo = (isset($_POST["dataEmprestimo"]) && $_POST["dataEmprestimo"] != null) ? $_POST["dataEmprestimo"] : "";
    $dataDevolucao = (isset($_POST["dataDevolucao"]) && $_POST["dataDevolucao"] != null) ? $_POST["dataDevolucao"] : "";
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
    $dataDevolucao = null;
}

if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "upd" && $idExemplar != "" && $idUsuario != "") {
    $emprestimo = new emprestimo($idUsuario, $idExemplar, "", "", "", "");
    $resultado = $object->atualizar($emprestimo);
    $idExemplar = $resultado->getExemplar();
    $dataDevolucao = $resultado->getDataDevolucao();
    $dataEmprestimo = $resultado->getDataEmprestimo();
    $observacao = $resultado->getObservacao();
    $idUsuario = $resultado->getUsuario();
    $objectExemplar = new daoExemplar();
    $objectExemplar = $objectExemplar->getAll();

}

if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "save" && $idExemplar != "" && $idExemplar != "" && $observacao != "" ) {
    $emprestimo = new emprestimo($idUsuario, $idExemplar, "", "", "", $observacao);

    $valida = $objectUsuario->isHabilitado($idUsuario);

    if($valida)
    {
        $msg = $object->salvar($emprestimo);
        $idExemplar = null;
        $dataEmprestimo = null;
        $dataDevolucao = null;
        $observacao = null;
        $idUsuario = null;
    }
    else
        echo "<script> alert('O Usuario possui o limite máximo de empréstimos ativos:\n -> Alunos e Funcionários: 3 abertos. \n -> Professores: 5 abertos. !'); </script>";

}
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "del" && $idUsuario != "" && $idExemplar != "") {
    $emprestimo = new emprestimo($idUsuario, $idExemplar, "", "", "", "");
    $msg = $object->remover($emprestimo);
    $idExemplar = null;
    $dataEmprestimo = null;
    $dataDevolucao = null;
    $observacao = null;
    $idUsuario = null;
}
?>

    <div class='content' xmlns="http://www.w3.org/1999/html">
        <div class='container-fluid'>
            <div class='row'>
                <div class='col-md-12'>
                    <div class='card'>
                        <div class='header'>
                            <h4 class='title'>Emprestimo</h4>
                            <p class='category'>Lista de Emprestimos do Sistema</p>

                        </div>
                        <div class='content table-responsive'>
                            <form action="?page=emprestimo&act=save&id=" method="POST" name="form1">
                                <input type="hidden" name="dataEmprestimo" value="<?php
                                // Preenche o id no campo id com um valor "value"
                                echo (isset($dataEmprestimo) && ($dataEmprestimo != null || $dataEmprestimo != "")) ? $dataEmprestimo : '';
                                ?>"/>
                                <?php
                                    $selectedValue = (isset($idExemplar) && ($idExemplar != null || $idExemplar != "")) ? $idExemplar : '';
                                    Functions::DropDownFor($objectExemplar, "idExemplar", "idtb_exemplar", "titulo", "Exemplar do Livro", $selectedValue);
                                ?>

                                <?php
                                    $selectedValue = (isset($idUsuario) && ($idUsuario != null || $idUsuario != "")) ? $idUsuario : '';
                                    Functions::DropDownFor($dadosUsuario, "idUsuario", "idtb_usuario", "nomeUsuario", "Usuario", $selectedValue);
                                ?>

                                <Label>Data do produto devolvido</Label>
                                <input class="form-control" type="date" size="50" name="dataDevolucao" value="<?php
                                // Preenche o nome no campo nome com um valor "value"
                                echo (isset($dataDevolucao) && ($dataDevolucao != null || $dataDevolucao != "")) ? $dataDevolucao : '';
                                ?>"/>
                                <br/>

                                <Label>Observação</Label>
                                <input class="form-control" type="text" size="50" name="observacao" value="<?php
                                // Preenche o nome no campo nome com um valor "value"
                                echo (isset($observacao) && ($observacao != null || $observacao != "")) ? $observacao : '';
                                ?>" required/>
                                <br/>

                                <input class="btn btn-success" type="submit" value="REGISTRAR">
                                <hr>
                            </form>
                            <?php
                                echo (isset($msg) && ($msg != null || $msg != "")) ? $msg : '';
                                $parameter = [
                                    [null,"tb_usuario_idtb_usuario-tb_exemplar_idtb_exemplar"],
                                    ["Usuario","nomeUsuario"],
                                    ["Exemplar", "titulo"],
                                    ["Data de Emprestimo", "dataEmprestimo"],
                                    ["Data Prevista Devolução", "dataPrevista"],
                                    ["Data Devolvido", "dataDevolucao"],
                                    ["Observação", "observacao"]
                                ];
                                //chamada a paginação
                                Functions::constructGrid($object->getAll(), $parameter, $page);
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>