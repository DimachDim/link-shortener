<?php

// Используем специальный класс для работы с контактами
include 'UrlOperator.php';


$UrlOperator = new UrlOperator;             // Экземпляр класса
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
        $response = $UrlOperator->createUrl($longUrl, $userId);
        break;

    
    // СОЗДАНИЕ пользователя
    case 'createUser':
        // Вызываем метод создания пользователя и сохраняем ответ
        $response = $UrlOperator->createUser();
        break;
    
    
    // ПОЛУЧЕНИЕ списка url
    case 'getUrls':
        // Достаем данные
        $userId = $_GET['userId'];

        // Отдаем методу
        $response = $UrlOperator->getUrls($userId);
        break;
}

// Кодируем ответ в json
$jsonData = json_encode($response);
// Передаем
echo $jsonData;
