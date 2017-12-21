$('#showTop').on('change', function () {
    var optionSelected = $("option:selected", this);
    var valueSelected = this.value;
    $("#top1, #top2, #top3, #top4, #top5, #top6, #top7, #top8, #top9").hide();
    if(valueSelected == 1){
        $("#top1").show();
    }
    else if(valueSelected == 2){
        $("#top1, #top2").show();
    }
    else if(valueSelected == 3){
        $("#top1, #top2, #top3").show();
    }
    else if(valueSelected == 4){
        $("#top1, #top2, #top3, #top4").show();
    }
    else if(valueSelected == 5){
        $("#top1, #top2, #top3, #top4, #top5").show();
    }
    else if(valueSelected == 6){
        $("#top1, #top2, #top3, #top4, #top5, #top6").show();
    }
    else if(valueSelected == 7){
        $("#top1, #top2, #top3, #top4, #top5, #top6, #top7").show();
    }
    else if(valueSelected == 8){
        $("#top1, #top2, #top3, #top4, #top5, #top6, #top7, #top8").show();
    }
    else if(valueSelected == 9){
        $("#top1, #top2, #top3, #top4, #top5, #top6, #top7, #top8, #top9").show();
    }
    else{
        $("#top1, #top2, #top3, #top4, #top5, #top6, #top7, #top8, #top9").hide();
    }
});

$('#showRear').on('change', function () {
    var optionSelected = $("option:selected", this);
    var valueSelected = this.value;
    $("#rear1, #rear2, #rear3, #rear4, #rear5, #rear6, #rear7, #rear8, #rear9").hide();
    if(valueSelected == 1){
        $("#rear1").show();
    }
    else if(valueSelected == 2){
        $("#rear1, #rear2").show();
    }
    else if(valueSelected == 3){
        $("#rear1, #rear2, #rear3").show();
    }
    else if(valueSelected == 4){
        $("#rear1, #rear2, #rear3, #rear4").show();
    }
    else if(valueSelected == 5){
        $("#rear1, #rear2, #rear3, #rear4, #rear5").show();
    }
    else if(valueSelected == 6){
        $("#rear1, #rear2, #rear3, #rear4, #rear5, #rear6").show();
    }
    else if(valueSelected == 7){
        $("#rear1, #rear2, #rear3, #rear4, #rear5, #rear6, #rear7").show();
    }
    else if(valueSelected == 8){
        $("#rear1, #rear2, #rear3, #rear4, #rear5, #rear6, #rear7, #rear8").show();
    }
    else if(valueSelected == 9){
        $("#rear1, #rear2, #rear3, #rear4, #rear5, #rear6, #rear7, #rear8, #rear9").show();
    }
    else{
        $("#rear1, #rear2, #rear3, #rear4, #rear5, #rear6, #rear7, #rear8, #rear9").hide();
    }
});


$('#showSide').on('change', function () {
    var optionSelected = $("option:selected", this);
    var valueSelected = this.value;
    $("#side1, #side2, #side3, #side4, #side5, #side6, #side7, #side8, #side9").hide();
    if(valueSelected == 1){
        $("#side1").show();
    }
    else if(valueSelected == 2){
        $("#side1, #side2").show();
    }
    else if(valueSelected == 3){
        $("#side1, #side2, #side3").show();
    }
    else if(valueSelected == 4){
        $("#side1, #side2, #side3, #side4").show();
    }
    else if(valueSelected == 5){
        $("#side1, #side2, #side3, #side4, #side5").show();
    }
    else if(valueSelected == 6){
        $("#side1, #side2, #side3, #side4, #side5, #side6").show();
    }
    else if(valueSelected == 7){
        $("#side1, #side2, #side3, #side4, #side5, #side6, #side7").show();
    }
    else if(valueSelected == 8){
        $("#side1, #side2, #side3, #side4, #side5, #side6, #side7, #side8").show();
    }
    else if(valueSelected == 9){
        $("#side1, #side2, #side3, #side4, #side5, #side6, #side7, #side8, #side9").show();
    }
    else{
        $("#side1, #side2, #side3, #side4, #side5, #side6, #side7, #side8, #side9").hide();
    }
});


function getMethodData(url,data,callback) {
    $.ajax({
        url: url,
        type: 'POST',
        data:data,
        dataType: 'json',
        beforeSend: function(){
            $('.ajax-loader').css("visibility", "visible");
          },
        success: function(data) {
          callback(data);
        },
        complete: function(){
          $('.ajax-loader').css("visibility", "hidden");
          // alert("The page has been successfully loaded !!!");
        },
    });
}
