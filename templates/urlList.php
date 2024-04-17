<h2>Список ссылок</h2>

<ul id="url-list" >
    
</ul>

<script>
    // Событие DOMContentLoaded срабатывает, когда DOM готов и весь HTML разобран
    document.addEventListener("DOMContentLoaded", function() {
        urlOperator.getUrls(<?php echo $userId; ?>)
    });
</script>