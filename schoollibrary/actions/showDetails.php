<?php
	require_once '../access/access.php';

    /** @var $domain */
    /** @var $user */
    /** @var $password */
    /** @var $bdname */

	$link = mysqli_connect($domain, $user, $password, $bdname)
	or die("Ошибка подключения".mysqli_error($link));

	if(isset($_POST["bookName"], $_POST["bookStatus"]))
	{
		$bookName = $_POST["bookName"];

		if($_POST["bookStatus"] === 'на полке')
		{
			$detailsShow = "SELECT * FROM детализация WHERE наименование = '$bookName' AND id_статуса != 3";
			$detailsShowResult = mysqli_query($link, $detailsShow) or die("Ошибка подключения".mysqli_error($link));
		}
		else if($_POST["bookStatus"] === 'выдано')
		{
			$detailsShow = "SELECT * FROM детализация WHERE наименование = '$bookName' AND id_статуса = 3";
			$detailsShowResult = mysqli_query($link, $detailsShow) or die("Ошибка подключения".mysqli_error($link));
		}
		else if($_POST["bookStatus"] === 'всего')
		{
			$detailsShow = "SELECT * FROM детализация WHERE наименование = '$bookName'";
			$detailsShowResult = mysqli_query($link, $detailsShow) or die("Ошибка подключения".mysqli_error($link));
		}

		$rowsCount = 0;
		foreach($detailsShowResult as $details)
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
				$getCategoryResult = mysqli_query($link, $getCategory) or die("Ошибка подключения".mysqli_error($link));
			
				$getStatus = "SELECT статус FROM статусы WHERE id_статуса = $idStatus";
				$getStatusResult = mysqli_query($link, $getStatus) or die("Ошибка подключения".mysqli_error($link));

				$getClass = "SELECT класс FROM классы WHERE id_класса = $idClass";
				$getClassResult = mysqli_query($link, $getClass) or die("Ошибка подключения".mysqli_error($link));

				$getDisciple = "SELECT ФИО FROM учащиеся WHERE id_ученика = $idDisciple";
				$getDiscipleResult = mysqli_query($link, $getDisciple) or die("Ошибка подключения".mysqli_error($link));

				foreach($getCategoryResult as $cat)
				{
					if($cat)
					{
						$categoryText = $cat["категория"];
					}
				}

				foreach($getStatusResult as $status)
				{
					if($status)
					{
						$statusText = $status["статус"];
					}
				}

				foreach($getClassResult as $class)
				{
					if($class)
					{
						$classText = $class["класс"];
					}
				}

				foreach($getDiscipleResult as $disciple)
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
		mysqli_close($link);
	}
	else
	{
		echo "Произошла ошибка";
	}
?>