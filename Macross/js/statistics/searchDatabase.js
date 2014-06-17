function searchDatabaseBySN(){
	var reg = /^\d{8}[A-Za-z0-9%*]{0,12}$/;
	var result = reg.test($("#search-serial").val());
	
	if(result)
	{
		
//		$("#ErrorM").text("");
		var searchString=new Object();			
//		
//		searchString.StartTime=$("#start_date").val();
//		searchString.EndTime=$("#end_date").val();
//		var project = $("#search-product").select2('data').text;
//		if(project!='All')
//		{				
//			searchString.PartType=project;
//		}	
//		
//		var ErrCode=$("#search-error").select2('data').text
//		if(ErrCode!='All')
//		{				
//			searchString.ErrorCode=ErrCode;
//		}

		if($("#search-serial").val()!="")
		{
			searchString.SerialNumber=$("#search-serial").val();
		}
//		
//		if($("#search-station").select2('data').text !="All")
//		{
//			searchString.TestStation=$("#search-station").select2('data').text;
//		}
//		
		if($('#STATUS').is(':checked')){
			searchString.TestStatus='0';
		}
		else searchString.TestStatus='1';
		
		
		searchString.PageNumber= "1";
	
		$('#containerMain').prepend('<div class="container" id="container_search" align="center"></div>');
		$('#container_search').append('<i class="icon-spinner icon-spin icon-4x"></i>');
		var table = '<table id="MyGrid" class="table table-striped table-bordered table-hover table-condensed datatable"></table>';
		$.ajaxSetup({
			url: 'php/search.php'
		});
		
		$.ajax({
			data:searchString,
			dataType: "json",
			timeout: 60000,
			success: function(result) {
				$('#container_search').empty();
				$('#container_search').append('<div class="container" id="dataTable"></div>');
				$('#dataTable').append(table);
				$('#container_search').prepend('<button type="button" class="close" data-dismiss="alert">&times;</button>');
				var aaData = result.Response;
				$('#MyGrid').dataTable( {
				    "bPaginate": false,
			        "bLengthChange": false,
			        "bFilter": true,
			        "bSort": false,
			        "bInfo": false,
			        "bAutoWidth": false,
					"aaData": aaData,
					"aoColumns": [
					              { "sTitle": "Id" },
					              { "sTitle": "SN#" },
					              { "sTitle": "Station" },
					              { "sTitle": "PN#" },
					              { "sTitle": "Error#" },
					              { "sTitle": "IDD" },
					              { "sTitle": "FW Ver" },
					              { "sTitle": "Test Time"},
					              { "sTitle": "Status" }
					          ]
				});
				$("#MyGrid").tablecloth({
					  theme: "stats",
					  sortable:true
					});
				if(result.TotalNumber>50){
					var TotalPage = Math.ceil(result.TotalNumber/50);
					$('#container_search').append('<div class="container" id="container_page" align="left"></div>');
					$('#container_page').append('<select id="page_list"></select>');
					for (var i=1;i<=TotalPage;i++)
					{
						$('#page_list').append('<option value="' + i + '">' + i + '</option>');
					}
					
					$("#page_list").select2({
						width : "75px"
					});
					$("#page_list").on("change", function(e) {
				    	searchString.PageNumber = e.val;
				    	$.ajax({
				    		data:searchString,
				    		dataType: "json",
				    		timeout: 60000,
				    		success: function(result) {
				    			var aaData = result.Response;
				    			$('#dataTable').empty();
			    				$('#dataTable').prepend(table);
			    				$('#MyGrid').dataTable( {
			    				    "bPaginate": false,
			    			        "bLengthChange": false,
			    			        "bFilter": true,
			    			        "bSort": false,
			    			        "bInfo": false,
			    			        "bAutoWidth": false,
			    					"aaData": aaData,
			    					"aoColumns": [
			    					              { "sTitle": "Id" },
			    					              { "sTitle": "SN#" },
			    					              { "sTitle": "Station" },
			    					              { "sTitle": "PN#" },
			    					              { "sTitle": "Error#" },
			    					              { "sTitle": "IDD" },
			    					              { "sTitle": "FW Ver" },
			    					              { "sTitle": "Test Time"},
			    					              { "sTitle": "Status" }
			    					          ]
			    				});
			    				$("#MyGrid").tablecloth({
			    					  theme: "stats",
			    					  sortable:true
			    					});
				    		}
				    	})
				    })
				}
				else{
				}
//			
			},
			error: function(jqXHR,textStatus,errorThrown){
				$("#ErrorM").text(textStatus+": "+"productList"+" timeout");				
			},
			cache: false
		});
	
	}
	else{
		$._messengerDefaults = {
				extraClasses: 'messenger-fixed messenger-theme-air messenger-on-top messenger-on-right'
			};
		$.globalMessenger().post({
			  message: 'The Serial Number Is Not Valid.',
			  type: 'error',
			  showCloseButton: true
			});
	}
};

function searchDatabase(){
	var reg = /^\d{8}[A-Za-z0-9%*]{0,12}$/;
	var result = reg.test($("#search-serial").val());
	
	if(result||$("#search-serial").val()=="")
	{
		
//		$("#ErrorM").text("");
		var searchString=new Object();			
		
		searchString.StartTime=$("#start_date").val();
		searchString.EndTime=$("#end_date").val();
		var project = $("#search-product").select2('data').text;
		if(project!='All')
		{				
			searchString.PartType=project;
		}	
		
		var ErrCode=$("#search-error").select2('data').text
		if(ErrCode!='All')
		{				
			searchString.ErrorCode=ErrCode;
		}

//		if($("#search-serial").val()!="")
//		{
//			searchString.SerialNumber=$("#search-serial").val();
//		}
		
		if($("#search-station").select2('data').text !="All")
		{
			searchString.TestStation=$("#search-station").select2('data').text;
		}
		
		if($('#STATUS').is(':checked')){
			searchString.TestStatus='0';
		}
		else searchString.TestStatus='1';
		
		
		searchString.PageNumber= "1";
	}
	$('#containerMain').prepend('<div class="container" id="container_search" align="center"></div>');
	$('#container_search').append('<i class="icon-spinner icon-spin icon-4x"></i>');
	var table = '<table id="MyGrid" class="table table-striped table-bordered table-hover table-condensed datatable"></table>';
	$.ajaxSetup({
		url: 'php/search.php'
	});
	
	$.ajax({
		data:searchString,
		dataType: "json",
		timeout: 60000,
		success: function(result) {
			$('#container_search').empty();
			$('#container_search').append('<div class="container" id="dataTable"></div>');
			$('#dataTable').append(table);
			$('#container_search').prepend('<button type="button" class="close" data-dismiss="alert">&times;</button>');
//			var aaData = new Array();
//			$.each(result.Response, function(itemNo,item){
//				var log= new Array();
//				$.each(item, function(key,value){
//					if(value != null){
//						temp = value;
//					}
//					else{
//						temp = "null";
//					}
//					log.push(temp.toString());
//				});
//				aaData.push(log);
//			});
			var aaData = result.Response;
			$('#MyGrid').dataTable( {
			    "bPaginate": false,
		        "bLengthChange": false,
		        "bFilter": true,
		        "bSort": false,
		        "bInfo": false,
		        "bAutoWidth": false,
				"aaData": aaData,
				"aoColumns": [
				              { "sTitle": "Id" },
				              { "sTitle": "SN#" },
				              { "sTitle": "Station" },
				              { "sTitle": "PN#" },
				              { "sTitle": "Error#" },
				              { "sTitle": "IDD" },
				              { "sTitle": "FW Ver" },
				              { "sTitle": "Test Time"},
				              { "sTitle": "Status" }
				          ]
			});
			$("#MyGrid").tablecloth({
				  theme: "stats",
				  sortable:true
				});
			if(result.TotalNumber>50){
				var TotalPage = Math.ceil(result.TotalNumber/50);
				$('#container_search').append('<div class="container" id="container_page" align="left"></div>');
				$('#container_page').append('<select id="page_list"></select>');
				for (var i=1;i<=TotalPage;i++)
				{
					$('#page_list').append('<option value="' + i + '">' + i + '</option>');
				}
				
				$("#page_list").select2({
					width : "75px"
				});
				$("#page_list").on("change", function(e) {
			    	searchString.PageNumber = e.val;
			    	$.ajax({
			    		data:searchString,
			    		dataType: "json",
			    		timeout: 60000,
			    		success: function(result) {
			    			var aaData = result.Response;
			    			$('#dataTable').empty();
		    				$('#dataTable').prepend(table);
		    				$('#MyGrid').dataTable( {
		    				    "bPaginate": false,
		    			        "bLengthChange": false,
		    			        "bFilter": true,
		    			        "bSort": false,
		    			        "bInfo": false,
		    			        "bAutoWidth": false,
		    					"aaData": aaData,
		    					"aoColumns": [
		    					              { "sTitle": "Id" },
		    					              { "sTitle": "SN#" },
		    					              { "sTitle": "Station" },
		    					              { "sTitle": "PN#" },
		    					              { "sTitle": "Error#" },
		    					              { "sTitle": "IDD" },
		    					              { "sTitle": "FW Ver" },
		    					              { "sTitle": "Test Time"},
		    					              { "sTitle": "Status" }
		    					          ]
		    				});
		    				$("#MyGrid").tablecloth({
		    					  theme: "stats",
		    					  sortable:true
		    					});
			    		}
			    	})
			    })
			}
			else{
			}
//			
		},
		error: function(jqXHR,textStatus,errorThrown){
			$("#ErrorM").text(textStatus+": "+"productList"+" timeout");				
		},
		cache: false
	});
	
    
	
	
};



$(function () {
	$("#SN_search_btn").click(function() {
		searchDatabaseBySN();
		
	});	
	
}); //end

$(function () {
	$("#Search").click(function() {
		searchDatabase();
		
	});	
	
}); //end

