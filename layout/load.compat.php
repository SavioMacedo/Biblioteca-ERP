<?php

    if(!isset($_REQUEST['page']) || empty($_REQUEST['page']) || is_array($_REQUEST['page']))
    {
        $_REQUEST['page'] = "index";
    }
    else
    {
        $_REQUEST['page'] = (string) $_REQUEST['page'];
    }

    if(Functions::isValidFolderName($_REQUEST['page']))
    {
        if(file_exists("pages/".$_REQUEST['page'].".php"))
        {
            $page = $_REQUEST['page'];
        }
        else
        {
            throw new Exception("Não foi possivel localizar a pagina" . htmlspecialchars($_REQUEST['page']) . '</b>, pagina inexistente.');
        }
    }
    else
        throw new Exception("Não foi possivel localizar a pagina" . htmlspecialchars($_REQUEST['page']) . '</b>, nome da pagina incorreta.');
        
    if(isset($_REQUEST['action']))
        $action = (string) $_REQUEST['action'];
    else
        $action = '';

    $logged = false;
    //$account_logged = new Account();
    $title = ucwords($page) . ' - ' . $config['General']['SiteName'];

    $topic = $page;

?>