<!DOCTYPE html>
<html>
<head>
    <title>Телефонный справочник</title>

    <!-- Подключение стилей -->
    <link rel="stylesheet" type="text/css" href="./Style.css">
</head>

<body">

<?php
    // Используем специальный класс для работы с базой url
    include 'UrlOperator.php';
    // Смотрим настрокий приложения
    include 'main.php';

    $userId = 0;    // Будет хранить id пользователя если он авторизован

    // Получаем url строку браузера
    $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    
    // Создаем экземпляр классса для работы с url и базой данных
    $UrlOperator = new UrlOperator;
    
    // Если в строке базовый url
    if($url == $BASE_URL)
    {
        // Отрисовываем форму для сокращения
        include './templates/formUrls.php';
        // Отрисовываем блок для авторизации
        include './templates/avtorization.php';
    

    // Если в строке не базовый url
    }else{
        // Получаем длинную ссылку по указанному url
        $response = $UrlOperator->getLongUrl($url);

        // Если есть такая сокращенная ссылка
        if($response)
        {
            // Перенаправляем на длинную ссылку
            header('Location: ' . $response);

        // Если нет такой сокращенной ссылки
        }else{
            
            // Проверяем есть ли пользователь с таким url
            $responseByUser = $UrlOperator->getUserId($url);
            
            // Если есть такой пользователь
            if($responseByUser)
            {
                // Запрашиваем его ссылки
                $urls = $UrlOperator->getUrls($responseByUser);
                $userId = $responseByUser;

                // Отрисовываем форму для сокращения
                include './templates/formUrls.php';
                // Отрисовываем список его ссылок
                include './templates/urlList.php';

            // Если нет такой ссылки и нет такого пользователя
            }else{
                echo 'Нет такой ссылки';
            }
        }
    }
    
?>


    <!--AJAX-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <!--Файл с скриптами-->
    <script type="text/javascript" src="./script.js"></script>
</body>
</html>