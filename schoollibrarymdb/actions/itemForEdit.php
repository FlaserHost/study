<?php
    require_once '../vendor/autoload.php';
    $db = new MongoDB\Driver\Manager();
    $bulka = new MongoDB\Driver\BulkWrite;

    if(isset($_POST["itemguid"], $_POST["itemCategory"], $_POST["itemStatus"], $_POST["itemClass"]))
    {
        $itemguid = $_POST["itemguid"];
        $itemCategory = $_POST["itemCategory"];
        $itemStatus = $_POST["itemStatus"];
        $itemClass = $_POST["itemClass"];

        $itemFilter = ['уникальность' => $itemguid];
        $categoryFilter = ['категория' => $itemCategory];
        $statusFilter = ['статус' => $itemStatus];
        $classFilter = ['класс' => $itemClass];

        $itemQuery = new MongoDB\Driver\Query($itemFilter);
        $categoryQuery = new MongoDB\Driver\Query($categoryFilter);
        $statusQuery = new MongoDB\Driver\Query($statusFilter);
        $classQuery = new MongoDB\Driver\Query($classFilter);

        $itemExecutor = $db->executeQuery("schoollibrary.детализация", $itemQuery);
        $categoryExecutor = $db->executeQuery("schoollibrary.категории", $categoryQuery);
        $statusExecutor = $db->executeQuery("schoollibrary.статусы", $statusQuery);
        $classExecutor = $db->executeQuery("schoollibrary.классы", $classQuery);

        foreach($itemExecutor as $itemResult)
        {
            $itemName = $itemResult->наименование;
            $itemAuthor = $itemResult->автор;
            $itemPublish = $itemResult->издательство;
        }

        foreach($categoryExecutor as $categoryResult)
        {
            $getItemCategory = $categoryResult->уникальность;
        }

        foreach($statusExecutor as $statusResult)
        {
            $getItemStatus = $statusResult->уникальность;
        }

        foreach($classExecutor as $classResult)
        {
            $getItemClass = $classResult->уникальность;
        }

        $editDataCollect = array(
            'editStatus' => 'Данные для редактирования успешно определены',
            'editName' => $itemName,
            'editCategory' => $getItemCategory,
            'editAuthor' => $itemAuthor,
            'editPublish' => $itemPublish,
            'editCurrentStatus' => $getItemStatus,
            'editCurrentClass' => $getItemClass
        );

        echo json_encode($editDataCollect);
    }
    else
    {
        echo "Произошла ошибка";
    }
?>