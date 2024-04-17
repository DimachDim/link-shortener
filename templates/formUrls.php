

<h1>Сократитель ссылок</h1>

<!-- Форма добавления нового url -->
<form  id='form-add-url'>

    <label for="long-url">Укажите длинный URL</label><br>
    <input type="text" id="long-url" name="long-url"><br>
            
    <input type="submit" value="Создать короткую ссылку" class="btn-add" onclick="urlOperator.subForm('form-add-url', <?php echo $userId;?>)">
</form>

<div class="short-url-container">
    <label for="long-url">Короткая ссылка</label>
    <input id='short-url'/>
    <button id="copy-short-url" onclick="urlOperator.copyUrl('short-url')">Копировать</button>
</div>

