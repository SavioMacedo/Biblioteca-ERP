<?php

require_once './dao/daoUsuario.php';
require_once "./db/Conexao.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    // as variáveis login e senha recebem os dados digitados na página anterior
    $login = $_POST['login'];
    $senha = $_POST['senha'];

    $usuario = new daoUsuario();

    $valido = $usuario->logar($login, $senha);

    if( $valido instanceof Usuario)
    {
        $_SESSION['Account'] = $valido; 
        header('location:index.php');
    }
    else {
        unset ($_SESSION['Account']);
        $_SESSION['ViewErrors'] = $valido;
        header('location:index.php');
    }
}

?>

<div class='content' xmlns="http://www.w3.org/1999/html">
        <div class='container-fluid'>
            <div class='row'>
                <div class='col-md-12'>
                    <div class='card'>
                        <div class='header'>
                            <h4 class='title'>Autenticação</h4>

                        </div>
                        <div class='content table-responsive'>
                        <?php if (isset($_SESSION['ViewErrors']))
                        {
                            $erro = $_SESSION['ViewErrors'];
                            echo "<div class='alert alert-danger' role='alert'>
                                    $erro
                                </div>";
                        }
                        ?>
                        <form method="post" id="formlogin" name="formlogin" >
                            <fieldset id="fie">
                                <legend>LOGIN</legend><br />
                                <label>Email : </label>
                                <input class="form-control" type="text" name="login" id="login"  /><br />
                                <label>SENHA :</label>
                                <input class="form-control" type="password" name="senha" id="senha" /><br />
                                <input type="submit" value="LOGAR" />
                            </fieldset>
                        </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>