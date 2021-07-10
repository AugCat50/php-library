<!DOCTYPE html>
<html lang="ru" class="html">
    <head>
        <meta charset="utf-8">
        <!-- <meta name="description" content="Тренажёр слепого набора">
        <meta name="keywords" content="Keyburner, Тренажёр слепого набора">
        <meta name="autor" content="draackul2@gmail.com"> -->
<!--        <meta name="viewport" content="width=device-width, initial-scale=1.0">-->
        <title>Front Controller</title>
        
        <!-- <link rel="shortcut icon" type="image/svg" href="img/favicon.jpg"/>
        <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&display=swap&subset=cyrillic" rel="stylesheet"> 
        
        <link rel="stylesheet" href="normalize/normalize.css">
        
        <link rel="stylesheet" href="common.blocks/body/body.css">
        
        <link rel="stylesheet" href="common.blocks/main-wrapper/main-wrapper.css">
        <link rel="stylesheet" href="common.blocks/main-header/main-header.css">
        <link rel="stylesheet" href="common.blocks/main/main.css">
        <link rel="stylesheet" href="common.blocks/statistics-section/statistics-section.css">
        <link rel="stylesheet" href="common.blocks/default-text-list/default-text-list.css">
        
        <link rel="stylesheet" href="common.blocks/footer/footer.css">
        
        <link rel="stylesheet" href="common.blocks/html/html.css">
        <link rel="stylesheet" href="common.blocks/neon/neon.css">
        
        <link rel="stylesheet" href="common.blocks/button/button.css">
        
        <link rel="stylesheet" href="common.blocks/dialog/dialog.css">
        
        <script src="js/jquery-3.5.1.min.js"></script>
        <script src="js/textarea_autosize.js"></script>
        <script src="js/main.js"></script>
        <script src="js/log_in.js"></script>
        <script src="js/check_in.js"></script> -->
<!--        <script src="js/default_text.js"></script>-->
    </head>
    
    <body class="body">
        <?php
            //Подключение дебаг функции
            require_once('functions/d.php');
            //Подключение автозагрузчика
            require_once('autoload.php');
            //Точка входа
            FrontController::run();
        ?>
    </body>
</html>
