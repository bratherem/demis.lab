<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 22.07.16
 * Time: 9:13
 */

require_once "init.php";

try {
//    Создание подключения к базе данных
    $db = new CDb();

    if ($db->errorCode())
    {
//        Если данные для подлючения были не верны, исключение
        throw new Exception($db->errorInfo());
    }

    // Указание файла на сервере
    $fileSQL = $_SERVER["DOCUMENT_ROOT"] . DIRECTORY_SEPARATOR . "db" . DIRECTORY_SEPARATOR . "install.sql";

    if (($res = $db->installFromFile($fileSQL)) === true)
    {
//        Файл найден, установка прошла корректно
        echo "Установка завершена успешно";
    }
    else
    {
//        На случай ошибки PDO выбросим исключение
        var_export( $res );
    }

}
catch (Exception $err)
{
//    Останавливаем выполнение скрипта, отображаем ошибку
    die($err);
}
