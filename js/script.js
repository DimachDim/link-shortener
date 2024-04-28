 


class UrlOperator{
    constructor(){
        this.url = './php/routerUrls.php'      // Место роутера
        this.eventAdded = false;            // Для фикса бага множественного срабатывания создания url
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

    // Обрабатывает отправку формы ! dom
    subForm(idForm, userId = null){
        // idForm - id формы

        // Для фикса бага множественного срабатывания создания url
        if (this.eventAdded) {
            return; // Если событие уже добавлено, просто выходим
        }

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
        
        // Для фикса бага множественного срабатывания создания url
        this.eventAdded = true; // Указываем, что событие уже добавлено

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

    // Метод передает текст в input с указанным классом. ! dom
    fillInput(idInput, text=''){

        // Получаем input по id
        let input = document.getElementById(idInput);
        // Передаем ему содержимое
        input.value = text;
    }

    // Метод копирует содержимое элемента id которого указан ! dom
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

    // Создание нового пользователя на сервере ! user
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

    // Получить список ссылок пользователя ! user
    getUrls(userId){
        // Формируем данные для отправки
        const data = {
            action: 'getUrls',
            userId: userId
        }

        // Делаем ajax запрос
        this.ajax(data, (response)=>{
            // Добавляем полученные ссылки в список

            // Парсим ответ сервера
            const arrDataUrls = JSON.parse(response);
            // Ссылаемся на созданный список
            const ul = document.getElementById('url-list');
            // Очищаем от старых данных
            ul.innerHTML ='';
            
            // Перебираем массив данных
            arrDataUrls.forEach(element => {
                // Создаем элемент списка
                let li = document.createElement('li');
                li.setAttribute('class', 'list-group-item')
                // Создаем ссылку
                let a = document.createElement('a');
                // Добавляем ей атрибутов
                a.setAttribute('href', element.short_url);
                a.textContent = element.short_url;
                a.setAttribute('target', '_blank');

                // Добавляем содержимое в li
                li.innerHTML = 
                `   <div>${a.outerHTML}</div>
                    <div>${element.count != null ? element.count : ''}</div>
                    <div>${element.long_url}</div>
                `
                // Добавляем элемент в список
                ul.appendChild(li);
            });
        })
    }
        
}

// Создаем экземпляр
let urlOperator = new UrlOperator;