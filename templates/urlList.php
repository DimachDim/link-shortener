<h2>Список ссылок</h2>

<ul>
    <?php foreach($urls as $url): ?>
        
        <li>
            <div><?php echo $url['short_url'] ?></div>
            <div><?php echo $url['count'] ?></div>
            <div><?php echo $url['long_url'] ?></div>
        </li>
        
    <?php endforeach; ?>
</ul>