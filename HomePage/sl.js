$(document).ready(function(){
	//------------------------------------------------------scroll---------------------------------------------------
	$("#purple").click(function (){
                    
                    $('html, body').animate({
                        scrollTop: $("#signupPanel").offset().top
                    }, 2000);});
	$("#red").click(function (){
                    
                    $('html, body').animate({
                        scrollTop: $("#ContactUsPanel").offset().top
                    }, 2000);});
					
	$("#grey").click(function (){
                    
                    $('html, body').animate({
                        scrollTop: $("#AppPanel").offset().top
                    }, 2000);});
    $("#green").click(function (){
                    
                    $('html, body').animate({
                        scrollTop: $("#HowToUsePanel").offset().top
                    }, 2000);});
	$("#yellow").click(function (){
                    
                    $('html, body').animate({
                        scrollTop: $("#AboutUsPanel").offset().top
                    }, 2000);});
	$("#blue").click(function (){
                    
                    $('html, body').animate({
                        scrollTop: $("#WhyUsPanel").offset().top
                    }, 2000);});
					
	$("#GoUp").click(function (){
                    
                    $('html, body').animate({
                        scrollTop: $(".mainPanel").offset().top
                    }, 2000);});
	//------------------------------------------------------scroll toolbar------------------------------------------
	$("#aboutus").click(function (){
                    
                    $('html, body').animate({
                        scrollTop: $("#AboutUsPanel").offset().top
                    }, 2000);});
	$("#whyus").click(function (){
                    
                    $('html, body').animate({
                        scrollTop: $("#WhyUsPanel").offset().top
                    }, 2000);});
	$("#howtouse").click(function (){
                    
                    $('html, body').animate({
                        scrollTop: $("#HowToUsePanel").offset().top
                    }, 2000);});
	$("#app").click(function (){
                    
                    $('html, body').animate({
                        scrollTop: $("#AppPanel").offset().top
                    }, 2000);});
	$("#sign").click(function (){
                    
                    $('html, body').animate({
                        scrollTop: $("#signupPanel").offset().top
                    }, 2000);});
	$("#contactus").click(function (){
                    
                    $('html, body').animate({
                        scrollTop: $("#ContactUsPanel").offset().top
                    }, 2000);});
	//-------------------------------------------------------hiding toolbar------------------------------------------
	idleTime = 0;

   //Increment the idle time counter every second.
   var idleInterval = setInterval(timerIncrement, 1000);

   function timerIncrement()
   {
     idleTime++;
     if (idleTime > 2)
     { clearInterval()
       doPreload(idleInterval);
     }
   }

   //Zero the idle timer on mouse movement.
   $(this).mousemove(function(e){
      idleTime = 0;
      $(".toolbar").slideDown();
   });
   $(this).keypress(function (e) {
        idleTime = 0;
        $(".toolbar").slideDown();
    });

   function doPreload()
   { if(document.body.scrollTop>700)
      $(".toolbar").slideUp();
   }
              
	//---------------------------------------------------login-----------------------------------
	 $("#pop").hide();
        $("#btn").click(function(){
            $("#pop").slideUp();
        });
        $("#login").click(function(){
            $("#pop").slideDown()
        });   
		$(".mainPanel").click(function(){
            $("#pop").slideUp();
        });
		$("#AboutUsPanel").click(function(){
            $("#pop").slideUp();
        });
		$("#WhyUsPanel").click(function(){
            $("#pop").slideUp();
        });
		$("#HowTousePanel").click(function(){
            $("#pop").slideUp();
        });
		$("#AppPanel").click(function(){
            $("#pop").slideUp();
        });
		$("#signupPanel").click(function(){
            $("#pop").slideUp();
        });
		$("#ContactUsPanel").click(function(){
            $("#pop").slideUp();
        });
		$("#yek").click(function(){
            $("#pop").slideUp();
        });    
        
	//---------------------------------------------------slide show-------------------------------------------
    var list=["first.jpg","1.jpg","2.jpg","4.jpg","5.jpg"];
    var size=5;
    var i=0;
    //$("#ax2").src = "3.jpg";
    $("#ax").attr('src', list[i]);i++;
	$("#ax").attr('width', '100%');
    $("#ax2").attr('src', list[i]);i++;
	$("#ax2").attr('width', '100%');
     
    setInterval(function(){
       setTimeout(function(){      //jam ax
        $("#ax").animate({width:'toggle'},1000);
          },2000);
       setTimeout(function(){          
        $("#ax2").css('z-index' , '0');
        $("#ax").css('z-index' , '-1');
        $("#ax").attr('src', list[i]);   //tavize ax
		$("#ax").attr('width', '100%');
         i++;
        if(i>=size)i=0;
         },3000);
       setTimeout(function(){      //pakhsh ax(hidden)
        $("#ax").animate({width:'toggle'},10);
          },4000);
       setTimeout(function(){         //jam ax2
        $("#ax2").animate({width:'toggle'},1000);
          },5000);
       setTimeout(function(){
        $("#ax2").css('z-index' , '-1');
        $("#ax").css('z-index' , '0');
        $("#ax2").attr('src', list[i]);   //tavize ax2
		$("#ax2").attr('width', '100%');
        i++;
        if(i>=size)i=0;
         },6000);
      setTimeout(function(){           //pakhsh ax2(hidden)
        $("#ax2").animate({width:'toggle'},10);
          },6500);


},7000);

//-----------------------------------------------App SlideShow---------------------------------------------------

 	var list2=["10.jpg","11.jpg","12.jpg","14.jpg","15.jpg","16.png"];
    var size2=6;
    var j=0;
    //$("#ax2").src = "3.jpg";
    $("#ax_1").attr('src', list2[j]);j++;
	$("#ax_1").attr('width', '100%');
    $("#ax_2").attr('src', list2[j]);j++;
	$("#ax_2").attr('width', '100%');
     
    setInterval(function(){
       setTimeout(function(){      //jam ax
        $("#ax_1").animate({width:'toggle'},1000);
          },2000);
       setTimeout(function(){          
        $("#ax_2").css('z-index' , '0');
        $("#ax_1").css('z-index' , '-1');
        $("#ax_1").attr('src', list2[j]);   //tavize ax
		$("#ax_1").attr('width', '100%');
         j++;
        if(j>=size2)j=0;
         },3000);
       setTimeout(function(){      //pakhsh ax(hidden)
        $("#ax_1").animate({width:'toggle'},10);
          },4000);
       setTimeout(function(){         //jam ax2
        $("#ax_2").animate({width:'toggle'},1000);
          },5000);
       setTimeout(function(){
        $("#ax_2").css('z-index' , '-1');
        $("#ax_1").css('z-index' , '0');
        $("#ax_2").attr('src', list2[j]);   //tavize ax2
		$("#ax_2").attr('width', '100%');
        j++;
        if(j>=size2)j=0;
         },6000);
      setTimeout(function(){           //pakhsh ax2(hidden)
        $("#ax_2").animate({width:'toggle'},10);
          },6500);


},7000);

//-----------------------------------disa :D ---------------------------
	    $(window).scroll(function(){
			if(document.body.scrollTop<700){
				$("#aboutus").hide();}
		     if(document.body.scrollTop>700){
				$("#aboutus").show();}
				
				if(document.body.scrollTop<700){
				$("#whyus").hide();}
		     if(document.body.scrollTop>700){
				$("#whyus").show();}
				
				if(document.body.scrollTop<700){
				$("#howtouse").hide();}
		     if(document.body.scrollTop>700){
				$("#howtouse").show();}
				
				if(document.body.scrollTop<700){
				$("#app").hide();}
		     if(document.body.scrollTop>700){
				$("#app").show();}
				
				if(document.body.scrollTop<700){
				$("#sign").hide();}
		     if(document.body.scrollTop>700){
				$("#sign").show();}
				
				if(document.body.scrollTop<700){
				$("#contactus").hide();}
		     if(document.body.scrollTop>700){
				$("#contactus").show();}
				
										
			})
});









