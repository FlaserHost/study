<?php
  require_once '../vendor/autoload.php';
  $db = new MongoDB\Driver\Manager();
  $bulka = new MongoDB\Driver\BulkWrite;

  /** @var $counter */
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
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
                            <input type="hidden" name="itemGuid" id="hiddenItemId">
                            <input class="input" name="itemAmount" id="Amount" type="number">
                            <input class="input" name="itemName" id="Name" type="text">
                            <select class="input" name="itemCategory" id="selectCat">
                                <?php
                                    $categoryFilter = [];
                                    $categoryQuery = new MongoDB\Driver\Query($categoryFilter);
                                    $outputCategory = $db->executeQuery("schoollibrary.категории", $categoryQuery);
                                    foreach($outputCategory as $categoryResult):
                                ?>
                                    <option id="cat_<?= $categoryResult->уникальность ?>" value="<?= $categoryResult->уникальность ?>"><?= $categoryResult->категория ?></option>
                                <?php endforeach; ?>
                            </select>
                            <input class="input" name="itemAuthor" id="Author" type="text">
                            <input class="input" name="itemPublish" id="Publish" type="text">
                            <select class="input" name="itemStatus" id="selectStatus">
                                <?php
                                    $statusFilter = [];
                                    $statusQuery = new MongoDB\Driver\Query($statusFilter);
                                    $outputStatus = $db->executeQuery("schoollibrary.статусы", $statusQuery);
                                    foreach($outputStatus as $statusResult):
                                ?>
                                    <option id="status_<?= $statusResult->уникальность ?>" value="<?= $statusResult->уникальность ?>"><?= $statusResult->статус ?></option>
                                <?php endforeach; ?>
                            </select>
                            <select class="input" name="itemClass" id="selectClass">
                                <?php
                                    $classFilter = [];
                                    $classQuery = new MongoDB\Driver\Query($classFilter);
                                    $outputClass = $db->executeQuery("schoollibrary.классы", $classQuery);
                                    foreach($outputClass as $classResult):
                                ?>
                                    <option id="class_<?= $classResult->уникальность ?>" value="<?= $classResult->уникальность ?>"><?= $classResult->класс ?></option>
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
    <div class="mainContainer">
        <div class="tablePlace">
            <button class='addBtn' id='addBtn' data-property="Добавление">Добавить книгу</button>
            <table id="itemsTable">
                <tr>
                    <th>П/н книги</th>
                    <th>Наименование</th>
                    <th>Категория</th>
                    <th>Автор</th>
                    <th>Издательство</th>
                    <th>Статус</th>
                    <th>Класс</th>
                    <th colspan="2">Действия</th>
                </tr>
                <?php
                    $filter = [];
                    $options = [
                            'sort' => ['наименование' => 1]
                    ];

                    $detailsQuery = new MongoDB\Driver\Query($filter, $options);
                    $detailsQueryResult = $db->executeQuery("schoollibrary.детализация", $detailsQuery);

                    foreach($detailsQueryResult as $record):
                ?>
                        <?php $counter++ ?>
                        <tr id="row_<?= $counter ?>">
                            <td class="numberCell" id="numberCell_<?= $counter ?>"><?= $counter ?></td>
                            <td id="nameItemCell_<?= $counter ?>"><?= $record->наименование ?></td>
                            <td id="categoryItemCell_<?= $counter ?>"><?= $record->категория ?></td>
                            <td id="authorItemCell_<?= $counter ?>"><?= $record->автор ?></td>
                            <td id="publishItemCell_<?= $counter ?>"><?= $record->издательство ?></td>
                            <td id="statusItemCell_<?= $counter ?>"><?= $record->статус ?></td>
                            <td id="classItemCell_<?= $counter ?>"><?= $record->класс ?></td>
                            <td>
                                <button class="rowBtn" id="editBtn_<?= $counter ?>" data-itemguid="<?= $record->уникальность ?>" data-btnnumber="<?= $counter ?>" data-itemcategory="<?= $record->категория ?>" data-itemstatus="<?= $record->статус ?>" data-itemclass="<?= $record->класс ?>" data-property="Редактирование">Редактировать</button>
                            </td>
                            <td>
                                <button class="rowBtn" id="delBtn_<?= $counter ?>" data-itemguid="<?= $record->уникальность ?>" data-btnnumber="<?= $counter ?>" data-property="Удаление">Удалить</button>
                            </td>
                        </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
    <script src="../js/scriptDetails.js"></script>
</body>
</html>
