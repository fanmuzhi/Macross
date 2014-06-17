$(function () {
	$("#buttonTotalErrorbyTime").click(function() {
		$('#containerMain').prepend('<div class="container container_chart" id="container1" align="center"></div>');
		$('#container1').append('<i class="icon-spinner icon-spin icon-4x"></i>');
		var project = $('#failure-product').select2('data').text;
		var teststation = $('#failure-station').select2('data').text;
		var errorCode = $('#failure-error').select2('data').text;
		var step = Math.ceil(($("#failure_startww").select2("val")-$("#failure_endww").select2("val"))/10);
		var options = {
		        chart: {
		            renderTo: 'container1',
		                        zoomType: 'xy',
		            type: 'column',

		        },
		        title: {
		            text: 'Total Failure Quantity By Week'
		        },
		        subtitle: {
		            text: 'Source: All Historic Data'
		        },
		        xAxis: {
		            gridLineWidth: 1,
                    lineColor: '#000',
                    tickColor: '#000',
                    categories: [],
                    maxZoom:0,
                    tickmarkPlacement: 'on',
                    title: {
                    	enabled: false
                    },
                    labels:{
		        		step: step
		        	}
		        },
		        yAxis: {
		            min: 0,
		            title: {
		                text: 'Quantity(pcs)'
		            },
//                    labels: {
//	                    formatter: function() {
//	                            return this.value;
//	                    }
//                    }
		            stackLabels: {
	                    enabled: true,
	                    style: {
	                        fontWeight: 'bold',
	                        color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
	                    }
	                }
		        },
		        legend: {
                    borderWidth: 0
                },
		        tooltip: {
		        	valueSuffix: ''
		        },
		        plotOptions: {
	                column: {
	                    stacking: 'normal',
	                    dataLabels: {
	                        enabled: false,
	                        color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
	                    }
	                }
	            },
		            series: []
		        };

		
		options.subtitle.text = "Test Station: " + teststation;
		var displayChart = new Highcharts.Chart(options);
		$.ajaxSetup({
			//url: '../php/totaltestedbyTime.php'
			url: 'php/total_error_by_week.php'
		});
		if(errorCode=='All')
		{
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
					
					timeout: 60000,
					success: function(items) {
						if(items.Response != null){
							
							var testTime=[];//displayChart.xAxis[0].categories;
							var quatity=new Array();
							
							$.each(items.Response, function(itemNo, item) {
								var temp=item[0];						
								testTime.push(temp.toString());	
							
								quatity.push(item[1]);
							});
							
							series1.data=quatity;
							series1.name = value_errorCode;
							
							displayChart.xAxis[0].setCategories(testTime,false);
						
							displayChart.addSeries(series1);
						}
					},
					
					error: function(jqXHR,textStatus,errorThrown){
						//$("#ErrorM").text(textStatus+": "+value_errorCode+" timeout");
						//$("#container3").hide();
					},	
					cache: false
					
				});

			}); //each project
			displayChart.redraw();
		}
		else
		{
			var series1 = {name: '', data: []}; // series1 is an object			
			var testTime=[];//displayChart.xAxis[0].categories;
			var quatity=new Array();
			
			$.ajax({
				data: "PartType=" + project + 
			  	  	  "&TestStation=" + teststation + 
			  	   	  "&StartWeek="+$('#failure_startww').select2('data').text + 
			  	   	  "&EndWeek=" + $('#failure_endww').select2('data').text +
			  	   	  "&ErrorCode=" + errorCode, //+"&TestStation="+value_teststation, 
				dataType: "json",
				
				timeout: 60000,
				success: function(items) {
					if(items.Response != null){
						$.each(items.Response, function(itemNo, item) {
							var temp=item[0];						
							testTime.push(temp.toString());			
							quatity.push(item[1]);
							
						});
						
						displayChart.xAxis[0].setCategories(testTime,false);
						series1.data=quatity;
						series1.name = errorCode;
											
						displayChart.addSeries(series1);
					}
					displayChart.redraw();
				},
				
				error: function(jqXHR,textStatus,errorThrown){
					$("#ErrorM").text(textStatus+": "+errorCode+" timeout");
					$("#container3").hide();
				},
				cache: false
				
			});
		}
		$('#container1').prepend('<button type="button" class="close" data-dismiss="alert">&times;</button>');
        $('#container1').prepend('<hr class="divider"></hr>');
        $('#container1').attr("id","");
		
	}); //end of button click
    
}); // end of function