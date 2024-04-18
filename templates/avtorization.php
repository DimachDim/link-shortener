<h2>Авторизация</h2>

<p>Нажмите "авторизация", и вам будет сгенерирована ссылка. 
    Сохраните ссылку, и в дальнейшем, переходя по ней, вам будет доступен список ваших ссылок.</p>
<button onclick="urlOperator.createUser()" class="btn btn-primary btn-avtorization">Авторизация</button>
<input value="" id="user-input" class="form-control">
<button class="btn btn-primary" id="copy-short-user-url" onclick="urlOperator.copyUrl('user-input')">Копировать</button>