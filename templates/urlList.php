<h2>Список ссылок</h2>

<ul class="list-group list-head" >
    <li class="list-group-item">
        <div>Короткая ссылка</div>
        <div>Переходы</div>
        <div>Длинная ссылка</div>
    </li>
</ul>

<ul id="url-list" class="list-group" >
    
</ul>

<script>
    // Событие DOMContentLoaded срабатывает, когда DOM готов и весь HTML разобран
    document.addEventListener("DOMContentLoaded", function() {
        urlOperator.getUrls(<?php echo $userId; ?>)
    });
</script>