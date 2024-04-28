<?php

include_once(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'main.php');  // Смотрим настрокий приложения

class LinkProcessing{
    
    // Данные подключения к базе данных
    private $host = DB_HOST;            
    private $db_name = DB_NAME;
    private $username = DB_USER;
    private $password = DB_PASS;
    public $conn;

    // Базовый домен
    private $domain = BASE_DOMAIN;

    // Для подключения к базе
    public function dbConnection()
    {
        $this->conn = null;    
        try{
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }

    // Получает длинный url по короткому url
    public function getLongUrl($shortUrl)
    {
        try {
            // Стартуем подключения к базе данных
            $db = $this->dbConnection();

            // готовим запрос SQL
            $sql = "SELECT * FROM urls WHERE short_url = :shortUrl LIMIT 1";

            // Выполняем запрос      
            $result = $db->prepare($sql);
            $result->execute([':shortUrl' => $shortUrl]);

            // Прооверяем найдена ли записи
            if($result->rowCount() > 0)
            {
                return $result->fetch(PDO::FETCH_ASSOC)['long_url'];
            }else{
                return false;
            }
    
        }catch(PDOException $exception){
            echo "Error: " . $exception->getMessage();
        }
    }
    
    // Получает все записи о ссылках по id пользователя 
    public function getUrls($userId)
    {
        try {
            // Стартуем подключения к базе данных
            $db = $this->dbConnection();

            // готовим запрос SQL
            $sql = "SELECT * FROM urls WHERE user_id = :userId";

            // Выполняем запрос      
            $result = $db->prepare($sql);
            $result->execute([':userId' => $userId]);

            // Прооверяем найдена ли записи
            if($result->rowCount() > 0)
            {
                // Отправляем отзеркаленный список
                return array_reverse($result->fetchAll(PDO::FETCH_ASSOC));
            }else{
                return false;
            }
    
        }catch(PDOException $exception){
            echo "Error: " . $exception->getMessage();
        }
    }

    // Увеличивает поле count в базе на 1
    public function incrementCountUrl($shortUrl)
    {
       try {
            // Стартуем подключения к базе данных
            $db = $this->dbConnection();

            // готовим запрос SQL
            $sql = "SELECT * FROM urls WHERE short_url = :shortUrl";

            // Выполняем запрос      
            $result = $db->prepare($sql);
            $result->execute([':shortUrl' => $shortUrl]);

            // Если запись найдена в БД
            if($result->rowCount() > 0)
            {
                // Смотрим значение счетчика и увеличиваем на 1
                $newCount = $result->fetch(PDO::FETCH_ASSOC)['count']+1;
                // Готовим запрос
                $sqlUpdate = "UPDATE urls SET count = :newCount WHERE short_url = :shortUrl";
                $updateStmt = $db->prepare($sqlUpdate);

                // выполняем запрос
                $updateStmt->execute([':newCount' => $newCount, ':shortUrl' => $shortUrl]);

            }else{
                return false;
            }
    
        }catch(PDOException $exception){
            echo "Error: " . $exception->getMessage();
        } 
    }
}