<?php

require_once "./dao/daoUsuario.php";
require_once "./dao/daoTipoUsuario.php";
require_once "./modelo/usuario.php";
require_once "./db/Conexao.php";

$object = new daoUsuario();
$objectTipoUsuario = new daoTipoUsuario();
$objectTipoUsuario = $objectTipoUsuario->getAll();

// Verificar se foi enviando dados via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = (isset($_POST["id"]) && $_POST["id"] != null) ? $_POST["id"] : "";
    $nome = (isset($_POST["nome"]) && $_POST["nome"] != null) ? $_POST["nome"] : "";
    $tipo = (isset($_POST["tipo"]) && $_POST["tipo"] != null) ? $_POST["tipo"] : "";
    $email = (isset($_POST["email"]) && $_POST["email"] != null) ? $_POST["email"] : "";
    $senha = (isset($_POST["senha"]) && $_POST["senha"] != null) ? $_POST["senha"] : "";
} else if (!isset($id)) {
    // Se não se não foi setado nenhum valor para variável $id
    $id = (isset($_GET["id"]) && $_GET["id"] != null) ? $_GET["id"] : "";
    $nome = null;
    $tipo = null;
    $email = null;
    $senha = null;
}

if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "upd" && $id != "") {
    $usuario = new usuario($id, "", "", "", "");
    $resultado = $object->atualizar($usuario);
    $id = $resultado->getIdtbUsuario();
    $nome = $resultado->getNomeUsuario();
    $tipo = $resultado->getTipo();
    $email = $resultado->getEmail();
    $senha = $resultado->getSenha();

}

if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "save" && $nome != "" ) {
    $usuario = new usuario($id, $nome, $tipo, $email, $senha);
    $msg = $object->salvar($usuario);
    $id = null;
    $nome = null;
    $tipo = null;
    $email = null;
    $senha = null;

}
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "del" && $id != "") {
    $usuario = new usuario($id, "", "", "", "");
    $msg = $object->remover($usuario);
    $id = null;
    $nome = null;
    $tipo = null;
    $email = null;
    $senha = null;
}
?>

    <div class='content' xmlns="http://www.w3.org/1999/html">
        <div class='container-fluid'>
            <div class='row'>
                <div class='col-md-12'>
                    <div class='card'>
                        <div class='header'>
                            <h4 class='title'>Usuario</h4>
                            <p class='category'>Lista de usuarios do Sistema</p>

                        </div>
                        <div class='content table-responsive'>
                            <form action="?page=usuarios&act=save&id=" method="POST" name="form1">

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
                                <?php
                                $selectedValue = (isset($tipo) && ($tipo != null || $tipo != "")) ? $tipo : '';
                                Functions::DropDownFor($objectTipoUsuario, "tipo", "idtb_tipo_usuario", "dc_tipo", "Tipo", $selectedValue);
                                ?>
                                <br/>
                                <Label>Email</Label>
                                <input class="form-control" type="text" size="50" name="email" value="<?php
                                // Preenche o nome no campo nome com um valor "value"
                                echo (isset($email) && ($email != null || $email != "")) ? $email : '';
                                ?>" required/>
                                <br/>
                                <Label>Senha</Label>
                                <input class="form-control" type="password" size="50" name="senha" value="<?php
                                // Preenche o nome no campo nome com um valor "value"
                                echo (isset($senha) && ($senha != null || $senha != "")) ? $senha : '';
                                ?>" required/>
                                <br/>

                                <input class="btn btn-success" type="submit" value="REGISTRAR">
                                <hr>
                            </form>
                            <?php
                                echo (isset($msg) && ($msg != null || $msg != "")) ? $msg : '';
                                
                                $parameter = [
                                    ["ID","idtb_usuario"],
                                    ["Nome", "nomeUsuario"],
                                    ["Tipo", "tipo"],
                                    ["Email", "email"],
                                    ["Senha", "senha"]
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