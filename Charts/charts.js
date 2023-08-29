var v = [100,50,210, 60, 120, 88];
var char = ['L', 'M', 'Mi', 'J', 'V', 'S'];
var c = document.getElementById("myCanvas");
var ctx = c.getContext("2d");
var wid=150,hei=300;
var maxxValue = 4000;
var lastx,lasty;
var weeks = true;

makeGraphBorder();
drawValues();

function getValues(){
	ctx.clearRect(0, 0, c.width, c.height);
	weeks = true;
	v = [];
	var i = 0;
	var k = 0;
	var j=0;
	var lastK=0;
	var numbers = document.getElementById('values').value;
	if(document.getElementById('select').value=='index'){
		console.log("index!!!");
		weeks = false;
	}

	 v = numbers.split(',').map(function(item) {
    return parseInt(item, 10);
	});

	console.log(v);
	makeGraphBorder();
	drawValues();

}
function maximum(){
	var maxx=v[0];
	for(var i = 0;i<v.length ;i++){
		if(maxx<v[i]){
			maxx = v[i];
		}

	}
	console.log(maxx);
	maxxValue = maxx;
}
function ma(){
	var sum = 0;
	for(var i=0;i<v.length;i++){
		sum+=v[i];
	}

	console.log(sum/(v.length));
	return Math.round(((sum/(v.length))*100)/100);
}
function makeGraphBorder(){
	ctx.strokeStyle = "black";
	ctx.lineWidth = 3;
	maximum();
	ctx.moveTo(wid, hei);
	ctx.lineTo(wid, hei-200);
	ctx.moveTo(wid, hei);
	ctx.lineTo(1200,hei); //220 e maximul de pixeli in sus
	//ctx.strokeStyle = "red";
	ctx.stroke();
	ctx.font = "17px Arial";

	ctx.fillText(maxxValue, wid-40, hei-200); //scriem oy de inceput
	ctx.fillText(0, wid-20, hei);


}
function drawValues(){
	lasty = 0;
	lastx = 0;
	var medie = ma();
	ctx.beginPath();
	var k = medie/maxxValue; //facem procentul
	k = k * 200;
	ctx.strokeStyle = "#ff4d4d";
	ctx.fillStyle = "#ff4d4d";
	ctx.fillText("ma: "+medie, wid-80, -k+hei); //scriem numarul in dreptu liniei
	ctx.moveTo(wid,-k+hei);
	ctx.lineTo(1200,-k+hei);
	ctx.stroke();

	//desenam valorile in sine pe grafic
	ctx.fillStyle = "black";
	var i = 0;
	var q = 0;
	for(var x=wid;x<=1200;x+=1100/(v.length+1)){
			if(v[i]==null){
				continue;
			}
			if(char[q]==null){
				q=0;
			}

			if(weeks){
			ctx.fillText(char[q], x, hei+20); //scriem numarul sub index
			}
		else{
			ctx.fillText(i+1, x, hei+20);
			}



	    var k = v[i]/maxxValue; //facem procentul
	    k = k * 200;

	    if(x>wid){ // sa nu fie prima valoare
				//facem linia de legatura
				ctx.beginPath();
	    	ctx.moveTo(lastx,lasty);
				ctx.strokeStyle = "lightblue";
	      ctx.lineTo(x,-k+hei);
	      ctx.stroke();
	    }
	    //desenam linia la nivelul la care avem nevoie si o adaugam in istoric pt linia de final
			ctx.beginPath();
			ctx.moveTo(x,hei);
			ctx.strokeStyle = "white";
	    //ctx.lineTo(x,-k+hei); // enable if u want vertical line
			ctx.fillText(v[i], x, -k+hei-10); //scriem numarul sub index
	    ctx.stroke();
	    lastx = x;
	    lasty = -k+hei;

	    i++;
			q++;

	}


}
