<?php
	require_once '../access/access.php';

    /** @var $domain */
    /** @var $user */
    /** @var $password */
    /** @var $bdname */

	$link = mysqli_connect($domain, $user, $password, $bdname)
	or die("Ошибка подключения".mysqli_error($link));

	if(isset($_POST["itemId"], $_POST["itemName"], $_POST["itemCategoryId"], $_POST["itemAuthor"], $_POST["itemPublish"], $_POST["itemStatusId"], $_POST["itemClassId"]))
	{
		$itemId = $_POST["itemId"];
		$itemName = $_POST["itemName"];
		$itemCategoryId = $_POST["itemCategoryId"];
		$itemAuthor = $_POST["itemAuthor"];
		$itemPublish = $_POST["itemPublish"];
		$itemStatusId = $_POST["itemStatusId"];
		$itemClassId = $_POST["itemClassId"];

        if($itemClassId != 14)
        {
            $discipleQuery = "SELECT id_ученика FROM учащиеся WHERE id_класса = $itemClassId ORDER BY RAND() LIMIT 1";
            $discipleQueryResult = mysqli_query($link, $discipleQuery) or die("Ошибка подключения".mysqli_error($link));

            foreach($discipleQueryResult as $getDiscipleId)
            {
                if($getDiscipleId)
                {
                    $itemDiscipleId = $getDiscipleId["id_ученика"];
                }
                else
                {
                    $itemDiscipleId = 21;
                }
            }
        }
        else
        {
            $itemDiscipleId = 21;
        }

		$outputItems = "UPDATE детализация SET наименование = '$itemName', id_категории = $itemCategoryId, автор = '$itemAuthor', издательство = '$itemPublish', id_статуса = $itemStatusId, id_класса = $itemClassId, id_учащегося = $itemDiscipleId WHERE id_книги = $itemId";
		$outputItemsResult = mysqli_query($link, $outputItems) or die("Ошибка подключения".mysqli_error($link));

		$selectNewCategory = "SELECT категория FROM категории WHERE id_категории = $itemCategoryId";
		$outputNewCategory = mysqli_query($link, $selectNewCategory) or die("Ошибка подключения".mysqli_error($link));

		$selectNewStatus = "SELECT статус FROM статусы WHERE id_статуса = $itemStatusId";
		$outputNewStatus = mysqli_query($link, $selectNewStatus) or die("Ошибка подключения".mysqli_error($link));

		$selectNewClass = "SELECT класс FROM классы WHERE id_класса = $itemClassId";
		$outputNewClass = mysqli_query($link, $selectNewClass) or die("Ошибка подключения".mysqli_error($link));

		foreach($outputNewCategory as $selectedCategory)
		{
			if($selectedCategory)
			{
				$newCategory = $selectedCategory["категория"];
			}
		}

		foreach($outputNewStatus as $selectedStatus)
		{
			if($selectedStatus)
			{
				$newStatus = $selectedStatus["статус"];
			}
		}

		foreach($outputNewClass as $selectedClass)
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
		mysqli_close($link);
	}
	else
	{
		echo "Упс... что-то пошло не так с обновлением данных.";
	}
?>