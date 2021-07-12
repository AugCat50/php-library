<?php
/**
 * Debug функция, выводит полученную информацию в удобном для чтения виде.
 * Так же выводит имя файла, строку, имя функции и путь к файлу, стек вызова
 * 
 * @param mixed     $value
 * @param bool|int  $exit
 * 
 * @return void
 */

if (! function_exists('d')) {

    function d($value = null, $exit = false)
    {
        

        echo '<table>';
        echo '<caption><h3>Cтек вызовов функций</h3></caption>';
        echo '<tr><th>File</th><th>Line</th><th> Function</th><th>Path</th></tr>';

        /**
         * Выводит стек вызовов функций в массив
         * @see https://www.php.net/manual/ru/function.debug-backtrace.php
         */
        $trace = debug_backtrace();
        $trace = array_reverse($trace);
        /**
         * Применяет заданную пользователем функцию к каждому элементу массива
         * @see https://www.php.net/manual/ru/function.array-walk.php
         */
        array_walk($trace, 'debugOut');

        echo '</table>';

        echo '<br><pre style="border:1px solid red">';
        echo '<br>';
        var_dump($value);
        // print_r($value);
        echo '</pre>';

        if($exit) exit;
    }

    function debugOut ($a)
    {
        echo '<tr><td><b>'. basename( $a['file'] ). '</b></td>'
            . '<td><span style="color: red">('. $a['line']. ')</span></td>'
            . '<td><span style="color: green"> '. $a['function']. '</span></td>'
            . '<td>---<span style="color: blue">'. dirname($a['file']). '</span></td></tr>';
    }

}
