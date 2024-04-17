 


class UrlOperator{
    constructor(){
        this.url = './routerUrls.php'      // Место роутера
    }

    // Делает запросы на сервер. data - данные для сервера, callback - функция обрабатывающая ответ сервера
    ajax(data, callback){
        $.ajax({
            url: this.url,    // путь к роутеру
            type: 'GET',
            data: data,
            success: function(response){
                callback(response)
            }
        });
    }

    // Обрабатывает отправку формы
    subForm(idForm, userId = null){
        // idForm - id формы

        const form = document.getElementById(idForm);       // Получаем форму по id
        

        form.addEventListener('submit', (event)=>{
            // Отменяем отправку для исключения обнавления
            event.preventDefault();

            // Достаем длинный url
            const longUrl = form.querySelector('#long-url').value;

            // Если передан 0 id пользователя
            if(userId == 0){
                // Отправляем длинный url
                this.createUrl(longUrl);
            }else{
                // Отправляем длинный url и id пользователя
                this.createUrl(longUrl, userId);
            }
        })

    }

    // Создает короткий url.
    createUrl(longUrl, userId = null){
        // longUrl - длинная ссылка

        // Формируем данные для отправки ajax запроса
        const data = {
            action: 'createUrl',
            longUrl: longUrl,
            userId: userId
        }

        // отправляем данные методу с обрабатывающему ajax
        this.ajax(data, (response)=>{
            // Эта функция выполняется при ответе сервера

            // Убираем лишние символы
            response = response.replace(/\\/g, '').replace(/"/g, '')
            
            // Передаем содержимое в input
            this.fillInput('short-url', response)
        })
        
    }

    // Метод передает текст в input с указанным классом.
    fillInput(idInput, text=''){

        // Получаем input по id
        let input = document.getElementById(idInput);
        // Передаем ему содержимое
        input.value = text;
    }

    // Метод копирует содержимое элемента id которого указан
    copyUrl(id){
        // Получаем элемент по id
        const element = document.getElementById(id);
        const text = element.value;
        
        // Создаем временный текстовый элемент для копирования текста
        const tempTextArea = document.createElement("textarea");
        tempTextArea.value = text;
        document.body.appendChild(tempTextArea);
        tempTextArea.select();

        // Пытаемся скопировать текст в буфер обмена
        document.execCommand('copy');        
        // Удаляем временный текстовый элемент после копирования
        document.body.removeChild(tempTextArea);
    }

    // Создание нового пользователя на сервере
    createUser(){
        // Формируем данные для отправки ajax запроса
        const data = {
            action: 'createUser'
        }
        // Делаем ajax запрос
        this.ajax(data, (response)=>{
            // Функция обрабатывает ответ сервера

            // Убираем лишние символы из ответа
            response = response.replace(/\\/g, '').replace(/"/g, '')
            // Вставляем текст в input
            this.fillInput('user-input', response)
        })
    }
        
}

// Создаем экземпляр
let urlOperator = new UrlOperator;