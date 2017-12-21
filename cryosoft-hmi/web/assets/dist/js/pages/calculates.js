$(document).ready(function(){
    modalCalculate();
    modalBrainCalculate();
});
/**********************show popup calculate****************************/
function modalCalculate(){
    $('body').on('click', '#btnCalculate', function (e) {
        e.preventDefault();
        var url = $(this).data('url');

        $.ajax({
            type: "POST",
            url: url,
            dataType: "json",
            success:function(result){
                if(result  == false){
                    var calculate_url = "/calculate"
                    if (!$('#model-calculate').length) {
                        var arr_model = [
                            '<div class="modal fade has-loading" id="model-calculate" tabindex="-1" role="dialog" aria-labelledby="ModalCalculate">',
                            '<div class="modal-dialog modal-lg" role="document">',
                            '<div class="modal-content"></div>',
                            '</div>',
                            '</div>',
                            '</div>'
                        ];

                        $('body').append(arr_model.join(''));
                    }

                    $('#model-calculate').modal('show').addClass('loading');

                    $.ajax({
                        type: "POST",
                        url: calculate_url,
                        // data:{'idstudyequipments':idstudyequipments},
                        success:function(html){
                            $('#model-calculate').removeClass('loading');
                            $('#model-calculate').find('.modal-content').html(html);
                        }
                    });
                } else {
                    window.location.href = "/checkcontrol";
                }
            }
        });  
    });
}
/**********************show popup brainCalculate**********************/
function modalBrainCalculate(){
    $('body').on('click', '#btnBrainCalculate', function (e) {
        e.preventDefault();
        var url = $(this).data('url');

        $.ajax({
            type: "POST",
            url: url,
            dataType: "json",
            success:function(result){
                if(result  == false){
                    var calculate_url = "/braincalculate"
                    if (!$('#model-brain-calculate').length) {
                        var arr_model = [
                            '<div class="modal fade has-loading" id="model-brain-calculate" tabindex="-1" role="dialog" aria-labelledby="ModalCalculate">',
                            '<div class="modal-dialog modal-lg" role="document">',
                            '<div class="modal-content"></div>',
                            '</div>',
                            '</div>',
                            '</div>'
                        ];

                        $('body').append(arr_model.join(''));
                    }

                    $('#model-brain-calculate').modal('show').addClass('loading');

                    $.ajax({
                        type: "POST",
                        url: calculate_url,
                        // data:{'idstudyequipments':idstudyequipments},
                        success:function(html){
                            $('#model-brain-calculate').removeClass('loading');
                            $('#model-brain-calculate').find('.modal-content').html(html);
                        }
                    });
                } else {
                    window.location.href = "/checkcontrol";
                }
            }
        });  
    });
}

/*******************show setting optinum toggle******************************/
function showSetting() {
    var x = document.getElementById("optinumFilter");
    if (x.style.display === "none") {
        x.style.display = "block";
    } else {
        x.style.display = "none";
    }
}

/*******************show setting brain toggle******************************/
function showBrainSetting() {
    var x = document.getElementById("brainFilter");
    if (x.style.display === "none") {
        x.style.display = "block";
    } else {
        x.style.display = "none";
    }
}
/*******************end show setting toggle***************************/

$(document).ready(function() {
    $("#calculationClick").click(function() {
        window.chay = setInterval(function() {
            $("#print").parent().after("Name component <br>");
        }, 1000);
        window.chày = setInterval(function() {
            $("#process").parent().after("In process... <br> ");
        }, 1000);
        $("#parent_met").click(function() {
            clearInterval(window.chay);
        });
        $("#parent_met").click(function() {
            clearInterval(window.chày);
        });
    });
});


$(document).on('click', '#btnEstimationConfirm', function() {
    // var id = $(this).attr('data_id');
    $.ajax({
        type: 'POST',
        url: '/startCalculate',
        
        data: {
        },
        error: function() {
            console.log("error");
        },
        success: function(data) {
            console.log("success");
        },
    });
});


/******************scheckOptim *********************/
function checkingOptimisation(sdisableNbOptim) {
    if (document.formOptinumCalculate.cbOptim.checked == true) {
        document.formOptinumCalculate.epsilonTemp.disabled = false;
        document.formOptinumCalculate.epsilonEnth.disabled = false;
        if (sdisableNbOptim == "") {
            document.formOptinumCalculate.epsilonEnth.nbOptimIter = false;
        }
    } else {
        document.formOptinumCalculate.epsilonTemp.disabled = true;
        document.formOptinumCalculate.epsilonEnth.disabled = true;
        if (sdisableNbOptim == "") {
            document.formOptinumCalculate.epsilonEnth.nbOptimIter = true;
        }
    }
}


function checkingStorage(sdisableStorage)
{
    if (sdisableStorage == "") {
        if (document.formOptinumCalculate.cbCharts.checked == true) {
            document.formOptinumCalculate.storagestep.disabled = false;
        } else {
            document.formOptinumCalculate.storagestep.disabled = true;
        }
    }
}

/***************************************************/