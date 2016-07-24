<?php

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 23.07.16
 * Time: 19:36
 */
class CMain
{
    /**
     * @var null
     */
    static $inst = null;

    /**
     * @var CDb|null
     */
    static $db = null;

    /**
     * CMain constructor.
     */
    private function __construct ()
    {
        self::$db = new CDb();
    }

    /**
     * @return CMain|null
     */
    static public function getInstance()
    {
        if (self::$inst == null)
        {
            self::$inst = new CMain();
        }

        return self::$inst;
    }

    /**
     * @return CDb|null
     */
    public function getDb()
    {
        return self::$db;
    }
}