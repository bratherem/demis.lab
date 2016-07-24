<?php

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 23.07.16
 * Time: 19:35
 */
class CCalculation
{
    /** Prefix table
     * @var string
     */
    private $prefix = 'tbl_';

    /** Table Name
     * @var string
     */
    private $table = 'calculation';

    /**
     * @var array
     */
    private $err = array();

    /**
     * @var array
     */
    private $secretCode = array();

    /**
     * @var null
     */
    private $lastID = null;

    /**
     * CCalculation constructor.
     */
    public function __construct ()
    {
        $this->db = self::getDB();
        $this->SC = new CSecretCode();
    }

    /**
     * @return CDb|null
     */
    private static function getDB()
    {
        return CMain::getInstance()->getDb();
    }

    /**
     * @param $data
     * @return false|array
     */
    public function setAddData ($data)
    {
        $sql = "INSERT INTO " . $this->prefix . $this->table . " ";

        if (is_array($data) === false)
        {
            $this->errors("Пустые данные");
        }

        $this->parseData($data['data']);

        $sql .= "(TIMESTAMP, NAME, CALCULATION_DATA) ";
        $sql .= "VALUES (NOW(), :NAME, :DATA);";

        $shm = $this->db->prepare($sql);
        $shm->bindParam(':NAME', $data['name']);
        $shm->bindParam(':DATA', $data['data']);
        $res = $shm->execute();

        if ($res == false)
            return $shm->errorInfo();

        $this->lastID = $this->db->lastInsertId();

        if (!$this->lastID)
            return false;

        return $this->SC->setSecretCode($this->secretCode, $this->lastID);
    }

    /**
     * @return null|string
     */
    public function getLastID ()
    {
        return $this->lastID;
    }

    /**
     * @param $secretCode
     * @return array|string
     */
    public function getSearchData ($secretCode)
    {
        if (empty($secretCode)) return "Пустой запрос";

        if(strpos($secretCode, ">=") !== false)
        {
            $sc = ltrim($secretCode, ">=") * 1;
            $where = 'WHERE tsc.CODE >= ' . $sc;
        }
        else if (strpos($secretCode, "<=") !== false)
        {
            $sc = ltrim($secretCode, ">=") * 1;
            $where = 'WHERE tsc.CODE <= ' . $sc;
        }
        else if (strpos($secretCode, ">") !== false)
        {
            $sc = ltrim($secretCode, ">") * 1;
            $where = 'WHERE tsc.CODE > ' . $sc;
        }
        else if (strpos($secretCode, "<") !== false)
        {
            $sc = ltrim($secretCode, "<") * 1;
            $where = 'WHERE tsc.CODE < ' . $sc;
        }
        else if (strpos($secretCode, "!") !== false)
        {
            $sc = ltrim($secretCode, "!") * 1;
            $where = 'WHERE tsc.CODE != ' . $sc;
        }
        else
        {
            $sc = $secretCode * 1;
            $where = 'WHERE tsc.CODE = ' . $sc;
        }

        $sql = "SELECT tc.ID, tc.NAME, tc.CALCULATION_DATA, GROUP_CONCAT(tsc.CODE SEPARATOR ', ') CODE "
            . "FROM " . $this->prefix . $this->table ." tc "
            . "LEFT JOIN " . $this->SC->getPrefix() . $this->SC->getTable() . " tsc "
            . "ON tc.ID = tsc.CALCULATION_ID "
            . $where . " "
            . "GROUP BY tc.ID ORDER BY tc.ID ASC";

        $shm = $this->db->prepare($sql);
        $res = $shm->execute();

        if ($res === false)
            return $shm->errorInfo();

        return $shm->fetchAll();
    }


    /** Get Data From DB Table all or limited
     * @param string $id
     * @param null $limit
     * @param null $offset
     */
    public function getAllData($limit = null, $offset = null)
    {

        if ($limit != null && strlen($limit) > 0)
        {
            $lim = "LIMIT ";

            if ($offset != null && strlen($offset) > 0)
            {
                $lim .= $offset . ", ";
            }

            $lim .= $limit;
        }
        else
        {
            $lim = "";
        }

        $sql = "SELECT tc.ID, tc.NAME, tc.CALCULATION_DATA, GROUP_CONCAT(tsc.CODE SEPARATOR ', ') CODE "
             . "FROM " . $this->prefix . $this->table ." tc "
             . "LEFT JOIN " . $this->SC->getPrefix() . $this->SC->getTable() . " tsc "
             . "ON tc.ID = tsc.CALCULATION_ID "
             . "GROUP BY tc.ID ORDER BY tc.ID DESC "
             . $lim;
        $shm = $this->db->prepare($sql);
        if (!$shm->execute())
            var_dump($shm->errorInfo());



        return $shm->fetchAll();

    }

    /**
     * @param $str
     */
    private function errors($str)
    {
        $this->err[] = $str;
    }

    /**
     * @param $str
     * @return array
     */
    private function parseData($str)
    {
        if (mb_strlen($str) <= 0)
        {
            $this->errors("Стока вхождения пустая.");
            return $this->err;
        }

        $SC = array();
        $tmpStr = "";

        $str = str_replace(" ", "", $str);
        $str = str_replace("\n", "", $str);
        $str = str_replace("\r", "", $str);
        $str = str_replace("\t", "", $str);
        $arr = str_split($str);

        $next = false;

        /* Обработка каждого символа */
        foreach ($arr as $key => $chr)
        {
            if ($chr == "}" && $next === true)
            {
                $SC[] = ltrim($tmpStr, "+");
                $tmpStr = "";
                $next = false;
            }

            /* ASCII: 48(0) - 57(9), 43(+), 45(-) */
            if ($next === true && ( (ord($chr) >= 48 && ord($chr) <= 57) || ord($chr) == 43 || ord($chr) == 45))
            {
                if ($arr[$key - 1] !== "{" && (ord($chr) == 43 || ord($chr) == 45) )
                {
                    $tmpStr = "";
                    $next = false;
                }
                else
                {
                    $tmpStr .= $chr;
                }
            }
            else
            {
                $tmpStr = "";
                $next = false;
            }

            if ($chr == "{")
            {
                $tmpStr = "";
                $next = true;
            }
        }

        $this->secretCode = $SC;

        unset($SC, $tmpStr, $next, $key, $chr);
    }

}