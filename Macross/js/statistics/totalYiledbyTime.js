$(function () {

	$("#buttonTotalYeildbyTime").click(function() {
		$('#containerMain').prepend('<div class="container container_chart" id="container1" align="center"></div>');
		$('#container1').append('<i class="icon-spinner icon-spin icon-4x"></i>');
		var project = $('#yield-product').select2('data').text;
		var teststation = $('#yield-station').select2('data').text;
		var step = Math.ceil(($("#yield_startww").select2("val")-$("#yield_endww").select2("val"))/8);
		var options = {
            chart: {
                renderTo: 'container1',
                type: 'line',
                zoomType: 'xy'
            },
            title: {
                text: '',
                x: -20 //center
            },
            subtitle: {
                text: "Test Station: " + teststation,
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
                    text: ''
                },
                min : 0,
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
		
		options.title.text='Trackpad Manufacturing Test Total Yield by Week';
		options.xAxis.maxZoom=5;
		options.yAxis.title.text='Percentage(%)';
		options.subtitle.text = "Test Station: "+teststation;
		
		if($('#PPM').is(':checked')){
			options.yAxis.title.text='Percentage (%)';
		}
		else {
			options.yAxis.title.text='PPM';
		}
		var displayChart = new Highcharts.Chart(options);
		displayChart.showLoading();
		$.ajaxSetup({
			url: 'php/total_yield_by_week.php'
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
					timeout: 60000,
					success: function(items) {
						if(items.Response != null){
							var testTime=[];//displayChart.xAxis[0].categories;
							var quatity=new Array();
							
							$.each(items.Response, function(itemNo, item) {
								var temp=item[0];
								testTime.push(temp.toString());	
								
								if(item[1]==0){
									quatity.push(null);
								}
								else {
									if($('#PPM').is(':checked')){									
									}
									else{
										item[1]=Math.round((100-item[1])*10000);
									}	
									quatity.push(item[1]);		
								}
												
							});
							series1.data = quatity;
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

			}); //each project
			
			displayChart.redraw();
		}
		else{
			var series1 = {name: '', data: []}; // series1 is an object			
			var testTime=[];//displayChart.xAxis[0].categories;
			var quatity=new Array();
			$.ajax({
				data: "PartType="+project + 
				      "&TestStation=" + teststation + 
				      "&StartWeek="+$('#yield_startww').select2('data').text + 
				      "&EndWeek=" + $('#yield_endww').select2('data').text, //+"&TestStation="+value_teststation,
				dataType: "json",
				timeout: 60000,
				success: function(items) {
			    	$('#container1').empty();
					$.each(items.Response, function(itemNo, item) {
						var temp=item[0];						
						testTime.push(temp.toString());	
						if(item[1]==0)
						{
							quatity.push(null);
						}
						
						else
						{
							if($('#PPM').is(':checked')){									
							}
							else{
								item[1]=Math.round((100-item[1])*10000);
							}	
							quatity.push(item[1]);	
						}		
					});
					displayChart.xAxis[0].setCategories(testTime,false);
					series1.data=quatity;
					
					series1.name = project;
								
					displayChart.addSeries(series1);
					displayChart.redraw();
				},
				error: function(jqXHR,textStatus,errorThrown){
					$("#ErrorM").text(textStatus+": "+project+" timeout");
				},
				cache: false
				
			});
		}
		displayChart.hideLoading();
		$('#container1').prepend('<button type="button" class="close" data-dismiss="alert">&times;</button>');
        $('#container1').prepend('<hr class="divider"></hr>');
        $('#container1').attr("id","");
		
	}); //end of button click
    
}); // end of function