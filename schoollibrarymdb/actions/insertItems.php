<?php
    require_once '../vendor/autoload.php';
    $db = new MongoDB\Driver\Manager();
    $bulka = new MongoDB\Driver\BulkWrite;

    if(isset($_POST["itemAmount"], $_POST["itemName"], $_POST["itemCategory"], $_POST["itemAuthor"], $_POST["itemPublish"], $_POST["itemStatus"]))
    {
        $symbolsCollection = "abcdefghijklmnopqrstuvwxyz0123456789";

        $itemAmount = $_POST["itemAmount"];
        $itemName = $_POST["itemName"];
        $itemCategoryUnique = $_POST["itemCategory"];
        $itemAuthor = $_POST["itemAuthor"];
        $itemPublish = $_POST["itemPublish"];
        $itemStatusUnique = $_POST["itemStatus"];

        $disciples = [
            'Ершова Полина Викторовна',
            'Пупкин Василий Васильевич',
            'Пыщ Пыщ Пыщ',
            'Дмитриев Сергей Георгиевич',
            'Попова Елизавета Павловна',
            'Климова Ксения Артемьевна',
            'Севастьянов Иван Львович',
            'Корнилова Мария Артёмовна',
            'Титов Борис Александрович',
            'Новикова Диана Михайловна',
            'Львов Артемий Григорьевич',
            'Жукова Кира Андреевна'
        ];

        $categoryFilter = ['уникальность' => $itemCategoryUnique];
        $statusFilter = ['уникальность' => $itemStatusUnique];

        $categoryQuery = new MongoDB\Driver\Query($categoryFilter);
        $statusQuery = new MongoDB\Driver\Query($statusFilter);

        $categoryExecutor = $db->executeQuery("schoollibrary.категории", $categoryQuery);
        $statusExecutor = $db->executeQuery("schoollibrary.статусы", $statusQuery);

        foreach($categoryExecutor as $getCategory)
        {
            $insertCategory = $getCategory->категория;
        }

        foreach($statusExecutor as $getStatus)
        {
            $insertStatus = $getStatus->статус;
        }

        if(isset($_POST["itemClass"]))
        {
            $itemClassUnique = $_POST["itemClass"];
            $classFilter = ['уникальность' => $itemClassUnique];
            $classQuery = new MongoDB\Driver\Query($classFilter);
            $classExecutor = $db->executeQuery("schoollibrary.классы", $classQuery);

            foreach($classExecutor as $getClass)
            {
                $insertClass = $getClass->класс;
            }
        }
        else
        {
            $insertClass = 'Нет';
        }

        for($i = 0; $i < $itemAmount; $i++)
        {
            $partOne = substr(str_shuffle($symbolsCollection), 0, 5);
            $partTwo = substr(str_shuffle($symbolsCollection), 0, 5);
            $partThree = substr(str_shuffle($symbolsCollection), 0, 6);

            $itemUnique = $partOne.$partTwo.$partThree;

            $guidCollection[$i] = $itemUnique;

            if($insertStatus === "Выдано")
            {
                $rand = rand(1, 12);
                $fio = $disciples[$rand];
            }
            else
            {
                $fio = "Нет";
            }

            $bulka->insert([
                'уникальность' => $itemUnique,
                'наименование' => $itemName,
                'категория' => $insertCategory,
                'автор' => $itemAuthor,
                'издательство' => $itemPublish,
                'статус' => $insertStatus,
                'класс' => $insertClass,
                'фио_учащегося' => $fio
            ]);
        }

        $db->executeBulkWrite("schoollibrary.детализация", $bulka);

        $insertedData = array(
            'insertStatus' => 'Добавление товара прошло успешно',
            'insertedAmount' => $itemAmount,
            'insertedGuid' => $guidCollection,
            'insertedItemName' => $itemName,
            'insertedItemCategory' => $insertCategory,
            'insertedItemAuthor' => $itemAuthor,
            'insertedItemPublish' => $itemPublish,
            'insertedItemStatus' => $insertStatus,
            'insertedItemClass' => $insertClass
        );

        echo json_encode($insertedData);
    }
    else
    {
        echo "Произошла ошибка";
    }
?>