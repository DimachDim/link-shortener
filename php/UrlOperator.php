<?php

include './generateRandomString.php';    // Генератор случайной строки


class UrlOperator{
    
    // Данные подключения к базе данных
    private $host = "localhost";            
    private $db_name = "link_shortener";
    private $username = "root";
    private $password = "";
    public $conn;

    // Базовый домен
    private $domain = 'http://linl-shortener.com/';


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

    public function createUrl($longUrl, $userId = null)
    {
        try {
            
            // Оздаем короткий url
            $shortUrl = $this->domain . generateRandomString(6);

            // Стартуем подключения к базе данных
            $db = $this->dbConnection();
            
            // Если id пользователя передавали
            if($userId != null){
                // Создаем SQL запрос
                $query = "INSERT INTO urls SET long_url=:longUrl, short_url=:shortUrl, user_id = :userId";
            }else{
                // Создаем SQL запрос
                $query = "INSERT INTO urls SET long_url=:longUrl, short_url=:shortUrl";
            }
            // Подготавливаем запрос
            $stmt = $db->prepare($query);
    
            // Значения для вставки
            $stmt->bindValue(":longUrl", $longUrl);
            $stmt->bindValue(":shortUrl", $shortUrl);

            // Если id пользователя передавали
            if($userId != null){
                $stmt->bindValue(":userId", $userId);
            }

            // Если запись создалась
            if($stmt->execute()){
                // Возвращаем короткую ссылку
                return $shortUrl; 
            }
    
        }catch(PDOException $exception){
            echo "Error: " . $exception->getMessage();
        }
    
        return false; // Не удалось создать запись
    }

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
   
    public function createUser()
    {
        try {
            
            // Создаем ссылку пользователя
            $linkUser = $this->domain . generateRandomString(6, 'user');

            // Стартуем подключения к базе данных
            $db = $this->dbConnection();
    
            // Создаем SQL запрос
            $query = "INSERT INTO users SET link=:linkUser";
            // Подготавливаем запрос
            $stmt = $db->prepare($query);
    
            // Значения для вставки
            $stmt->bindValue(":linkUser", $linkUser);

            // Выполняем запрос
            if($stmt->execute()){
                // Возвращаем url пользователя
                return $linkUser; 
            }
    
        }catch(PDOException $exception){
            echo "Error: " . $exception->getMessage();
        }
    }

    public function getUserId($urlUser)
    {
        try {
            // Стартуем подключения к базе данных
            $db = $this->dbConnection();

            // готовим запрос SQL
            $sql = "SELECT * FROM users WHERE link = :urlUser";

            // Выполняем запрос      
            $result = $db->prepare($sql);
            $result->execute([':urlUser' => $urlUser]);

            // Прооверяем найдена ли записи
            if($result->rowCount() > 0)
            {
                return $result->fetch(PDO::FETCH_ASSOC)['id'];
            }else{
                return false;
            }
    
        }catch(PDOException $exception){
            echo "Error: " . $exception->getMessage();
        }
    }

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