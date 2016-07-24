<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 22.07.16
 * Time: 9:13
 */
if ($_SERVER["REQUEST_METHOD"] !== "POST" && $_REQUEST['ajax'] !== "Y") die();

require_once "init.php";

$calculation = new CCalculation();

if (isset($_REQUEST['ActionAdd']) && $_REQUEST['ActionAdd'] == 'Y')
{
    $result = $calculation->setAddData($_REQUEST);

    header('Content-Type: application/json');

    if ($result !== false)
        die (json_encode(array("RESULT" => "OK", "RES_DATA" => $result, "LAST_ID" => $calculation->getLastID())));
    else
        die (json_encode(array("ERROR"=>"FAILED")));
}

if (isset($_REQUEST['ActionSearchCode']) && $_REQUEST['ActionSearchCode'] == "Y")
{
    $result = $calculation->getSearchData($_REQUEST['WHERE']);

    header('Content-Type: application/json');

    $JSON = json_encode($result, JSON_UNESCAPED_UNICODE);

    die ($JSON);
}
