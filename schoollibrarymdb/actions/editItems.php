<?php
    require_once '../vendor/autoload.php';
    $db = new MongoDB\Driver\Manager();
    $bulka = new MongoDB\Driver\BulkWrite;

    if(isset($_POST["itemGuid"], $_POST["itemName"], $_POST["itemCategory"], $_POST["itemAuthor"], $_POST["itemPublish"], $_POST["itemStatus"]))
    {
        $itemGuid = $_POST["itemGuid"];
        $itemName = $_POST["itemName"];
        $itemCategory = $_POST["itemCategory"];
        $itemAuthor = $_POST["itemAuthor"];
        $itemPublish = $_POST["itemPublish"];
        $itemStatus = $_POST["itemStatus"];

        // определение критериев фильтрации
        $catgoryFilter = ['уникальность' => $itemCategory];
        $statusFilter = ['уникальность' => $itemStatus];

        // установка фильтров в запросы
        $categoryQuery = new MongoDB\Driver\Query($catgoryFilter);
        $statusQuery = new MongoDB\Driver\Query($statusFilter);

        // выполнение запросов
        $categoryExecutor = $db->executeQuery("schoollibrary.категории", $categoryQuery);
        $statusExecutor = $db->executeQuery("schoollibrary.статусы", $statusQuery);

        // прогонка результатов выполнения запросов
        foreach($categoryExecutor as $getCategory)
        {
            $showCategory = $getCategory->категория;
        }

        foreach($statusExecutor as $getStatus)
        {
            $showStatus = $getStatus->статус;
        }

        if(isset($_POST["itemClass"]))
        {
            $itemClass = $_POST["itemClass"];

            $classFilter = ['уникальность' => $itemClass];
            $classQuery = new MongoDB\Driver\Query($classFilter);
            $classExecutor = $db->executeQuery("schoollibrary.классы", $classQuery);

            foreach($classExecutor as $getClass)
            {
                $showClass = $getClass->класс;
            }
        }
        else
        {
            $showClass = 'Нет';
        }

        $bulka->update(
            ['уникальность' => $itemGuid],
            ['$set' => [
                'наименование' => $itemName,
                'категория' => $showCategory,
                'автор' => $itemAuthor,
                'издательство' => $itemPublish,
                'статус' => $showStatus,
                'класс' => $showClass
            ]],
            ['multi' => false, 'upsert' => false]
        );

        $db->executeBulkWrite('schoollibrary.детализация', $bulka);

        $mirage = array(
            'updStatus' => 'Обновление данных прошло успешно',
            'newName' => $itemName,
            'newCategory' => $showCategory,
            'newAuthor' => $itemAuthor,
            'newPublish' => $itemPublish,
            'newStatus' => $showStatus,
            'newClass' => $showClass
        );

        echo json_encode($mirage);
    }
    else
    {
        echo "Произошла ошибка";
    }
?>
