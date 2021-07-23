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
            $assembler  = new DomainObjectAssembler\DomainObjectAssembler('UserText');
            $assembler1 = new DomainObjectAssembler\DomainObjectAssembler('User');
            $assembler2 = new DomainObjectAssembler\DomainObjectAssembler('DefaultText');
            // $assembler->delete(5);

            $identityObject = $assembler->getIdentityObject()
                                                ->field('id')
                                                ->eq('4')
                                                // ->field('id')
                                                // ->eq(4)
                                                ;
            // //
            $identityObject1 = $assembler1->getIdentityObject()
            ->field('id')
            ->eq('4');

            $identityObject2 = $assembler2->getIdentityObject()
            ->field('id')
            ->eq('11');

            $model  = $assembler->findOne($identityObject);
            $model1 = $assembler1->findOne($identityObject1);
            $model2 = $assembler2->findOne($identityObject2);


            $model->setName("new Name ИО");
            // $model1->setName("Вася ИО");
            $model2->setName("Шото ИО");

            // // d($model);
            $assembler->update($model);
            // $assembler1->update($model1);
            $assembler2->update($model2);

            $assembler1->delete($model1);

            $model3 = $assembler1->createNewModel([
                    // 'id'       => -1,
                    'name'     => "Вася1",
                    'password' => "password1",
                    'solt'     => random_bytes(10),
                    'mail'     => "text@mail.test1"
                ]);

            $model4 = $assembler1->createNewModel([
                // 'id'       => -1,
                'name'     => "Вася3",
                'password' => "password3",
                'solt'     => random_bytes(10),
                'mail'     => "text@mail.test3"
            ]);
            d($model3);
            d($model4);

            // $model4 = new DomainObjectAssembler\DomainModel\UserModel(-1, "Вася3", "password3", random_bytes(10), "text@mail.test3");

            // $assembler2->insert($model3);
            // $assembler2->insert($model4);

            // $q = $model->getAssembler();
            // d($q);
            // // $model = new DomainObjectAssembler\DomainModel\UserTextModel(-1, 1, 2, "Имя новый пользовательский текст", "Содержание Новый пользовательский текст");
            // // $model = new DomainObjectAssembler\DomainModel\TempModel(-1, 3, 'ewfewfew', 'exemple@mail.test');
            // // $model = new DomainObjectAssembler\DomainModel\DefaultTextModel(-1, "Имя новый текст2", "Содержание Новый текст2", true);
            // // $model = new DomainObjectAssembler\DomainModel\UserThemeModel(-1, 1, "Содержание Новый текст2");
            // $assembler->update($model);

            // $model1 = $assembler->findOne($identityObject);
            // d($model1);

            // $assembler->delete($model);

            $objectWatcher = DomainObjectAssembler\IdentityMap\ObjectWatcher::getInstance();
            d($objectWatcher);
            $objectWatcher->performOperations();
            d($objectWatcher);
        ?>
    </body>
</html>
