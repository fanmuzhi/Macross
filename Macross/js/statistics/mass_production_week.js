function get_mass_week_list(){
	$("#mass_producing_week").empty();
	product_value=$("#product5").val();//get part type
	test_item=$("#TestItem").val();
	$.ajax({
		url: '../php/mass_producing_week_list.php',
		data:"PartType="+product_value+"&TestItem="+test_item,//transport parameters to php
		dataType: "json",
		success:function(items)
		{
			$("#mass_producing_week").kendoComboBox.refresh;
			weeks=items;
			var weekAll={ text: '',	value:''};
			weeklist.push(weekAll);	
			$.each(weeks, function(itemNo, item) {				
			   var week={ text: '',	value:''};
				week.text=item;
				week.value=item;					
				weeklist.push(week);
			});	
			$("#mass_producing_week").kendoComboBox({
				
				dataTextField: "text",
//				dataSource=[];
				dataSource: weeklist,
				dataValueField: "value",
				filter: "contains",
				suggest: true,
				index: 1
			});
			weeklist = [];
		}
	});
	
	$("#start_week_cpk_trend").empty();
	$("#end_week_cpk_trend").empty();
	product_value=$("#product_cpk_trend").val();//get part type
	test_item=$("#TestItem_cpk_trend").val();
	$.ajax({
		url: '../php/mass_producing_week_list.php',
		data:"PartType="+product_value+"&TestItem="+test_item,//transport parameters to php
		dataType: "json",
		success:function(items)
		{
			weeks=items;
			var weekAll={ text: '',	value:''};
			weeklist.push(weekAll);	
			$.each(weeks, function(itemNo, item) {				
			   var week={ text: '',	value:''};
				week.text=item;
				week.value=item;					
				weeklist.push(week);
			});	
			$("#start_week_cpk_trend").kendoComboBox.refresh;
			$("#end_week_cpk_trend").kendoComboBox.refresh;
//			var length = weeklist.length;
			$("#start_week_cpk_trend").kendoComboBox({
				
				dataTextField: "text",
//				dataSource=[];
				dataSource: weeklist,
				dataValueField: "value",
				filter: "contains",
				suggest: true,
				index: weeklist.length-1
			});
			
//			weeks=items;
//			var weekAll={ text: '',	value:''};
//			weeklist.push(weekAll);	
//			$.each(weeks, function(itemNo, item) {				
//			   var week={ text: '',	value:''};
//				week.text=item;
//				week.value=item;					
//				weeklist.push(week);
//			});	
			$("#end_week_cpk_trend").kendoComboBox({
				
				dataTextField: "text",
//				dataSource=[];
				dataSource: weeklist,
				dataValueField: "value",
				filter: "contains",
				suggest: true,
				index: 1
			});
			weeklist = [];
		}
	});
}