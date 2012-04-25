/**
 * Created by JetBrains PhpStorm.
 * User: GUFENG
 * Date: 11-12-21
 * Adapted: 2012-04-04 by seryi70 for Highstock
 */

;(function($){

//    $('.highstock-container').each(function(idx,el){
//        el.style.position='';
//    });
    
    $.fn.highstockview = function(){
        return this.each(function(){
            var $this = $(this);
            var id = $this.attr('id');
        });
    };
    
    $.fn.highstockview.update = function(id, url){
        $.ajax( {
            url:url + '&json=1',
            success:function(dataProvider){
                var len = $.chart.series.length;
                var datas = $.parseJSON(dataProvider);
                for(var i=0; i<len; i++){
                    var data = new Array();
                    var dataY = $.chart.options.series[i]['dataResource'];
                    var dataX = $.chart.options.series[i]['dateResource'];
                    for(var j=0; j<datas.length; j++){
                        data.push(array(Number(datas[j][dataX]),Number(datas[j][dataY])));
                    }
                    $.chart.series[i].setData(data,true);
                }
            }
        });
    };
    
})(jQuery);