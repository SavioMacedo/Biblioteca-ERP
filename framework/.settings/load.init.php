<?php
    $config = array();
    require_once './App_Data/config/config.php';
    foreach (glob('./controllers/*.php') as $filename)
    {
        include_once $filename;
    }
    require_once './.system/configuration/configurationmanager.php';



    $time_start = microtime(true);
    session_start();
?>