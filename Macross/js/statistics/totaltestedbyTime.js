$(function () {
	$("#buttonTotalTestedbyTime").click(function(){
		$('#containerMain').prepend('<div class="container container_chart" id="container1" align="center"></div>');
		$('#container1').append('<i class="icon-spinner icon-spin icon-4x"></i>');
		var project = $('#tested-product').select2('data').text;
		var teststation = $('#tested-station').select2('data').text;
		var step = Math.ceil(($("#tested_startww").select2("val")-$("#tested_endww").select2("val"))/10);
		var options = {
		        chart: {
		            renderTo: 'container1',
		                        zoomType: 'xy',
		            type: 'column',

		        },
		        title: {
		            text: 'Total Tested Quantity By Week'
		        },
		        subtitle: {
		            text: 'Source: All Historic Data'
		        },
		        xAxis: {
		        	gridLineWidth: 1,
		        	lineColor: '#000',
		        	tickColor: '#000',
		        	tickInterval:1,
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
		                text: 'Quantity (k)'
		            },
//                  labels: {
//	                  formatter: function() {
//	                      return this.value;
//	                  }
//                  }
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
	                	pointPadding: 0.2,
	                	borderWidth: 0,
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
		displayChart.redraw();
		displayChart.showLoading();
		
		$.ajaxSetup({			
			url: 'php/total_tested_by_week.php'
		});
		
		
		if(project=='All'){
			
			$.each(projects, function(index_project, value_project){
				
				var series1 = {name: '', data: []}; // series1 is an object
				
				$.ajax({
					data: "PartType=" + value_project + 
					  	  "&TestStation=" + teststation + 
					  	  "&StartWeek="+$('#tested_startww').select2('data').text + 
					  	  "&EndWeek=" + $('#tested_endww').select2('data').text, //+"&TestStation="+value_teststation,
					dataType: "json",
					
					timeout: 60000,
					success: function(items) {
						if(items.Response != null){
							var testTime=[];//displayChart.xAxis[0].categories;
							var quatity=new Array();
							$.each(items.Response, function(itemNo, item){
								var interval=Math.ceil(items.Response.length/10);
								var temp=item[0];
								testTime.push(temp.toString());	
								quatity.push(item[1]);
							});
							series1.data=quatity;
							series1.name = value_project;
							displayChart.xAxis[0].setCategories(testTime,false);
							displayChart.addSeries(series1);
						}	
					},
					error: function(jqXHR,textStatus,errorThrown){
							$("#ErrorM").text(textStatus+": "+value_project+" timeout");
						},					
					cache: false
					
				});
				
			});//each project
			
			displayChart.redraw();
			
			
		}
		else
		{
			var series1 = {name: '', data: []}; // series1 is an object			
			var testTime=[];//displayChart.xAxis[0].categories;
			var quatity=new Array();
			
			$.ajax({
				data: "PartType="+project + 
			  	  	  "&TestStation=" + teststation + 
			  	  	  "&StartWeek="+$('#tested_startww').select2('data').text + 
			  	  	  "&EndWeek=" + $('#tested_endww').select2('data').text, //+"&TestStation="+value_teststation,
				dataType: "json",
				
				timeout: 60000,
				success: function(items) {
					$.each(items.Response, function(itemNo, item){
//						var interval=Math.ceil(items.Response.length/10);
//						if(itemNo%interval==0)
//						{
							var temp=item[0];						
							testTime.push(temp.toString());	
//						}
//						else
//						{
//							testTime.push('');
//						}									
						quatity.push(item[1]);						
					});					
					
					series1.data=quatity;
					series1.name = project;
					
					displayChart.xAxis[0].setCategories(testTime,false);
					
					displayChart.addSeries(series1);
					displayChart.redraw();
				}
//				error: function(jqXHR,textStatus,errorThrown){
//						$("#ErrorM").text(textStatus+": "+project+" timeout");
//						$("#container3").hide();
//					},
//				cache: false
				
			});
		}
		displayChart.hideLoading();
		$('#container1').prepend('<button type="button" class="close" data-dismiss="alert">&times;</button>');
        $('#container1').prepend('<hr class="divider"></hr>');
        $('#container1').attr("id","");
	}); //end of button click
    
}); // end of function