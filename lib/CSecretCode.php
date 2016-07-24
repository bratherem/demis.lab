<?php

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 23.07.16
 * Time: 23:35
 */
class CSecretCode
{
    /** Prefix table
     * @var string
     */
    private $prefix = 'tbl_';

    /** Table Name
     * @var string
     */
    private $table = 'secret_codes';

    /**
     * @var array
     */
    private $err = array();

    /**
     * @var array
     */
    private $secretCode = array();

    /**
     * CCalculation constructor.
     */
    public function __construct ()
    {
        $this->db = self::getDB();
    }

    /**
     * @return string
     */
    public function getPrefix ()
    {
        return $this->prefix;
    }

    /**
     * @return string
     */
    public function getTable ()
    {
        return $this->table;
    }

    /**
     * @return CDb|null
     */
    private static function getDB()
    {
        return CMain::getInstance()->getDb();
    }

    /**
     * @param $secretCode
     * @param $CALCULATION_ID
     * @return array|bool
     */
    public function setSecretCode ($secretCode, $CALCULATION_ID)
    {
        $this->secretCode = $secretCode;

        return $this->addCodes($CALCULATION_ID);
    }

    /**
     * @param $CALCULATION_ID
     * @return array|bool
     */
    public function addCodes($CALCULATION_ID)
    {
        $sql = "INSERT INTO " . $this->prefix . $this->table . " ";
        $sql .= "(CALCULATION_ID, CODE) VALUES(:CALCULATION_ID, :CODE)";

        $this->db->beginTransaction();

        $shm = $this->db->prepare($sql);

        foreach ($this->secretCode as $code)
        {
            $res = $shm->execute(array(
                ":CALCULATION_ID" => $CALCULATION_ID,
                ":CODE" => $code
            ));

            if ($res == false)
            {
                $this->db->rollBack();

                return false;
            }
        }

        if ($this->db->inTransaction())
            $this->db->commit();

        return $this->secretCode;
    }
}