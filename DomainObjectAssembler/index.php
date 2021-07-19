<!DOCTYPE html>
<html lang="ru" class="html">
    <head>
        <meta charset="utf-8">
        <title>DomainObjectAssembler</title>
    </head>
    
    <body class="body">
        <?php


            //Подключение дебаг функции
            require_once('DomainObjectAssembler/functions/d.php');
            //Подключение автозагрузчика
            require_once('autoload.php');

            $reg = DomainObjectAssembler\Registry\Registry::getInstance();
            $reg->setPdo();

            //Точка входа
            $assembler      = new DomainObjectAssembler\DomainObjectAssembler('DefaultText');
            
            $identityObject = $assembler->getIdentityObject()
                                                ->field('name')
                                                ->eq('Земля')
                                                // ->field('id')
                                                // ->eq(4)
                                                ;
                    
            //ниже
            // $w = new DomainObjectAssembler\Factories\DomainObjectFactory\DefaultTextObjectFactory();
            // $r = $w instanceof DomainObjectAssembler\Factories\DomainObjectFactory\DomainObjectFactory;
            // d($r ,1);
            // d($identityObject,1);
            //  d($identityObject->getObjectFields(), 1);

            $data = $assembler->findOne($identityObject);
            d($data);
            $data2 = $assembler->findOne($identityObject);
            d($data2);
            // $idobj = $factory->getldentity0bject()
            //                         ->field(' name')
            //                         ->eq('The Eyeball Inn');
            // $collection = $finder->find($idobj);
            // foreach ($data  as $venue) {
            //     print $venue->getName()."\n";
            // }
                // $we = $data->current();
                // d($we);
        ?>
    </body>
</html>
