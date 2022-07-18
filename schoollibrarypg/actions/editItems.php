<?php
	require_once '../access/access.php';

    /** @var $requisites */

	$link = pg_connect($requisites)
	or die(pg_connection_status($link));

	if(isset($_POST["itemId"], $_POST["itemName"], $_POST["itemCategoryId"], $_POST["itemAuthor"], $_POST["itemPublish"], $_POST["itemStatusId"], $_POST["itemClassId"]))
	{
		$itemId = $_POST["itemId"];
		$itemName = $_POST["itemName"];
		$itemCategoryId = $_POST["itemCategoryId"];
		$itemAuthor = $_POST["itemAuthor"];
		$itemPublish = $_POST["itemPublish"];
		$itemStatusId = $_POST["itemStatusId"];
		$itemClassId = $_POST["itemClassId"];

        if($itemClassId != 26)
        {
            $discipleQuery = "SELECT id_ученика FROM учащиеся WHERE id_класса = $itemClassId ORDER BY RANDOM() LIMIT 1";
            $discipleQueryResult = pg_query($link, $discipleQuery) or die(pg_connection_status($link));

            while($getDiscipleId = pg_fetch_assoc($discipleQueryResult))
            {
                if($getDiscipleId)
                {
                    $itemDiscipleId = $getDiscipleId["id_ученика"];
                }
                else
                {
                    $itemDiscipleId = 20;
                }
            }
        }
        else
        {
            $itemDiscipleId = 20;
        }

		$outputItems = "UPDATE детализация SET наименование = '$itemName', id_категории = $itemCategoryId, автор = '$itemAuthor', издательство = '$itemPublish', id_статуса = $itemStatusId, id_класса = $itemClassId, id_учащегося = $itemDiscipleId WHERE id_книги = $itemId";
		$outputItemsResult = pg_query($link, $outputItems) or die(pg_connection_status($link));

		$selectNewCategory = "SELECT категория FROM категории WHERE id_категории = $itemCategoryId";
		$outputNewCategory = pg_query($link, $selectNewCategory) or die(pg_connection_status($link));

		$selectNewStatus = "SELECT статус FROM статусы WHERE id_статуса = $itemStatusId";
		$outputNewStatus = pg_query($link, $selectNewStatus) or die(pg_connection_status($link));

		$selectNewClass = "SELECT класс FROM классы WHERE id_класса = $itemClassId";
		$outputNewClass = pg_query($link, $selectNewClass) or die(pg_connection_status($link));

		while($selectedCategory = pg_fetch_assoc($outputNewCategory))
		{
			if($selectedCategory)
			{
				$newCategory = $selectedCategory["категория"];
			}
		}

		while($selectedStatus = pg_fetch_assoc($outputNewStatus))
		{
			if($selectedStatus)
			{
				$newStatus = $selectedStatus["статус"];
			}
		}

		while($selectedClass = pg_fetch_assoc($outputNewClass))
		{
			if($selectedClass)
			{
				$newClass = $selectedClass["класс"];
			}
		}

		$mirage = array(
			'updStatus' => 'Обновление данных прошло успешно',
			'newName' => $itemName,
			'newCategory' => $newCategory,
			'newAuthor' => $itemAuthor,
			'newPublish' => $itemPublish,
			'newStatus' => $newStatus,
			'newClass' => $newClass
		);

		echo json_encode($mirage);
	}
	else
	{
		echo "Упс... что-то пошло не так с обновлением данных.";
	}

	pg_close($link);
?>