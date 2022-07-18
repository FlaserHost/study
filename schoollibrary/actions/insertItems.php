<?php
	require_once '../access/access.php';

    /** @var $domain */
    /** @var $user */
    /** @var $password */
    /** @var $bdname */

	$link = mysqli_connect($domain, $user, $password, $bdname)
	or die("Ошибка подключения".mysqli_error($link));

	if(isset($_POST["itemAmount"], $_POST["itemName"], $_POST["itemCategoryId"], $_POST["itemAuthor"], $_POST["itemPublish"], $_POST["itemStatusId"]))
	{
        $itemAmount = $_POST["itemAmount"];

		if(isset($_POST["itemClassId"]))
		{
			$itemClassId = $_POST["itemClassId"];

            for($i = 0; $i < $itemAmount; $i++)
            {
                $discipleQuery = "SELECT id_ученика FROM учащиеся WHERE id_класса = $itemClassId ORDER BY RAND() LIMIT 1";
                $discipleQueryResult = mysqli_query($link, $discipleQuery) or die("Ошибка подключения".mysqli_error($link));

                foreach($discipleQueryResult as $getDiscipleId)
                {
                    if($getDiscipleId)
                    {
                        $itemDiscipleId[$i] = $getDiscipleId["id_ученика"];
                    }
                    else
                    {
                        $itemDiscipleId[$i] = 21;
                    }
                }
            }
		}
		else
		{
			$itemClassId = 14;

            for($i = 0; $i < $itemAmount; $i++)
            {
                $itemDiscipleId[$i] = 21;
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
			$insertRequest = "INSERT INTO детализация VALUES (NULL, '$itemName', $idCategory, '$itemAuthor', '$itemPublish', $itemStatusId, $itemClassId, '$discipleId')";
			$executeInsertResult = mysqli_query($link, $insertRequest) or die("Ошибка подключения".mysqli_error($link));
		}

		$categoryRequest = "SELECT категория FROM категории WHERE id_категории = $idCategory";
		$executeCategoryResult = mysqli_query($link, $categoryRequest) or die("Ошибка подключения".mysqli_error($link));

		$statusRequest = "SELECT статус FROM статусы WHERE id_статуса = $itemStatusId";
		$executeStatusResult = mysqli_query($link, $statusRequest) or die("Ошибка подключения".mysqli_error($link));

		$classRequest = "SELECT класс FROM классы WHERE id_класса = $itemClassId";
		$executeClassResult = mysqli_query($link, $classRequest) or die("Ошибка подключения".mysqli_error($link));

		$lastAddedIdSelect = "SELECT MAX(id_книги) as max FROM детализация";
		$executeMax = mysqli_query($link, $lastAddedIdSelect) or die("Ошибка подключения".mysqli_error($link));

		foreach($executeCategoryResult as $getCategory)
		{
			if($getCategory)
			{
				$insertedCategory = $getCategory["категория"];
			}
		}

		foreach($executeStatusResult as $getStatus)
		{
			if($getStatus)
			{
				$insertedStatus = $getStatus["статус"];
			}
		}

		foreach($executeClassResult as $getClass)
		{
			if($getClass)
			{
				$insertedClass = $getClass["класс"];
			}
		}

		foreach($executeMax as $getMax)
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
		mysqli_close($link);
	}
	else
	{
		echo 'Упс... Произошла ошибка';
	}
?>