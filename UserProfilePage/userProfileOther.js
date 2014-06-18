$(document).ready(function(e) {	
	$('#blackBG').hide(1) ; 
	
	
	
   //$( "#messages" ).accordion( { heightStyle: "content" } );
   
   var m=10;
	$(".co").click(function(){
         if(typeof(m)!="number"){
		m.slideUp();}
         m=$(this).parent().next()
	m.slideDown();
     });
		
	$('#sendMessage').click(function(e) {
        $('.messagePop').slideDown(500) ; 
		$('#blackBG').show(500) ; 
    });
	
	$('#exitNewMessage').click(function(e) {
        $('.messagePop').slideUp(500) ; 
		$('#blackBG').hide(500) ; 
    });
	
	$("#addFriend").click(function(e) {
        $("#addFriend").hide(500) ; 
		$("#sendMessage").animate({ marginLeft: "6em" } , 500) ;  
		
    });
	
	

	$('#red').on('click',function(e) {
		e.preventDefault();
        $(this).toggleClass('projectPClick');
	}); 
	
	$('#green').on('click',function(e) {
		e.preventDefault();
        $(this).toggleClass('projectPClick');
	}); 

	$('#yellow').on('click',function(e) {
		e.preventDefault();
        $(this).toggleClass('projectPClick');
	}); 
	
	
	$('.tabs .tab-links a').on('click', function(e)  {
        var currentAttrValue = $(this).attr('href');
        $('.ideaPop ' + currentAttrValue).show().siblings().hide();
        $(this).parent('li').addClass('active').siblings().removeClass('active');
        e.preventDefault();
    });


	 

    $('#lineChart').highcharts({
        chart: {
            type: 'line' , 
			style: { fontFamily : 'B Homa' }
        },
        title: {
            text: 'پروژه های تکمیل شده' , 
			style : {
					fontFamily : 'B Homa', 		
				}
        },
        xAxis: {
            categories: ['فروردین ' ,'اردیبهشت', 'خرداد', 'تیر' , 'مرداد', 'شهریور', 'مهر', 'آبان', 'آدر', 'دی', 'بهمن', 'اسفند'] , 
			labels : {
				style : {
					fontFamily : 'B Homa' 					
				}				
			}
        },
        yAxis: {
            title: {
                text: 'تعداد پروژه'
            }
        },
        series: [{
            name: 'کیان',
            data: [20, 7, 3 ,11 , 4 , 22 , 11 , 31 , 0 , 3 , 1 ]
        }],
			
    });
	
	$('#barChart').highcharts({
        chart: {
            type: 'column' , 
			style: { fontFamily : 'B Homa' }
        },
        title: {
            text: 'نمودار فعالیت در پروژه ها ' , 
			style : {
					fontFamily : 'B Homa' 					
				}
        },
        xAxis: {
            categories: ['پروژه1' , 'پروژه2' , 'پروژه3' , 'پروژه4' , 'پروژه5' ,'پروژه6' , 'پروژه1' , 'پروژه2' , 'پروژه3' , 'پروژه4' , 'پروژه5' ,'پروژه6'  ] , 
			labels : {	
				style : {
						fontFamily : 'B Homa' 					
					}
			}
        },
        yAxis: {
			min : 0 , 
			max : 100 , 
            title: {
                text: 'درصد تکمیل وظیفه'
            }
        },
        series: [{
            name: 'کیان',
            data: [100, 77, 13 ,51 , 23 , 5 ,100, 77, 13 ,51 , 23 , 5  ]
        }],
		
		plotOptions: {
    		series: {borderWidth: 1, borderColor: 'black'} 
		}
    });
	
});

