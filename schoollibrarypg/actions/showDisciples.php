<?php
	require_once '../access/access.php';

	$link = pg_connect($requisites)
	or die(pg_connection_status($link));

	if(isset($_POST["classId"]))
	{
		$classId = $_POST["classId"];

		$disciplesSelect = "SELECT * FROM учащиеся WHERE id_класса = $classId";
		$disciplesSelectResult = pg_query($link, $disciplesSelect) or die(pg_connection_status($link));

		$discipleCount = 0;
		$discipleArray[] = '';
		$getFullDisciple = array();
		while($disciple = pg_fetch_assoc($disciplesSelectResult))
		{
			if($disciple)
			{
				$discipleCount++;
				$discipleFIO = $disciple["ФИО"];
				$discipleAge = $disciple["возраст"];
				$discipleClassId = $disciple["id_класса"];

				$getClass = "SELECT класс FROM классы WHERE id_класса = $discipleClassId";
				$getClassResult = pg_query($link, $getClass) or die(pg_connection_status($link));

				while($class = pg_fetch_assoc($getClassResult))
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

	pg_close($link);
?>