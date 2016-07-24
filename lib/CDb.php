<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 22.07.16
 * Time: 9:14
 */

class CDb extends PDO
{
    /* Drive to MySQL */
    const DB_DRIVE = "mysql";

    /* Host to Server DB */
    const DB_HOST = "localhost";

    /* User MySQL Server */
    const DB_USER = "yaemyandex";

    /* Password MySQL User */
    const DB_PASSWORD = "123QWE!@#";

    /* DataBase Name */
    const DB_NAME = "yaemyandex";

    /**
     * CDb constructor.
     */
    public function __construct ()
    {
        $dns = static::DB_DRIVE . ':host=' . static::DB_HOST . ';dbname=' . static::DB_NAME;

        parent::__construct ($dns, static::DB_USER, static::DB_PASSWORD);
    }

    /* Get content from file .sql to install */
    /**
     * @param $file
     * @return array|bool
     * @throws Exception
     */
    public function installFromFile ($file)
    {
        if (file_exists ($file))
        {
            $contentDB = file_get_contents ($file);

            $this->exec ($contentDB);

            if ($this->errorCode() > 0)
            {
                return $this->errorInfo ();
            }
            else
            {
                return true;
            }
        }
        else
        {
            throw new Exception("Файл не существует!");
        }
    }
}