<?php
	require_once '../access/access.php';

	$link = pg_connect($requisites)
	or die(pg_connection_status($link));

	$class = "SELECT * FROM классы WHERE id_класса != 14 ORDER BY класс ASC";
	$classResult = pg_query($link, $class) or die(pg_connection_status($link));

	$classCount = 0;
	$classArray[] = '';
	$getterArray = array();
	while($classRecord = pg_fetch_assoc($classResult))
	{
		if($classRecord)
		{
			$classCount++;
			$getClassId = $classRecord["id_класса"];
			$getClass = $classRecord["класс"];

			$getterArray = array(
				'classId' => $getClassId,
				'getClass' => $getClass
			);

			$classArray[0] = $classCount;
			$classArray[$classCount] = $getterArray;
		}
	}

	echo json_encode($classArray);
	pg_close($link);
?>