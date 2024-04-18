

<h1>Сократитель ссылок</h1>

<!-- Форма добавления нового url -->
<form  id='form-add-url'>

    <label for="long-url" class="form-label">Укажите длинную ссылку</label><br>
    <input class="form-control" type="text" id="long-url" name="long-url"><br>
            
    <input 
        class="btn btn-primary"
        type="submit" 
        value="Создать короткую ссылку" 
        class="btn-add" 
        onclick="clickCreateUrl()"
    >
</form>

<div class="short-url-container">
    <label for="long-url" class="form-label">Короткая ссылка</label>
    <input id='short-url' class="form-control"/>
    <button class="btn btn-primary" id="copy-short-url" onclick="urlOperator.copyUrl('short-url')">Копировать</button>
</div>

<script>
    // Функция вызывается при клике "Создатьссылку"
    function clickCreateUrl(){
        // Извлекаем id пользователя
        const userId = <?php echo $userId;?>;
        // Обрабатываем создание новой ссылки
        urlOperator.subForm('form-add-url', userId);

        // Если есть пользователь
        if(userId){
            // Получаем ссылки пользователя
            urlOperator.getUrls(userId);
        }
    }
</script>

