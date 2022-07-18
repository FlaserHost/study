<?php
	require_once '../access/access.php';

    /** @var $domain */
    /** @var $user */
    /** @var $password */
    /** @var $bdname */

	$link = mysqli_connect($domain, $user, $password, $bdname)
	or die("Ошибка подключения".mysqli_error($link));

	if(isset($_POST["classId"]))
	{
		$classId = $_POST["classId"];

		$disciplesSelect = "SELECT * FROM учащиеся WHERE id_класса = $classId";
		$disciplesSelectResult = mysqli_query($link, $disciplesSelect) or die("Ошибка подключения".mysqli_error($link));

		$discipleCount = 0;
		$discipleArray[] = '';
		$getFullDisciple = array();
		foreach($disciplesSelectResult as $disciple)
		{
			if($disciple)
			{
				$discipleCount++;
				$discipleFIO = $disciple["ФИО"];
				$discipleAge = $disciple["возраст"];
				$discipleClassId = $disciple["id_класса"];

				$getClass = "SELECT класс FROM классы WHERE id_класса = $discipleClassId";
				$getClassResult = mysqli_query($link, $getClass) or die("Ошибка подключения".mysqli_error($link));

				foreach($getClassResult as $class)
				{
					if($class)
					{
						$classText = $class["класс"];
					}
				}

				$getFullDisciple = array(
					'discipleFIO' => $discipleFIO,
					'discipleAge' => $discipleAge,
					'discipleClass' => $classText
				);

				$discipleArray[0] = $discipleCount;
				$discipleArray[$discipleCount] = $getFullDisciple;
			}
		}

		echo json_encode($discipleArray);
	}
	else
	{
		echo 'Произошла ошибка';
	}
?>