<?php
    require_once './System/IObject.php';
    require_once './System/Object.php';
    require_once './System/IException.php';
    require_once './System/Exception.php';
    require_once './System/Collections/IEnumerable.php';
    require_once './System/Collections/EnumerableException.php';
    require_once './System/Collections/ISet.php';
    require_once './System/Linq/IGrouping.php';
    require_once './System/Linq/ILookup.php';
    require_once './System/Collections/IList.php';
    require_once './System/Collections/IDictionary.php';
    require_once './System/Collections/EnumerableBase.php';
    require_once './System/Collections/ArrayCollectionBase.php';
    require_once './System/Collections/Set.php';
    require_once './System/Collections/Collection.php';
    require_once './System/Collections/DictionaryEntry.php';
    require_once './System/Collections/Dictionary.php';
    require_once './System/Linq/Enumerable.php';
    require_once './System/Linq/Grouping.php';
    require_once './System/Linq/Lookup.php';
    include_once "./classes/functions.php";

    $time_start = microtime(true);
    session_start();

    $config = array();
    include('./config/config.php');
?>