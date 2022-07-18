<?php
	require_once '../access/access.php';

    /** @var $domain */
    /** @var $user */
    /** @var $password */
    /** @var $bdname */

	$link = mysqli_connect($domain, $user, $password, $bdname)
	or die("Ошибка подключения".mysqli_error($link));

	$class = "SELECT * FROM классы WHERE id_класса != 14 ORDER BY класс ASC";
	$classResult = mysqli_query($link, $class) or die("Ошибка подключения".mysqli_error($link));

	$classCount = 0;
	$classArray[] = '';
	$getterArray = array();
	foreach($classResult as $classRecord)
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
?>