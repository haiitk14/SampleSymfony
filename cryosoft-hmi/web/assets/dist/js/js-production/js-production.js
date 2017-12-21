var minMaxProdtion={};
        (function(){
            getMethodData("/getAllMinMaxProduction",{},function(res){
                minMaxProdtion = res;
            });
        }());

        $(document).ready(function(){
            $("#confirm-production").on('click',function(){
                var p1=$("input[name=_DAILY_PROD]").val();
                var p2=$("input[name=_WEEKLY_PROD]").val();
                var p3=$("input[name=_NB_PROD_WEEK_PER_YEAR]").val();
                var p4=$("input[name=_DAILY_STARTUP]").val();
                var p5=$("input[name=_AMBIENT_TEMP]").val();
                var p6=$("input[name=_AMBIENT_HUM]").val();
                var p7=$("input[name=_AVG_T_DESIRED]").val();
                var p8=$("input[name=_PROD_FLOW_RATE]").val();

                // console.log(p4);
                // console.log(p5);
                if(p1 < minMaxProdtion.p1min || p1 > minMaxProdtion.p1max){
                    var text="Daily production ("+minMaxProdtion.p1min+" : "+minMaxProdtion.p1max+")";
                    toast(text,'error');
                    $("input[name=_DAILY_PROD]").focus();
                    return;
                }
                if(isNaN(p1)){
                    var text="Not a valid number in Daily production !";
                    toast(text,'error');
                    $("input[name=_DAILY_PROD]").focus();
                    return;
                }
                if(p1 == "" || hasWhiteSpace(p1)){
                    var text="Enter a value in Daily production !";
                    toast(text,'error');
                    $("input[name=_DAILY_PROD]").focus();
                    return;
                }
                
                if(p2 < minMaxProdtion.p2min || p2 > minMaxProdtion.p2max){
                    var text="Weekly production ("+minMaxProdtion.p2min+" : "+minMaxProdtion.p2max+")";
                    toast(text,'error');
                    $("input[name=_WEEKLY_PROD]").focus();
                    return;
                }
                if(isNaN(p2)){
                    var text="Not a valid number in Weekly production !";
                    toast(text,'error');
                    $("input[name=_WEEKLY_PROD]").focus();
                    return;
                }
                if(p2 == "" || hasWhiteSpace(p2)){
                    var text="Enter a value in Weekly production !";
                    toast(text,'error');
                    $("input[name=_WEEKLY_PROD]").focus();
                    return;
                }
                if(p3 < minMaxProdtion.p3min || p3 > minMaxProdtion.p3max){
                    var text="Annual production ("+minMaxProdtion.p3min+" : "+minMaxProdtion.p3max+")";
                    toast(text,'error');
                    $("input[name=_NB_PROD_WEEK_PER_YEAR]").focus();
                    return;
                }
                if(isNaN(p3)){
                    var text="Not a valid number in Annual production !";
                    toast(text,'error');
                    $("input[name=_NB_PROD_WEEK_PER_YEAR]").focus();
                    return;
                }
                if(p3 == "" || hasWhiteSpace(p3)){
                    var text="Enter a value in Annual production !";
                    toast(text,'error');
                    $("input[name=_NB_PROD_WEEK_PER_YEAR]").focus();
                    return;
                }
                if(p4 < minMaxProdtion.p4min || p4 > minMaxProdtion.p4max ){
                    var text="Number of equipment cooldowns ("+minMaxProdtion.p4min+" : "+minMaxProdtion.p4max+")";
                    toast(text,'error');
                    $("input[name=_DAILY_STARTUP]").focus();
                    return;
                }
                if(isNaN(p4)){
                    var text="Not a valid number in Number of equipment cooldowns !";
                    toast(text,'error');
                    $("input[name=_DAILY_STARTUP]").focus();
                    return;
                }
                if(p4 == "" || hasWhiteSpace(p4)){
                    var text="Enter a value in Number of equipment cooldowns !";
                    toast(text,'error');
                   $("input[name=_DAILY_STARTUP]").focus();
                    return;
                }
                if(p5 < minMaxProdtion.p5min || p5 > minMaxProdtion.p5max ){
                    var text="Factory Air temperature ("+minMaxProdtion.p5min+" : "+minMaxProdtion.p5max+")";
                    toast(text,'error');
                    $("input[name=_AMBIENT_TEMP]").focus();
                    return;
                }
                if(isNaN(p5)){
                    var text="Not a valid number in Factory Air temperature !";
                    toast(text,'error');
                    $("input[name=_AMBIENT_TEMP]").focus();
                    return;
                }
                if(p5 == "" || hasWhiteSpace(p5)){
                    var text="Enter a value in Factory Air temperature !";
                    toast(text,'error');
                    $("input[name=_AMBIENT_TEMP]").focus();
                    return;
                }
                if(p6 < minMaxProdtion.p6min || p6 > minMaxProdtion.p6max){
                    var text="Relative Humidity of Factory Air  ("+minMaxProdtion.p6min+" : "+minMaxProdtion.p6max+")";
                    toast(text,'error');
                    $("input[name=_AMBIENT_HUM]").focus();
                    return;
                }
                if(isNaN(p6)){
                    var text="Not a valid number in Relative Humidity of Factory Air !";
                    toast(text,'error');
                    $("input[name=_AMBIENT_HUM]").focus();
                    return;
                }
                if(p6 == "" || hasWhiteSpace(p6)){
                    var text="Enter a value in Relative Humidity of Factory Air !";
                    toast(text,'error');
                    $("input[name=_AMBIENT_HUM]").focus();
                    return;
                }

                if(p7 < minMaxProdtion.p7min || p7 > minMaxProdtion.p7max || isNaN(p7) || p7==""){
                    var text="Required Average temperature ("+minMaxProdtion.p7min+" : "+minMaxProdtion.p7max+")";
                    toast(text,'error');
                    $("input[name=_AVG_T_DESIRED]").focus();
                    return;
                }
                if(isNaN(p7)){
                    var text="Not a valid number in Required Average temperature !";
                    toast(text,'error');
                    $("input[name=_AVG_T_DESIRED]").focus();
                    return;
                }
                if(p7 == "" || hasWhiteSpace(p7)){
                    var text="Enter a value in Required Average temperature !";
                    toast(text,'error');
                    $("input[name=_AVG_T_DESIRED]").focus();
                    return;
                }
                if(p8 < minMaxProdtion.p8min || p8 > minMaxProdtion.p8max || isNaN(p8) || p8==""){
                    var text="Required Production Rate ("+minMaxProdtion.p8min+" : "+minMaxProdtion.p8max+")";
                    toast(text,'error');
                    $("input[name=_PROD_FLOW_RATE]").focus();
                    return;
                }
                if(isNaN(p8)){
                    var text="Not a valid number in Required Production Rate !";
                    toast(text,'error');
                    $("input[name=_PROD_FLOW_RATE]").focus();
                    return;
                }
                if(p8 == "" || hasWhiteSpace(p8)){
                    var text="Enter a value in Required Production Rate !";
                    toast(text,'error');
                    $("input[name=_PROD_FLOW_RATE]").focus();
                    return;
                }
                $("#form-production").submit();
                
            });
        });