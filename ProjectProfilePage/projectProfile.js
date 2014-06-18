$(document).ready(function(e) {
	
	var m=10;
	$(".co").click(function(){
         if(typeof(m)!="number"){
		m.slideUp();}
         m=$(this).parent().next()
	m.slideDown();
     });

    $('#blackBG').hide(0) ;
    $('#submitNewPm').click(function(e) {
        $('.messagePop').slideDown(500) ;
        $('#blackBG').show(500) ;
    });

    $('#exitNewMessage').click(function(e) {
        $('.messagePop').slideUp(500) ;
        $('#blackBG').hide(500) ;
    });

	 
	 $('#pieChart').highcharts({
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false, 
			style: { fontFamily : 'B Homa' ,  
					 fontSize : '5px' 
			       },
			//marginLeft : 10,
			//marginTop : -10 ,

        },
        title: { 
            text: '' 
        },
        tooltip: {
    	    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                    style: {
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                    }
                }
            }
        },
        series: [{
            type: 'pie',
            name: 'Project',
            data: [
                ['Done',   45.0],
                ['Doing',       26.8],
				['ToDo' , 28] 
            ]
        }]
    });
	alert("dasdas") ;
	
	 $('#lineChart').highcharts({
        chart: {
            type: 'line' , 
			style: { fontFamily : 'B Homa' }
        },
        title: {
            text: 'مسئولیت های تکمیل شده' , 
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
                text: 'تعداد'
            }
        },
        series: [{
            name: 'projectTitle',
            data: [20, 7, 3 ,11 , 4 , 22 , 11 , 31 , 0 , 3 , 1 ]
        }],
			
    });
	
	
	$('#barChart').highcharts({
            chart: {
                type: 'column',
				style: { fontFamily : 'B Homa' ,  
					 fontSize : '5px' 
				},
            },
			
            title: {
                text: 'انالیز اعضا'
            },
            xAxis: {
                categories: ['وحید خرازی', 'رضا شایسته پور' , 'وحید خرازی', 'رضا شایسته پور' , 'وحید خرازی']
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'تعداد'
                },
                stackLabels: {
                    enabled: true,
                    style: {
                        fontWeight: 'bold',
                        color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                    }
                }
            },

            plotOptions: {
                column: {
                    stacking: 'normal',
                    dataLabels: {
                        enabled: true,
                        color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
                        style: {
                            textShadow: '0 0 3px black, 0 0 3px black'
                        }
                    }
                }
            },
            series: [{
                name: 'Done',
                data: [5, 3, 4, 7, 2]
            }, {
                name: 'Doing',
                data: [2, 2, 3, 2, 1]
            }, {
                name: 'ToDo',
                data: [3, 4, 4, 2, 5]
            }]
        });
	
});