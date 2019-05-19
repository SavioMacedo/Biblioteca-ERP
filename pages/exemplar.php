<?php

require_once "./dao/daoExemplar.php";
require_once "./dao/daoLivro.php";
require_once "./modelo/exemplar.php";
require_once "./db/Conexao.php";

$object = new daoExemplar();
$objectLivro = new daoLivro();
$objectLivro = $objectLivro->getAll();

$objectCircular = [
    [
        "key" => "N",
        "value" => "Não"
    ],
    [
        "key" => "S",
        "value" => "Sim"
    ]
];

// Verificar se foi enviando dados via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = (isset($_POST["id"]) && $_POST["id"] != null) ? $_POST["id"] : "";
    $livro = (isset($_POST["livro"]) && $_POST["livro"] != null) ? $_POST["livro"] : "";
    $podeCircular = (isset($_POST["podeCircular"]) && $_POST["podeCircular"] != null) ? $_POST["podeCircular"] : "";
} else if (!isset($id)) {
    // Se não se não foi setado nenhum valor para variável $id
    $id = (isset($_GET["id"]) && $_GET["id"] != null) ? $_GET["id"] : "";
    $livro = null;
    $podeCircular = null;
}

if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "upd" && $id != "") {
    $exemplar = new exemplar($id, "", "");
    $resultado = $object->atualizar($exemplar);
    $id = $resultado->getIdExemplar();
    $livro = $resultado->getLivro();
    $podeCircular = $resultado->podeCircular();
}

if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "save" && $livro != "" && $podeCircular != "" ) {
    $exemplar = new exemplar($id, $livro, $podeCircular);
    $msg = $object->salvar($exemplar);
    $id = null;
    $livro = null;
    $podeCircular = null;

}
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "del" && $id != "") {
    $exemplar = new exemplar($id, "", "");

        
    $valida = $object->hasEmprestimo($exemplar);

    if(!$valida)
    {
        $msg = $object->remover($exemplar);
        $id = null;
        $podeCircular = null;
    }
    else
        echo "<script> alert('Não é possivel excluir um exemplar que possua registros de empréstimos !'); </script>";
}
?>

    <div class='content' xmlns="http://www.w3.org/1999/html">
        <div class='container-fluid'>
            <div class='row'>
                <div class='col-md-12'>
                    <div class='card'>
                        <div class='header'>
                            <h4 class='title'>Exemplar</h4>
                            <p class='category'>Lista de Exemplares do Sistema</p>

                        </div>
                        <div class='content table-responsive'>
                            <form action="?page=exemplar&act=save&id=" method="POST" name="form1">

                                <input type="hidden" name="id" value="<?php
                                // Preenche o id no campo id com um valor "value"
                                echo (isset($id) && ($id != null || $id != "")) ? $id : '';
                                ?>"/>
                                <?php
                                $selectedValue = (isset($livro) && ($livro != null || $livro != "")) ? $livro : '';
                                Functions::DropDownFor($objectLivro, "livro", "idtb_livro", "titulo", "Livro", $selectedValue);
                                ?>
                                <br/>

                                <?php
                                $selectedValue = (isset($podeCircular) && ($podeCircular != null || $podeCircular != "")) ? $podeCircular : '';
                                Functions::DropDownFor($objectCircular, "podeCircular", "key", "value", "Pode Circular", $selectedValue);
                                ?>
                                <br/>
                                <input class="btn btn-success" type="submit" value="REGISTRAR">
                                <hr>
                            </form>
                            <?php
                                echo (isset($msg) && ($msg != null || $msg != "")) ? $msg : '';
                                //chamada a paginação
                                $parameter = [
                                    ["ID","idtb_exemplar"],
                                    ["Titulo do Livro", "titulo"],
                                    ["Pode Circular", "podeCircular"]
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