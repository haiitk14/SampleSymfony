// var msg_1 = new Array("Enter a value in ", "Entrez une valeur ", "Entrate un valore ", "Gehen Sie hinein ein Wert ", "Entre un valor ");
// var msg_2 = new Array("Not a valid number in ", "Num�ro invalide ", "Numero invalido ", "Invalide Nummer ", "N�mero inv�lido ");
// var msg_3 = new Array("Value out of range in ", "Valeur hors intervalle ", "Valore fuori intervallo ", "Wert au�erhalb des Intervalls ", "Valor fuera del intervalo ");

// function validateField(field, fieldName, type, sign, noValue, min, max) {
//     return validateFormField("document.forms[0]", field, fieldName, type, sign, noValue, min, max);
// }

// function validateFormField(formName, field, fieldName, type, sign, noValue, min, max) {
//     var nbh = trim(field.value);

//     if (noValue) {
//         if (nbh == "") {
//             return true;
//         }
//     }
//     if ((nbh == "")) {
//         alert(msg_1[idLang - 1] + fieldName + " !");
//         setFocus(formName, field);
//         return false;
//     }
//     if (type == "float") {
//         if (!isFloat(nbh, sign)) {
//             alert(msg_2[idLang - 1] + fieldName + " !");
//             setFocus(formName, field);
//             return false;
//         }
//     } else {
//         if (!isNumber(nbh)) {
//             alert(msg_2[idLang - 1] + fieldName + " !");
//             setFocus(formName, field);
//             return false;
//         }
//     }
//     if (!isInRange(nbh, min, max)) {
//         alert(msg_3[idLang - 1] + fieldName + " (" + min + " : " + max + ") !");
//         setFocus(formName, field);
//         return false;
//     }
//     return true;
// }

function activefield(cb) 
{
    var a = "_alphavalue"+(cb.value);
    document.formCalculationSetting.elements[a].disabled = true;
    document.formCalculationSetting.elements[a].value = "0";
    if (cb.checked ==true) {
        document.formCalculationSetting.elements[a].disabled = false;
    }
}

$(document).on('click', "#btnMeshSave", function(e){
    var dimension1 = $('#_dimension1').val();
    var dimension2 = $('#_dimension2').val();
    var dimension3 = $('#_dimension3').val();

    $.ajax({
        url: '/validate/mesh',
        type: 'POST',
        dataType: "json",
        data: {
            dimension1: dimension1,
            dimension2: dimension2,
            dimension3: dimension3
        },
        success: function(data) {
            console.log(data);
            flag = data.status;
            msg = data.msg;
            if (!flag) {
                $.toast().reset('all');
                $.toast({
                    heading: data.heading,
                    text: msg,
                    showHideTransition: 'fade',
                    position: 'top-center',
                    loader: false,
                    icon: 'error',
                    allowToastClose: false
                });
            } else {
                $.toast().reset('all');
                $.toast({
                    heading: data.heading,
                    text: msg,
                    showHideTransition: 'fade',
                    position: 'top-center',
                    loader: true,
                    icon: 'success',
                    allowToastClose: false
                });
                $('#formMeshSetting').submit();
            }
        },
        error: function (jqXHR, exception) {
            var msg = '';
            if (jqXHR.status === 0) {
                msg = 'Not connect.\n Verify Network.';
            } else if (jqXHR.status == 404) {
                msg = 'Requested page not found. [404]';
            } else if (jqXHR.status == 500) {
                msg = 'Internal Server Error [500].';
            } else if (exception === 'parsererror') {
                msg = 'Requested JSON parse failed.';
            } else if (exception === 'timeout') {
                msg = 'Time out error.';
            } else if (exception === 'abort') {
                msg = 'Ajax request aborted.';
            } else {
                msg = 'Uncaught Error.\n' + jqXHR.responseText;
            }
            console.log(msg);
        }
    });
});

$(document).on('click', "#btnCalculationSave", function(e){
    var maxIter = $('#_maxIter').val();
    var relaxCoef = $('#_relaxCoef').val();
    var precision = $('#_precision').val();
    var r2Suface = $('#_r2Suface').val();
    var r2Internal = $('#_r2Internal').val();
    var r2Bottom = $('#_r2Bottom').val();
    var r2Average = $('#_r2Average').val();
    var alphavalue0 = $('#_alphavalue0').val();
    var alphavalue1 = $('#_alphavalue1').val();
    var alphavalue2 = $('#_alphavalue2').val();
    var alphavalue3 = $('#_alphavalue3').val();
    var alphavalue4 = $('#_alphavalue4').val();
    var alphavalue5 = $('#_alphavalue5').val();
    var storageStep = $('#_storageStep').val();
    var precisionStep = $('#_precisionStep').val();
    var timeStep = $('#_timeStep').val();

    $.ajax({
        url: '/validate/calculation',
        type: 'POST',
        dataType: "json",
        data: {
            maxIter: maxIter,
            relaxCoef: relaxCoef,
            precision: precision,
            r2Suface: r2Suface,
            r2Internal: r2Internal,
            r2Bottom: r2Bottom,
            r2Average: r2Average,
            alphavalue0: alphavalue0,
            alphavalue1: alphavalue1,
            alphavalue2: alphavalue3,
            alphavalue3: alphavalue3,
            alphavalue4: alphavalue4,
            alphavalue5: alphavalue5,
            storageStep: storageStep,
            precisionStep: precisionStep,
            timeStep: timeStep

        },
        success: function(data) {
            flag = data.status;
            msg = data.msg;
            if (!flag) {
                $.toast().reset('all');
                $.toast({
                    heading: data.heading,
                    text: msg,
                    showHideTransition: 'fade',
                    position: 'top-center',
                    loader: false,
                    icon: 'error',
                    allowToastClose: false
                });
            } else {
                $.toast().reset('all');
                $.toast({
                    heading: data.heading,
                    text: msg,
                    showHideTransition: 'fade',
                    position: 'top-center',
                    loader: true,
                    icon: 'success',
                    allowToastClose: false
                });
                $('#formCalculationSetting').submit();
            }
        },
        error: function () {
            console.log("error");
        }
    });
});

$(document).on('click', "#btnResultSave", function(e){
    var axis1Top = $('#_axis1Top').val();
    var axis2Top = $('#_axis2Top').val();
    var axis3Top = $('#_axis3Top').val();
    var axis1Int = $('#_axis1Int').val();
    var axis2Int = $('#_axis2Int').val();
    var axis3Int = $('#_axis3Int').val();
    var axis1Bot = $('#_axis1Bot').val();
    var axis2Bot = $('#_axis2Bot').val();
    var axis3Bot = $('#_axis3Bot').val();
    var plan1Value = $('#_plan1Value').val();
    var plan2Value = $('#_plan2Value').val();
    var plan3Value = $('#_plan3Value').val();
    var axis2Axe1 = $('#_axis2Axe1').val();
    var axis3Axe1 = $('#_axis3Axe1').val();
    var axis1Axe2 = $('#_axis1Axe2').val();
    var axis3Axe2 = $('#_axis3Axe2').val();
    var axis1Axe3 = $('#_axis1Axe3').val();
    var axis2Axe3 = $('#_axis2Axe3').val();

    $.ajax({
        url: '/validate/result',
        type: 'POST',
        dataType: "json",
        data: {
            axis1Top: axis1Top,
            axis2Top: axis2Top,
            axis3Top: axis3Top,
            axis1Int: axis1Int,
            axis2Int: axis2Int,
            axis3Int: axis3Int,
            axis1Bot: axis1Bot,
            axis2Bot: axis2Bot,
            axis3Bot: axis3Bot,
            plan1Value: plan1Value,
            plan2Value: plan2Value,
            plan3Value: plan3Value,
            axis2Axe1: axis2Axe1,
            axis3Axe1: axis3Axe1,
            axis1Axe2: axis1Axe2,
            axis3Axe2: axis3Axe2,
            axis1Axe3: axis1Axe3,
            axis2Axe3: axis2Axe3

        },
        success: function(data) {
            flag = data.status;
            msg = data.msg;
            if (!flag) {
                $.toast().reset('all');
                $.toast({
                    heading: data.heading,
                    text: msg,
                    showHideTransition: 'fade',
                    position: 'top-center',
                    loader: false,
                    icon: 'error',
                    allowToastClose: false
                });
            } else {
                $.toast().reset('all');
                $.toast({
                    heading: data.heading,
                    text: msg,
                    showHideTransition: 'fade',
                    position: 'top-center',
                    loader: true,
                    icon: 'success',
                    allowToastClose: false
                });
                $('#formResultSetting').submit();
            }
        },
        error: function (jqXHR, exception) {
            var msg = '';
            if (jqXHR.status === 0) {
                msg = 'Not connect.\n Verify Network.';
            } else if (jqXHR.status == 404) {
                msg = 'Requested page not found. [404]';
            } else if (jqXHR.status == 500) {
                msg = 'Internal Server Error [500].';
            } else if (exception === 'parsererror') {
                msg = 'Requested JSON parse failed.';
            } else if (exception === 'timeout') {
                msg = 'Time out error.';
            } else if (exception === 'abort') {
                msg = 'Ajax request aborted.';
            } else {
                msg = 'Uncaught Error.\n' + jqXHR.responseText;
            }
            console.log(msg);
        }
    });
});
