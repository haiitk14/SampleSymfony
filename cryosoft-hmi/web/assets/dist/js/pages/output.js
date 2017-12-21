$(function() {

    $("input[name$='DPH']").change(function() {
        window.location.assign(this.value);
    });
    
    $("input[name$='TR']").change(function() {
        window.location.assign(this.value);
    });

    $('.my-tooltip-link').hover(
        function(){
            $(this).next().show();
        },
        function(){
            $(this).next().hide();   
        }
    )
});

$(document).ready(function(){
    modalOutPutRefinePopup();
    modalOutPutEquipSizing();
    modalOutPutPopupTr();
    modalOutPutConsumptionpie();
    modalOutPutTocPopup();
    equipCaculationValidation();
    addSelectedToList();
    removeSelectedFromList();
    loadGrapresultChart();
})

function modalOutPutRefinePopup(){
    $('body').on('click', '.output-refine-popup', function (e) {
        e.preventDefault();
        var url = $(this).data('url');
        var idstudyequipments = $(this).data('idstudyequipments');
        var brandmode = $(this).data('brandmode');
        

        if (!$('#model-output-refine-popup').length) {
            var arr_model = [
                '<div class="modal fade has-loading" id="model-output-refine-popup" tabindex="-1" role="dialog" aria-labelledby="ModalOutPutRefinePopup">',
                '<div class="modal-dialog modal-lg" role="document">',
                '<div class="modal-content"></div>',
                '</div></div>'
            ];

            $('body').append(arr_model.join(''));
        }

        $('#model-output-refine-popup').modal('show').addClass('loading');

        $.ajax({
            type: "POST",
            url: url,
            data:{'idstudyequipments':idstudyequipments, 'brandmode':brandmode},
            success:function(html){
                $('#model-output-refine-popup').removeClass('loading');
                $('#model-output-refine-popup').find('.modal-content').html(html);
            }
        });

    });
}

function modalOutPutEquipSizing(){
    $('body').on('click', '.output-equip-sizing', function (e) {
        e.preventDefault();
        var url = $(this).data('url');
        var idstudyequipments = $(this).data('idstudyequipments');
        

        if (!$('#model-output-equip-sizing').length) {
            var arr_model = [
                '<div class="modal fade has-loading" id="model-output-equip-sizing" tabindex="-1" role="dialog" aria-labelledby="ModalOutPutEquipSizing">',
                '<div class="modal-dialog modal-md" role="document">',
                '<div class="modal-content"></div>',
                '</div></div>'
            ];

            $('body').append(arr_model.join(''));
        }

        $('#model-output-equip-sizing').modal('show').addClass('loading');

        $.ajax({
            type: "POST",
            url: url,
            data:{'idstudyequipments':idstudyequipments},
            success:function(html){
                $('#model-output-equip-sizing').removeClass('loading');
                $('#model-output-equip-sizing').find('.modal-content').html(html);
                equipCaculationValidation();
                keyPressValidateOutput("width", "Width");
                keyPressValidateOutput("length", "Length");
                keyPressValidateOutput("surface", "Surface");
            }
        });

    });
}

function modalOutPutPopupTr(){
    $('body').on('click', '.output-popup-tr', function (e) {
        e.preventDefault();
        var url = $(this).data('url');
        var modalClass = "modal-md";
        if(url == "popup-tr"){
            var modalClass = "modal-lg"
        }
        var idstudyequipments = $(this).data('idstudyequipments');
        

        if (!$('#model-output-popup-tr').length) {
            var arr_model = [
                '<div class="modal fade has-loading" id="model-output-popup-tr" tabindex="-1" role="dialog" aria-labelledby="ModalOutPutPopupTr">',
                '<div class="modal-dialog '+ modalClass +'" role="document">',
                '<div class="modal-content"></div>',
                '</div></div>'
            ];

            $('body').append(arr_model.join(''));
        }

        $('#model-output-popup-tr').modal('show').addClass('loading');

        $.ajax({
            type: "POST",
            url: url,
            data:{'idstudyequipments':idstudyequipments},
            success:function(html){
                $('#model-output-popup-tr').removeClass('loading');
                $('#model-output-popup-tr').find('.modal-content').html(html);
                if(url == "popup-tr"){
                    tsbuttonValidate();
                    trbuttonValidate();
                    submitCalculatePopupTr();
                }    
            }
        });

    });
}

function modalOutPutConsumptionpie(){
    $('body').on('click', '.output-consumption-pie', function (e) {
        e.preventDefault();
        var url = $(this).data('url');
        var idstudyequipments = $(this).data('idstudyequipments');
        var chartpercent = $(this).data('chartpercent');
        var chartlabel = $(this).data('chartlabel');

        if (!$('#model-output-consumption-pie').length) {
            var arr_model = [
                '<div class="modal fade has-loading" id="model-output-consumption-pie" tabindex="-1" role="dialog" aria-labelledby="ModalOutPutConsumtionPie">',
                '<div class="modal-dialog modal-lg" role="document">',
                '<div class="modal-content"></div>',
                '</div></div>'
            ];

            $('body').append(arr_model.join(''));
        }

        $('#model-output-consumption-pie').modal('show').addClass('loading');

        $("#model-output-consumption-pie").on('shown.bs.modal', function() {
            $.ajax({
                type: "POST",
                url: url,
                data: {'idstudyequipments':idstudyequipments, 'data-chartpercent': chartpercent, 'chartlabel': chartlabel},
                success:function(html){
                    $('#model-output-consumption-pie').removeClass('loading');
                    $('#model-output-consumption-pie').find('.modal-content').html(html);                

                    loadChartConsumptionPie(chartpercent, chartlabel);
                    closePie();
                }
            });
                
        });
            
    });
}

function modalOutPutTocPopup(){
    $('body').on('click', '.output-toc-popup', function (e) {
        e.preventDefault();
        var url = $(this).data('url');
        var idstudyequipments = $(this).data('idstudyequipments');

        if (!$('#model-output-toc-popup').length) {
            var arr_model = [
                '<div class="modal fade has-loading" id="model-output-toc-popup" tabindex="-1" role="dialog" aria-labelledby="ModalOutPutTocPopup">',
                '<div class="modal-dialog modal-lg" role="document">',
                '<div class="modal-content"></div>',
                '</div></div>'
            ];

            $('body').append(arr_model.join(''));
        }

        $('#model-output-toc-popup').modal('show').addClass('loading');

        $("#model-output-toc-popup").on('shown.bs.modal', function() {
            $.ajax({
                type: "POST",
                url: url,
                data: {'idstudyequipments':idstudyequipments},
                success:function(html){
                    $('#model-output-toc-popup').removeClass('loading');
                    $('#model-output-toc-popup').find('.modal-content').html(html);
                }
            });
                
        });
            
    });
}

function showSettings(){
    var scrollHeight = $("#modal-body-refine").get(0).scrollHeight;
    var style = $("#output-refine-setting").css("visibility");
    if(style == "hidden"){
        $("#output-refine-setting").css({"visibility":"visible", "height":"auto", "overflow":"auto"});
        $("#modal-body-refine").css({"height":"650px", "max-height": "650px"});
    }else{
        $("#output-refine-setting").css({"visibility":"hidden", "height":"0", "overflow":"hidden"});
        $("#modal-body-refine").css({"height":"400px", "max-height":"400px"});
    }
}

function equipCaculationValidation(){
    $("#equipCaculation").click(function(){
        var idStudyEquipments = $(this).data("id");
        var calculatemode = $(this).data("calculatemode");
        var width = $("input[name=width]").val();
        var length = $("input[name=length]").val();
        var surface = $("input[name=surface]").val();

        var minWidth = $("input[name=width]").data("min");
        var maxWidth = $("input[name=width]").data("max");

        var minLength = $("input[name=length]").data("min");
        var maxLength = $("input[name=length]").data("max");

        var minSurf = $("input[name=surface]").data("min");
        var maxSurf = $("input[name=surface]").data("max");

        var errorDiv = $("#errorMsgEquipCalculation");

        if (width == "") {
            errorDiv.html("<strong>Enter a value in Width !</strong>");
            $("input[name=width]").focus();
            return false;
        } else if ($.isNumeric(width) == false) {
            errorDiv.html("<strong>Not a valid number in Width !</strong>");
            $("input[name=width]").focus();
            return false;
        } else if (!(isInRangeOutput(width, minWidth, maxWidth))) {
            errorDiv.html("<strong>Value out of range in Width("+ minWidth +" : "+ maxWidth +") !</strong>");
            $("input[name=width]").focus();
            return false;
        } else {
            errorDiv.html("");
        }

        if(length == ""){
            errorDiv.html("<strong>Enter a value in Length !</strong>");
            $("input[name=length]").focus();
            return false;
        }else if($.isNumeric(length) == false){
            errorDiv.html("<strong>Not a valid number in Length !</strong>");
            $("input[name=length]").focus();
            return false;
        }else if(!(isInRangeOutput(length, minLength, maxLength))){
            errorDiv.html("<strong>Value out of range in Length("+ minLength +" : "+ maxLength +") !</strong>");
            $("input[name=width]").focus();
            return false;
        }
        else{
            errorDiv.html("");
        }

        if (surface == "") {
            errorDiv.html("<strong>Enter a value in Surface</strong>");
            $("input[name=surface]").focus();
            return false;
        } else if ($.isNumeric(surface) == false){
            errorDiv.html("<strong>Not a valid number in Surface</strong>");
            $("input[name=surface]").focus();
            return false;
        } else if (!(isInRangeOutput(surface, minLength, maxLength))){
            errorDiv.html("<strong>Value out of range in Length("+ minSurf +" : "+ maxSurf +") !</strong>");
            $("input[name=width]").focus();
            return false;
        } else {
            errorDiv.html("");
        }

        $.ajax({
            type: "POST",
            url: "/save-new-size-and-recalculate",
            dataType: "json",
            data: {"idStudyEquipments":idStudyEquipments, "calculatemode":calculatemode, "width":width, "length":length},
            success:function(result){
                if(result == true){
                    window.location.reload();
                }else{
                    alert("Error");
                } 
            },
	        error: function(jqXHR, exception) {
	            if (jqXHR.status === 0) {
	                console.log('Not connect.\n Verify Network.');
	            } else if (jqXHR.status == 404) {
	                console.log('Requested page not found. [404]');
	            } else if (jqXHR.status == 500) {
	                console.log('Internal Server Error [500].');
	            } else if (exception === 'parsererror') {
	                console.log('Requested JSON parse failed.');
	            } else if (exception === 'timeout') {
	                console.log('Time out error.');
	            } else if (exception === 'abort') {
	                console.log('Ajax request aborted.');
	            } else {
	                console.log('Uncaught Error.\n' + jqXHR.responseText);
	            }
	        } 
        })
    })
}

function keyPressValidateOutput(inputName, label) {
    var errorDiv = $("#errorMsgEquipCalculation"); 
    errorDiv.html("");
    $("input[name="+ inputName +"]").keyup(function() {
        var value = $(this).val();

        var min = $(this).data("min");
        var max = $(this).data("max");

        if(value == ""){
            errorDiv.html("<strong>Enter a value in "+ label +" !</strong>");
        }else if($.isNumeric(value) == false){
            errorDiv.html("<strong>Not a valid number in "+ label +" !</strong>");
        }else if(!(isInRangeOutput(value, min, max))){
            errorDiv.html("<strong>Value out of range in "+ label +"("+ min +" : "+ max +") !</strong>");
        }else{
            errorDiv.html("");
        }
    })
}

function isInRangeOutput(value, min, max)
{
    if (value < min) {
        return false;
    }
    if (value > max) {
        return false;
    }
  
    return true;
}

function tsbuttonValidate() {
    var errorDiv = $("#errorMsgTs");
    $("#tsbutton").click(function () {
        var label = "Residence / Dwell time";
        var min = $(this).data("min");
        var max = $(this).data("max");
        var ts = $("input[name='STS[]']").map(function(){return $(this).val();}).get();
        for (var i = 0; i < ts.length; i++) {
            var value = ts[i];
            if (value == "") {
                errorDiv.html("<strong>Enter a value in "+ label +"!</strong>");
                return false;
            } else if ($.isNumeric(value) == false){
                errorDiv.html("<strong>Not a valid number in "+ label +"!</strong>");
                return false;
            } else if(!(isInRangeOutput(value, min, max))){
                errorDiv.html("<strong>Value out of range in "+ label +"("+ min +" : "+ max +") !</strong>");
                return false;
            } else {
                errorDiv.html("");
            }
        }
    })  
}

function trbuttonValidate() {
    var errorDiv = $("#errorMsgTr");
    $("#trbutton").click(function () {
        var label = "Control temperature";
        var min = $(this).data("min");
        var max = $(this).data("max");
        var tr = $("input[name='STR[]']").map(function(){return $(this).val();}).get();
        for (var i = 0; i < tr.length; i++) {
            var value = tr[i];
            if (value == "") {
                errorDiv.html("<strong>Enter a value in "+ label +"!</strong>");
                return false;
            } else if ($.isNumeric(value) == false){
                errorDiv.html("<strong>Not a valid number in "+ label +"!</strong>");
                return false;
            } else if(!(isInRangeOutput(value, min, max))){
                errorDiv.html("<strong>Value out of range in "+ label +"("+ min +" : "+ max +") !</strong>");
                return false;
            } else {
                errorDiv.html("");
            }
        }
    })  
}

function submitCalculatePopupTr() {
    $("#popupTrCaculation").click(function(){
        var errorTsDiv = $("#errorMsgTs");
        var errorTrDiv = $("#errorMsgTr");

        var labelTs = "Residence / Dwell time";
        var labelTr = "Control temperature";

        var minTs = $("#tsbutton").data("min");
        var maxTs = $("#tsbutton").data("max");

        var minTr = $("#trbutton").data("min");
        var maxTr = $("#trbutton").data("max");

        var ts = $("input[name='STS[]']").map(function(){return $(this).val();}).get();
        var tr = $("input[name='STR[]']").map(function(){return $(this).val();}).get();

        for (var i = 0; i < ts.length; i++) {
            var value = ts[i];
            if (value == "") {
                errorTsDiv.html("<strong>Enter a value in "+ labelTr +"!</strong>");
                return false;
            } else if ($.isNumeric(value) == false){
                errorTsDiv.html("<strong>Not a valid number in "+ labelTr +"!</strong>");
                return false;
            } else if(!(isInRangeOutput(value, minTs, maxTs))){
                errorTsDiv.html("<strong>Value out of range in "+ labelTr +"("+ minTs +" : "+ maxTs +") !</strong>");
                return false;
            } else {
                errorTsDiv.html("");
            }
        }

        for (var i = 0; i < tr.length; i++) {
            var value = tr[i];
            if (value == "") {
                errorTrDiv.html("<strong>Enter a value in "+ labelTr +"!</strong>");
                return false;
            } else if ($.isNumeric(value) == false){
                errorTrDiv.html("<strong>Not a valid number in "+ labelTr +"!</strong>");
                return false;
            } else if(!(isInRangeOutput(value, minTr, maxTr))){
                errorTrDiv.html("<strong>Value out of range in "+ labelTr +"("+ minTr +" : "+ maxTr +") !</strong>");
                return false;
            } else {
                errorTrDiv.html("");
            }
        }
    }) 
}

function loadChartConsumptionPie(chartpercent, chartlabel){
    var chartPerCentData = [];
    var chartBackgroundColor = [];
    var chartLabelTxt = [];
    if(chartpercent.Product > 0){
        chartPerCentData.push(chartpercent.Product);
        chartBackgroundColor.push('#0000FF');
        chartLabelTxt.push(chartlabel.Product);
    }
    if(chartpercent.EquipmentPerm > 0){
        chartPerCentData.push(chartpercent.EquipmentPerm);
        chartBackgroundColor.push('#00BFBF');
        chartLabelTxt.push(chartlabel.EquipmentPerm);
    }
    if(chartpercent.EquipmentDown > 0){
        chartPerCentData.push(chartpercent.EquipmentDown);
        chartBackgroundColor.push('#00FFFF');
        chartLabelTxt.push(chartlabel.EquipmentDown);
    }
    if(chartpercent.Line > 0){
        chartPerCentData.push(chartpercent.Line);
        chartBackgroundColor.push('#33CC33');
        chartLabelTxt.push(chartlabel.Line);
    }
    chartPerCentData = JSON.stringify(chartPerCentData);
    chartBackgroundColor = JSON.stringify(chartBackgroundColor);
    chartLabelTxt = JSON.stringify(chartLabelTxt);
    // alert(chartPerCentData)

    var config = {
        type: 'pie',
        data: {
            datasets: [{
                data: JSON.parse(chartPerCentData),
                backgroundColor: JSON.parse(chartBackgroundColor),
                label: 'Dataset 1'
            }],
            labels: JSON.parse(chartLabelTxt)
        },
        options: {
            responsive: true,
            legend: {
                position: 'top',
            },
            title: {
                display: false,
                text: ''
            },
            animation: {
                animateScale: false,
                animateRotate: false
            },
            pieceLabel: {
                render: 'percentage',
                fontSize: 14,
                fontStyle: 'bold',
                fontColor: ['#fff', '#000', '#000', '#fff'],
                fontFamily: '"Lucida Console", Monaco, monospace',
            }
        }
    };
    
    var ctx = document.getElementById("chart-area-comsumtion-pie").getContext("2d");
    new Chart(ctx, config);
}

function closePie(){
    $("#btnCloseConsumtionPie, #btnCloseConsumtionPieCharacter").click(function(){
        var idstudyequipments = $(this).data("idstudyequipments")
        var report_pie = 0;
        if ($("input[name=report_pie]").is(":checked")) {
            report_pie = 1;
        }

        $.ajax({
            type: "POST",
            dataType: "json",
            url: "/close-pie",
            data: {"idstudyequipments": idstudyequipments, "report_pie": report_pie},
            success:function(result){
                if(result == true){
                    $("#model-output-consumption-pie").modal("hide");
                } 
            }
        })
        
    })
}

function addSelectedToList(){
    $("#move_dev_list_select").click(function(){
        var html = "";
        var equipmentListId = [];
        $('#dev_list_select > option:selected').each(
            function(i) {
                html += "<option value='"+ $(this).val() +"'>"+ $(this).text() +"</option>";
                equipmentListId[i] = $(this).val();
            }
        );
        $("#dev_list_result").append(html);
        for (var i = 0; i < equipmentListId.length; i++) {
            $("#dev_list_select option[value='"+ equipmentListId[i] +"']").remove();
        }
    })
}

function removeSelectedFromList(){
    $("#move_dev_list_result").click(function(){
        var html = "";
        var activeEquipmentId = [];
        $('#dev_list_result > option:selected').each(
            function(i) {
                html += "<option value='"+ $(this).val() +"'>"+ $(this).text() +"</option>";
                activeEquipmentId[i] = $(this).val();
            }
        );
        $("#dev_list_select").append(html);
        for (var i = 0; i < activeEquipmentId.length; i++) {
            $("#dev_list_result option[value='"+ activeEquipmentId[i] +"']").remove();
        }
    })
}

function loadGrapresultChart(){
    if($("#dev_list_result").length > 0){
        var liststudyequip = $("#dev_list_result option").map(function() {
            return $(this).val();
        }).get();

        if(liststudyequip.length > 0){
            if($("canvas#canvasGrapResult").length == 0){
                $("#loadGrapResult").html("<canvas id='canvasGrapResult'></canvas>");
            }
            $.ajax({
                type: "POST",
                dataType: "json",
                url: "/grap-out-sizing-result",
                data: {"liststudyequip": liststudyequip},
                success:function(chartData){
                    grapResultChart(chartData);
                }
            })
        }else{
            $("#loadGrapResult").html("");
        }

        
    };
    
}

function grapResultChart(data) {
    var chartId = 'canvasGrapResult';
    var chartType = 'bar';
    var dataChart = data.dataChart;
    var labelArr = [];
    var columnS1Arr = [];
    var columnS2Arr = [];
    var columnS3Arr = [];
    var columnS4Arr = [];
    var customFlowRate = [];

    for (var i = 0; i < dataChart.length; i++) {
        labelArr.push(dataChart[i].sEquipName);
        columnS1Arr.push(dataChart[i].sDHP[0]);
        columnS2Arr.push(dataChart[i].sDHP[1]);
        columnS3Arr.push(dataChart[i].sConso[0]);
        columnS4Arr.push(dataChart[i].sConso[1]);
        customFlowRate.push(data.custom_flow_rate);
    }

    var labelData = JSON.stringify(labelArr);
    var columnS1Data = JSON.stringify(columnS1Arr);
    var columnS2Data = JSON.stringify(columnS2Arr);
    var columnS3Data = JSON.stringify(columnS3Arr);
    var columnS4Data = JSON.stringify(columnS4Arr);
    var customFlowRateData = JSON.stringify(customFlowRate);

    var chartData = {
        labels: JSON.parse(labelData),
        datasets: [
            {
                type: 'line',
                label: data.s5,
                borderColor: "#f00",
                borderWidth: 2,
                fill: false,
                radius: 0,
                data: JSON.parse(customFlowRateData),
            }, 
            {
                label: data.s1,
                backgroundColor: "rgb(0, 0, 255)",
                data: JSON.parse(columnS1Data)
            }, 
            {
                label: data.s2,
                backgroundColor: "rgb(153, 204, 255)",
                data: JSON.parse(columnS2Data)
            }, 
            {
                label: data.s3,
                backgroundColor: "rgb(51, 204, 51)",
                data: JSON.parse(columnS3Data),
                yAxisID: "y-axis-2"
            }, 
            {
                label: data.s4,
                backgroundColor: "rgb(102, 255, 153)",
                data: JSON.parse(columnS4Data),
                yAxisID: "y-axis-2"
            }
            
        ]

    };

    var optionsData = {
        responsive: true,
        title:{
            display:false,
            text:"Chart 1",
            fontColor: '#000',
            fontSize: 20
        },
        tooltips: {
            mode: 'index',
            intersect: true
        },
        scales: {
            yAxes: [{
                type: "linear",
                display: true,
                position: "left",
                id: "y-axis-1",
                
                scaleLabel: {
                    display: true,
                    labelString: data.axisLeftLabel,
                    fontColor: '#f00',
                    fontSize: 20
                }
            }, {
                type: "linear",
                display: true,
                position: "right",
                id: "y-axis-2",
                gridLines: {
                    drawOnChartArea: false
                },
                scaleLabel: {
                    display: true,
                    labelString: data.axisRightLabel,
                    fontColor: '#f00',
                    fontSize: 20
                }
            }],
        },
        showTooltips: false
    }

    chart(chartId, chartType, chartData, optionsData);
}