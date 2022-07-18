<?php
    require_once '../access/access.php';

    /** @var $domain */
    /** @var $user */
    /** @var $password */
    /** @var $bdname */

    $link = mysqli_connect($domain, $user, $password, $bdname)
    or die("Ошибка подключения".mysqli_error($link));

    $outputItems = "SELECT * FROM детализация ORDER BY наименование ASC";
    $outputItemsResult = mysqli_query($link, $outputItems) or die("Ошибка подключения".mysqli_error($link));

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
									$outputCategoryEditResult = mysqli_query($link, $outputCategoryEdit) or die("Ошибка подключения".mysqli_error($link));
									foreach($outputCategoryEditResult as $editCat):
								?>
									<option id="cat_<?= $editCat["id_категории"]; ?>" value="<?= $editCat["id_категории"]; ?>"><?= $editCat["категория"]; ?></option>
								<?php endforeach; ?>
							</select>
							<input class="input" name="itemAuthor" id="Author" type="text">
							<input class="input" name="itemPublish" id="Publish" type="text">
							<select class="input" name="itemStatusId" id="selectStatus">
								<?php
									$outputStatus = "SELECT * FROM статусы";
									$outputStatusResult = mysqli_query($link, $outputStatus) or die("Ошибка подключения".mysqli_error($link));
									foreach($outputStatusResult as $editStatus):
								?>
									<option id="status_<?= $editStatus["id_статуса"]; ?>" value="<?= $editStatus["id_статуса"]; ?>"><?= $editStatus["статус"]; ?></option>
								<?php endforeach; ?>
							</select>
							<select class="input" name="itemClassId" id="selectClass">
								<?php
									$outputClassModal = "SELECT * FROM классы ORDER BY класс ASC";
									$outputClassModalResult = mysqli_query($link, $outputClassModal) or die("Ошибка подключения".mysqli_error($link));
									foreach($outputClassModalResult as $editClass):
								?>
									<option id="class_<?= $editClass["id_класса"]; ?>" value="<?= $editClass["id_класса"]; ?>"><?= $editClass["класс"]; ?></option>
								<?php endforeach; ?>
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
				<?php foreach($outputItemsResult as $item): ?>
					<?php
						$catId = $item['id_категории'];
						$statusID = $item['id_статуса'];
						$classID = $item['id_класса'];

						$outputCategory = "SELECT категория FROM категории WHERE id_категории = $catId";
						$outputCategoryResult = mysqli_query($link, $outputCategory) or die("Ошибка подключения".mysqli_error($link));

						$outputStatusShow = "SELECT статус FROM статусы WHERE id_статуса = $statusID";
						$outputStatusShowResult = mysqli_query($link, $outputStatusShow) or die("Ошибка подключения".mysqli_error($link));

						$outputClass = "SELECT класс FROM классы WHERE id_класса = $classID";
						$outputClassResult = mysqli_query($link, $outputClass) or die("Ошибка подключения".mysqli_error($link));

						foreach($outputCategoryResult as $cat)
						{
							$catText = $cat['категория'];
						}

						foreach($outputStatusShowResult as $status)
						{
							$statusText = $status['статус'];
						}

						foreach($outputClassResult as $class)
						{
							$classText = $class['класс'];
						}
					?>
					<?php $rowBtnId++; ?>
					<tr id="row_<?= $rowBtnId; ?>">
						<td class="numberCell" id="numberCell_<?= $rowBtnId; ?>"><?= $rowBtnId; ?></td>
						<td id="nameItemCell_<?= $rowBtnId; ?>"><?= $item["наименование"]; ?></td>
						<td id="categoryItemCell_<?= $rowBtnId; ?>"><?= $catText; ?></td>
						<td id="authorItemCell_<?= $rowBtnId; ?>"><?= $item["автор"]; ?></td>
						<td id="publishItemCell_<?= $rowBtnId; ?>"><?= $item["издательство"]; ?></td>
						<td id="statusItemCell_<?= $rowBtnId; ?>"><?= $statusText; ?></td>
						<td id="classItemCell_<?= $rowBtnId; ?>"><?= $classText; ?></td>
						<td><button class="rowBtn" id="editBtn_<?= $rowBtnId; ?>" data-btnNumber="<?= $rowBtnId; ?>" data-itemId="<?= $item["id_книги"]; ?>" data-property="Редактирование">Редактировать</button></td>
						<td><button class="rowBtn" id="delBtn_<?= $rowBtnId; ?>" data-btnNumber="<?= $rowBtnId; ?>" data-itemId="<?= $item["id_книги"]; ?>" data-property="Удаление">Удалить</button></td>
					</tr>
				<?php endforeach; ?>
			</table>
		</div>
	</div>
	<script src="../js/scriptDetails.js"></script>
</body>
</html>