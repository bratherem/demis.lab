/**
 * Created by admin on 23.07.16.
 */
$(document).ready(
    function(){
        var formAdd = document.calculationAdd;
        var formSearch = document.codeSearch;

        var searchContent = function (data){
            var table = $('#searchResult');
            $("tr").remove("#rows");

            $(data).each(function(k,v){
                table.append("<tr id='rows'>" +
                    "<td>" + v.ID + "</td>" +
                    "<td>" + v.NAME + "</td>" +
                    "<td><pre>" + v.CALCULATION_DATA + "</pre></td>" +
                    "<td>" + v.CODE + "</td>" +
                "</tr>");
            });

            table.fadeIn(100);
        };

        $(formAdd).on("submit", function(t){
            var data = {};

            data.ajax = "Y";
            data.name = this.name.value;
            data.data = this.data.value;
            data.ActionAdd = 'Y';

            if (data.name == "" || data.data == ""){
                alert("Поля ввода не должны быть пустыми!");

                return false;
            }


            $.ajax({
                    url: 'ajax.php',
                    data: data,
                    method: 'POST'})
                .done(function(res) {
                    if (res.ERROR)
                        alert("Error: " + res.ERROR);
                    else{
                        var fakeArr = res.RES_DATA,
                            realArr = $.makeArray( fakeArr),
                            codeStr = realArr.join(", ");

                        $('#panel-list table tbody').prepend("<tr>" +
                            "<td>" + res.LAST_ID + "</td>" +
                            "<td>" + formAdd.name.value + "</td>" +
                            "<td><pre>" + formAdd.data.value + "</pre></td>" +
                            "<td>" + codeStr + "</td>" +
                            "</tr>"
                        );

                        formAdd.name.value = "";
                        formAdd.data.value = "";

                        alert("Успешно добавлено!")
                    }

                })
                .fail(function(err) {
                    console.log(err);

                });

            return false;
        });

        $(formSearch).on("submit", function(){
            var data = {};
            data.WHERE = this.chars.value;
            data.ActionSearchCode = "Y";

            if (data.WHERE == "")
                return false;

            $.ajax({
                dataType: 'JSON',
                url: 'ajax.php',
                data: data,
                method: 'POST',
                success: function (res, t){
                    if (res.length < 1)
                        alert("Результат поиска пуст.");
                    else{

                        searchContent(res);
                    }
                },
                error: function (err, t) {
                    console.log(t);
                }
            });

            return false;
        });
    }
);