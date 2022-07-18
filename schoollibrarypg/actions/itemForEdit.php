<?php
	require_once '../access/access.php';

	$link = pg_connect($requisites)
	or die(pg_connection_status($link));

	if(isset($_POST["itemId"]))
	{
		$itemId = $_POST["itemId"];

		$outputItemForEdit = "SELECT * FROM детализация WHERE id_книги = $itemId";
		$outputItemForEditResult = pg_query($link, $outputItemForEdit) or die(pg_connection_status($link));

		while($editItem = pg_fetch_assoc($outputItemForEditResult))
		{
			$itemName = $editItem["наименование"];
			$itemCategory = $editItem["id_категории"];
			$itemAuthor = $editItem["автор"];
			$itemPublish = $editItem["издательство"];
			$itemStatusId = $editItem["id_статуса"];
			$itemClassId = $editItem["id_класса"];
		}

		$editDataCollect = array(
			'editStatus' => 'Данные для редактирования успешно определены',
			'editName' => $itemName,
			'editCategory' => $itemCategory,
			'editAuthor' => $itemAuthor,
			'editPublish' => $itemPublish,
			'editCurrentStatus' => $itemStatusId,
			'editCurrentClass' => $itemClassId
		);

		echo json_encode($editDataCollect);
	}
	else
	{
		echo "Ошибка подгрузки.";
	}

	pg_close($link);
?>