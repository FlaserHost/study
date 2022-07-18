<?php
    require_once '../vendor/autoload.php';
    $db = new MongoDB\Driver\Manager();
    $bulka = new MongoDB\Driver\BulkWrite;

    /** @var $rowNum */

    if(isset($_POST["bookName"], $_POST["bookStatus"]))
    {
        $bookName = $_POST["bookName"];
        $bookStatus = $_POST["bookStatus"];

        if($bookStatus !== "Всего")
        {
            $filter = ['наименование' => $bookName, 'статус' => $bookStatus];
        }
        else
        {
            $filter = ['наименование' => $bookName];
        }

        $query = new MongoDB\Driver\Query($filter);
        $queryExecutor = $db->executeQuery("schoollibrary.детализация", $query);

        foreach($queryExecutor as $exe)
        {
            $rowNum++;
            $assocArray = array(
                'bookName' => $exe->наименование,
                'bookCategory' => $exe->категория,
                'bookAuthor' => $exe->автор,
                'bookPublish' => $exe->издательство,
                'bookStatus' => $exe->статус,
                'bookInClass' => $exe->класс,
                'bookDisciple' => $exe->фио_учащегося
            );

            $dataArray[0] = $rowNum;
            $dataArray[$rowNum] = $assocArray;
        }

        echo json_encode($dataArray);
    }
    else
    {
        echo "Произошла ошибка isset";
    }
?>
