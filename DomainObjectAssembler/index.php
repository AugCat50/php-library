<!DOCTYPE html>
<html lang="ru" class="html">
    <head>
        <meta charset="utf-8">
        <title>DomainObjectAssembler</title>
    </head>
    
    <body class="body">
        <?php
            //Подключение дебаг функции


require_once('functions/d.php');
            //Подключение автозагрузчика
            require_once('autoload.php');

            $reg = Registry\Registry::getInstance();
            $reg->setPdo();

            //Точка входа
            $finder = new Assembler\DomainObjectAssembler('DefaultText');
        ?>
    </body>
</html>
