<?php
	require_once '../access/access.php';

	$link = pg_connect($requisites)
	or die(pg_connection_status($link));

	if(isset($_POST["bookName"], $_POST["bookStatus"]))
	{
		$bookName = $_POST["bookName"];
		$bookStatus = $_POST["bookStatus"];

		if($bookStatus === 'на полке')
		{
			$detailsShow = "SELECT * FROM детализация WHERE наименование = '$bookName' AND id_статуса != 2";
			$detailsShowResult = pg_query($link, $detailsShow) or die(pg_connection_status($link));
		}
		else if($bookStatus === 'выдано')
		{
			$detailsShow = "SELECT * FROM детализация WHERE наименование = '$bookName' AND id_статуса = 2";
			$detailsShowResult = pg_query($link, $detailsShow) or die(pg_connection_status($link));
		}
		else if($bookStatus === 'всего')
		{
			$detailsShow = "SELECT * FROM детализация WHERE наименование = '$bookName'";
			$detailsShowResult = pg_query($link, $detailsShow) or die(pg_connection_status($link));
		}

		$rowsCount = 0;
		while($details = pg_fetch_assoc($detailsShowResult))
		{
			if($details)
			{
				$rowsCount++;
				$name = $details["наименование"];
				$idCategory = $details["id_категории"];
				$author = $details["автор"];
				$publish = $details["издательство"];
				$idStatus = $details["id_статуса"];
				$idClass = $details["id_класса"];
				$idDisciple = $details["id_учащегося"];

				$getCategory = "SELECT категория FROM категории WHERE id_категории = $idCategory";
				$getCategoryResult = pg_query($link, $getCategory) or die(pg_connection_status($link));
			
				$getStatus = "SELECT статус FROM статусы WHERE id_статуса = $idStatus";
				$getStatusResult = pg_query($link, $getStatus) or die(pg_connection_status($link));

				$getClass = "SELECT класс FROM классы WHERE id_класса = $idClass";
				$getClassResult = pg_query($link, $getClass) or die(pg_connection_status($link));

				$getDisciple = "SELECT ФИО FROM учащиеся WHERE id_ученика = $idDisciple";
				$getDiscipleResult = pg_query($link, $getDisciple) or die(pg_connection_status($link));

				while($cat = pg_fetch_assoc($getCategoryResult))
				{
					if($cat)
					{
						$categoryText = $cat["категория"];
					}
				}

				while($status = pg_fetch_assoc($getStatusResult))
				{
					if($status)
					{
						$statusText = $status["статус"];
					}
				}

				while($class = pg_fetch_assoc($getClassResult))
				{
					if($class)
					{
						$classText = $class["класс"];
					}
				}

				while($disciple = pg_fetch_assoc($getDiscipleResult))
				{
					if($disciple)
					{
						$discipleText = $disciple["ФИО"];
					}
				}

				if($classText === '')
				{
					$classText = 'Нет';
				}

				if($discipleText === null)
				{
					$discipleText = 'Нет';
				}

				$getData = array(
					'bookName' => $name,
					'bookCategory' => $categoryText,
					'bookAuthor' => $author,
					'bookPublish' => $publish,
					'bookStatus' => $statusText,
					'bookInClass' => $classText,
					'bookDisciple' => $discipleText
				);

				$detailsData[0] = $rowsCount;
				$detailsData[$rowsCount] = $getData;
			}
		}

		echo json_encode($detailsData);
	}
	else
	{
		echo "Произошла ошибка";
	}

	pg_close($link);
?>