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
            $assembler      = new DomainObjectAssembler\DomainObjectAssembler('UserText');
            
            $identityObject = $assembler->getIdentityObject()
                                                ->field('id')
                                                ->eq('2')
                                                // ->field('id')
                                                // ->eq(4)
                                                ;
            //

            $data = $assembler->findOne($identityObject);
            d($data);
        ?>
    </body>
</html>
