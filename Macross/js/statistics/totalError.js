$(function () {
  
	$("#buttonTotalError").click(function () {
		$('#containerMain').prepend('<div class="container container_chart" id="container1" align="center"></div>');
		$('#container1').append('<i class="icon-spinner icon-spin icon-4x"></i>');
		var project = $('#failure-product').select2('data').text;
		var teststation = $('#failure-station').select2('data').text;
		var errorCode = $('#failure-error').select2('data').text;
		var options = {
            chart: {
                renderTo: 'container1',
                type: 'column',
                zoomType: 'x'
            },
            title: {
                text: '',
                x: -20 //center
            },
            subtitle: {
                text: '',
                x: -20
            },
            xAxis:{
                gridLineWidth: 1,
                lineColor: '#000',
                tickColor: '#000',maxZoom: 5,
                categories: [],
                tickmarkPlacement: 'on',
                title: {
                    enabled: false
                }
            },
            yAxis: {
                title: {
                    text: ''
                },
                labels: {
                	enabled : true
                },

                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
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
            tooltip: {
                valueSuffix: ''
            },
            legend: {
                borderWidth: 0
            },
            
            series: []
        };
		options.title.text='Trackpad Manufacturing Test Total Failure';
		options.xAxis.maxZoom=0;
		options.chart.type='column';
//		options.tooltip.formatter=function() {
//						return ''+ this.series.name +': '+ this.y;
//					};
		options.yAxis.title.text='Quantity';
		options.subtitle.text = "Test Station: " + teststation;
		options.xAxis.categories = "";
		var displayChart = new Highcharts.Chart(options);
		
		$.ajaxSetup({
			url: 'php/total_error_by_errorcode.php'
		});
		
		
		if(errorCode=='All')
		{
			//$("#ErrorM").text("All function is just for your reference. please choose a product for this function");
			var errorCodes = ["10","11","12","21","31","32","41","51","52","53","54","55","61","62","63","64","65","81","91","92"];	
			
			$.each(errorCodes, function(index_errorCode, value_errorCode){
				
				var series1 = {name: '', data: []}; // series1 is an object
					
				$.ajax({
					data: "PartType=" + project + 
				  	  	  "&TestStation=" + teststation + 
				  	   	  "&StartWeek="+$('#failure_startww').select2('data').text + 
				  	   	  "&EndWeek=" + $('#failure_endww').select2('data').text +
				  	   	  "&ErrorCode=" + value_errorCode, //+"&TestStation="+value_teststation,
					dataType: "json",
					success: function(items) {
					$('#container1').empty();
					series1.name = value_errorCode;
					
					$.each(items.Response, function(itemNo, item) {
						if(item[1]>0)
							{
								series1.data.push(item);														
								displayChart.addSeries(series1);
								
							}
															
						});							
					},
					error: function(jqXHR,textStatus,errorThrown){
						//$("#ErrorM").text(textStatus+": "+value_errorCode+" timeout");
					},
					cache: false
				});
				
			}); //each project
			displayChart.redraw();
		}
		else
		{
			var series1 = {name: '', data: []}; // series1 is an object						
			$.ajax({
				
				data: "PartType=" + project + 
			  	  	  "&TestStation=" + teststation + 
			  	   	  "&StartWeek="+$('#failure_startww').select2('data').text + 
			  	   	  "&EndWeek=" + $('#failure_endww').select2('data').text +
			  	   	  "&ErrorCode=" + errorCode, //+"&TestStation="+value_teststation,
				dataType: "json",
				success: function(items) {
					$('#container1').empty();
					series1.name = errorCode;						
					$.each(items.Response, function(itemNo, item) {
						series1.data.push(item);								
					});
									
					displayChart.addSeries(series1);							
					displayChart.redraw();	
				},
					error: function(jqXHR,textStatus,errorThrown){					
					$("#ErrorM").text(textStatus+": "+project+" timeout");
				},
				cache: false
			});
		}
		$(".highcharts-legend-item text").attr("data-toggle","tooltip");
		$(".highcharts-legend-item text").attr("title","first tooltip");
		$(".highcharts-legend-item text").tooltip(options);
		
		$('#container1').prepend('<button type="button" class="close" data-dismiss="alert">&times;</button>');
        $('#container1').prepend('<hr class="divider"></hr>');
        $('#container1').attr("id","");
		

	}); //end of button click
}); //end