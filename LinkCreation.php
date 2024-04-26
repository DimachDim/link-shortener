<?php

include './generateRandomString.php';    // Генератор случайной строки
require_once  './main.php';                // Смотрим настрокий приложения

class LinkCreation{
    
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

    // Создает короткий url
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
}