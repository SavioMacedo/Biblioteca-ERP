<?php
    use System\Configuration;

    $path = $_SERVER['REQUEST_URI']; 
    $path = rtrim($path, '/'); 
    $path = filter_var($path, FILTER_SANITIZE_URL);

    $siteFolder = $config['General']['SitePath'];

    $path = str_replace($siteFolder."/", "", $path);
    $path = explode('/', $path);

    $controller = $path[1]."Controller"; 
    unset($path[1]);
    $action = $path[2];
    unset($path[2]);
    unset($path[0]);
    $parameters = array();

    if(count($path) == 1)
    {
        $parameters = ["id" => $path[3]];
    }
    else if (count($path) > 1)
    {
        foreach($path as $key => $value)
        {
            if($key % 2 != 0)
                array_push($parameters, [$value => $path[$key+1]]);
        }
    }

    $controllerObject = new $controller;
    $controllerObject->eval($action);
    


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