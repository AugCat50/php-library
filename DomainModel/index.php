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

            //Тестирование зависимости venue -> space
            // $venue = new DomainModel\VenueModel(-1, "The Green Trees111");
            // $venue->addSpace(
            //     new DomainModel\SpaceModel(-1, 'The Space Upstairs2222', -1)
            // );
            // $venue->addSpace(
            //     new DomainModel\SpaceModel (-1, 'The Bar Stage333', -1)
            // );

            // //тестирование зависимости space -> event
            // $mapper = new Mapper\VenueMapper();
            // $venue1 = $mapper->find(4);
            // $venue = new DomainModel\SpaceModel(-1, "The Green Trees", $venue1->getId(), $venue1);
            // $venue->addEvent(
            //     new DomainModel\EventModel(-1, 'Старт1', 111, 'Событие1', -1)
            // );
            // $venue->addEvent(
            //     new DomainModel\EventModel (-1, 'Старт2', 123, 'Событие2', -1)
            // );

            // Этот метод может быть вызван из контроллера
            // или вспомогательного класса

            //Контроллер высокого уровня обычно вызывает метод performOperations (),
            // и поэтому, как правило, достаточно создать или модифицировать объект, а класс
            // (в данном случае — ObjectWatcher), созданный по шаблону Unit of Work,
            // выполнит свои обязанности только один раз в конце запроса.
            // IdentityMap\ObjectWatcher::getInstance()->performOperations();


            // $mapper = new Mapper\SpaceMapper();
            // $spece  = $mapper->find(2);
            // // d($spece);
            // $events = $spece->getEvents();
            // d($events);

            // $f = new DomainObjectFactory\SpaceObjectFactory();
            // d($f instanceof DomainObjectFactory\DomainObjectFactory);
            // $mapper = new Mapper\VenueMapper();
            // $obj = $mapper->find(1);
            // d($obj->getSpaces());

                //Вызов метода field () приводит к созданию объекта типа Field и присвоению ссылки на него свойству $currentfield.
            // $idobj = new IdentityObject\IdentityObject();
            // $idobj ->field ("name")
            // ->eq("The Good Show");

            // d($idobj);

            // $vuf = new UpdateFactory\VenueUpdateFactory();
            // print_r($vuf->newUpdate(new DomainModel\VenueModel(2, "The Happy Hairband")));

            // $vio = new VenueIdentityObject();
            // $vio->field("name")->eq("The Happy Hairband");
            // $vsf = new VenueSelectionFactory();
            // print_r($vsf->newSelection($vio));
        ?>
    </body>
</html>
