var k = 0;
var n = 13;
var m = 32;
var wallIndex = 0;
var oplen = 0;
var cllen = 0;
var coldStart = true;
var start;
var finish;
var finX,finY, finId;
var blocks = document.getElementsByClassName("button");
// A*  PathFinding
var open = [{
  'obs':false,
	'f':0, 'g':0, 'h':0,
	'x':0, 'y':0,
	'fromx':0, 'fromy':0,
  'id':0,
}];

var close = [{
  'obs':false,
	'f':0, 'g':0, 'h':0,
	'x':0, 'y':0,
	'fromx':0, 'fromy':0,
  'id':0,
}];

var grid = [];
var ready = true;

setTimeout(startScript, 200);

var mouseDown = 0;
document.body.onmousedown = function(){
  ++mouseDown;
  console.log(mouseDown);
}

document.body.onmouseup = function(){
  --mouseDown;
}

function startScript(){
  oplen = 0;
  cllen = 0;
  grid = [];
  open = [{
    'obs':false,
  	'f':0, 'g':0, 'h':0,
  	'x':0, 'y':0,
  	'fromx':0, 'fromy':0,
  }];
  close = [{
    'obs':false,
  	'f':0, 'g':0, 'h':0,
  	'x':0, 'y':0,
  	'fromx':0, 'fromy':0,
  }];

    var i;
    for (i = 0; i < blocks.length; i++) {
      if(coldStart==true){
        blocks[i].id = i;
      }
      blocks[i].style.background = 'lightgray';
      blocks[i].innerHTML = "";
    }
    coldStart=false;
  var x = 0;
  for(let i=0;i<n;i++){
    grid[i] = [];
      for(let j=0;j<m;j++){
        grid[i][j] = {
          'obs':false,
        	'f':0, 'g':0, 'h':0,
        	'x':0, 'y':0,
        	'fromx':0, 'fromy':0,
          'id':x++,
        }
      }

    }

    console.log(grid);

};

function clearWalls(){
  console.log("clear walls started");
  var blacks = document.getElementsByClassName("black");
  console.log(blacks);
  while (blacks.length>0) {

      console.log("clear this wall");
      blacks[0].style.background = 'lightgray';
      blacks[0].className = "button";
  }

}

function selectWall(){
  wallIndex++;
  if(wallIndex%2==1){
    document.getElementById("selectWallId").style.background = "black";
    document.getElementById("selectWallId").style.color = "white";
  }
  else{
    document.getElementById("selectWallId").style.background = "lightgray";
    document.getElementById("selectWallId").style.color = "gray";
  }

}

function refresh(){
  if(coldStart){
    startScript();
  }
  else{
    k = 0;
  clearWalls();
  document.getElementsByClassName("green")[0].style.background = "lightgray";
  document.getElementsByClassName("green")[0].className = "button";

  document.getElementsByClassName("red")[0].style.background = "lightgray";
  document.getElementsByClassName("red")[0].className = "button";
  startScript();
}}

function makeWalls(i){
    if(mouseDown==1){

    }

}

function changeColor(i){
  if(wallIndex%2==1){
    wallChange(i);

  }
  else{
  if(k==0){
    document.getElementById(i).style.background = "lightgreen";
    document.getElementById(i).className = "green";
    k++;
  }
  else if(k==1 && document.getElementById(i).className!="green"){
    if(document.getElementsByClassName("red")[0]!=null){
    document.getElementsByClassName("red")[0].style.background = "lightgray";
    document.getElementsByClassName("red")[0].className = "button";
    }

    document.getElementById(i).style.background = "#ff4d4d";
    document.getElementById(i).className = "red";
    k++;
  }
}
};

function wallChange(i){
  if(document.getElementById(i).className=="green"){
    document.getElementById(i).style.background="lightgray";
    document.getElementById(i).className="button";
    k=0;
  }
  else if(document.getElementById(i).className=="red"){
    document.getElementById(i).style.background="lightgray";
    document.getElementById(i).className="button";
    k=1;
  }
  else if(document.getElementById(i).className=="black"){
    document.getElementById(i).style.background="lightgray";
    document.getElementById(i).className="button";
  }
  else {
    document.getElementById(i).style.background="black";
    document.getElementById(i).className="black";
  }

};

function collectData(){
  startScript();
  var x = 0;
  for(var i=0;i<n;i++){
      for(var j=0;j<m;j++){

        if(document.getElementById(x).className=="black"){
          grid[i][j].obs = true;//block
        }
        else if(document.getElementById(x).className=="red"){
          console.log("finish: " + i + " " + j);
          finish = grid[i][j];
          finX = i;
          finY = j;
          finId = grid[i][j].id;
        }
        else if(document.getElementById(x).className=="green"){
          start = grid[i][j];
        }

        grid[i][j].x = i;
        grid[i][j].y = j;
        x++;
      }
  }


  console.log(grid);
  console.log('start');

  open[1] = start;
  oplen = 1;
  AStar();
};

function valid(aux) {

	if (aux.obs == true)
		return false;

	for (var i = 1; i <= cllen; i++)
		if (aux.x == close[i].x && aux.y == close[i].y) {
			return false;
		}

	return true;


};

function g_cost(curr) {
  var fin = finish;
	var cost = 0;
  //console.log("fin");
  //console.log(fin);

	while (fin.x != curr.x && fin.y != curr.y) {
		cost += 14;

		if (curr.x > fin.x){
			fin.x++;
    }
		else{
			fin.x--;
    }
		if (curr.y > fin.y)
    {
			fin.y++;
    }
		else{
			fin.y--;
    }
	}

	while (fin.x != curr.x || fin.y != curr.y) {
			cost += 10;

		if (curr.x > fin.x)
			fin.x++;
		else if (curr.x < fin.x)
			fin.x--;
		if (curr.y > fin.y)
			fin.y++;
		else if (curr.y < fin.y)
			fin.y--;

	}
  //ca sa nu pastreze coordonatele care au fost pasate
  finish.x = finX;
  finish.y = finY;
	return cost;
};

function isOpen(aux) {

	for (var i = 1; i <= oplen; i++) {
		if (open[i].x == aux.x && open[i].y == aux.y)
			return true;

	}

	return false;

};

function reconstruct(now) {
  //document.getElementById(finish.id).style.background="#ff4d4d";
	if (now.f != 0) {
		grid[now.x][now.y].f = 8;
    document.getElementById(grid[now.x][now.y].id).style.background="#ffd699";
    document.getElementById(grid[now.x][now.y].id).className = "button";
		console.log(now.x + " " + now.y);
		now = grid[now.fromx][now.fromy];
    setTimeout(reconstruct,1,now);
	}
  document.getElementById(finish.id).style.background="#ff4d4d";
  document.getElementById(finish.id).className="red";

}

function find_vecin(curr) {

  //console.log("finish (find vecin): " + finish.x + " " + finish.y);
	var gi = [-1,-1,1,1,-1,0,1,0];
	var gj = [-1,1,1,-1,0,1,0,-1];

	for (var i = 0; i < 8; i++) {
		if (gi[i] + curr.x >= 0 && gi[i] + curr.x < n && gj[i] + curr.y >= 0 && gj[i] + curr.y < m && oplen>0) {
			// e in grid
			var now = grid[gi[i] + curr.x][gj[i] + curr.y];


			//DE VERIFICAT DACA MERITA SA SCHIMBAM F COSTUL


			if (valid(now)) {
				if (now.x == finish.x && finish.y==now.y) {
					finish.fromx = curr.x;
					finish.fromy = curr.y;
					oplen = 0;
          console.log("DONE!");
          finish.f = 5;
          reconstruct(finish);
				}
				else {
					now.g = g_cost(now);
					if (i<4) {

						if (now.h >= curr.h + 14 || now.h<2) {
							now.h = curr.h + 14;
							now.fromx = curr.x;
							now.fromy = curr.y;
						}
					}
					else {
						if (now.h >= curr.h + 10 || now.h<2) {
							now.h = curr.h + 10;
							now.fromx = curr.x;
							now.fromy = curr.y;
						}
					}

					now.f = now.h + now.g;
          document.getElementById(now.id).innerHTML = now.f;

					grid[gi[i] + curr.x][gj[i] + curr.y] = now;

					if(isOpen(now)==false){
						open[++oplen] = now;
            document.getElementById(now.id).style.background=" #b3b3cc";
            document.getElementById(finId).style.background="#ff4d4d";

            }
          }


				}
			}
		}


	if (oplen > 1) {
		for (var i = 1; i < oplen; i++) {
			open[i] = open[i + 1];
		}
		oplen--;
	close[++cllen] = curr;
  if(curr!=start)
    document.getElementById(curr.id).style.background="#9494b8";
}}

function AStar() {

	if (oplen >= 1) {



		for (var i = 1; i <= oplen; i++) {
			if (open[1].f > open[i].f && valid(open[i])) {
				var aux = open[1];
				open[1] = open[i];
				open[i] = aux;
			}

		}

		if (valid(open[1])) {
      find_vecin(open[1]);
      setTimeout(AStar,1);

		}
    else{
      oplen = 0;
      console.log("no path...")

    }

    if(open.length >= blocks.length-3){
      oplen = 0;
    }
	}
  clearTimeout();


}
