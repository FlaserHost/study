<?php
    require_once '../vendor/autoload.php';
    $db = new MongoDB\Driver\Manager();
    $bulka = new MongoDB\Driver\BulkWrite;

    if(isset($_POST["itemguid"]))
    {
        $itemguid = $_POST["itemguid"];

        $bulka->delete(['уникальность' => $itemguid], ['limit' => 1]);
        $db->executeBulkWrite("schoollibrary.детализация", $bulka);
    }
    else
    {
        echo 'Произошла ошибка isset';
    }
?>