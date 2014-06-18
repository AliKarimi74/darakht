$(document).ready(function(){
var list0={Dec:{29:"sale ghabli"}};   //liste har sal joda-->list={mah:{rooz1:{etelaat},rooz2{...},...},mah2:{...},...}
var list1={June:{1:"project1",7:"3332" },July:{12:"پروژه ی اونا"},May:{24:"وغیره!"},Feb:{6:"Happy!"}};
var list2={Jan:{22:"sale badi"}};
var listha=[list0,list1,list2];var ts=3; //listha=[listeSaleGhabl,Emsal,saleBad,saleBadish,...],ts=tedad sal hayi ke gozashtim
//---------------------------------------------------------------------------
var liste={};  
var list=liste;
var ekhtelaf;


function mod(m,n) {
        return ((m % n) + n) % n;
}
function salyab(){
    ekhtelaf=sal-d.getFullYear()+1
    if(ekhtelaf<ts&&ekhtelaf>-1)
    var list=listha[ekhtelaf];  //etelaate har sal ro too list mirize
    else
    list=liste;}
function empty(){
for(var i=0;i<42;i++){        //khali kardane hameye khoone ha
    var x = document.getElementById("TD").rows[parseInt(i/7)+1].cells;
    x[i%7].innerHTML=" ";
    $(x[i%7]).css('background', '#C2C2A3');
    }
}

function porKon(y,e,m){
for(var i=y;i<y+e;i++){                 //por kardane dobareye mah
    if(parseInt(i/7)>4){
        $("#tah").show();                      //namayeshe radife ezafi(dar soorate niaz)
    }
    var x = document.getElementById("TD").rows[parseInt(i/7)+1].cells;
    x[i%7].innerHTML=j;

    ekhtelaf=sal-d.getFullYear()+1
    if(ekhtelaf<ts&&ekhtelaf>-1)
    var list=listha[ekhtelaf];
    else
    list=liste;
    if(typeof(list[m])!="undefined"){
        if(typeof(list[m][$(x[i%7]).html()])!="undefined"){
            $(x[i%7]).css('background', '#6699FF');
             }}
    if(i%7==6){
	x[i%7].style.color="red";
	}
    j++;

    }
}


//---------------------tarikh yabi-------------------
var d = new Date();
var chandom = d.getDate();        //tarikhe rooz
var shanbe=(d.getDay()+1)%7;      //shomareye rooz(0=shanbe)
yekom =mod((shanbe-chandom+1),7);  //yekome in mah chandshanbe ast

//---------------------porkardane taghvim-------------------
var j=1;
var mah=d.getMonth();
var sal=d.getFullYear();
var nmah=["Jan","Feb","March","Apr","May","June","July","Agu","Sep","Oct","Nov","Dec"];
var nrooz=[31,28,31,30,31,30,31, 31,30,31,30,31];
var mth=nmah[mah];      //esme mah
var end=nrooz[mah];     //tedad rooz haye in mah

$("#tarikh").text(mth+sal);

empty();


for(var i=yekom;i<yekom+end;i++){       //por kardane taghvim (avalin bar)
    var x = document.getElementById("TD").rows[parseInt(i/7)+1].cells;
    x[i%7].innerHTML=j;
    if(i%7==6){
	x[i%7].style.color="red";
     }
    if(chandom==j){
        x[i%7].style.border="3px solid red";  //moshakhas kardane emrooz
        var today=x[i%7];
    }
    j++;
    

    ekhtelaf=sal-d.getFullYear()+1
    if(ekhtelaf<ts&&ekhtelaf>-1)
    var list=listha[ekhtelaf];
    else
    list=liste;
    if(typeof(list[mth])!="undefined"){
        if(typeof(list[mth][$(x[i%7]).html()])!="undefined"){
            $(x[i%7]).css('background', '#6699FF');}}
}

//----------------------next/last-----------------------
$("#next").click(function(){
$("#tah").hide();                     //hazfe radife ezafie taghvim
today.style.border="1px solid black"; //gheire moshakhas kardane emrooz

empty();


yekom=(yekom+end)%7    //tarikh haye mahe badi
mah=(mah+1)%12;
mth=nmah[mah];
end=nrooz[mah];
//bargashtan be emrooz(moshakhas kardanesh)
if(mah==d.getMonth()&&sal==d.getFullYear()){       
today.style.border="3px solid red";
}
if(mah==0){                  //raftan be sale bad
sal++;}
if(mah==1){                  //sale kabise
if (sal%4==0)
end=29;}
j=1;
$("#tarikh").text(mth+sal);

porKon(yekom,end,mth);


});
//---------------------------------------last------------------

$("#last").click(function(){
$("#tah").hide();                     //hazfe radife ezafie taghvim
today.style.border="1px solid black"; //gheire moshakhas kardane emrooz

empty();

mah=mod((mah-1),12);
end=nrooz[mah];
if(mah==11){                  //raftan be sale bad
sal--;
}
if(mah==1){                  //sale kabise
if (sal%4==0)
end=29;}
j=1;
yekom=mod((yekom-end),7)   //tarikh haye mahe ghabli 
mth=nmah[mah];

if(mah==d.getMonth()&&sal==d.getFullYear()){       //bargashtan be emrooz(moshakhas kardanesh)
today.style.border="3px solid red";}

$("#tarikh").text(mth+sal);

porKon(yekom,end,mth);
});
//---------------------hover pop -------------------
var currentMousePos = { x: -1, y: -1 };
$(document).mousemove(function() {
        currentMousePos.x = event.pageX;    //zakhireye makane mouse
        currentMousePos.y = event.pageY;
    });


for(var i=0;i<42;i++){

    var x = document.getElementById("TD").rows[parseInt(i/7)+1].cells;
$(x[i%7]).hover(function(){

salyab();
    if(typeof(list[mth])!="undefined"){
        if(typeof(list[mth][$(this).html()])!="undefined"){
             $("#pop").css({top:currentMousePos.y ,left:currentMousePos.x});
             $("#p1").text($(this).html());
             $("#p2").text(list[mth][$(this).html()]);
           $("#pop").show();}}
     },function(){

    salyab();
if(typeof(list[mth])!="undefined"){
        if(typeof(list[mth][$(this).html()])!="undefined"){
            $("#pop").hide();}}
});//end of hover
}//end of if


});//end of jQ