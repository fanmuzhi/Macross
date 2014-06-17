$(function (){
	$("#buttonTotalYeild").click(function () {
		$('#containerMain').prepend('<div class="container container_chart" id="container1" align="center"></div>');
		$('#container1').append('<i class="icon-spinner icon-spin icon-4x"></i>');
		var project = $('#yield-product').select2('data').text;
		var teststation = $('#yield-station').select2('data').text;
		var options = {
            chart: {
                renderTo: 'container1',
                type: 'column',
                zoomType: 'x'
            },
            title: {
                text: 'Trackpad Manufacturing Test Cpk by Week',
                x: -20 //center
            },
            subtitle: {
                text: "Test Station: " + teststation,
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
            tooltip: {
                valueSuffix: ''
            },
            legend: {
                borderWidth: 0
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
		
		options.title.text='Trackpad Manufacturing Test Total Yield';
		options.xAxis.maxZoom=0;
		options.chart.type='column';
//		options.tooltip.formatter=function() {
//						return ''+ this.series.name +': '+ this.y;
//					};
		options.yAxis.title.text='PPM';
		options.subtitle.text = "Test Station: " + teststation;
		
		options.xAxis.categories = " ";
		
		
		if($('#PPM').is(':checked')){
			options.yAxis.title.text='Percentage (%)';
		}
		else {
			options.yAxis.title.text='PPM';
		}
		var displayChart = new Highcharts.Chart(options);
		
		$.ajaxSetup({
			url: 'php/total_yield_by_station.php'
		});
		
		if(project=='All'){
			$.each(projects, function(index_project, value_project){
				var series1 = {name: '', data: []}; // series1 is an object		
				$.ajax({
					data: "PartType="+value_project + 
						  "&TestStation=" + teststation + 
						  "&StartWeek="+$('#yield_startww').select2('data').text + 
						  "&EndWeek=" + $('#yield_endww').select2('data').text, //+"&TestStation="+value_teststation,
					dataType: "json",
					success: function(items) {
				    	$('#container1').empty();
						series1.name = value_project;
						$.each(items.Response, function(itemNo, item) {
							if(document.getElementById("PPM").checked)
							{									
							}
							else
							{
								if(item[1]>0)
								{
									item[1]=Math.round((100-item[1])*10000);
								}									
							}								
							series1.data.push(item); // item is an array = [TMT, xxx] or [TPT, xxx];
						});
						
						displayChart.addSeries(series1);
						displayChart.redraw();
					}
//					error: function(jqXHR,textStatus,errorThrown){
//						$("#ErrorM").text(textStatus+": "+value_project+" timeout");
//						$("#container3").hide();
//					},
//					cache: false
				});
			}); //each project
		}
		else{
			var series1 = {name: '', data: []}; // series1 is an object		
			$.ajax({
				data: "PartType="+project + 
				  	  "&TestStation=" + teststation + 
				  	  "&StartWeek=" + $('#yield_startww').select2('data').text + 
				  	  "&EndWeek=" + $('#yield_endww').select2('data').text, 
				dataType: "json",
				success: function(items) {
			    	$('#container1').empty();
					series1.name = project;
					$.each(items.Response, function(itemNo, item){
						if(document.getElementById("PPM").checked)
								{									
								}
								else
								{
									if(item[1]>0)
									{
										item[1]=Math.round((100-item[1])*10000);
									}	
								}	
						
							series1.data.push(item); // item is an array = [TMT, xxx] or [TPT, xxx];
						});
					
						displayChart.addSeries(series1);
						displayChart.redraw();
					}
//					error: function(jqXHR,textStatus,errorThrown){
//					$("#ErrorM").text(textStatus+": "+project+" timeout");
//					$("#container3").hide();
//				},
//				cache: false
			});
		}
		$('#container1').prepend('<button type="button" class="close" data-dismiss="alert">&times;</button>');
        $('#container1').prepend('<hr class="divider"></hr>');
        $('#container1').attr("id","");
		

	}); //end of button click
}); //end