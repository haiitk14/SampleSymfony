function show(nr) {
    document.getElementById("graph-value1").style.display = "none";
    document.getElementById("graph-value2").style.display = "none";
    document.getElementById("graph-value" + nr).style.display = "block";
}


function showGraph(nr) {
    document.getElementById("getGraph1").style.display = "none";
    document.getElementById("getGraph" + nr).style.display = "block";
}

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
            $('#blah')
                .attr('src', e.target.result)
                .width(580)
                .height(350);
        };

        reader.readAsDataURL(input.files[0]);
    }
}

function readURL1(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
            $('#blah1')
                .attr('src', e.target.result)
                .width(580)
                .height(350);
        };

        reader.readAsDataURL(input.files[0]);
    }
}

function showReport(nr) {
    document.getElementById("graph-value1").style.display = "none";
    document.getElementById("graph-value2").style.display = "none";
    document.getElementById("graph-value3").style.display = "none";
    document.getElementById("graph-value" + nr).style.display = "block";
}

function showGraph(nr) {
    document.getElementById("getGraph1").style.display = "none";
    document.getElementById("getGraph" + nr).style.display = "block";
}

$(function() {
    $('.chosen-select').chosen({
        width: "100%"
    });
    $('.chosen-select-deselect').chosen({
        allow_single_deselect: true
    });
});

$(function() {
    $('#example1').DataTable();
    $("#tableComponent").DataTable({
        /* Disable initial sort */
       "order": []  
    });
})

$(document).ready(function() {
    $("#calculationSel1").click(function() {
        window.chay = setInterval(function() {
            $("#print").parent().after("Name component <br>");
        }, 1000);
        window.chay2 = setInterval(function() {
            $("#process").parent().after("In process... <br> ");
        }, 1000);
        $("#parent_met").click(function() {
            clearInterval(window.chay);
        });
        $("#parent_met").click(function() {
            clearInterval(window.chay2);
        });
    });
});

$('#custom-headers').multiSelect({
    selectableHeader: "<div class='custom-header'>Equipment List</div>",
    selectionHeader: "<div class='custom-header'>Active Equipment</div>"
});

$('#custom-headers1').multiSelect({
    selectableHeader: "<div class='custom-header'>Equipment List</div>",
    selectionHeader: "<div class='custom-header'>Active Equipment</div>"
});

$(function() {
    //JS script
    $('.if-modal').on('click', function(e){
        e.preventDefault();
        var href = $(this).attr('href');
        var target = $(this).data('target');
        if(href!='#') {
            /*var modal = $(target);
            var modal_body = $(target + ' .modal-body');
            modal.on('show.bs.modal', function () {
                // use your remote content URL to load the modal body
                modal_body.load(href);
            }).modal();*/
            //console.log(target, $(target));

            //$(target).modal('show').find('.modal-body').load(href);
            $(target).modal('show').find('iframe').attr('src',href);
            //$(target).modal('show').find('.modal-body').html('yyy');
            //$(target).show();
            //$(target).find('.modal-body').html('xxxx');
        }
    });
})