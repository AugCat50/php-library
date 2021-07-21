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

            $model = $assembler->findOne($identityObject);
            d($model);
            $model->setName('Изменённый имя текста');
            $model->setText('Изменённый текст. Изменённый текст. Изменённый текст. Изменённый текст. Изменённый текст. Изменённый текст. Изменённый текст. Изменённый текст. Изменённый текст. Изменённый текст. Изменённый текст. Изменённый текст. ');
            d($model);

            // $model = new DomainObjectAssembler\DomainModel\UserModel(123, "Вася", "password", "dsfdsw23e23", "text@mail.test");
            // $model = new DomainObjectAssembler\DomainModel\UserTextModel(-1, 1, 2, "Имя новый пользовательский текст", "Содержание Новый пользовательский текст");
            $assembler->update($model);

            $model1 = $assembler->findOne($identityObject);
            d($model1);
        ?>
    </body>
</html>
