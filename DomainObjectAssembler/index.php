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
            $assembler->delete(5);

            // $identityObject = $assembler->getIdentityObject()
            //                                     ->field('id')
            //                                     ->eq('3')
            //                                     // ->field('id')
            //                                     // ->eq(4)
            //                                     ;
            // //

            // $model = $assembler->findOne($identityObject);
            // // $model = $assembler->find($identityObject);
            // // d($model);

            // $model->setName("new Name");
            // $model->setPassword('newPass');
            // $model->setSolt(random_bytes(10));
            // $model->setMail('example@mail.test');
            // // d($model);

            // $model = new DomainObjectAssembler\DomainModel\UserModel(6, "Вася1", "password1", random_bytes(10), "text@mail.test1");
            // // $model = new DomainObjectAssembler\DomainModel\UserTextModel(-1, 1, 2, "Имя новый пользовательский текст", "Содержание Новый пользовательский текст");
            // // $model = new DomainObjectAssembler\DomainModel\TempModel(-1, 3, 'ewfewfew', 'exemple@mail.test');
            // // $model = new DomainObjectAssembler\DomainModel\DefaultTextModel(-1, "Имя новый текст2", "Содержание Новый текст2", true);
            // // $model = new DomainObjectAssembler\DomainModel\UserThemeModel(-1, 1, "Содержание Новый текст2");
            // $assembler->update($model);

            // $model1 = $assembler->findOne($identityObject);
            // d($model1);
        ?>
    </body>
</html>
