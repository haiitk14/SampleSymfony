$(document).ready(function() {
    checkHideShowShape=true;
     $('#shapeName').change(function() {
         $('#D1,#D2,#D3').hide();
         $('#ba1,#ba2,#ba3').hide();
         $('#D1,#D2,#D3').prop("disabled", true);
         $('#D1').val(objMinMax.defaultvalue1);
         $('#D3').val(objMinMax.defaultvalue3);

         if ($(this).val() == 1 || $(this).val() == 6) {
             $('#D1,#D2,#D3').show();
             $('#ba1,#ba2,#ba3').show();
             $('#D1,#D2,#D3').prop("disabled", true).val('Not Applicabale');
         } else if ($(this).val() == 2 || $(this).val() == 3 || $(this).val() == 9) {
             $('#D1,#D2,#D3').show();
             $('#ba1,#ba2,#ba3').show();
             $('#D1,#D3').prop("disabled", false);
             $('#D2').prop("disabled", true).val('Not Applicabale');
         } else if ($(this).val() == 4 || $(this).val() == 5 || $(this).val() == 7 || $(this).val() == 8) {
             $('#D1,#D2,#D3').show();
             $('#ba1,#ba2,#ba3').show();
             $('#D2,#D3').prop("disabled", true).val('Not Applicabale');
             $('#D1').prop("disabled", false);
         }
     });
     $('#shapeName').change(function() {
         var optionSelected = $("option:selected", this);
         var valueSelected = this.value;
         var idShapeOld=$("#id-shape-old").val();
         if(idShapeOld != valueSelected ){
            checkHideShowShape=false;
         }

         $("#img1, #img2, #img3, #img4, #img5, #img6,#img7,#img8,#img9 ").hide();
         if (valueSelected == 1) {
             $('#img1').show();
         } else if (valueSelected == 2) {
             $('#img2').show();
         } else if (valueSelected == 3) {
             $('#img3').show();
         } else if (valueSelected == 4) {
             $('#img4').show();
         } else if (valueSelected == 5) {
             $('#img5').show();
         } else if (valueSelected == 6) {
             $('#img6').show();
         } else if (valueSelected == 7) {
             $('#img7').show();
         } else if (valueSelected == 8) {
             $('#img8').show();
         } else if (valueSelected == 9) {
             $('#img9').show();
         }
     });
// click to Confirm in modal Product
     $("#createModiPro").click(function() {

         var namePro = $("#productName").val();
         var nameShape = $("#shapeName option:selected").text();
         var idShape = $('#shapeName').val();
         var Dim1 = $("#D1").val();
         if ($("#D2").val() === 'Not Applicabale' || $("#D2").val() === '') {
             var Dim2 = 0;
         }
         var Dim3 = $("#D3").val();
         if (namePro === null || namePro === "" || namePro.length >32) {
             var text='Enter a value in product name (maximum 32 characters)!';
             toast(text,'error');
             $("#productName").focus();
             return;
         }
         if (idShape == 2 || idShape == 3 || idShape == 9) {
             if (Dim1 < objMinMax.limitmin1 || Dim1 > objMinMax.limitmax1) {
                  var text="Value out of range in Dimension 1 (" + objMinMax.limitmin1 + " : " + objMinMax.limitmax1+")";
                  toast(text,'error');
                 $("#D1").focus();
                 return;
             }
             if(isNaN(Dim1)){
                var text="Not a valid number in Dimension 1 !";
                toast(text,'error');
                $("#D1").focus();
                return;
            }
             if(Dim1 == "" || hasWhiteSpace(Dim1)){
                var text="Enter a value in Dimension 1 !";
                toast(text,'error');
                $("#D1").focus();
                return;
             }
             if (Dim3 < objMinMax.limitmin3 || Dim3 > objMinMax.limitmax3) {
                 var text="Value out of range in Dimension 3 (" + objMinMax.limitmin3 + " : " + objMinMax.limitmax3+")";
                 toast(text,'error');
                 $("#D3").focus();
                 return;
             }
             if(isNaN(Dim3)){
                var text="Not a valid number in Dimension 3 !";
                toast(text,'error');
                 $("#D3").focus();
                return;
            }
             if(Dim3 == "" || hasWhiteSpace(Dim3)){
                var text="Enter a value in Dimension 3 !";
                toast(text,'error');
                 $("#D3").focus();
                return;
             }
         }
         if (idShape == 4 || idShape == 5 || idShape == 7 || idShape == 8) {
             if (Dim1 < objMinMax.limitmin1 || Dim1 > objMinMax.limitmax1) {
                  var text="Value out of range in Dimension 1 (" + objMinMax.limitmin1 + " : " + objMinMax.limitmax1+")";
                  toast(text,'error');
                 $("#D1").focus();
                 return;
             }
             if(isNaN(Dim1)){
                var text="Not a valid number in Dimension 1 !";
                toast(text,'error');
                $("#D1").focus();
                return;
            }
             if(Dim1 == "" || hasWhiteSpace(Dim1)){
                var text="Enter a value in Dimension 1 !";
                toast(text,'error');
                $("#D1").focus();
                return;
             }
         }
         if(!checkHideShowShape || idShape ==1){
            $(".table-prod").addClass('hidden');
             $(".hide-column-prod").removeClass('hidden');
             if ($('#shapeName').val() == 1) {
                 $('.hide-column-shape1').removeClass('hidden');
                 var markup = "<td><a href='#' data-toggle='modal' data-target='#newPro'>" + namePro + "</a></td><td> " +
                     nameShape + "</td><td>" + Dim2 + "</td>";
                 var movename = "<label style='color: red'>" + namePro + "</label>";
             } else if ($('#shapeName').val() == 2) {
                 $('.hide-column-shape29').removeClass('hidden');
                 var markup = "<td><a href='#' data-toggle='modal' data-target='#newPro'>" + namePro + "</a></td><td> " +
                     nameShape + "</td> <td>" + Dim1 + "</td> <td>" + Dim2 + "</td> <td>" + Dim3 + "</td>";
                 var movename = "<label style='color: red'>" + namePro + "</label>";
             } else if ($('#shapeName').val() == 3) {
                 $('.hide-column-shape3').removeClass('hidden');
                 var markup = "<td><a href='#' data-toggle='modal' data-target='#newPro'>" + namePro + "</a></td><td> " +
                     nameShape + "</td> <td>" + Dim1 + "</td> <td>" + Dim2 + "</td> <td>" + Dim3 + "</td>";
                 var movename = "<label style='color: red'>" + namePro + "</label>";
             } else if ($('#shapeName').val() == 4) {
                 $('.hide-column-shape4').removeClass('hidden');
                 var markup = "<td><a href='#' data-toggle='modal' data-target='#newPro'>" + namePro + "</a></td><td> " +
                     nameShape + "</td> <td>" + Dim1 + "</td> <td>" + Dim2 + "</td> ";
                 var movename = "<label style='color: red'>" + namePro + "</label>";

             } else if ($('#shapeName').val() == 5) {
                 $('.hide-column-shape5').removeClass('hidden');
                 var markup = "<td><a href='#' data-toggle='modal' data-target='#newPro'>" + namePro + "</a></td><td> " +
                     nameShape + "</td> <td>" + Dim1 + "</td> <td>" + Dim2 + "</td> ";
                 var movename = "<label style='color: red'>" + namePro + "</label>";
             } else if ($('#shapeName').val() == 6) {
                 $('.hide-column-shape6').removeClass('hidden');
                 var markup = "<td><a href='#' data-toggle='modal' data-target='#newPro'>" + namePro + "</a></td><td> " +
                     nameShape + "</td><td>" + Dim2 + "</td> ";
                 var movename = "<label style='color: red'>" + namePro + "</label>";
             } else if ($('#shapeName').val() == 7) {
                 $('.hide-column-shape7').removeClass('hidden');
                 var markup = "<td><a href='#' data-toggle='modal' data-target='#newPro'>" + namePro + "</a></td><td> " +
                     nameShape + "</td><td>" + Dim1 + "</td> <td>" + Dim2 + "</td>  ";
                 var movename = "<label style='color: red'>" + namePro + "</label>";
             } else if ($('#shapeName').val() == 8) {
                 $('.hide-column-shape8').removeClass('hidden');
                 var markup = "<td><a href='#' data-toggle='modal' data-target='#newPro'>" + namePro + "</a></td><td> " +
                     nameShape + "</td> <td>" + Dim1 + "</td> <td>" + Dim2 + "</td> ";
                 var movename = "<label style='color: red'>" + namePro + "</label>";
             } else if ($('#shapeName').val() == 9) {
                 $('.hide-column-shape29').removeClass('hidden');
                 var markup = "<td><a href='#' data-toggle='modal' data-target='#newPro'>" + namePro + "</a></td><td> " +
                     nameShape + "</td> <td>" + Dim1 + "</td> <td>" + Dim2 + "</td> <td>" + Dim3 + "</td>";
                 var movename = "<label style='color: red'>" + namePro + "</label>";
             }
                $('.aaa').hide();
                var tb = document.getElementsByName('trNew')[0];
                 tb.innerHTML = markup;
                 var tb1 = document.getElementsByName('proNames')[0];
                 tb1.innerHTML = movename;
         }
         
         if (Dim1 == "Not Applicabale") {
             Dim1 = 0;
         }
         if (Dim3 == "Not Applicabale") {
             Dim3 = 0;
         }
         getMethodData('/createModify', {
             'namePro': namePro,
             'idShape': idShape,
             'Dim1': Dim1,
             'Dim3': Dim3
         }, responseProduct);
         
         $('#newPro').modal('hide');
        

     });
     var responseProduct = function(res) {
         // $("#idShapeHide").val(res.idProduct);#comp-family
         $('.form-selectbox select').prop('disabled', false).trigger("chosen:updated");
         if (res.check == 1) {

             var table = $('#tableComponent').DataTable();
             table.clear().draw();
             $('#realmasslabel').hide();
             $('#realMass').hide();
             $('.realMass').hide();
             $(".product-mass").html("");
         } else if (res.check == 2) {
             window.location.reload();
         } else {
             $('#realmasslabel').show();
             $('#realMass').show();
         }
     };
     $("#modifyReal-product").click(function() {
         var realMass = $("#realProduct").val();
         var number = document.getElementById('realProduct').value;
         var tb = document.getElementsByName('realMass')[0];


         if (isNaN(number)) {
             var text='Not a valid number in Real Product';
             toast(text,'error');
             $("#realProduct").focus();
             return;
         } else if (number == "") {
             var text="Enter a value in Real Product";
             toast(text,'error');
             $("#realProduct").focus();
             return;
         } else if (realMass < objMinMax.limitminprod || realMass > objMinMax.limitmaxprod) {
             var text="Value out of range in Mass  (" + objMinMax.limitminprod + " : " + objMinMax.limitmaxprod+")";
             toast(text,'error');
             $("#realProduct").val("");
             $("#realProduct").focus();
             return;
         }
         var dataSend = {
             // 'idProd': $("#idShapeHide").val(),
             'realweight': number
         };
         getMethodData("/update-realweight-prod", dataSend, function(res) {
             var mass = "<span class='label label-danger' >" + realMass + "</span>";
             tb.innerHTML = mass;
             $('#realPro').modal('hide');
         });
     });
});

function getRealWeightComp(idProEml, prodElmtRealweight, prodElmtWeight) {
     $("#in-weight-comp").val(prodElmtWeight);
     $("#in-realweight-comp").val(prodElmtRealweight);
     $("#in-idProdEml-comp").val(idProEml);
}
function getDataProduct(idShape,dim1,dim3){
    $('#shapeName').val(idShape);
    if(dim1!=''){
        $("#D1").attr('disabled',false);
        $("#D1").val(dim1);
    }else{
        $("#D1").attr('disabled',true);
        $("#D1").val("Not Applicabale");
    }
     if(dim3!=''){
        $("#D3").attr('disabled',false);
        $("#D3").val(dim3);
    }else{
        $("#D3").attr('disabled',true);
        $("#D3").val("Not Applicabale");
    }
}
 var objMinMax = "";
 (function() {
     getMethodData("/getAllMinMaxProductChar", {}, function(res) {
        // console.log(res);
         objMinMax = res;
     });
 }());

 $(document).ready(function() {
     // click to name component in table component
     $('#tableComponent #table-list-component').on('click', 'a', function() {
         var table = $('#tableComponent').DataTable();
         $("#name-Compent").html($(this).text());
         $("#specific-Dim").val($(this).data('thick'));

         $("#description").val($(this).data('decription'));
         $(".idShape-Comp").val($(this).data('idshape'));
         // console.log($(this).data('decription'));
         idProdEmlCurr = $(this).closest('tr').data("idprodeml");
         $(".idProdEml-Comp").val(idProdEmlCurr);


     });

     // change combo component family
     $("#comp-family").on("change", function() {
         var idfam = $("#comp-family").val();
         $("#sub-family").val("");
         var water = $("#water-percentage").val();
         filterComponent(idfam, "", water);

     });
     $("#sub-family").on("change", function() {
         var idfam = $("#comp-family").val();
         var idsub = $("#sub-family").val();
         var water = $("#water-percentage").val(),
             water1 = 0,
             water2 = 1;

         filterComponent(idfam, idsub, water);

     });
     $("#water-percentage").on("change", function() {
         var idfam = $("#comp-family").val();
         var idsub = $("#sub-family").val();
         var water = $("#water-percentage").val();

         filterComponent(idfam, idsub, water);
     });
     $("#component-list").on("change", function() {
         var idComp = $("#component-list").val();
         console.log(idComp);
         if (idComp != "") {
             $("#select-componentList").submit();
         }
     });
     //            builder checkbox sub family
     var reponesDataSubFamily = function(res) {
         var str = '<option></option><option selected value="">-- None --</option>';
         for (var i in res) {
             str += '<option value="' + res[i].idTranslation + '">' + res[i].label + '</option>';
         }
         $("#sub-family").html(str);
         $('#sub-family').trigger('chosen:updated');
     };

     function filterComponent(idfam, idsub, water) {
         var water1 = "",
             water2 = ""
         if (water != "") {
             var w = getWater(water);
             water1 = w[0];
             water2 = w[1];
         }
         if (idfam != "" && idsub == "" && water == "") {
             getMethodData('/getComponentListFam', {
                 'idfam': idfam
             }, reponesDataComponentList)
         }else if (idfam != "" && idsub != "" && water == "") {
             getMethodData('/getComponentListFamSub', {
                 'idfam': idfam,
                 'idsub': idsub
             }, reponesDataComponentList)
         }else if (idfam != "" && idsub == "" && water != "") {
             getMethodData('/getComponentListFamWater', {
                 'idfam': idfam,
                 'water1': water1,
                 'water2': water2
             }, reponesDataComponentList)
         }else if (idfam != "" && idsub != "" && water != "") {
             getMethodData('/getComponentListFamSubWater', {
                 'idfam': idfam,
                 'water1': water1,
                 'water2': water2,
                 'idsub': idsub
             }, reponesDataComponentList)
         } else if (idfam == "" && idsub != "" && water != "") {
             getMethodData('/getComponentListSubWater', {
                 'water1': water1,
                 'water2': water2,
                 'idsub': idsub
             }, reponesDataComponentList)
         } else if (idfam == "" && idsub != "" && water == "") {
             getMethodData('/getComponentListSub', {
                 'idsub': idsub
             }, reponesDataComponentList)
         } else if (idfam == "" && idsub == "" && water != "") {
             getMethodData('/getComponentListWater', {
                 'water1': water1,
                 'water2': water2
             }, reponesDataComponentList)
         } else {
             getMethodData('/getComponentListAll', {}, reponesDataComponentList)
         }
     }
//  building checkbox component list
     var reponesDataComponentList = function(res) {
         if (res['listSubFamily'] != undefined) {
             reponesDataSubFamily(res['listSubFamily']);
         }
         var str = ' <optgroup id="group1" label="Select a component">',
             str2 = '<optgroup id="group2" label="Sleeping component database">';
         for (var i in res['compList']) {
             if (res['compList'][i].compRelease != 6) {
                 str += '<option value="' + res['compList'][i].idComp + '">' + res['compList'][i].label + '</option>';
             } else {
                 str2 += '<option value="' + res['compList'][i].idComp + '">' + res['compList'][i].label + '</option>';
             }
         }
         str += '</optgroup>';
         str2 += '</optgroup>';
         var strAll = '<option selected value="">-- None --</option>';
         strAll += str;
         strAll += str2;
         $("#component-list").html(strAll);
         $('#component-list').trigger('chosen:updated');
     };
     $("#idConfirm-Comp").on("click", function(event) {
         var dim2 = $("#specific-Dim").val();
         if (dim2 < objMinMax.limitmin2 || dim2 > objMinMax.limitmax2 ||  dim2=="") {
             var text="Value out of range in Dimension 2 (" + objMinMax.limitmin2 + " : " + objMinMax.limitmax2+")";
             toast(text,'error');
             $("#specific-Dim").focus();
             return;
         } else if(isNaN(dim2)){
            var text="Not a valid number in Dimension 2 !";
            toast(text,'error');
             $("#specific-Dim").focus();
         }else if(dim2 == "" || hasWhiteSpace(dim2)){
            var text="Enter a value in Dimension 2 !";
            toast(text,'error');
            $("#specific-Dim").focus();
         }else{
             $("#confirm-updateThickness").submit();
         }
     });
     $("#confirm-realweight-comp").on("click", function(event) {
        var mass = $("#in-realweight-comp").val();
        if (mass < objMinMax.limitminmass || mass > objMinMax.limitmaxmass) {
            var text="Value out of range in Mass (" + objMinMax.limitminmass + " : " + objMinMax.limitmaxmass+")";
            toast(text,'error');
            $("#in-realweight-comp").focus();
            return;
        } else if(isNaN(mass)){
            var text="Not a valid number in Real Weight !";
            toast(text,'error');
            $("#in-realweight-comp").focus();
            return;
        }else if(mass == "" || hasWhiteSpace(mass)){
            var text="Enter a value in Real Weight !";
            toast(text,'error');
            $("#in-realweight-comp").focus();
            return;
        }else {
             $("#modal-edit-realmass-comp").submit();
         }

     });
     
 });

 function getWater(water) {
     return [water*10, (++water)*10];
 }