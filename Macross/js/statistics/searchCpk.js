$(function () {
	$("#buttonCpk").click(function(){
		$('#containerMain').prepend('<div class="container container_chart" id="container1" align="center"></div>');
		$('#container1').append('<i class="icon-spinner icon-spin icon-4x"></i>');
		var project = $('#stats_product').select2('data').text;
        var testitem = '';
        switch ($('#stats_testitem').select2('data').text){
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
		
		$.ajaxSetup({
			url: 'php/calculate_CPK.php'
		});	    	
		var passString = "PartType=" + project + 
						 "&TestItem=" + testitem + 
						 "&SampleQuantity=" + $("#stats_quantity").select2('data').text;
		if($("#Sensor").val()!=""){
			passString=passString+"&IndexNumber="+$("#Sensor").val();
		}
		
		$.ajax({
			data: passString, 
			dataType: "json",
			
			timeout: 60000,
			success: function(result){
				$('#container1').empty();
				if(result.error==0)
				{
					var abnLow = result.Response.mean-12*result.Response.stdev;
					var abnHigh = result.Response.mean+12*result.Response.stdev;
//					var step = Math.ceil((result.Response.max-result.Response.min)/80);
					var step = Math.ceil((abnHigh-abnLow)/80);
//					alert(step);
					var options = {
						chart: {
				            renderTo: 'container1',
							zoomType: 'x',
				            type: 'column'
				        },
				        title: {
				            text: 'Total Tested Quantity'
				        },
				        subtitle: {
				            text: 'Source: All Historic Data'
				        },
				        xAxis: {
				            gridLineWidth: 1,
							lineColor: '#000',
							tickColor: '#000',
							tickInterval:1,
//							categories: [],
							maxZoom:0,
							tickmarkPlacement: 'on',
							title: {
								enabled: false
							},
							labels: {
								step:step,
				                rotation: -45,
				                align: 'right',
				                style: {
				                    fontSize: '12px',
				                    fontFamily: 'Verdana, sans-serif'
				                }
				            },
							plotLines:[{
								color: '#FF0000',
								width: 2,
								label: {
									text:"low limit"
								},
								value: 0
							},
							{
								color: '#FF0000',
								width: 2,
								label: {
									text:"high limit"
								},
								value: 0
							}]
				        },
				        yAxis: {
				            min: 0,
				            title: {
				                text: 'Count'
				            },
							labels: {
								formatter: function() {
									return this.value;
								}
							}	
				        },
				        legend: {
				            backgroundColor: '#FFFFFF',
							borderColor: '#FFFFFF'
				        },
				        tooltip: {
				        	valueSuffix: ''
				        },
				        plotOptions: {
				            column: {
				                pointPadding: 0.2,
				                borderWidth: 0,
								dataLabels: {
				                    enabled: true
				                }
				            },
							area: {
								stacking: 'normal',
								lineColor: '#666666',
								lineWidth: 1,
								marker: {
									lineWidth: 1,
									lineColor: '#666666'
								}
							}
				        },
				        series: []
                    };
					options.subtitle.text = "Test Item: "+testitem;	
//					options.xAxis.tickInterval=Math.ceil((result.Response.max-result.Response.min)/30);
					var series1 = {name: '', data: []};
					$.each(result.Response.frequency,function(itemNo, item){
						if(item[0]>abnLow&&item[0]<abnHigh)
						{
							series1.data.push(item);
						}						
					});
					
					series1.name = result.PartType;
					options.xAxis.plotLines[0].value=result.Response.lower_limit;
					options.xAxis.plotLines[1].value=result.Response.upper_limit;
					
					var displayChart = new Highcharts.Chart(options);
					displayChart.addSeries(series1);
					displayChart.redraw();
					var index = "<b>CP: </b>" + result.Response.CP + "<br/>" + 
								"<b>CPK: </b>" + result.Response.CPK + "<br/>" + 
								"<b>L_limit: </b>" + result.Response.lower_limit + "<br/>" + 
								"<b>H_limit: </b>" + result.Response.upper_limit + "<br/>" + 
								"<b>Min: </b>" + Math.round(result.Response.min*100)/100 + "<br/>" + 
								"<b>Max: </b>" + Math.round(result.Response.max*100)/100 + "<br/>" + 
								"<b>Mean: </b>" + Math.round(result.Response.mean*100)/100 + "<br/>" + 
								"<b>Stdev: </b>" + Math.round(result.Response.stdev*100)/100 + "<br/>" + 
								"<b>Var: </b>" + Math.round(result.Response.variance*100)/100 ;
					var text = displayChart.renderer.text(index, 850, 50)
					.attr({
						zIndex: 5
					})
		            .css({
		                color: '#4572A7',
		                fontSize: '12px'
		            })
		            .add();
					var box = text.getBBox();
					displayChart.renderer.rect(box.x - 5, box.y - 5, box.width + 10, box.height + 10, 5)
			            .attr({
			                fill: '#FFFFFF',
			                stroke: 'gray',
			                'stroke-width': 1,
			                zIndex: 4
			            })
			            .add();
					$('#container1').prepend('<div class="container" id="container_temp" align="left"></div>');
//					$("#container_temp").prepend("variance:"+Math.round(result.Response.variance*100)/100+"<br/>");
//					$("#container_temp").prepend("mean:\&nbsp"+Math.round(result.Response.mean*100)/100+"\&nbsp\&nbsp stdev:\&nbsp"+Math.round(result.Response.stdev*100)/100+"<br/>");
//					$("#container_temp").prepend("min:\&nbsp"+Math.round(result.Response.min*100)/100+"\&nbsp\&nbsp max:\&nbsp"+Math.round(result.Response.max*100)/100+"<br/>");
//					$("#container_temp").prepend("lower_limit:\&nbsp"+result.Response.lower_limit+"\&nbsp\&nbsp upper_limit:\&nbsp"+result.Response.upper_limit+"<br/>");
//					$("#container_temp").prepend("Cp:\&nbsp"+result.Response.CP+"\&nbsp\&nbsp Cpk:\&nbsp"+result.Response.CPK+"<br/>");
					$('#container1').prepend('<button type="button" class="close" data-dismiss="alert">&times;</button>');
                    $('#container1').prepend('<hr class="divider"></hr>');
                    $('#container1').attr("id","");
				}
				else
				{
					$("#ErrorM").text("error: "+result.error);
				}
//				
			}
//			error: function(jqXHR,textStatus,errorThrown){
//						$("#ErrorM").text(textStatus+": "+project+" timeout");
//						$("#container3").hide();
//					},
//			cache: false
			
		});
		
		
		
	}); //end of button click
    
}); // end of function