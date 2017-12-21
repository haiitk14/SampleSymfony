
$(function () {
    modalUnit();
    modalMoney();
    $('select.unit').on('change', function () {
        // console.log('changed');
        var that = $(this);
        var idunit = $(this).val();
        // alert(idunit);
        var label = $(this).find(':selected').data('label');
        // alert(label);

        var aId = $(this).find(':selected').data('aid');
        var bId = $(this).find(':selected').data('bid');

        var table = $(this).find(':selected').data('table');
        // /alert(aId);
        // alert(that.val());
        var id = that.val();
        $.ajax('/unitsManager',{
            data : {'id': id, 'table':table},
            type:'POST',
            dataType: 'json',
            success:function(ret){
                var info = ret;
                console.log(info);
                if(aId != ''){
                    $(aId).val(info.conCoeffA);
                    $(label).data('unita', info.conCoeffA);
                }

                if(bId != ''){
                    $(bId).val(info.conCoeffB);
                    $(label).data('unitb', info.conCoeffB);
                }
                
                // $(label).attr('data-idunit', idunit);
                $(label).data('idunit', idunit);

            }
        })
    });
    

});

function modalUnit(){
    $('body').on('click', '.modalPage', function (e) {
        e.preventDefault();
        var url = $(this).data('url');
        var idunit = $(this).data('idunit');
        var symbol = $(this).data('symbol');
        var type = $(this).data('type');
        var unita = $(this).data('unita');
        var unitb = $(this).data('unitb');

        if (!$('#model-cart-box').length) {
            var arr_model = [
                '<div class="modal fade has-loading" id="model-cart-box" tabindex="-1" role="dialog" aria-labelledby="ModalCartBox">',
                '<div class="modal-dialog modal-md" role="document">',
                '<div class="modal-content"></div>',
                '<div class="overlay">',
                '<div class="windows8">',
                '<div class="wBall" id="wBall_1"><div class="wInnerBall"></div></div>',
                '<div class="wBall" id="wBall_2"><div class="wInnerBall"></div></div>',
                '<div class="wBall" id="wBall_3"><div class="wInnerBall"></div></div>',
                '<div class="wBall" id="wBall_4"><div class="wInnerBall"></div></div>',
                '<div class="wBall" id="wBall_5"><div class="wInnerBall"></div></div>',
                '</div></div>',
                '</div>',
                '</div>'
            ];

            $('body').append(arr_model.join(''));
        }

        $('#model-cart-box').modal('show').addClass('loading');

        $.ajax({
            type: "POST",
            url: url,
            data:{'idunit':idunit, 'symbol': symbol, 'unita': unita, 'unitb': unitb, 'type':type},
            success:function(r){
                // $('#model-cart-box').modal('show').addClass('loading');
                $('#model-cart-box').removeClass('loading');
                $('#model-cart-box').find('.modal-content').html(r);
            }
        })
            .fail(function() {
                alert('Error!!! not load modal');
                 $('#model-cart-box').modal('hide').addClass('loading');
            });


    });
}
function modalMoney(){
    $('body').on('click', '.modalMoneyPage', function (me) {
        me.preventDefault();
        var url = $(this).data('url');
        var idmoney = $(this).data('idunit');
        var moneysym = $(this).data('symbol');
        var moneytext = $(this).data('unita');
       

        if (!$('#model-cart-box1').length) {
            var arr_model1 = [
                '<div class="modal fade has-loading" id="model-cart-box1" tabindex="-1" role="dialog" aria-labelledby="ModalCartBox">',
                '<div class="modal-dialog modal-md" role="document">',
                '<div class="modal-content"></div>',
                '<div class="overlay">',
                '<div class="windows8">',
                '<div class="wBall" id="wBall_1"><div class="wInnerBall"></div></div>',
                '<div class="wBall" id="wBall_2"><div class="wInnerBall"></div></div>',
                '<div class="wBall" id="wBall_3"><div class="wInnerBall"></div></div>',
                '<div class="wBall" id="wBall_4"><div class="wInnerBall"></div></div>',
                '<div class="wBall" id="wBall_5"><div class="wInnerBall"></div></div>',
                '</div></div>',
                '</div>',
                '</div>'
            ];

            $('body').append(arr_model1.join(''));
        }

        $('#model-cart-box1').modal('show').addClass('loading');

        $.ajax({
            type: "POST",
            url: url,
            data:{'idmoney': idmoney, 'moneysym': moneysym, 'moneytext': moneytext},
            success:function(m){
                // $('#model-cart-box').modal('show').addClass('loading');
                $('#model-cart-box1').removeClass('loading');
                $('#model-cart-box1').find('.modal-content').html(m);
            }
        })
            .fail(function() {
                alert('Error!!! not load modal');
                 $('#model-cart-box').modal('hide').addClass('loading');
            });


    });
}
