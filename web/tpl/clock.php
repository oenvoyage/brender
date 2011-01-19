<span id="tP">&nbsp;</span>

<script type="text/javascript">
function tS(){ x=new Date(); x.setTime(x.getTime()); return x; } 
function lZ(x){ return (x>9)?x:'0'+x; } 
function y2(x){ x=(x<500)?x+1900:x; return String(x).substring(2,4) } 
function dT(){
	 document.getElementById('tP').innerHTML=eval(oT);
	 setTimeout('dT()',1000); 
} 
var dN=new Array('Sun','Mon','Tue','Wed','Thu','Fri','Sat'),mN=new Array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'),oT="dN[tS().getDay()]+' '+tS().getDate()+' '+mN[tS().getMonth()]+' '+y2(tS().getYear())+' '+':'+':'+' '+lZ(tS().getHours())+':'+lZ(tS().getMinutes())+':'+lZ(tS().getSeconds())+' '";
if(document.all){ 
	window.onload=dT; 
}
else{
	dT();
}
</script>

<!-- Clock Part 2 - Ends Here  -->

