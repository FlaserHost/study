<?php
    require_once '../access/access.php';

    /** @var $requisites */

    $link = pg_connect($requisites)
    or die(pg_connection_status($link));

    $selectQuery = "SELECT * FROM детализация ORDER BY id_книги ASC";
    $outputItemsResult = pg_query($link, $selectQuery);

    $rowBtnId = 0;
?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Школьная библиотека</title>
	<link rel="stylesheet" href="../css/styleDetails.css">
	<script src="../js/jquery-3.6.0.js"></script>
</head>
<body>
	<header>
		<h1>Детализация</h1>
	</header>
	<nav class="leftNavPanel" data-panel="Панель скрыта">
		<div class="btnStack">
			<div class="panelName">
				<span>Меню</span>
			</div>
			<a class="navBtn" id="pivotLink" href="../index.php">Каталог</a>
			<div class="navBtn" id="details">Детализация</div>
			<div class="navBtn" id="classes">Классы</div>
		</div>
		<div class="showPanelArrow">
			<span>Меню</span>
		</div>
	</nav>
	<div class="modalArea">
		<div class="modalSquare">
			<div class="actionStatusNotice">
				<span></span>
			</div>
			<div class="modalNameArea">
				<span></span>
			</div>
			<div class="closeCross">X</div>
			<div class="contentPart">
				<form id="modalInteractiveForm">
					<div class="info">
						<div class="labelsPlace">
							<label class="label">Кол-во книг</label>
							<label class="label">Наименование</label>
							<label class="label">Категория</label>
							<label class="label">Автор</label>
							<label class="label">Издательство</label>
							<label class="label">Статус</label>
							<label class="label">Класс</label>
						</div>
						<div class="inputsPlace">
							<input type="hidden" name="itemId" id="hiddenItemId">
							<input class="input" name="itemAmount" id="Amount" type="number">
							<input class="input" name="itemName" id="Name" type="text">
							<select class="input" name="itemCategoryId" id="selectCat">
								<?php
									$outputCategoryEdit = "SELECT * FROM категории";
									$outputCategoryEditResult = pg_query($link, $outputCategoryEdit);
									while($editCat = pg_fetch_object($outputCategoryEditResult)):
								?>
									<option id="cat_<?= $editCat->id_категории; ?>" value="<?= $editCat->id_категории; ?>"><?= $editCat->категория; ?></option>
								<?php endwhile; ?>
							</select>
							<input class="input" name="itemAuthor" id="Author" type="text">
							<input class="input" name="itemPublish" id="Publish" type="text">
							<select class="input" name="itemStatusId" id="selectStatus">
								<?php
									$outputStatus = "SELECT * FROM статусы";
									$outputStatusResult = pg_query($link, $outputStatus);
									while($editStatus = pg_fetch_object($outputStatusResult)):
								?>
									<option id="status_<?= $editStatus->id_статуса; ?>" value="<?= $editStatus->id_статуса; ?>"><?= $editStatus->статус; ?></option>
								<?php endwhile; ?>
							</select>
							<select class="input" name="itemClassId" id="selectClass">
								<?php
									$outputClassModal = "SELECT * FROM классы ORDER BY класс ASC";
									$outputClassModalResult = pg_query($link, $outputClassModal);
									while($editClass = pg_fetch_object($outputClassModalResult)):
								?>
									<option id="class_<?= $editClass->id_класса; ?>" value="<?= $editClass->id_класса; ?>"><?= $editClass->класс; ?></option>
								<?php endwhile; ?>
							</select>
						</div>
					</div>
					<div class="intoModalBtnPlace">
						<button class="intoModalBtnMain" id="intoModalBtnMain"></button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="tablePlace">
			<button class='addBtn' id='addBtn' data-property="Добавление">Добавить книгу</button>
			<table id="itemsTable">
				<tr>
					<th>П/н записи</th>
					<th>Наименование</th>
					<th>Категория</th>
					<th>Автор</th>
					<th>Издательство</th>
					<th>Статус</th>
					<th>Класс</th>
					<th colspan="2">Действия</th>
				</tr>
				<?php while($item = pg_fetch_object($outputItemsResult)): ?>
					<?php
						$catId = $item->id_категории;
						$statusID = $item->id_статуса;
						$classID = $item->id_класса;

						$outputCategory = "SELECT категория FROM категории WHERE id_категории = $catId";
						$outputCategoryResult = pg_query($link, $outputCategory);

						$outputStatusShow = "SELECT статус FROM статусы WHERE id_статуса = $statusID";
						$outputStatusShowResult = pg_query($link, $outputStatusShow);

						$outputClass = "SELECT класс FROM классы WHERE id_класса = $classID";
						$outputClassResult = pg_query($link, $outputClass);

						while($cat = pg_fetch_object($outputCategoryResult))
						{
							$catText = $cat->категория;
						}

						while($status = pg_fetch_object($outputStatusShowResult))
						{
							$statusText = $status->статус;
						}

						while($class = pg_fetch_object($outputClassResult))
						{
							$classText = $class->класс;
						}

						if($statusText === 'Выдано')
						{
							$redRow = 'class="redRow"';
						}
						else
						{
							$redRow = '';
						}
					?>
					<?php $rowBtnId++; ?>
					<tr <?= $redRow ?> id="row_<?= $rowBtnId; ?>">
						<td class="numberCell" id="numberCell_<?= $rowBtnId; ?>"><?= $rowBtnId; ?></td>
						<td id="nameItemCell_<?= $rowBtnId; ?>"><?= $item->наименование; ?></td>
						<td id="categoryItemCell_<?= $rowBtnId; ?>"><?= $catText; ?></td>
						<td id="authorItemCell_<?= $rowBtnId; ?>"><?= $item->автор; ?></td>
						<td id="publishItemCell_<?= $rowBtnId; ?>"><?= $item->издательство; ?></td>
						<td id="statusItemCell_<?= $rowBtnId; ?>"><?= $statusText; ?></td>
						<td id="classItemCell_<?= $rowBtnId; ?>"><?= $classText; ?></td>
						<td><button class="rowBtn" id="editBtn_<?= $rowBtnId; ?>" data-btnNumber="<?= $rowBtnId; ?>" data-itemId="<?= $item->id_книги; ?>" data-property="Редактирование">Редактировать</button></td>
						<td><button class="rowBtn" id="delBtn_<?= $rowBtnId; ?>" data-btnNumber="<?= $rowBtnId; ?>" data-itemId="<?= $item->id_книги; ?>" data-property="Удаление">Удалить</button></td>
					</tr>
				<?php endwhile; ?>
			</table>
		</div>
	</div>
	<script src="../js/scriptDetails.js"></script>
</body>
</html>