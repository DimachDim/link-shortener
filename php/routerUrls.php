<?php


// Используем классы для обработки логики
include_once(__DIR__ . DIRECTORY_SEPARATOR .'./LinkCreation.php');
include './LinkProcessing.php';
include './Authorization.php';


// Создаем экземпляры классов для обработки логики
$LinkCreation = new LinkCreation;
$LinkProcessing = new LinkProcessing;
$Authorization = new Authorization;

$action = $_GET['action'];                  // action
$response = null;                           // Хранит ответ

// Смотрим экшен
switch($action)
{
    // СОЗДАНИЕ короткой ссылки
    case 'createUrl':
        // Достаем данные
        $longUrl = $_GET['longUrl'];
        $userId = $_GET['userId'];

        // Отдаем методу
        $response = $LinkCreation->createUrl($longUrl, $userId);
        break;

    
    // СОЗДАНИЕ пользователя
    case 'createUser':
        // Вызываем метод создания пользователя и сохраняем ответ
        $response = $Authorization->createUser();
        break;
    
    
    // ПОЛУЧЕНИЕ списка url
    case 'getUrls':
        // Достаем данные
        $userId = $_GET['userId'];

        // Отдаем методу
        $response = $LinkProcessing->getUrls($userId);
        break;
}

// Кодируем ответ в json
$jsonData = json_encode($response);
// Передаем
echo $jsonData;
