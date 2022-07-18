<?php
	require_once '../access/access.php';

    /** @var $domain */
    /** @var $user */
    /** @var $password */
    /** @var $bdname */

	$link = mysqli_connect($domain, $user, $password, $bdname)
	or die("Ошибка подключения".mysqli_error($link));

	if(isset($_POST["itemId"]))
	{
		$itemId = $_POST["itemId"];

		$outputItemForEdit = "SELECT * FROM детализация WHERE id_книги = $itemId";
		$outputItemForEditResult = mysqli_query($link, $outputItemForEdit) or die("Ошибка подключения".mysqli_error($link));

		foreach($outputItemForEditResult as $editItem)
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
		mysqli_close($link);
	}
	else
	{
		echo "Ошибка подгрузки.";
	}
?>