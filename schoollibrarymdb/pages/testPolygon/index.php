<?php
/* require_once 'vendor/autoload.php';

$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
$bulk = new MongoDB\Driver\BulkWrite;

$bulk->insert([
    'наименование' => 'Астория России2',
    'категория' => 'Учебная литература2',
    'автор' => 'Шан Сунг2',
    'издательство' => 'Дрофа2',
    'статус' => 'Выдано2',
    'класс' => '4Б2',
    'ФИО учащегося' => 'Ершова Полина Викторовна2'
]);

$manager->executeBulkWrite('schoollibrary.details', $bulk);

$bulk->update(
    ['наименование' => 'История России2'],
    ['$set' => ['издательство' => 'Аутофильский интерасплод']],
    ['multi' => false, 'upsert' => false]
);


$bulk->delete(['наименование' => 'Ястория России2'], ['limit' => 1]);

$manager->executeBulkWrite('schoollibrary.details', $bulk);

$filter = [];
$options = [
    'projection' => ['_id' => 0],
    'sort' => ['наименование' => 1],
];

$query = new MongoDB\Driver\Query($filter, $options);
$cursor = $manager->executeQuery('schoollibrary.details', $query);

foreach($cursor as $document) {
    $counter++;
    echo $document->наименование . "<br>";
}

echo "Количество записей: {$counter}";






















 $connection = new MongoDB\Client();

 if($connection)
 {
    $collection = $connection->schoollibrary->details;

    $collection->insertOne([
        'наименование' => 'Кстория России2',
        'категория' => 'Учебная литература2',
        'автор' => 'Шан Сунг2',
        'издательство' => 'Дрофа2',
        'статус' => 'Выдано2',
        'класс' => '4Б2',
        'ФИО учащегося' => 'Ершова Полина Викторовна2'
    ]);

    $result = $collection->find(["статус"=>"Выдано2"])->toArray();

     foreach ($result as $entry) {
         echo $entry['наименование'] . "\n";
     }
 }
 else
 {
     echo "Проблемы с подключением";
 }
*/?>