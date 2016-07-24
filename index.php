<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 22.07.16
 * Time: 9:12
 */
require_once ($_SERVER["DOCUMENT_ROOT"] . "/init.php");
$calculation = new CCalculation();
$arResult = $calculation->getAllData();
?>
<!doctype html>
<html lang="ru" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <title>Расчеты</title>
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/script.js"></script>
    <link rel="stylesheet" href="css/bootstrap.min.css"/>
</head>
<body>
<div class="container" style="width: 800px">
    <div class="row clearfix">
        <div class="col-md-12 column">
            <div class="tabbable" id="tabs-641818">
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a data-toggle="tab" href="#panel-add">Добавление</a>
                    </li>
                    <li>
                        <a data-toggle="tab" href="#panel-list">Список</a>
                    </li>
                    <li>
                        <a data-toggle="tab" href="#panel-search">Поиск</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="panel-add">
                        <p contenteditable="true">

                        <form role="form" name="calculationAdd">
                            <div class="form-group">
                                <label for="InputName">Название расчета</label>
                                <input name="name" class="form-control" required="required" id="InputName" type="text"/>
                            </div>
                            <div class="form-group">
                                <label for="InputData">Расчет</label>
                                <textarea required="required" name="data" class="form-control" id="InputData" rows="5" cols="30"></textarea>
                            </div>
                            <button class="btn btn-default" type="submit">Добавить</button>
                        </form>
                        </p>
                    </div>
                    <div class="tab-pane" id="panel-list">
                        <p>

                        <table class="table" contenteditable="true">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Название</th>
                                <th>Расчет</th>
                                <th>Коды</th>
                            </tr>
                            </thead>
                            <tbody>
                            <? foreach($arResult as $item): ?>
                            <tr>
                                <td><?= $item['ID']?></td>
                                <td><?= $item['NAME']?></td>
                                <td><pre><?= $item['CALCULATION_DATA']?></pre></td>
                                <td><?= $item['CODE']?></td>
                            </tr>
                            <? endforeach; ?>
                            </tbody>
                        </table>

                        </p>
                    </div>
                    <div class="tab-pane" id="panel-search">
                        <p>

                        <div class="row">
                            <div class="col-lg-12">
                                <form role="form" name="codeSearch">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="chars">
                                              <span class="input-group-btn">
                                                <button class="btn btn-default" type="submit">Найти</button>
                                              </span>
                                    </div>
                                    <table class="table" contenteditable="true" style="display: none" id="searchResult">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Название</th>
                                                <th>Расчет</th>
                                                <th>Коды</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </form>
                            </div>
                        </div>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
