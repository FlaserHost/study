<?php
	require_once '../access/access.php';

    /** @var $requisites */

	$link = pg_connect($requisites)
	or die(pg_connection_status($link));

	if(isset($_POST["itemAmount"], $_POST["itemName"], $_POST["itemCategoryId"], $_POST["itemAuthor"], $_POST["itemPublish"], $_POST["itemStatusId"]))
	{
        $itemAmount = $_POST["itemAmount"];

		if(isset($_POST["itemClassId"]))
		{
			$itemClassId = $_POST["itemClassId"];

            for($i = 0; $i < $itemAmount; $i++)
            {
                $discipleQuery = "SELECT id_ученика FROM учащиеся WHERE id_класса = $itemClassId ORDER BY RANDOM() LIMIT 1";
                $discipleQueryResult = pg_query($link, $discipleQuery) or die(pg_connection_status($link));

                while($getDiscipleId = pg_fetch_assoc($discipleQueryResult))
                {
                    if($getDiscipleId)
                    {
                        $itemDiscipleId[$i] = $getDiscipleId["id_ученика"];
                    }
                    else
                    {
                        $itemDiscipleId[$i] = 20;
                    }
                }
            }
		}
		else
		{
			$itemClassId = 26;

            for($i = 0; $i < $itemAmount; $i++)
            {
                $itemDiscipleId[$i] = 20;
            }
		}

		$itemName = $_POST["itemName"];
		$idCategory = $_POST["itemCategoryId"];
		$itemAuthor = $_POST["itemAuthor"];
		$itemPublish = $_POST["itemPublish"];
		$itemStatusId = $_POST["itemStatusId"];

		for($i = 0; $i < $itemAmount; $i++)
		{
            $discipleId = intval($itemDiscipleId[$i]);
			$insertRequest = "INSERT INTO детализация (наименование, id_категории, автор, издательство, id_статуса, id_класса, id_учащегося) VALUES ('$itemName', $idCategory, '$itemAuthor', '$itemPublish', $itemStatusId, $itemClassId, '$discipleId')";
			$executeInsertResult = pg_query($link, $insertRequest) or die(pg_connection_status($link));
		}

		$categoryRequest = "SELECT категория FROM категории WHERE id_категории = $idCategory";
		$executeCategoryResult = pg_query($link, $categoryRequest) or die(pg_connection_status($link));

		$statusRequest = "SELECT статус FROM статусы WHERE id_статуса = $itemStatusId";
		$executeStatusResult = pg_query($link, $statusRequest) or die(pg_connection_status($link));

		$classRequest = "SELECT класс FROM классы WHERE id_класса = $itemClassId";
		$executeClassResult = pg_query($link, $classRequest) or die(pg_connection_status($link));

		$lastAddedIdSelect = "SELECT MAX(id_книги) as max FROM детализация";
		$executeMax = pg_query($link, $lastAddedIdSelect) or die(pg_connection_status($link));

		while($getCategory = pg_fetch_assoc($executeCategoryResult))
		{
			if($getCategory)
			{
				$insertedCategory = $getCategory["категория"];
			}
		}

		while($getStatus = pg_fetch_assoc($executeStatusResult))
		{
			if($getStatus)
			{
				$insertedStatus = $getStatus["статус"];
			}
		}

		while($getClass = pg_fetch_assoc($executeClassResult))
		{
			if($getClass)
			{
				$insertedClass = $getClass["класс"];
			}
		}

		while($getMax = pg_fetch_assoc($executeMax))
		{
			if($getMax)
			{
				$maxId = $getMax["max"];
			}
		}

		$insertedData = array(
			'insertStatus' => 'Добавление товара прошло успешно',
			'insertedAmount' => $itemAmount,
			'insertedItemId' => $maxId,
			'insertedItemName' => $itemName,
			'insertedItemCategory' => $insertedCategory,
			'insertedItemAuthor' => $itemAuthor,
			'insertedItemPublish' => $itemPublish,
			'insertedItemStatus' => $insertedStatus,
			'insertedItemClass' => $insertedClass
		);

		echo json_encode($insertedData);
	}
	else
	{
		echo 'Упс... Произошла ошибка';
	}

	pg_close($link);
?>