
var ID_COOLING_FAMILY_LN = 2, 
ID_COOLING_FAMILY_CO2 = 3,
listDataToClickConveyor = {},
shelvesValaueCurr={};
var checkPipeLine=false;
var objMinMaxEquip=[], minMaxEquip={}, idStudyEquipmentClient = 0;
$(document).ready(function(){
	$("#id-refrigeration").on('change',function(){
		processEquipment();
	});
	$("#id-manufacturer").on('change',function(){
		processEquipment();
	});
	$("#id-equipseries").on('change',function(){
		processEquipment();
	});
	$("#id-equiporigin").on('change',function(){
		processEquipment();
	});
	$("#id-processtype").on('change',function(){
		processEquipment();
	});
	$("#id-model").on('change',function(){
		processEquipment();
	});
	$("#id-size").on('change',function(){
		processEquipment();
	});
	function processEquipment(){
		var refri=$("#id-refrigeration").val();
		if(checkPipeLine  && (refri =="" || (refri !=ID_COOLING_FAMILY_LN && refri !=ID_COOLING_FAMILY_CO2))){
			if(confirm("The option line is compatible only with the type of energy LN or CO2. Do you want to come back on page 'Definition' to modify the options of the study?")){
				window.location.href='/open';
			}else{
				$('#id-refrigeration').val(ID_COOLING_FAMILY_LN);
				refri=ID_COOLING_FAMILY_LN;
        		$('#id-refrigeration').trigger("chosen:updated");
        		$("#id-manufacturer").val("").trigger("chosen:updated");
				$("#id-equipseries").val("").trigger("chosen:updated");
				$("#id-equiporigin").val("").trigger("chosen:updated");
				$("#id-processtype").val("").trigger("chosen:updated");
				$("#id-model").val("").trigger("chosen:updated");
				$("#id-size").val("").trigger("chosen:updated");
			}
		}

		var manu=$("#id-manufacturer").val();
		var series=$("#id-equipseries").val();
		var origin=$("#id-equiporigin").val();
		var proType=$("#id-processtype").val();
		var model=$("#id-model").val();
		var size=$("#id-size").val();
		var dataSend={
			'idRefri':refri,
			'idManu':manu,
			'idSeries':series,
			'idOrigin':origin,
			'idProcess':proType,
			'idModel':model,
			'idSize':size
		};
		getMethodData("/searchEquipment",dataSend,function(res){
			buldingManufacturer(res.listManufacturer,manu);
			buldingEquipSeries(res.listEquipSeries,series);
			buldingEquipOrigin(res.listEquipOrigin,origin);
			buldingProcessType(res.listProcessType,proType);
			buldingModel(res.listModel,model);
			buldingSize(res.listSize,size);
			buldingEquipment(res.listEquipment,dataSend);
		});
	}
	function buldingEquipment(res,pos){
		var str='';
        for(var i in res){
	        str+='<option value="'+res[i].idEquip+'">'+ res[i].equipName+' - '+res[i].equipVersion+'</option>';
        }
        $("#id-equipment").html(str);
        $('#id-equipment').trigger('chosen:updated');
	}
	function buldingManufacturer(res,pos){
        var str='<option selected value="-1">-- All --</option>';
        for(var i in res){
        	if(pos== res[i].constructor){
        		 str+='<option selected value="'+res[i].constructor+'">'+ res[i].constructor+'</option>';
        	}else{
        		 str+='<option value="'+res[i].constructor+'">'+ res[i].constructor+'</option>';
        	}
        }
        $("#id-manufacturer").html(str);
        $('#id-manufacturer').trigger('chosen:updated');

	}
	function buldingEquipSeries(res,pos){
		var str='<option selected value="-1">-- All --</option>';
        for(var i in res){
        	if(pos==res[i].idFamily){
        		str+='<option selected value="'+res[i].idFamily+'">'+ res[i].label+'</option>';
        	}else{
        		str+='<option value="'+res[i].idFamily+'">'+ res[i].label+'</option>';
        	}
        }
        $("#id-equipseries").html(str);
        $('#id-equipseries').trigger('chosen:updated');
	}
	function buldingEquipOrigin(res,pos){
		var str='<option selected value="-1">-- All --</option>';
        for(var i in res){
        	if(pos==res[i].std){
        		str+='<option selected value="'+res[i].std+'">'+ res[i].label+'</option>';
        	}else{
        		str+='<option value="'+res[i].std+'">'+ res[i].label+'</option>';
        	}
        }
        $("#id-equiporigin").html(str);
        $('#id-equiporigin').trigger('chosen:updated');
	}
	function buldingProcessType(res,pos){
		var str='<option selected value="-1">-- All --</option>';
        for(var i in res){
        	if(pos==res[i].batchProcess){
        	    str+='<option selected value="'+res[i].batchProcess+'">'+ res[i].label+'</option>';        	
        	}else{
        		str+='<option value="'+res[i].batchProcess+'">'+ res[i].label+'</option>';
        	}
        }
        $("#id-processtype").html(str);
        $('#id-processtype').trigger('chosen:updated');
	}
	function buldingModel(res,pos){
		var str='<option selected value="-1">-- All --</option>';
        for(var i in res){
        	if(pos==res[i].idEquipseries){
        		str+='<option selected value="'+res[i].idEquipseries+'">'+ res[i].label+'</option>';
        	}else{
        		str+='<option value="'+res[i].idEquipseries+'">'+ res[i].label+'</option>';
        	}
        }
        $("#id-model").html(str);
        $('#id-model').trigger('chosen:updated');
	}
	function buldingSize(res,pos){
		var str='<option selected value="-1">-- All --</option>';
        for(var i in res){
        	var p=res[i].eqpLength+'x'+res[i].eqpWidth
        	if(pos == p){
        		 str+='<option selected value="'+res[i].eqpLength+'x'+res[i].eqpWidth+'">'+ res[i].eqpLength+' x '+res[i].eqpWidth+'</option>';
        	}else{
        		 str+='<option value="'+res[i].eqpLength+'x'+res[i].eqpWidth+'">'+ res[i].eqpLength+' x '+res[i].eqpWidth+'</option>';
        	}
        }
        $("#id-size").html(str);
        $('#id-size').trigger('chosen:updated');
	}
// click result item equipment
	$("#id-equipment").on('click',function(){
		if($('#id-equipment :selected').hasClass('pos-equipment-modal')){
			$(".input-equip-size-lenght").val("").focus();
			$(".input-equip-size-width").val("");
			$("#modal-equip-size").modal("show");
			$("#id-form-equip-size").attr("action","/updateEquipmentSizeIdEquip");
			$(".idSE").val($(this).val());
		}else{
			$("#idform-addEquipment").submit();
		}
	});


// onchange price of Cryogen
	$("#input-price-cryogen").on('change',function(){
		var price= $("#input-price-cryogen").val();
		if(price > objMinMaxEquip.maxPrice || price < objMinMaxEquip.minPrice){
			var text='Value out of range in Price of the Cryogen ('+ objMinMaxEquip.minPrice +' : '+objMinMaxEquip.maxPrice+') !';
			toast(text,'error');
			$("#input-price-cryogen").focus();
		}else if(isNaN(price)){
			var text="Not a valid number in Price of the Cryogen !";
			toast(text,'error');
			$("#input-price-cryogen").focus();
		}else if(price == "" || hasWhiteSpace(price)){
			var text="Enter a value in Price of the Cryogen !";
			toast(text,'error');
			$("#input-price-cryogen").focus();
		}else{
			var arr=[];
	        $('.input-checked-economic:checked').each(function () {
	          	arr.push($(this).val());
	       	});
	        if(arr.length==0){
	        	arr.push(0);
	        }
			$("#idform-addEquipment").attr("action","/onChangePriceEquip/"+arr);
			$("#idform-addEquipment").submit();
		}
	});
	$('#input-price-cryogen').keyup(function(e){
		var price= $("#input-price-cryogen").val();
	    if(e.keyCode == 13){
	       if(price > objMinMaxEquip.maxPrice || price < objMinMaxEquip.minPrice){
				var text='Value out of range in Price of the Cryogen ('+ objMinMaxEquip.minPrice +' : '+objMinMaxEquip.maxPrice+') !';
				toast(text,'error');
				$("#input-price-cryogen").focus();
			}else if(isNaN(price)){
				var text='Not a valid number in Price of the Cryogen !';
				toast(text,'error');
				$("#input-price-cryogen").focus();
			}else if(price == "" || hasWhiteSpace(price)){
				var text='Enter a value in Price of the Cryogen !';
				toast(text,'error');
				$("#input-price-cryogen").focus();
			}else{
				var arr=[];
		        $('.input-checked-economic:checked').each(function () {
		          	arr.push($(this).val());
		       	});
				$("#idform-addEquipment").attr("action","/onChangePriceEquip/"+arr);
				$("#idform-addEquipment").submit();
			}
	    }
	});
// onchange Loading Rate frame
	$("#input-equip-lenght").on('change',function(){
		var lenght= $(this).val();
		if(lenght > objMinMaxEquip.maxL || lenght < objMinMaxEquip.minL){
			var text='Value out of range in Spaces (length and width) ('+ objMinMaxEquip.minL +' : '+objMinMaxEquip.maxL+') !';
			toast(text,'error');
			$(this).focus();
		}else if(isNaN(lenght)){
			var text='Not a valid number in Spaces !';
			toast(text,'error');
			$(this).focus();
		}else if(lenght == "" || hasWhiteSpace(lenght)){
			var text='Enter a value in Spaces !';
			toast(text,'error');
			$(this).focus();
		}else{
			$("#modal-equip-lenght-width").modal("show");
		}
	});
	$('#input-equip-lenght').keyup(function(e){
	    if(e.keyCode == 13)
	    {
	        var lenght= $(this).val();
			if(lenght > objMinMaxEquip.maxL || lenght < objMinMaxEquip.minL){
				var text='Value out of range in Spaces (length and width) ('+ objMinMaxEquip.minL +' : '+objMinMaxEquip.maxL+') !';
				toast(text,'error');
				$(this).focus();
			}else if(isNaN(lenght)){
				var text='Not a valid number in Spaces !';
				toast(text,'error');
				$(this).focus();
			}else if(lenght == "" || hasWhiteSpace(lenght)){
				var text='Enter a value in Spaces !';
				toast(text,'error');
				$(this).focus();
			}else{
				$("#modal-equip-lenght-width").modal("show");
			}
	    }
	});
	$("#input-equip-width").on('change',function(){
		var width= $(this).val();

		if(width > objMinMaxEquip.maxW || width < objMinMaxEquip.minW){
			var text='Value out of range in Spaces (length and width) ('+ objMinMaxEquip.minW +' : '+objMinMaxEquip.maxW+') !';
			toast(text,'error');
			$(this).focus();
		}else if(isNaN(width)){
			var text='Not a valid number in Spaces !';
			toast(text,'error');
			$(this).focus();
		}else if(width == "" || hasWhiteSpace(width)){
			var text='Enter a value in Spaces !';
			toast(text,'error');
			$(this).focus();
		}else{
			$("#modal-equip-lenght-width").modal("show");
		}
	});
	$('#input-equip-width').keyup(function(e){
	    if(e.keyCode == 13)
	    {
	       var width= $(this).val();

			if(width > objMinMaxEquip.maxW || width < objMinMaxEquip.minW){
				var text='Value out of range in Spaces (length and width) ('+ objMinMaxEquip.minW +' : '+objMinMaxEquip.maxW+') !';
				toast(text,'error');
				$(this).focus();
			}else if(isNaN(width)){
				var text='Not a valid number in Spaces !';
				toast(text,'error');
				$(this).focus();
			}else if(width == "" || hasWhiteSpace(width)){
				var text='Enter a value in Spaces !';
				toast(text,'error');
				$(this).focus();
			}else{
				$("#modal-equip-lenght-width").modal("show");
			}
	    }
	});

// click confirm when change lenght ang width
	$("#btn-confirm-lenght-width").on('click',function(){
		$("#idform-addEquipment").attr("action","/onChangeEquipLenght");
		$("#idform-addEquipment").submit();
	});
	$("#btn-unseletect-all").on('click',function(){
		$("#form-select-equipment").attr("action","/unselectall");
		$("#form-select-equipment").submit();
	});
//delete StudyEquipment
	$("#btn-confirm-delete-study-equip").on('click',function(){
		$("#form-del-study-equip").submit();
	})
// onclick position No
	$(".id-no-modal").on('click',function(e){
		e.preventDefault();
		$idStudyEquip=$(this).data('id');
		var name = $(this).closest('tr').data('nameequipment');
		$(".pos-name-euiqpment").html(name);
		getMethodData('/modalOperatingSettings',{'id':$idStudyEquip},function(res){
			$('#paramOS').modal('show');
// show array value TS 
			buildingShowTsTrVc(res.Ts,'id-dwell-time','ts');
// show array value TR
			buildingShowTsTrVc(res.Tr,'id-control-temper','tr');
// show array value VC
			buildingShowTsTrVc(res.Vc,'id-convection-setting','vc');
// check display btn Control Temperature
			if(!res.btnCoTemper){
				$("#btn-ConTemper").prop('disabled',true);
			}
// check display btn Compute Residence/ Dwell Time
			if(!res.btnResidence){
				$("#btn-Residence").prop('disabled',true);
			}
// check disable input Control Temperature (TR)
			if(!res.inputTr){
				$(".input-modal-tr").prop('disabled',true);
			}
// check disable input Convection Setting (VC)
			if(!res.inputVc){
				$(".input-modal-vc").prop('disabled',true);
			}
// check display gas temperature
			if(res.frameGasTemper){
				$(".frame-gastemperature").removeClass('hidden');
// show Gas Temperature table
				$(".input-modal-gas").val(res.TExt);
				buildingShowTableGasTemper(res.Gas);
			}

// set MinMix TR, TS, VC, Alpha
			minMaxEquip= res.minMaxEquip;
// check show icon equip
			if(res.iconEquip){
				$("#id-image-iconequip").html('<img src="./assets/img/ref_equip/change_tr.gif" />');
			}
// check and show 6 position alpha
			buildingShowAlpha(res.Alpha);
		});
	});
	function buildingShowAlpha(res){
		if(res.topfixed){
			$(".checkbox-top").prop('checked',true);
			$(".input-top").prop('disabled',false).val(res.top);
		}
		$(".input-top").val(res.top);
		if(res.buttomfixed){
			$(".checkbox-buttom").prop('checked',true);
			$(".input-buttom").prop('disabled',false).val(res.buttom);
		}
		$(".input-buttom").val(res.buttom);
		if(res.leftfixed){
			$(".checkbox-left").prop('checked',true);
			$(".input-left").prop('disabled',false).val(res.left);
		}
		$(".input-left").val(res.left);
		if(res.rightfixed){
			$(".checkbox-right").prop('checked',true);
			$(".input-right").prop('disabled',false).val(res.right);
		}
		$(".input-right").val(res.right);
		if(res.frontfixed){
			$(".checkbox-front").prop('checked',true);
			$(".input-front").prop('disabled',false).val(res.front);
		}
		$(".input-front").val(res.front);
		if(res.rearfixed){
			$(".checkbox-rear").prop('checked',true);
			$(".input-rear").prop('disabled',false).val(res.rear);
		}
		$(".input-rear").val(res.rear);
	}
	function buildingShowTsTrVc(res,pos,spec){
		var str='';
		for(var item in res){
			str+='<input type="text" value="'+res[item]+'" name="" class="form-control input-modal-'+spec+'" placeholder="'+res[item]+'">';
		}
		$('#'+pos).html(str==''?'<input type="text" value="0" name="" class="form-control input-modal-'+spec+'" placeholder="0.0">':str);
	}
	function buildingShowTableGasTemper(res){
		var strTR='<tr><th scope="row">Control Temperature (°C)</th>';
		var strTExt='<th scope="row">Gas Temperature (°C)</th>';
		for(var item in res){
			strTR+='<td>'+res[item].Tr+'</td>';
			strTExt+='<td>'+res[item].TExt+'</td>'
		}
		strTR+='</tr>';
		strTExt+='</tr>';

		$("#posshow-table-gastemperature").html(strTR+strTExt);

	}
// click 6 checkbox checkbox Convection Coefficient (Alpha Coefficient)
	$(".checkbox-top").on('click',function(){
		$(this).is(':checked') ?
			$(".input-top").prop('disabled',false)
		:
			$(".input-top").prop('disabled',true);
			
            // $.toast().reset('all');
                
	});
	$(".checkbox-buttom").on('click',function(){
		$(this).is(':checked') ?
			$(".input-buttom").prop('disabled',false)
		:
			$(".input-buttom").prop('disabled',true);
	});
	$(".checkbox-left").on('click',function(){
		$(this).is(':checked') ?
			$(".input-left").prop('disabled',false)
		:
			$(".input-left").prop('disabled',true);
	});
	$(".checkbox-right").on('click',function(){
		$(this).is(':checked') ?
			$(".input-right").prop('disabled',false)
		:
			$(".input-right").prop('disabled',true);
	});
	$(".checkbox-front").on('click',function(){
		$(this).is(':checked') ?
			$(".input-front").prop('disabled',false)
		:
			$(".input-front").prop('disabled',true);
	});
	$(".checkbox-rear").on('click',function(){
		$(this).is(':checked') ?
			$(".input-rear").prop('disabled',false)
		:
			$(".input-rear").prop('disabled',true);
	});
// click btn Compute Control Temperture
	$("#btn-ConTemper").on('click',function(){
		var arr=[], checkTs=true;
		$('.input-modal-ts').each(function(){
			if($(this).val() < minMaxEquip.minMaxTs.limitMin || $(this).val()> minMaxEquip.minMaxTs.limitMax ){
				var text="Value out of range in Residence / Dwell time ("+ minMaxEquip.minMaxTs.limitMin 
				+":"+ minMaxEquip.minMaxTs.limitMax +")!";
				toast(text,'error');
				$(this).focus();
				checkTs=false;
				return;
			}else if(isNaN($(this).val())){
				var text="Not a valid number in Residence / Dwell time !";
				toast(text,'error');
				$(this).focus();
				checkTs=false;
				return;
			}else if($(this).val()=="" || hasWhiteSpace($(this).val())){
				var text="Enter a value in Residence / Dwell time !";
				toast(text,'error');
				$(this).focus();
				checkTs=false;
				return;
			}
			arr.push($(this).val());
		});
		if(checkTs){
			$("#id-form-opperating-setting").attr("action","/calculateTr/"+arr);
			$("#id-form-opperating-setting").submit();
		}
	});	
// click btn Compute Residence/Dwell Time
	$("#btn-Residence").on('click',function(){
		var arr=[],checkTr=true;
		$('.input-modal-tr').each(function(){
			if($(this).val() < minMaxEquip.minMaxTr.limitMin || $(this).val()> minMaxEquip.minMaxTr.limitMax){
				var text="Value out of range in Control temperature ("+ minMaxEquip.minMaxTr.limitMin +":"+ minMaxEquip.minMaxTr.limitMax +")!";
				toast(text,'error');
				$(this).focus().val(minMaxEquip.minMaxTr.limitMin);
				checkTr=false;
				return;
			}else if(isNaN($(this).val())){
				var text="Not a valid number in Control temperature !";
				toast(text,'error');
				$(this).focus();
				checkTr=false;
				return;
			}else if($(this).val()=="" || hasWhiteSpace($(this).val())){
				var text="Enter a value in Control temperature !";
				toast(text,'error');
				$(this).focus();
				checkTr=false;
				return;
			}
			arr.push($(this).val());
		});
		if(checkTr){
			$("#id-form-opperating-setting").attr("action","/calculateTs/"+arr);
			$("#id-form-opperating-setting").submit();
		}
	});
// click btn CONFIRM modal 	Opperating Setting
	$("#id-confirm-modal-opperating-setting").on('click',function(event){
		var arrTS=[],checkTr=checkTs=checkVc=checkAlpha=checkGas=true;

		var arrVC=[];
		$('.input-modal-vc').each(function(){
			if(!$(this).prop('disabled')){
				if($(this).val() < minMaxEquip.minMaxVC.limitMin || $(this).val()> minMaxEquip.minMaxVC.limitMax ){
					var text="Value out of range in Control temperature ("+ minMaxEquip.minMaxVC.limitMin +":"+ minMaxEquip.minMaxVC.limitMax +")!";
					toast(text,'error');
					$(this).focus().val(minMaxEquip.minMaxVC.limitMin);
					return;
				}else if(isNaN($(this).val())){
					var text="Not a valid number in Control temperature !";
					toast(text,'error');
					$(this).focus();
					return;
				}else if($(this).val()=="" || hasWhiteSpace($(this).val())){
					var text="Enter a value in Control temperature !";
					toast(text,'error');
					$(this).focus();
					return;
				}
				arrVC.push($(this).val());
			}
		});
///////
////// UNITS_CONVERTER.convertToDouble(UNITS_CONVERTER):::: ts, tr,vc, alpha, gas
//////
		$('.input-modal-ts').each(function(){
			if($(this).val() < minMaxEquip.minMaxTs.limitMin || $(this).val()> minMaxEquip.minMaxTs.limitMax){
				var text="Value out of range in Residence / Dwell time ("+ minMaxEquip.minMaxTs.limitMin +":"+ minMaxEquip.minMaxTs.limitMax +")!";
				toast(text,'error');
				$(this).focus();
				checkTs=false;
				return;
			}else if(isNaN($(this).val())){
				var text="Not a valid number in Residence / Dwell time !";
				toast(text,'error');
				$(this).focus();
				checkTs=false;
				return;
			}else if($(this).val()=="" || hasWhiteSpace($(this).val())){
				var text="Enter a value in Residence / Dwell time !";
				toast(text,'error');
				$(this).focus();
				checkTs=false;
				return;
			}
			arrTS.push($(this).val());
		});
		if(checkTs){
			var arrTR=[];
			$('.input-modal-tr').each(function(){
				if($(this).val() < minMaxEquip.minMaxTr.limitMin || $(this).val()> minMaxEquip.minMaxTr.limitMax){
					var text="Value out of range in Control temperature ("+ minMaxEquip.minMaxTr.limitMin +":"+ minMaxEquip.minMaxTr.limitMax +")!";
					toast(text,'error');
					$(this).focus();
					checkTr=false;
					return;
				}else if(isNaN($(this).val())){
					var text="Not a valid number in Control temperature !";
					toast(text,'error');
					$(this).focus();
					checkTr=false;
					return;
				}else if($(this).val()=="" || hasWhiteSpace($(this).val())){
					var text="Enter a value in Control temperature !";
					toast(text,'error');
					$(this).focus();
					checkTr=false;
					return;
				}
				arrTR.push($(this).val());
			});
			if(checkTr){
				var arrVC=[];
				$('.input-modal-vc').each(function(){
					if(!$(this).prop('disabled')){
						if($(this).val() < minMaxEquip.minMaxVC.limitMin || $(this).val()> minMaxEquip.minMaxVC.limitMax){
							var text="Value out of range in Control temperature ("+ minMaxEquip.minMaxVC.limitMin 
								+":"+ minMaxEquip.minMaxVC.limitMax +")!";
							toast(text,'error');
							$(this).focus();
							checkVc=false;
							return;
						}else if(isNaN($(this).val())){
							var text="Not a valid number in Control temperature !";
							toast(text,'error');
							$(this).focus();
							checkVc=false;
							return;
						}else if($(this).val()=="" || hasWhiteSpace($(this).val())){
							var text="Enter a value in Control temperature !";
							toast(text,'error');
							$(this).focus();
							checkVc=false;
							return;
						}
						arrVC.push($(this).val());
					}
					
				});
				if(checkVc){
					var arrAlpha=[];
					$('.input-modal-alpha').each(function(){
						if(!$(this).prop('disabled')){
							if($(this).val() < minMaxEquip.minMaxAlpha.limitMin || $(this).val()> minMaxEquip.minMaxAlpha.limitMax ){
								var text="Value out of range in Alpha ("+ minMaxEquip.minMaxAlpha.limitMin 
									+":"+ minMaxEquip.minMaxAlpha.limitMax +")!";
								toast(text,'error');
								$(this).focus();
								checkAlpha=false;
								return false;
							}else if(isNaN($(this).val())){
								var text="Not a valid number in Alpha !";
								toast(text,'error');
								$(this).focus();
								checkAlpha=false;
								return false;
							}else if($(this).val()=="" || hasWhiteSpace($(this).val())){
								var text="Enter a value in Alpha !";
								toast(text,'error');
								$(this).focus();
								checkAlpha=false;
								return false;
							}
							arrAlpha.push($(this).val());
						}else{
							arrAlpha.push('');
						}
					});
					if(checkAlpha){
						var gas=null;
						if(!$('.frame-gastemperature').hasClass("hidden")){
							gas=$(".input-modal-gas").val();
							if(gas < minMaxEquip.minMaxTr.limitMin || gas> 0 ){
									var text="Value out of range in Gas Temperature ("+ minMaxEquip.minMaxTr.limitMin +":"+ 0 +")!";
									toast(text,'error');
									$(".input-modal-gas").focus();
									checkGas=false;
							}else if(isNaN(gas)){
								var text="Not a valid number in Gas Temperature !";
								toast(text,'error');
								$(".input-modal-gas").focus();
								checkGas=false;
							}else if(gas=="" || hasWhiteSpace(gas)){
								var text="Enter a value in Gas Temperature !";
								toast(text,'error');
								$(".input-modal-gas").focus();
								checkGas=false;
							}
						}
						if(checkGas){
							var dataSend={
								'listTS':arrTS,
								'listTR':arrTR,
								'listVC':arrVC,
								'listAlpha':arrAlpha,
								'TExt':gas
							};
							getMethodData('/setEquipmentData',dataSend,function(res){
								if(res.notice == 1){
									var text="List control temperature not NULL";
									toast("ERROR",text,'error');
								}
								if(res.notice == 2){
									var text="List Residence/ Dwell time not NULL";
									toast(text,'error');
								}
								if(res.notice == 3){
									var text="Convection Setting not NULL";
									toast(text,'error');
								}
								if(res.notice='success'){
									var text="Confirm success !! ";
									toast(text,'success');
								}
								$('#paramOS').modal('hide');
								location.reload();
							});
						}
					}
				}
			}
		}
	});
// click to HELP
	var countShow=1;
	$("#help-gastemperature").on('click',function(){
		if(countShow==1){
			$(".table-gastemperature").removeClass("hidden");
			countShow=0;
		}else{
			$(".table-gastemperature").addClass("hidden");
			countShow=1;
		}
	});
// click to Name equipment in study equipment table and click confirm in modal equipment size
	$(".id-study-equip-name").on('click',function(e){
		e.preventDefault();
		var idStudyEquipCurr = $(this).closest('tr').prop('id');
		$(".idSE").val(idStudyEquipCurr);
		getMethodData('/getLengthWidthSE',{'id':idStudyEquipCurr},function(res){
			$(".input-equip-size-lenght").val(res.length);
			$('.input-equip-size-width').val(res.width);
			$("#modal-equip-size").modal("show");
		});
	});
	$("#btn-confirm-equipsize").on('click',function(){
		var length=$(".input-equip-size-lenght").val();
		var width=$('.input-equip-size-width').val();
		if(length < objMinMaxEquip.minSizeL || length>objMinMaxEquip.maxSizeL){
			var text="Value out of range in Length ("+objMinMaxEquip.minSizeL+" : "+objMinMaxEquip.maxSizeL+") !";
			toast(text,"error");
			$(".input-equip-size-lenght").focus();
			return;
		}
		if(isNaN(length)){
			var text="Not a valid number in Length !";
			toast(text,"error");
			$(".input-equip-size-lenght").focus();
			return;
		}
		if(length == "" || hasWhiteSpace(length)){
			var text="Enter a value in Length !";
			toast(text,"error");
			$(".input-equip-size-lenght").focus();
			return;
		}
		if(width < objMinMaxEquip.minSizeW || length>objMinMaxEquip.maxSizeW){
			var text="Value out of range in Width ("+objMinMaxEquip.minSizeW+" : "+objMinMaxEquip.maxSizeW+") !";
			toast(text,"error");
			$(".input-equip-size-width").focus();
			return;
		}
		if(isNaN(width)){
			var text="Not a valid number in Width !";
			toast(text,"error");
			$(".input-equip-size-width").focus();
			return;
		}
		if(width == "" || hasWhiteSpace(width)){
			var text="Enter a value in Width !";
			toast(text,"error");
			$(".input-equip-size-width").focus();
			return;
		}
		var idStudyEquipCurr=$(".idSE").val();
		if(idStudyEquipCurr != null){
			$("#id-form-equip-size").submit();
		}
	});
// click orientation
	$(".id-study-equip-orientation").on('click',function(e){
		e.preventDefault();
		var idStudyEquipCurr = $(this).closest('tr').prop('id');
		$(".name-equipment-modal-prodposition").html($(this).closest('tr').data('nameequipment'));
		$(".prodposition_idse").val(idStudyEquipCurr);
		if($(this).text() == "Perpendicular"){
			$(".prodposition-select").val(2);
		}else{
			$(".prodposition-select").val(1);
		}
		$("#modal-equip-std-eqp-prodposition").modal("show");
	});
	$(".btn-confirm-prodposition").on('click',function(){
		$("#id-form-prod-position").submit();
	});

	$(".btn-cancel-prodposition").on('click',function(){
		$("#id-form-prod-position").attr('action','/eventCancelProdPosition');
		$("#id-form-prod-position").submit();
	});
// click conveyor
	$(".id-study-equip-conveyor").on('click', function(e){
		e.preventDefault();
		var idStudyEquipCurr = $(this).closest('tr').prop('id');
		idStudyEquipmentClient = idStudyEquipCurr;
		$(".idSE").val(idStudyEquipCurr);
		var name = $(this).closest('tr').data('nameequipment');
		$(".name-equipment-modal-prodposition").html(name);
		getMethodData('/frameInputConveyor',{'idSE':idStudyEquipCurr},function(res){
			showDims = 0;
			listDataToClickConveyor = res;
			shelvesValaueCurr = res.shelvesValaue;
			// check capability exist
			if(res.checkCalculateConveyor == 1){
				// set value + check disabled length width
				$("#input-intervalslength").val(res.intervalsLength);
				$("#input-intervalswidth").val(res.intervalsWidth);
				if(res.lengthDisabled == "disabled"){
					$("#input-intervalslength, #input-intervalswidth").prop("disabled",true);
				}
				// check display compobox Shelves
				if(res.shelvesSelectDisabled == "disabled"){
					$("#shelves-select").prop('disabled', true)
				}
				// show display OUTPUT
				if(res.checkDisplayShelves == 1){
					$(".th-output-first").html('Side remaining interval');
					$(".th-output-second").html('Number per tunnel length');
				}else{
					$(".th-output-first").html('Width of Spaces');
					$(".th-output-second").html('Number of items per meter run');
				}
				var strTableOutput ="<td>"+res.dataOutput.LeftRightInterval +"</td><td>"+res.dataOutput.NumberPerM+"</td><td>"
				+res.dataOutput.NumberInWidth+"</td><td>"+res.dataOutput.QuantityPerBatch+"</td>"
				$(".tr-table-output").html(strTableOutput);
			}else{
				$(".checkdisplayspaces").addClass("hidden");
			}
			// check display (Shelves, Dimensions (Length x Width), Number of shelves)
			if(res.checkDisplayShelves == 0){
				$(".checkDisplayShelves").addClass("hidden");
			}else{
				$(".checkDisplayShelves").removeClass("hidden");
				$("#input-length-shelves").val(0.8).prop('disabled', true);
				$("#input-width-shelves").val(0.6).prop('disabled', true);
			}
			// set value  beltCoverage
			$("#input-belt-converage").val(res.beltCoverage);
			$("#tunnel").modal("show");
		});

	});
// event change compobox shelves 
	var showDims = 0;
	$("#shelves-select-equip").on('change', function(){
		var shelves = $(this).val();
		if(shelves == 0){
			$("#input-length-shelves").val(0.8).prop('disabled', true);
			$("#input-width-shelves").val(0.6).prop('disabled', true);
		}else if(shelves == 1){
			$("#input-length-shelves").val(0.65).prop('disabled', true);
			$("#input-width-shelves").val(0.53).prop('disabled', true);
		}else if(shelves == 2){
			$("#input-length-shelves").val(shelvesValaueCurr.shelvesLength).prop('disabled', false);
			$("#input-width-shelves").val(shelvesValaueCurr.shelvesWidth).prop('disabled', false);
		}
		showDims ++;
		if (showDims == 1) {
			showDims();
		}
	});
// event click calculate in form conveyor
	$("#btn-calculate-conveyor").on('click', function(){
		var intervalsLength = $("#input-intervalslength").val();
		var intervalsWidth = $("#input-intervalswidth").val();
		if(intervalsLength < objMinMaxEquip.minL || intervalsLength > objMinMaxEquip.maxL){
			var text="Value out of range in Length ("+objMinMaxEquip.minL+" : "+objMinMaxEquip.maxL+") !";
			toast(text,"error");
			$("#input-intervalslength").focus();
			return;
		}
		if(isNaN(intervalsLength)){
			var text = "Not a valid number in Intervals length !";
			toast(text, 'error');
			$("#input-intervalslength").focus();
			return;
		}
		if(intervalsLength == "" || hasWhiteSpace(intervalsLength) ){
			var text = "Enter a value in Intervals length !";
			toast(text, 'error');
			$("#input-intervalslength").focus();
			return;
		}
		if(intervalsWidth < objMinMaxEquip.minW || intervalsWidth > objMinMaxEquip.maxW){
			var text="Value out of range in Width ("+objMinMaxEquip.minW+" : "+objMinMaxEquip.maxW+") !";
			toast(text,"error");
			$("#input-intervalswidth").focus();
			return;
		}
		if(isNaN(intervalsWidth) ){
			var text = "Not a valid number in Intervals width ";
			toast(text, 'error');
			$("#input-intervalswidth").focus();
			return;
		}
		if(intervalsWidth == "" || hasWhiteSpace(intervalsWidth) ){
			var text = "Enter a value in Intervals width ";
			toast(text, 'error');
			$("#input-intervalswidth").focus();
			return;
		}
		if(listDataToClickConveyor.checkDisplayShelves == 1){
			var mmShelvesLength = listDataToClickConveyor.mmShelvesLength;
			var mmShelvesWidth = listDataToClickConveyor.mmShelvesWidth;
			var mmShelvesNb = listDataToClickConveyor.mmShelvesNb;
			var shelvesLength = $("#input-length-shelves").val();
			var shelvesWidth = $("#input-width-shelves").val();
			var numberShelves = $("#input-numberof-shelves").val();
 			if(shelvesLength < mmShelvesLength.minShelvesLength || shelvesLength > mmShelvesLength.maxShelvesLength ){
				var text = "Value out of range in Dimensions (length x width) ( "+ mmShelvesLength.minShelvesLength  +" : "+mmShelvesLength.maxShelvesLength+" ) !";
				toast(text, 'error');
				$("#input-length-shelves").focus();
				return;
			}
			if(isNaN(shelvesLength)){
				var text = "Not a valid number in Dimensions (length x width) !";
				toast(text, 'error');
				$("#input-length-shelves").focus();
				return;
			}
			if(shelvesLength == "" || hasWhiteSpace(shelvesLength) ){
				var text = "Enter a value in Dimensions (length x width) !";
				toast(text, 'error');
				$("#input-length-shelves").focus();
				return;
			}

			if(shelvesWidth < mmShelvesWidth.minShelvesWidth || shelvesWidth > mmShelvesWidth.maxShelvesWidth ){
				var text = "Value out of range in Dimensions (length x width) ( "+ mmShelvesWidth.minShelvesWidth  +" : "+mmShelvesWidth.maxShelvesWidth+" ) !";
				toast(text, 'error');
				$("#input-width-shelves").focus();
				return;
			}
			if(isNaN(shelvesWidth)){
				var text = "Not a valid number in Dimensions (length x width) !";
				toast(text, 'error');
				$("#input-width-shelves").focus();
				return;
			}
			if(shelvesWidth == "" || hasWhiteSpace(shelvesWidth) ){
				var text = "Enter a value in Dimensions (length x width) !";
				toast(text, 'error');
				$("#input-width-shelves").focus();
				return;
			}

			if(numberShelves < mmShelvesNb.minShelvesNb || numberShelves > mmShelvesNb.maxShelvesNb){
				var text = "Value out of range in Number of shelves ( "+ mmShelvesNb.minShelvesNb  +" : "+mmShelvesNb.maxShelvesNb+" ) !";
				toast(text, 'error');
				$("#input-numberof-shelves").focus();
				return;
			}
			if(isNaN(numberShelves)){
				var text = "Not a valid number in Number of shelves !";
				toast(text, 'error');
				$("#input-numberof-shelves").focus();
				return;
			}
			if(numberShelves == "" || hasWhiteSpace(numberShelves)){
				var text = "Enter a value in Number of shelves !";
				toast(text, 'error');
				$("#input-numberof-shelves").focus();
				return;
			}
		}
		var orientation = $(".class-orientation-select").val();
		var dataSend = {
			'_idSE' : idStudyEquipmentClient,
			'_intervalslength' : intervalsLength,
			'_intervalswidth' : intervalsWidth,
			'_orientation' : orientation,
			'_lengthshelves' : shelvesLength != undefined ? shelvesLength : 0,
			'_widthshelves' : shelvesWidth != undefined ? shelvesWidth : 0,
			'_numberofshelves' : numberShelves != undefined ? numberShelves : 0
		};
		getMethodData('/calculateconveyor', dataSend, function(res){
			if(res.status == 1){
				var text = "Calculate success !!";
				toast(text, 'success');
			}
		});
		
		// $("#from-modal-conveyor").attr('action', '/calculateconveyor');
		// $("#from-modal-conveyor").submit();
	});
	$("#btn-confirm-conveyor").on('click', function(){
		var intervalsLength = $("#input-intervalslength").val();
		var intervalsWidth = $("#input-intervalswidth").val();
		if(intervalsLength == "" || isNaN(intervalsLength) ){
			var text = "Enter length ";
			toast(text, 'error');
			$("#input-intervalslength").val("").focus();
			return;
		}
		if(intervalsWidth == "" || isNaN(intervalsWidth) ){
			var text = "Enter width ";
			toast(text, 'error');
			$("#input-intervalswidth").val("").focus();
			return;
		}
		if(listDataToClickConveyor.checkDisplayShelves == 1){
			var mmShelvesLength = listDataToClickConveyor.mmShelvesLength;
			var mmShelvesWidth = listDataToClickConveyor.mmShelvesWidth;
			var mmShelvesNb = listDataToClickConveyor.mmShelvesNb;
			var shelvesLength = $("#input-length-shelves").val();
			var shelvesWidth = $("#input-width-shelves").val();
			var numberShelves = $("#input-numberof-shelves").val();
 			if(shelvesLength == "" || isNaN(shelvesLength) || shelvesLength < mmShelvesLength.minShelvesLength 
				|| shelvesLength > mmShelvesLength.maxShelvesLength ){
				var text = "Value out of range in Dimensions (length x width) ( "+ mmShelvesLength.minShelvesLength  +" : "+mmShelvesLength.maxShelvesLength+" ) !";
				toast(text, 'error');
				return;
			}
			if(shelvesWidth == "" || isNaN(shelvesWidth) || shelvesWidth < mmShelvesWidth.minShelvesWidth 
				|| shelvesWidth > mmShelvesWidth.maxShelvesWidth ){
				var text = "Value out of range in Dimensions (length x width) ( "+ mmShelvesWidth.minShelvesWidth  +" : "+mmShelvesWidth.maxShelvesWidth+" ) !";
				toast(text, 'error');
				return;
			}
			if(numberShelves == "" || isNan(numberShelves) || numberShelves < mmShelvesNb.minShelvesNb || numberShelves > mmShelvesNb.maxShelvesNb){
				var text = "Value out of range in Number of shelves ( "+ mmShelvesNb.minShelvesNb  +" : "+mmShelvesNb.maxShelvesNb+" ) !";
				toast(text, 'error');
				return;
			}
		}
		var toc = $("#input-belt-converage").val();
		var idShape = listDataToClickConveyor.idShape;
		var maxToc = 100;
		if(idShape == 4 || idShape == 6 || idShape == 7){
			maxToc == 78.54;
		}
		if(toc == "" || isNaN(toc) || toc < 0 || toc > maxToc){
			var text = "Value out of range in Belt coverage ( "+ 0 + ":" + maxToc + " )";
			toast(text, 'error');
			return;
		}
		$("#from-modal-conveyor").submit();
	});
	$("#input-belt-converage").on('change', function(){
		userTocManuel();
	});






});
//// check ajax error
// $( document ).ajaxError(function( event, request, settings) {
// 	var msg = '';
// 	if (request.status === 0) {
// 		msg = 'Not connect.\n Verify Network.';
// 	} else if (request.status == 404) {
// 		msg = 'Requested page not found. '+settings.url+' [404]';
// 	} else if (request.status == 500) {
// 		msg = 'Internal Server Error [500].';
// 	} 
// 	toast(msg, 'error');
// });
(function () {
    getMethodData("/getDataPipeline",{},function(res){
         checkPipeLine=res.optionCryopipeline;
    });
}());
(function () {
    getMethodData("/getAllMinMaxEquip",{},function(res){
       	objMinMaxEquip=res;
    });
}());
function getMethodData(url,data,callback) {
    $.ajax({
            url: url,
            type: 'POST',
            data:data,
            dataType: 'json',
            success: function(data) {
                callback(data);
            },
	});
}
function toast(text,icon){
	$.toast({
        heading: "Notification",
        text: text,
        showHideTransition: 'fade',
        position: 'top-center',
        loader: false,
        icon: icon,
        allowToastClose: false
    });
}
function hasWhiteSpace(s) {
  return s.indexOf(' ') >= 0;
}
function showDims(){
	getMethodData('/showDims', {}, function(res){});
}
function userTocManuel(){
	getMethodData('/UserTocManuel', {}, function(res){});
}