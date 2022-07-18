<?php
	require_once '../access/access.php';

	$link = pg_connect($requisites)
	or die(pg_connection_status($link));

	if(isset($_POST["itemId"]))
	{
		$itemId = $_POST["itemId"];
		$deleteRequest = "DELETE FROM детализация WHERE id_книги = $itemId";
		$executeDeleteRequest = pg_query($link, $deleteRequest);
	}
	else
	{
		echo 'Упс... Произошла ошибка';
	}

	pg_close($link);
?>