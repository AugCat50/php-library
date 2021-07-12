<!DOCTYPE html>
<html lang="ru" class="html">
    <head>
        <meta charset="utf-8">
        <title>Front Controller</title>
    </head>
    
    <body class="body">
        <?php
            //Подключение дебаг функции
            require_once('functions/d.php');
            //Подключение автозагрузчика
            require_once('autoload.php');
            //Точка входа
            $mapper = new Mapper\VenueMapper();
            $venue  = $mapper->find(1);
            d($venue);
        ?>
    </body>
</html>
