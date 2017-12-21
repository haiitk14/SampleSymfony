
$('.modalPageProdComp').click(function () {
    var id = $(this).data('productcomponentid');
    var pos = $(this).data('pos');
    // console.log(pos);
    var src = '/modifyProdcomp';
    $.ajax({
          url: src,
          data: {'id' : id, 'pos': pos},
          success: function(data){
            var info = data;
            $('#modal-InitialT .modal-body').html(data);
            $('#modal-InitialT').modal('show');
            unCheckInitialProdComp();
          } ,
        });
    // return false;
});

$(document).on('change', '#uncheckMeshTempPoint', function(unCheckInitialProdComp) {
  if(this.checked) {
    $("#modalMeshPoint").hide();
    $("#modal-initialProdComp").show();
  } else {
    $("#modalMeshPoint").show();
    $("#modal-initialProdComp").hide();
  }
});

$('#btnMeshParam').click(function(){
    var src = '/modalMeshParam';
    $.ajax({
          url: src,
          data: {},
          success: function(data){
            $('#modal-MeshPara .modal-body').html(data);
            $('#modal-MeshPara').modal('show');
          } 
        });
});

$('#btnMeshDefault').click(function(){
    var src = '/modalMeshDef';
    $.ajax({
          url: src,
          data: {},
          success: function(data){
            $('#modal-MeshDefault .modal-body').html(data);
            $('#modal-MeshDefault').modal('show');
          } 
        });
});

$('#btnMeshBuilder').click(function(){
    var src = '/modalMeshBuilder';
    $.ajax({
          url: src,
          data: {},
          success: function(data){
            // $(window).load(function(){ 
            $('#modal-MeshBuilder .modal-body').html(data);
            $('#modal-MeshBuilder').modal('show');
          // });
      }
  });
});

$('#btnMeshResult').click(function(){
    var src = '/modalMeshRes';
    $.ajax({
          url: src,
          data: {},
          success: function(data){
            $('#modal-MeshResult .modal-body').html(data);
            $('#modal-MeshResult').modal('show');
          } 
        });
});

