$(document).ready(function(){
	
	// form new study
	checkboxChainingClick();
	$("#id-open-study").on('click', function(){
		checkboxChainingClick();
		getMethodData('/setSessionOpenStudy', {}, function(res){});
	});

	// form open study
	$("#id-new-study").on('click', function(){
		checkboxChainingClick();
		getMethodData('/setSessionNewStudy', {}, function(res){});
	});

	$("#id-select-user").on('change', function(){
		$("#load-form").attr("action", "/searchStudy").submit();
	});

	$("#id-select-comp-family").on('change', function(){
		$("#id-select-subfamily").val(0).trigger("chosen:updated");
		$("#id-select-comp").val(0).trigger("chosen:updated");
		$("#load-form").attr("action", "/searchStudy").submit();
	});

	$("#id-select-subfamily").on('change', function(){
		$("#id-select-comp").val(0).trigger("chosen:updated");
		$("#load-form").attr("action", "/searchStudy").submit();
	});

	$("#id-select-comp").on('change', function(){
		$("#load-form").attr("action", "/searchStudy").submit();
	});

	$('#studyNameLs').on('change', function() {
	    $("#load-form").attr("action", "/selectStudy").submit();
	});

	$('#delete').click(function() {
	    $("#modal-waring-delete-study").modal("show");
	});

	$("#id-confirm-delete-study").on('click', function(){
		$("#load-form").attr("action", "/deletestudy").submit();
	});

	$('#saveAs').click(function() {
	    //change action for form
	    $("#load-form").attr("action", "/save");
	    //form.submit
	    document.getElementById("#load-form").submit();
	})

	$('#update').click(function() {
	    //change action for form
	    $("#load-form").attr("action", "/update");
	    //form.submit
	    document.getElementById("#load-form").submit();
	})

	$('#studyNameLs').change(function() {
	    $('#update, #delete, #saveAs').prop("disabled", true);
	    
	    if ($(this).val() === "choose study") {
	        $('#update, #delete, #saveAs').prop("disabled", true);
	        document.getElementById("productionLink").style.pointerEvents = "none";
	        document.getElementById("linkpro").style.pointerEvents = "none";
	        document.getElementById("linkMesh").style.pointerEvents = "none";
	        document.getElementById("linkPack").style.pointerEvents = "none";
	        document.getElementById("linkEqui").style.pointerEvents = "none";
	        document.getElementById("linkLine").style.pointerEvents = "none";
	    } else {
	        $('#update, #delete, #saveAs').prop("disabled", false);
	        document.getElementById("productionLink").style.pointerEvents = "visible";
	        document.getElementById("linkpro").style.pointerEvents = "visible";
	        document.getElementById("linkMesh").style.pointerEvents = "visible";
	        document.getElementById("linkPack").style.pointerEvents = "visible";
	        document.getElementById("linkEqui").style.pointerEvents = "visible";
	        document.getElementById("linkLine").style.pointerEvents = "visible";
	    }
	});

	// function using common
	$(".class-perform-chaining").on('click', function(){
		checkboxChainingClick();
	});

	$("#id-btn-chaining").on('click', function(){
		var comment = $("#comment").val();

		if(comment.length > 2000){
			$("#modal-warning-chaining").modal('show');
		}
	});
});

function checkboxChainingClick()
{
	if($(".class-perform-chaining").prop("disabled")){
		$(".class-adding-component").prop('checked', false);
		$(".class-adding-component").prop('disabled', true);

	}else if(!$(".class-perform-chaining").is(':checked')){
		$(".class-adding-component").prop('checked', false);
		$(".class-adding-component").prop('disabled', true);
		$(".class-cryogenic-pieline").prop('checked', false);
		$(".class-cryogenic-pieline").prop('disabled', false);

	}else{
		$(".class-adding-component").prop('disabled', false);
		$(".class-cryogenic-pieline").prop('disabled', true);		
		$(".class-cryogenic-pieline").prop('checked', false);
	}	
}