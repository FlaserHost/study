<?php
	require_once 'access/access.php';

	$link = pg_connect($requisites)
	or die(pg_connection_status($link));

	$pivotTable = "SELECT наименование, COUNT(наименование) as на_полке, COUNT(CASE WHEN id_статуса = 2 THEN 1 ELSE NULL END) as выдано FROM детализация GROUP BY наименование ORDER BY наименование ASC";
	$pivotTableCreate = pg_query($link, $pivotTable) or die(pg_connection_status($link));

	$rowCounter = 0;
?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Школьная библиотека</title>
	<link rel="stylesheet" href="css/stylePivot.css">
	<script src="js/jquery-3.6.0.js"></script>
</head>
<body>
	<header>
		<h1>Каталог</h1>
	</header>
	<nav class="leftNavPanel" data-panel="Панель скрыта">
		<div class="btnStack">
			<div class="panelName">
				<span>Меню</span>
			</div>
			<div class="navBtn" id="pivotLink">Каталог</div>
			<a class="navBtn" id="details"  href="pages/details.php">Детализация</a>
			<div class="navBtn" id="classes">Классы</div>
		</div>
		<div class="showPanelArrow">
			<span>Меню</span>
		</div>
	</nav>
	<div class="classModal">
		<div class="classPrintWindow">
			<div class="contentPartClasses">
				<div class="titlePart">
					<div class="classTitle">
						<span>Классы</span>
					</div>
					<div class="closeCrossClasses">X</div>
				</div>
				<div class="classDataPlace">
					<div class="selectPlace">
						<select name="currentClass" id="classSelector" class="classSelector"></select>
					</div>
					<div class="disciplesTablePlace">
						<table id="disciplesTable">
							<tr>
								<th>Класс</th>
								<th>ФИО ученика</th>
								<th>Возраст ученика</th>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="datailsModal">
		<div class="datailsSquare">
			<div class="contentPart">
				<div class="titlePart">
					<div class="pivotTitle">
						<span>Детализация</span>
					</div>
					<div class="closeCross">X</div>
				</div>
				<div class="pivotDataPlace">
					<table id="pivotData">
						<tr>
							<th>П/н книги</th>
							<th>Наименование книги</th>
							<th>Категория</th>
							<th>Автор</th>
							<th>Издательство</th>
							<th>Статус</th>
							<th>Класс получатель</th>
							<th>ФИО ученика получателя</th>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="pivotTableArea">
			<table id="pivotTable">
				<tr>
					<th>Наименование</th>
					<th>На полке шт.</th>
					<th>Выдано шт.</th>
					<th>Всего в наличии шт.</th>
				</tr>
				<?php while($pivotBody = pg_fetch_assoc($pivotTableCreate)): ?>
					<?php $rowCounter++ ?>
					<tr id="pivotRow_<?= $rowCounter ?>">
						<td><?= $pivotBody["наименование"] ?></td>
						<td><a class="showDetails" data-property="<?= $pivotBody["наименование"] ?>" data-linkname="на полке" href=""><?= $pivotBody["на_полке"] - $pivotBody["выдано"] ?></a></td>
						<td><a class="showDetails" data-property="<?= $pivotBody["наименование"] ?>" data-linkname="выдано" href=""><?= $pivotBody["выдано"] ?></a></td>
						<td><a class="showDetails" data-property="<?= $pivotBody["наименование"] ?>" data-linkname="всего" href=""><?= $pivotBody["на_полке"] ?></a></td>
					</tr>
				<?php endwhile; ?>
			</table>
		</div>
	</div>
	<script src="js/scriptPivot.js"></script>
</body>
</html>