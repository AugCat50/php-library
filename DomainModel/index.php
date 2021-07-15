<!DOCTYPE html>
<html lang="ru" class="html">
    <head>
        <meta charset="utf-8">
        <title>Domain Model</title>
    </head>
    
    <body class="body">
        <?php
            //Подключение дебаг функции
            require_once('functions/d.php');
            //Подключение автозагрузчика
            require_once('autoload.php');
            //Точка входа
            // $mapper = new Mapper\VenueMapper();
            // $venue  = new DomainModel\VenueModel();

            //Тестирование IdentityMap
            $mapper = new Mapper\VenueMapper();
            $venue  = new DomainModel\VenueModel(-1, "Какой-то текст");

            d($venue);

            $mapper->insert($venue);

            $venue2 = $mapper->find($venue->getId());
            d($venue2);

            $venue2->setName("Изменённый текст");

            $mapper->update($venue2);
            $venue3 = $mapper->find($venue->getId());
            d($venue3);
        ?>
    </body>
</html>
