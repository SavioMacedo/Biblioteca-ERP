<?php

require_once "./dao/daoLivro.php";
require_once "./dao/daoEditora.php";
require_once "./dao/daoCategoria.php";
require_once "./modelo/livro.php";
require_once "./db/Conexao.php";

$object = new daoLivro();

$objectEditora = new daoEditora();
$objectEditora = $objectEditora->getAll();

$objectCategoria = new daoCategoria();
$objectCategoria = $objectCategoria->getAll();
//$livroEnumerable = System\Linq\Enumerable::createEnumerable($objectLivro);

// Verificar se foi enviando dados via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    $id = (isset($_POST["id"]) && $_POST["id"] != null) ? $_POST["id"] : "";
    $titulo = (isset($_POST["titulo"]) && $_POST["titulo"] != null) ? $_POST["titulo"] : "";
    $isbn = (isset($_POST["isbn"]) && $_POST["isbn"] != null) ? $_POST["isbn"] : "";
    $edicao = (isset($_POST["edicao"]) && $_POST["edicao"] != null) ? $_POST["edicao"] : "";
    $ano = (isset($_POST["ano"]) && $_POST["ano"] != null) ? $_POST["ano"] : "";
    $upload = (isset($_POST["upload"]) && $_POST["upload"] != null) ? $_POST["upload"] : "";
    $editora = (isset($_POST["tb_editora_idtb_editora"]) && $_POST["tb_editora_idtb_editora"] != null) ? $_POST["tb_editora_idtb_editora"] : "";
    $categoria = (isset($_POST["tb_categoria_idtb_categoria"]) && $_POST["tb_categoria_idtb_categoria"] != null) ? $_POST["tb_categoria_idtb_categoria"] : "";
} 
else if (!isset($id)) 
{
    // Se não se não foi setado nenhum valor para variável $id
    $id = (isset($_GET["id"]) && $_GET["id"] != null) ? $_GET["id"] : "";
    $titulo = null;
    $isbn = null;
    $edicao = null;
    $ano = null;
    $upload = null;
    $editora = null;
    $categoria = null;
}

if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "upd" && $id != "") 
{
    $livro = new livro($id, "", "", "", "", "", "", "");
    $resultado = $object->atualizar($livro);
    $id = $resultado->getIdLivro();
    $titulo = $resultado->getTitulo();
    $isbn = $resultado->getIsbn();
    $edicao = $resultado->getEdicao();
    $ano = $resultado->getAno();
    $upload = $resultado->getUpload();
    $editora = $resultado->getEditora();
    $categoria = $resultado->getCategoria();
}

if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "save" && $titulo != "" && $isbn != "" && $edicao != "" && $ano != "" && $upload != "" && $editora != "" && $categoria != "" ) 
{
    $livro = new livro($id, $titulo, $isbn, $edicao, $ano, $upload, $editora, $categoria);
    $msg = $object->salvar($livro);
    $id = null;
    $titulo = null;
    $isbn = null;
    $edicao = null;
    $ano = null;
    $upload = null;
    $editora = null;
    $categoria = null;

}
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "del" && $id != "") {
    $livro = new livro($id, "", "", "", "", "", "", "");
    $msg = $object->remover($livro);
    $titulo = null;
    $isbn = null;
    $edicao = null;
    $ano = null;
    $upload = null;
    $editora = null;
    $categoria = null;
}
?>

    <div class='content' xmlns="http://www.w3.org/1999/html">
        <div class='container-fluid'>
            <div class='row'>
                <div class='col-md-12'>
                    <div class='card'>
                        <div class='header'>
                            <h4 class='title'>Livro</h4>
                            <p class='category'>Lista de Livros do Sistema</p>

                        </div>
                        <div class='content table-responsive'>
                            <form action="?page=livro&act=save&id=" method="POST" name="form1">

                                <input type="hidden" name="id" value="<?php
                                // Preenche o id no campo id com um valor "value"
                                echo (isset($id) && ($id != null || $id != "")) ? $id : '';
                                ?>"/>
                                <Label>Titulo</Label>
                                <input class="form-control" type="text" size="50" name="titulo" value="<?php
                                // Preenche o nome no campo nome com um valor "value"
                                echo (isset($titulo) && ($titulo != null || $titulo != "")) ? $titulo : '';
                                ?>" required/>
                                <br/>
                                <Label>ISBN</Label>
                                <input class="form-control" type="text" size="50" name="isbn" value="<?php
                                // Preenche o nome no campo nome com um valor "value"
                                echo (isset($isbn) && ($isbn != null || $isbn != "")) ? $isbn : '';
                                ?>" required/>
                                <br/>
                                <Label>Edição</Label>
                                <input class="form-control" type="text" size="50" name="edicao" value="<?php
                                // Preenche o nome no campo nome com um valor "value"
                                echo (isset($edicao) && ($edicao != null || $edicao != "")) ? $edicao : '';
                                ?>" required/>
                                <br/>
                                <Label>Ano</Label>
                                <input class="form-control" type="text" size="50" name="ano" value="<?php
                                // Preenche o nome no campo nome com um valor "value"
                                echo (isset($ano) && ($ano != null || $ano != "")) ? $ano : '';
                                ?>" required/>
                                <br/>
                                <Label>Upload</Label>
                                <input class="form-control" type="text" size="50" name="upload" value="<?php
                                // Preenche o nome no campo nome com um valor "value"
                                echo (isset($upload) && ($upload != null || $upload != "")) ? $upload : '';
                                ?>" required/>
                                <br/>
                                <?php
                                $selectedValue = (isset($editora) && ($editora != null || $editora != "")) ? $editora : '';
                                Functions::DropDownFor($objectEditora, "tb_editora_idtb_editora", "idtb_editora", "nomeEditora", "Editora", $selectedValue);
                                ?>
                                <br/>
                                <?php
                                $selectedValue = (isset($categoria) && ($categoria != null || $categoria != "")) ? $categoria : '';
                                Functions::DropDownFor($objectCategoria, "tb_categoria_idtb_categoria", "idtb_categoria", "nomeCategoria", "Categoria", $selectedValue);
                                ?>
                                <br/>
                                <input class="btn btn-success" type="submit" value="REGISTRAR">
                                <hr>
                            </form>
                            <?php
                                echo (isset($msg) && ($msg != null || $msg != "")) ? $msg : '';
                                //chamada a paginação
                                $parameter = [
                                    ["ID","idtb_livro"],
                                    ["Titulo", "titulo"],
                                    ["ISBN", "isbn"],
                                    ["Ano de Publicação", "ano"],
                                    ["Editora do Livro", "editora"],
                                    ["Categoria", "categoria"]
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