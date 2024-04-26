<?php

include './generateRandomString.php';    // Генератор случайной строки
require_once  './main.php';                // Смотрим настрокий приложения

class Authorization{
    
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

    // Создает ссылку для нового пользоваьтеля
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

    // Получает id пользователя в базе по url пользователя 
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
}