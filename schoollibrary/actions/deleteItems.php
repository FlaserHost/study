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
		$deleteRequest = "DELETE FROM детализация WHERE id_книги = $itemId";
		$executeDeleteRequest = mysqli_query($link, $deleteRequest) or die("Ошибка подключения".mysqli_error($link));

		mysqli_close($link);
	}
	else
	{
		echo 'Упс... Произошла ошибка';
	}
?>