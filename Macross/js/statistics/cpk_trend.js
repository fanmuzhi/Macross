$(function (){
    $("#buttonCpkTrend").click(function(){
    	$('#containerMain').prepend('<div class="container container_chart" id="container1" align="center"></div>');
		$('#container1').append('<i class="icon-spinner icon-spin icon-4x"></i>');
        var project = $('#trend_product').select2('data').text;
        var testitem = '';
        switch ($('#trend_testitem').select2('data').text){
            case 'idac':
                testitem="idacvalue";
                break;
            case 'raw count':
                testitem="rawcountaverage";
                break;
            case 'noise':
                testitem="rawcountnoise";
                break;
            case 'idd':
                testitem="iddvalue";
                break;
            case 'idd sleep1':
                testitem="iddsleep1";
                break;
            case 'idd deep sleep':
                testitem="idddeepsleep";
                break;
        }
        var step = Math.ceil(($("#trend_startww").select2("val")-$("#trend_endww").select2("val"))/10);
        $.ajaxSetup({
            url: 'php/cpk_trend.php'
        });

        $.ajax({
            data: "PartType=" + project + 
                  "&TestItem=" + testitem + 
                  "&WW_start=" + $('#trend_startww').select2('data').text + 
                  "&WW_end=" + $('#trend_endww').select2('data').text, 
            dataType: "json",
            timeout: 60000,
            success: function(result) {
            	$('#container1').empty();
                var weekNumber = [];
                var cpk=new Array();
              if(result.error==0)
              {
                    $.each(result.Response, function(itemNo, item) {
                        var temp=item[0];
                        weekNumber.push(temp.toString());
                        cpk.push(item[1]);
                    });
                    var options = {
                        chart: {
                            renderTo: 'container1',
                            type: 'line',
                            zoomType: 'xy'
                        },
                        title: {
                            text: 'Trackpad Manufacturing Test Cpk by Week',
                            x: -20 //center
                        },
                        subtitle: {
                            text: "Test Item: " + testitem,
                            x: -20
                        },
                        xAxis: {
                            gridLineWidth: 1,
                            lineColor: '#000',
                            tickColor: '#000',maxZoom: 5,
                            categories: [],
                            tickmarkPlacement: 'on',
                            title: {
                                enabled: false
                            },
                            labels:{
        		        		step: step
        		        	}
                        },
                        yAxis: {
                            title: {
                                text: 'Cpk Value'
                            },
                            plotLines: [{
                                value: 0,
                                width: 1,
                                color: '#808080'
                            }]
                        },
                        tooltip: {
                            valueSuffix: ''
                        },
                        legend: {
                            borderWidth: 0
                        },
                        series: []
                    };

                    var displayChart = new Highcharts.Chart(options);
                    displayChart.xAxis[0].setCategories(weekNumber,false);
                    var series1 = {name: '', data: []};
                    series1.data = cpk;
                    series1.name = result.PartType;
                    displayChart.addSeries(series1);

                    displayChart.redraw();
                    $('#container1').prepend('<button type="button" class="close" data-dismiss="alert">&times;</button>');
                    $('#container1').prepend('<hr class="divider"></hr>');
                    $('#container1').attr("id","");
              }
              else
              {
                  $("#ErrorM").text("error: "+result.error);
//                  $('.alert').show();
              }
            },
          error: function(jqXHR,textStatus,errorThrown){
              $("#ErrorM").text(textStatus+": "+project+" timeout");
              $("#container3").hide();
          },
          cache: false
        });//end of AJAX
    }); //end of button click
}); // end of function
