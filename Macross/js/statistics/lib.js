var productList=[];
var productList2=[];
var projects=[];
var weeks=[];
var product_value = "";
var test_item = "";
	
$(document).ready(function(){
	
	$('#search-serial').keydown(function(e){ 
		if(e.keyCode==13){ 
			$('#SN_search_btn').click();
		} 
	});
	
	$(window).scroll(function(){
        if ($(this).scrollTop() > 100) {
            $('.scrollup').fadeIn();
        } else {
            $('.scrollup').fadeOut();
        }
    }); 

    $('.scrollup').click(function(){
        $("html, body").animate({ scrollTop: 0 }, 600);
        return false;
    });
	
	
	$.ajax({
		url: '/php/total_project_list.php',
		dataType: "json",
		success: function(items) {
			projects=items;
			var i=1;
			$.each(items, function(itemNo, item) {	
				$('.product').append(
					'<option value="'+i+'">'+item+'</option>'
				);
				i++;
			});
			$('#search-product, #tested-product, #yield-product').append(
				'<li class="divider">',
				'<option value="'+i+'">All</option>'
			);
			$(".product").select2({
			    allowClear: true,
			    width: '100%'
			});
			
			$.ajax({
				url: '/php/get_station.php',
				data:	"PartType="  + '00200200',
				dataType: "json",
				success: function(items) {
					var i=1;
					$.each(items, function(itemNo, item) {	
						$('.station').append(
							'<option value="'+i+'">'+item+'</option>'
						);
						i++;	
					});
					
					$('#search-station').append(
						'<li class="divider"></li>',
						'<option value="'+i+'">All</option>'
					);
					
					$('.station').select2({
					    allowClear: true,
					    width: '100%'
					});
					
					var project = $('#failure-product').select2('data').text;
					var teststation = $('#failure-station').select2('data').text;
					var startww = $('#failure_startww').select2('data').text;
					var endww = $('#failure_endww').select2('data').text;
					$.ajax({
						url: 'php/error_list.php',
						dataType: "json",
						data:	"PartType="  + project + 
								"&TestStation=" + teststation + 
								"&StartWeek=" + startww + 
								"&EndWeek=" + endww ,//transport parameters to php
						success: function(items) {
							$('#search-error ,#failure-error').empty();
							var i=1;
							$('#search-error ,#failure-error').append(
									'<option value="'+i+'">All</option>',
									'<li class="divider">'
								);
							$.each(items, function(itemNo, item) {	
								$('#search-error ,#failure-error').append(
									'<option value="' + i + '">' + item + '</option>'
								);
								i++;	
							});
							
							$("#search-error ,#failure-error").select2({
							    allowClear: true,
							    width: '100%'
							});
						},
						error: function(jqXHR,textStatus,errorThrown){
							$("#ErrorM").text(textStatus+": "+"productList"+" timeout");				
						},
						cache: false
					});
					
				},
				error: function(jqXHR,textStatus,errorThrown){
					$("#ErrorM").text(textStatus+": "+"productList"+" timeout");				
				},
				cache: false
			});
			
		},
		error: function(jqXHR,textStatus,errorThrown){
			$("#ErrorM").text(textStatus+": "+"productList"+" timeout");				
		},
		cache: false
	});

	
	
	$('.test_item').append(
		'<option value="1">idac</option>',
		'<option value="2">raw count</option>',
		'<option value="3">noise</option>',
		'<option value="4">idd</option>',
		'<option value="5">idd sleep1</option>',
		'<option value="6">idd deep sleep</option>'
	); 
	
	$('.quantity').append(
		'<option value="1">5000</option>',
		'<option value="2">10000</option>',
		'<option value="3">15000</option>',
		'<option value="4">20000</option>'
	);
	
	
	
	$(".error_code").select2({
	    allowClear: true,
	    width: '100%'
	});
	$(".error_code").select2("val", "21");
	
	
	$(".quantity").select2({
	    allowClear: true,
	    width: '100%'
	});
	$(".test_item").select2({
	    allowClear: true,
	    width: '100%'
	});
	
		
	$.ajax({
		url: 'php/mass_producing_week_list.php',
		dataType: "json",
		data:	"PartType="  + '00200200' + 
				"&TestStation=" + 'TMT' ,
		success: function(items){
			var i=1;
			$.each(items, function(itemNo, item) {	
				$('.WeekList').append(
					'<option value="' + i + '">' + item + '</option>'
				);
				i++;	
			});
			$(".WeekList").select2({
			    allowClear: true,
			    width: '100%'
			    
			});
			$("#tested_startww , #yield_startww , #failure_startww , #trend_startww").select2("val", i-1);
		},
		error: function(jqXHR,textStatus,errorThrown){
			$("#ErrorM").text(textStatus+": "+"productList"+" timeout");				
		},
		cache: false
	});
	
	
		
			
	var objDate = new Date();
	var today = objDate.getFullYear()+"-"+(objDate.getMonth()+1)+"-"+objDate.getDate();
	$(".form_datetime").datetimepicker({
		initialDate: today,
		minView: "2",
        format: "yyyy-mm-dd",
        autoclose: true,
        todayBtn: true,
        pickerPosition: "bottom-left"
    });
	
	$('#PPM , #STATUS').iCheck({
	    checkboxClass: 'icheckbox_flat-aero',
	    increaseArea: '20%' // optional
	});
	
	
	$("#search-product").on("change", function(e) {
		var project = $('#search-product').select2('data').text;
		$.ajax({
			url: 'php/get_station.php',
			dataType: "json",
			data:	"PartType="  + project,//transport parameters to php
			success: function(items){
				$('#search-station').empty();
				var i=1;
				$.each(items, function(itemNo, item){	
					$('#search-station').append(
						'<option value="' + i + '">' + item + '</option>'
					);
					i++;	
				});
				$("#search-station").select2({
				    allowClear: true,
				    width: '100%'
				});
				var project = $('#search-product').select2('data').text;
				var teststation = $('#search-station').select2('data').text;
				var StartDate = $("#start_date").val();
				var EndDate = $("#end_date").val();
				$.ajax({
					url: 'php/error_list.php',
					dataType: "json",
					data:	"PartType="  + project + 
							"&TestStation=" + teststation + 
							"&StartDate=" + StartDate + 
							"&EndDate=" + EndDate ,//transport parameters to php
					success: function(items) {
						$('#search-error').empty();
						var i=1;
						$('#search-error').append(
								'<option value="'+i+'">All</option>',
								'<li class="divider">'
							);
						$.each(items, function(itemNo, item) {	
							$('#search-error').append(
								'<option value="' + i + '">' + item + '</option>'
							);
							i++;	
						});
						
						$("#search-error").select2({
						    allowClear: true,
						    width: '100%'
						});
					},
					error: function(jqXHR,textStatus,errorThrown){
						$("#ErrorM").text(textStatus+": "+"productList"+" timeout");				
					},
					cache: false
				});
			},
			error: function(jqXHR,textStatus,errorThrown){
				$("#ErrorM").text(textStatus+": "+"productList"+" timeout");				
			},
			cache: false
		});
    });
	
	$("#search-station").on("change", function(e) {
		var project = $('#search-product').select2('data').text;
		var teststation = $('#search-station').select2('data').text;
		var StartDate = $("#start_date").val();
		var EndDate = $("#end_date").val();
		$.ajax({
			url: 'php/error_list.php',
			dataType: "json",
			data:	"PartType="  + project + 
					"&TestStation=" + teststation + 
					"&StartDate=" + StartDate + 
					"&EndDate=" + EndDate ,//transport parameters to php
			success: function(items) {
				$('#search-error').empty();
				var i=1;
				$('#search-error').append(
						'<option value="'+i+'">All</option>',
						'<li class="divider">'
					);
				$.each(items, function(itemNo, item) {	
					$('#search-error').append(
						'<option value="' + i + '">' + item + '</option>'
					);
					i++;	
				});
				
				$("#search-error").select2({
				    allowClear: true,
				    width: '100%'
				});
			},
			error: function(jqXHR,textStatus,errorThrown){
				$("#ErrorM").text(textStatus+": "+"productList"+" timeout");				
			},
			cache: false
		});
	});
	
	
	$("#tested-product").on("change", function(e) {
		var project = $('#tested-product').select2('data').text;
		
		$.ajax({
			url: 'php/get_station.php',
			dataType: "json",
			data:	"PartType="  + project,//transport parameters to php
			success: function(items){
				$('#tested-station').empty();
				var i=1;
				$.each(items, function(itemNo, item){	
					$('#tested-station').append(
						'<option value="' + i + '">' + item + '</option>'
					);
					i++;	
				});
				$("#tested-station").select2({
				    allowClear: true,
				    width: '100%'
				});
				
				var project = $('#tested-product').select2('data').text;
				var teststation = $('#tested-station').select2('data').text;
				$.ajax({
					url: 'php/mass_producing_week_list.php',
					dataType: "json",
					data:	"PartType="  + project + 
							"&TestStation=" + teststation,//transport parameters to php
					success: function(items){
						$('#tested_startww, #tested_endww').empty();
						var i=1;
						$.each(items, function(itemNo, item){	
							$('#tested_startww, #tested_endww').append(
								'<option value="' + i + '">' + item + '</option>'
							);
							i++;	
						});
						$("#tested_startww, #tested_endww").select2({
						    allowClear: true,
						    width: '100%'
						});
						$("#tested_startww").select2("val", i-1);
						
					},
					error: function(jqXHR,textStatus,errorThrown){
						$("#ErrorM").text(textStatus+": "+"productList"+" timeout");				
					},
					cache: false
				});
			},
			error: function(jqXHR,textStatus,errorThrown){
				$("#ErrorM").text(textStatus+": "+"productList"+" timeout");				
			},
			cache: false
		});
    });
	
	
	
	$("#tested-station").on("change", function(e) {
		var project = $('#tested-product').select2('data').text;
		var teststation = $('#tested-station').select2('data').text;
		$.ajax({
			url: 'php/mass_producing_week_list.php',
			dataType: "json",
			data:	"PartType="  + project + 
					"&TestStation=" + teststation,//transport parameters to php
			success: function(items){
				$('#tested_startww, #tested_endww').empty();
				var i=1;
				$.each(items, function(itemNo, item){	
					$('#tested_startww, #tested_endww').append(
						'<option value="' + i + '">' + item + '</option>'
					);
					i++;	
				});
				$("#tested_startww, #tested_endww").select2({
				    allowClear: true,
				    width: '100%'
				});
				$("#tested_startww").select2("val", i-1);
			},
			error: function(jqXHR,textStatus,errorThrown){
				$("#ErrorM").text(textStatus+": "+"productList"+" timeout");				
			},
			cache: false
		});
		
    });
	
	
	
	$("#yield-product").on("change", function(e) {
		var project = $('#yield-product').select2('data').text;
		$.ajax({
			url: 'php/get_station.php',
			dataType: "json",
			data:	"PartType="  + project,//transport parameters to php
			success: function(items){
				$('#yield-station').empty();
				var i=1;
				$.each(items, function(itemNo, item){	
					$('#yield-station').append(
						'<option value="' + i + '">' + item + '</option>'
					);
					i++;	
				});
				$("#yield-station").select2({
				    allowClear: true,
				    width: '100%'
				});
				var project = $('#yield-product').select2('data').text;
				var teststation = $('#yield-station').select2('data').text;
				$.ajax({
					url: 'php/mass_producing_week_list.php',
					dataType: "json",
					data:	"PartType="  + project + 
							"&TestStation=" + teststation,//transport parameters to php
					success: function(items){
						$('#yield_startww, #yield_endww').empty();
						var i=1;
						$.each(items, function(itemNo, item){	
							$('#yield_startww, #yield_endww').append(
								'<option value="' + i + '">' + item + '</option>'
							);
							i++;	
						});
						$("#yield_startww, #yield_endww").select2({
						    allowClear: true,
						    width: '100%'
						});
						$("#yield_startww").select2("val", i-1);
					},
					error: function(jqXHR,textStatus,errorThrown){
						$("#ErrorM").text(textStatus+": "+"productList"+" timeout");				
					},
					cache: false
				});
			},
			error: function(jqXHR,textStatus,errorThrown){
				$("#ErrorM").text(textStatus+": "+"productList"+" timeout");				
			},
			cache: false
		});
    });
	
	$("#yield-station").on("change", function(e){
		var project = $('#yield-product').select2('data').text;
		var teststation = $('#yield-station').select2('data').text;
		$.ajax({
			url: 'php/mass_producing_week_list.php',
			dataType: "json",
			data:	"PartType="  + project + 
					"&TestStation=" + teststation,//transport parameters to php
			success: function(items) {
				$('#yield_startww, #yield_endww').empty();
				var i=1;
				$.each(items, function(itemNo, item) {	
					$('#yield_startww, #yield_endww').append(
						'<option value="' + i + '">' + item + '</option>'
					);
					i++;	
				});
				$("#yield_startww, #yield_endww").select2({
				    allowClear: true,
				    width: '100%'
				});
				$("#yield_startww").select2("val", i-1);
			},
			error: function(jqXHR,textStatus,errorThrown){
				$("#ErrorM").text(textStatus+": "+"productList"+" timeout");				
			},
			cache: false
		});
    });
	
	
	
	$("#failure-product").on("change", function(e) {
		var project = $('#failure-product').select2('data').text;
		$.ajax({
			url: 'php/get_station.php',
			dataType: "json",
			data:	"PartType="  + project,//transport parameters to php
			success: function(items){
				$('#failure-station').empty();
				var i=1;
				$.each(items, function(itemNo, item){	
					$('#failure-station').append(
						'<option value="' + i + '">' + item + '</option>'
					);
					i++;	
				});
				$("#failure-station").select2({
				    allowClear: true,
				    width: '100%'
				});
				var project = $('#failure-product').select2('data').text;
				var teststation = $('#failure-station').select2('data').text;
				$.ajax({
					url: 'php/mass_producing_week_list.php',
					dataType: "json",
					data:	"PartType="  + project + 
							"&TestStation=" + teststation,//transport parameters to php
					success: function(items){
						$('#failure_startww, #failure_endww').empty();
						var i=1;
						$.each(items, function(itemNo, item){	
							$('#failure_startww, #failure_endww').append(
								'<option value="' + i + '">' + item + '</option>'
							);
							i++;	
						});
						$("#failure_startww, #failure_endww").select2({
						    allowClear: true,
						    width: '100%'
						});
						$("#failure_startww").select2("val", i-1);
						
						var project = $('#failure-product').select2('data').text;
						var teststation = $('#failure-station').select2('data').text;
						var startww = $('#failure_startww').select2('data').text;
						var endww = $('#failure_endww').select2('data').text;
						$.ajax({
							url: 'php/error_list.php',
							dataType: "json",
							data:	"PartType="  + project + 
									"&TestStation=" + teststation + 
									"&StartWeek=" + startww + 
									"&EndWeek=" + endww ,//transport parameters to php
							success: function(items) {
								$('#failure-error').empty();
								var i=1;
								$('#failure-error').append(
										'<option value="'+i+'">All</option>',
										'<li class="divider">'
									);
								$.each(items, function(itemNo, item) {	
									$('#failure-error').append(
										'<option value="' + i + '">' + item + '</option>'
									);
									i++;	
								});
								
								$("#failure-error").select2({
								    allowClear: true,
								    width: '100%'
								});
							},
							error: function(jqXHR,textStatus,errorThrown){
								$("#ErrorM").text(textStatus+": "+"productList"+" timeout");				
							},
							cache: false
						});
						
					},
					error: function(jqXHR,textStatus,errorThrown){
						$("#ErrorM").text(textStatus+": "+"productList"+" timeout");				
					},
					cache: false
				});
			},
			error: function(jqXHR,textStatus,errorThrown){
				$("#ErrorM").text(textStatus+": "+"productList"+" timeout");				
			},
			cache: false
		});
    });
	
	$("#failure-station").on("change", function(e) {
		var project = $('#failure-product').select2('data').text;
		var teststation = $('#failure-station').select2('data').text;
		$.ajax({
			url: 'php/mass_producing_week_list.php',
			dataType: "json",
			data:	"PartType="  + project + 
					"&TestStation=" + teststation,//transport parameters to php
			success: function(items) {
				$('#failure_startww, #failure_endww').empty();
				var i=1;
				$.each(items, function(itemNo, item) {	
					$('#failure_startww, #failure_endww').append(
						'<option value="' + i + '">' + item + '</option>'
					);
					i++;	
				});
				$("#failure_startww, #failure_endww").select2({
				    allowClear: true,
				    width: '100%'
				});
				$("#failure_startww").select2("val", i-1);
				var project = $('#failure-product').select2('data').text;
				var teststation = $('#failure-station').select2('data').text;
				var startww = $('#failure_startww').select2('data').text;
				var endww = $('#failure_endww').select2('data').text;
				$.ajax({
					url: 'php/error_list.php',
					dataType: "json",
					data:	"PartType="  + project + 
							"&TestStation=" + teststation + 
							"&StartWeek=" + startww + 
							"&EndWeek=" + endww ,//transport parameters to php
					success: function(items) {
						$('#failure-error').empty();
						var i=1;
						$('#failure-error').append(
								'<option value="'+i+'">All</option>',
								'<li class="divider">'
							);
						$.each(items, function(itemNo, item) {	
							$('#failure-error').append(
								'<option value="' + i + '">' + item + '</option>'
							);
							i++;	
						});
						$("#failure-error").select2({
						    allowClear: true,
						    width: '100%'
						});
					},
					error: function(jqXHR,textStatus,errorThrown){
						$("#ErrorM").text(textStatus+": "+"productList"+" timeout");				
					},
					cache: false
				});
			},
			error: function(jqXHR,textStatus,errorThrown){
				$("#ErrorM").text(textStatus+": "+"productList"+" timeout");				
			},
			cache: false
		});
    });
	
	$("#failure_startww,#failure_endww").on("change", function(e) {
		var project = $('#failure-product').select2('data').text;
		var teststation = $('#failure-station').select2('data').text;
		var startww = $('#failure_startww').select2('data').text;
		var endww = $('#failure_endww').select2('data').text;
		$.ajax({
			url: 'php/error_list.php',
			dataType: "json",
			data:	"PartType="  + project + 
					"&TestStation=" + teststation + 
					"&StartWeek=" + startww + 
					"&EndWeek=" + endww ,//transport parameters to php
			success: function(items) {
				$('#failure-error').empty();
				var i=1;
				$('#failure-error').append(
						'<option value="'+i+'">All</option>',
						'<li class="divider">'
					);
				$.each(items, function(itemNo, item) {	
					$('#failure-error').append(
						'<option value="' + i + '">' + item + '</option>'
					);
					i++;	
				});
				$("#failure-error").select2({
				    allowClear: true,
				    width: '100%'
				});
			},
			error: function(jqXHR,textStatus,errorThrown){
				$("#ErrorM").text(textStatus+": "+"productList"+" timeout");				
			},
			cache: false
		});
	});
	
	
	$("#stats_product,#stats_testitem").on("change", function(e) {
		var project = $('#stats_product').select2('data').text;
//		$('#stats_testitem').empty();
//		if(project== '01200500' || project== '01200200' || project== '01200600'){
//			$('#stats_testitem').append(
//				'<option value="1">idac</option>',
//				'<option value="2">raw count</option>',
//				'<option value="3">noise</option>',
//				'<option value="4">idd</option>',
//				'<option value="5">idd sleep1</option>',
//				'<option value="6">idd deep sleep</option>'
//			);
//			$("#stats_testitem").select2({
//				allowClear: true,
//			    width: '100%'
//			}); 
//		}
//		else{
//			$('#stats_testitem').append(
//				'<option value="1">idac</option>',
//				'<option value="2">raw count</option>',
//				'<option value="3">noise</option>',
//				'<option value="4">idd</option>'
//			); 
//			$("#stats_testitem").select2({
//				allowClear: true,
//			    width: '100%'
//			}); 
//		}
		
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
		$.ajax({
			url: 'php/mass_producing_week_list.php',
			dataType: "json",
			data:	"PartType="  + project + 
					"&TestItem=" + testitem,//transport parameters to php
			success: function(items) {
				$('#stats_ww').empty();
				var i=1;
				$.each(items, function(itemNo, item) {	
					$('#stats_ww').append(
						'<option value="' + i + '">' + item + '</option>'
					);
					i++;	
				});
				$("#stats_ww").select2({
				    allowClear: true,
				    width: '100%'
				});
			},
			error: function(jqXHR,textStatus,errorThrown){
				$("#ErrorM").text(textStatus+": "+"productList"+" timeout");				
			},
			cache: false
		});
    });
	
	$("#trend_product,#trend_testitem").on("change", function(e) {
		var project = $('#trend_product').select2('data').text;
//		$('#trend_testitem').empty();
//		if(project== '01200500' || project== '01200200' || project== '01200600'){
//			$('#trend_testitem').append(
//				'<option value="1">idac</option>',
//				'<option value="2">raw count</option>',
//				'<option value="3">noise</option>',
//				'<option value="4">idd</option>',
//				'<option value="5">idd sleep1</option>',
//				'<option value="6">idd deep sleep</option>'
//			); 
//			$("#trend_testitem").select2({
//			    allowClear: true,
//			    width: '100%'
//			}); 
//		}
//		else{
//			$('#trend_testitem').append(
//				'<option value="1">idac</option>',
//				'<option value="2">raw count</option>',
//				'<option value="3">noise</option>',
//				'<option value="4">idd</option>'
//			);
//			$("#trend_testitem").select2({
//			    allowClear: true,
//			    width: '100%'
//			}); 
//		}
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
		$.ajax({
			url: 'php/mass_producing_week_list.php',
			dataType: "json",
			data:	"PartType="  + project + 
					"&TestItem=" + testitem,//transport parameters to php
			success: function(items) {
				$('#trend_startww,#trend_endww').empty();
				var i=1;
				$.each(items, function(itemNo, item) {	
					$('#trend_startww,#trend_endww').append(
						'<option value="' + i + '">' + item + '</option>'
					);
					i++;	
				});
				$("#trend_startww,#trend_endww").select2({
				    allowClear: true,
				    width: '100%'
				});
				$("#trend_startww").select2("val", i-1);
			},
			error: function(jqXHR,textStatus,errorThrown){
				$("#ErrorM").text(textStatus+": "+"productList"+" timeout");				
			},
			cache: false
		});
    });
	
	
});


