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
            // $venue  = $mapper->find(2);
            // d($venue);

            // $mapper = new Mapper\VenueMapper();
            // $venue = new VenueModel(-1, "The Likey Lounge");
            // // Добавим объект в базу данных
            // $mapper->insert($venue) ;
            // // Снова найдем объект - просто для проверки, что все работает!
            // $venue = $mapper->find($venue->getId());
            // d($venue);
            // // Внесем изменение в найденный объект
            // $venue->setName("The Bibble Beer Likey Lounge");

            // // Вызовем операцию обновления измененных данных
            // $mapper->update($venue);
            // // И снова обратимся к базе данных, чтобы проверить, что все работает
            // $venue = $mapper->find($venue->getId());
            // d($venue);


            //Тест для коллекций
            // $reg        = Registry\Registry::getInstance ();
            // $collection = $reg->getVenueCollection();
            // $collection->add(new VenueModel(-1, "Loud and Thumping"));
            // $collection->add(new VenueModel(-1, "Eeezy"));
            // $collection->add(new VenueModel(-1, "Duck and Badger"));
            // foreach ($collection as $venue) {
            //     print $venue->getName() . "<br>";
            //     // d($venue->getName());
            // }

            // $sm = new Mapper\SpaceMapper();
            // $sm->findByVenue(2);

            // $mapper = new Mapper\VenueMapper();
            // $venue  = new DomainModel\VenueModel(-1, "The Likey Lounge");
            // $mapper->insert($venue);
            // $venue  = $mapper->find($venue->getld());
            // print_r($venue);
        ?>
    </body>
</html>
