$("#ruleconsumer").on("submit", function (event) {
    event.preventDefault();
    $.ajax({
        type: "POST",
        url: "/api/v1/process",
        headers : {
            Authorization : 'Bearer ' + 'e2405ccd203e430b408364be2a3cda78cdb82701'
        }, 
        context: document.body,
        data: $("#ruleconsumer").serialize(),
        success:function(result){
            var jsonObj = $.parseJSON(result);
            var text = JSON.stringify(jsonObj, null, '\t');
            $("#result").html(text);
        },
        error : function(xhr, status, error) {
            $("#result").html(xhr.responseText);               
        }
    });
});

$("#TestType").on("change", function(){
    $.ajax({
        type: "GET", 
        url: "http://local.re.com/process/rules?id="+$(this).val(),
        success:function(result){
            $("#rules").html(result);
            $("#processRule").on("change", function(){
                $.ajax({
                    type: "GET",
                    url: "http://local.re.com/process/condition?id="+$(this).val(),
                    success:function(result){
                        $("#conditions").html(result);
                    },
                    error : function(xhr, status, error) {
                        $("#conditions").html(xhr.responseText);
                    }
                });

            });
        },
        error : function(xhr, status, error) {
            $("#rules").html(xhr.responseText);
        }
    });
});

$(document).ready(function(){
    $.ajax({
        type: "GET", 
        dataType: "html",
        url: "http://local.re.com/process/condition?id="+$("#TestType").val(), 
        success:function(result){
            $("#conditions").html(result);
        },
        error : function(xhr, status, error) {
            $("#conditions").html(xhr.responseText);               
        }
    });
});