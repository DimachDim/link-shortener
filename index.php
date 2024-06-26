<!DOCTYPE html>
<html>
<head>
    <title>Сократитель ссылок</title>
    <!-- Подключение бутстрап -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- Подключение стилей -->
    <link rel="stylesheet" type="text/css" href="./Style.css">
</head>

<body>

<?php
    // Используем классы для обработки логики
    include './php/LinkCreation.php';
    include './php/LinkProcessing.php';
    include './php/Authorization.php';

    // Смотрим настрокий приложения
    require_once  'main.php';

    $userId = 0;    // Будет хранить id пользователя если он авторизован


    // Получаем url строку браузера
    $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    

    // Создаем экземпляры классов для обработки логики
    $LinkCreation = new LinkCreation;
    $LinkProcessing = new LinkProcessing;
    $Authorization = new Authorization;
    
    // Если в строке базовый url
    if($url == BASE_DOMAIN)
    {

        // Отрисовываем форму для сокращения
        include './templates/formUrls.php';
        // Отрисовываем блок для авторизации
        include './templates/avtorization.php';
    
    // Если в строке не базовый url
    }else{
        // Получаем длинную ссылку по указанному url
        $response = $LinkProcessing->getLongUrl($url);

        // Если есть такая сокращенная ссылка
        if($response)
        {
            // Добавляем +1 к посещению ссылки
            $LinkProcessing->incrementCountUrl($url);
            // Перенаправляем на длинную ссылку
            header('Location: ' . $response);

        // Если нет такой сокращенной ссылки
        }else{
            
            // Проверяем есть ли пользователь с таким url
            $responseByUser = $Authorization->getUserId($url);
            
            // Если есть такой пользователь
            if($responseByUser)
            {
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
    <script type="text/javascript" src="./js/script.js"></script>
</body>
</html>