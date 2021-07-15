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
            // $mapper = new Mapper\VenueMapper();
            // $venue  = new DomainModel\VenueModel(-1, "Какой-то текст");

            // d($venue);

            // $mapper->insert($venue);

            // $venue2 = $mapper->find($venue->getId());
            // d($venue2);

            // $venue2->setName("Изменённый текст");

            // $mapper->update($venue2);
            // $venue3 = $mapper->find($venue->getId());
            // d($venue3);


            $venue = new DomainModel\VenueModel(-1, "The Green Trees");
            $venue->addSpace(
                new DomainModel\SpaceModel(-1, 'The Space Upstairs')
            );
            $venue->addSpace(
                new DomainModel\SpaceModel (-1, 'The Bar Stage')
            );

            // Этот метод может быть вызван из контроллера
            // или вспомогательного класса

            //Контроллер высокого уровня обычно вызывает метод performOperations (),
            // и поэтому, как правило, достаточно создать или модифицировать объект, а класс
            // (в данном случае — ObjectWatcher), созданный по шаблону Unit of Work,
            // выполнит свои обязанности только один раз в конце запроса.
            IdentityMap\ObjectWatcher::getInstance()->performOperations();
        ?>
    </body>
</html>
