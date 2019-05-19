<?php

require_once "./dao/daoCategoria.php";
require_once "./modelo/categoria.php";
require_once "./db/Conexao.php";

$object = new daoCategoria();

// Verificar se foi enviando dados via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = (isset($_POST["id"]) && $_POST["id"] != null) ? $_POST["id"] : "";
    $nome = (isset($_POST["nome"]) && $_POST["nome"] != null) ? $_POST["nome"] : "";
} else if (!isset($id)) {
    // Se não se não foi setado nenhum valor para variável $id
    $id = (isset($_GET["id"]) && $_GET["id"] != null) ? $_GET["id"] : "";
    $nome = null;
}

if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "upd" && $id != "") {
    $categoria = new categoria($id, "");
    $resultado = $object->atualizar($categoria);
    $id = $resultado->getIdCategoria();
    $nome = $resultado->getNomeCategoria();

}

if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "save" && $nome != "" ) {
    $categoria = new categoria($id, $nome);
    $msg = $object->salvar($categoria);
    $id = null;
    $nome = null;

}
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "del" && $id != "") {
    $categoria = new categoria($id, "");
        
    $valida = $object->hasLivros($categoria);

    if(!$valida)
    {
        $msg = $object->remover($categoria);
        $id = null;
    }
    else
        echo "<script> alert('Não é possivel excluir uma categoria que possua livros associados a ela!'); </script>";
}
?>

    <div class='content' xmlns="http://www.w3.org/1999/html">
        <div class='container-fluid'>
            <div class='row'>
                <div class='col-md-12'>
                    <div class='card'>
                        <div class='header'>
                            <h4 class='title'>Categoria</h4>
                            <p class='category'>Lista de Categorias do Sistema</p>

                        </div>
                        <div class='content table-responsive'>
                            <form action="?page=categoria&act=save&id=" method="POST" name="form1">

                                <input type="hidden" name="id" value="<?php
                                // Preenche o id no campo id com um valor "value"
                                echo (isset($id) && ($id != null || $id != "")) ? $id : '';
                                ?>"/>
                                <Label>Nome</Label>
                                <input class="form-control" type="text" size="50" name="nome" value="<?php
                                // Preenche o nome no campo nome com um valor "value"
                                echo (isset($nome) && ($nome != null || $nome != "")) ? $nome : '';
                                ?>" required/>
                                <br/>
                                <input class="btn btn-success" type="submit" value="REGISTRAR">
                                <hr>
                            </form>
                            <?php
                                echo (isset($msg) && ($msg != null || $msg != "")) ? $msg : '';
                                //chamada a paginação
                                $parameter = [
                                    ["ID","idtb_categoria"],
                                    ["Nome", "nomeCategoria"]
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