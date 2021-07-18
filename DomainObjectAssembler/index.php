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
                                                ->eq('Земля');
                                                
            d($identityObject->getObjectFields(), 1);

            $assembler->find($identityObject);
            d($identityObject);

            // $idobj = $factory->getldentity0bject()
            //                         ->field(' name')
            //                         ->eq('The Eyeball Inn');
            // $collection = $finder->find($idobj);
            // foreach ($collection as $venue) {
            // print $venue->getName()."\n";
        ?>
    </body>
</html>
