/** Note, cannot always go back and forth betweeen getAttribute('src') and .src due to path name issues*/

/*create a 2-D array to hold all the images, including the blank*/

if(typeof dir==='undefined'){
	var dir="images/"
}

var images=Array(3);
for(let i=0;i<3;i++){
	images[i]=Array();
}

var tileNum=0;

for(let i=0;i<3;i++){
	for(let j=0;j<3;j++){
		var nextImg=dir+"tile"+tileNum+".jpg";
		tileNum++;
		images[i][j]=nextImg;
	}
}

var nextImg=dir+"blank.jpg";
images[0][0]=nextImg;//replace the top left image with the blank

var finalTile=dir+"tile0.jpg";//top left will be the open space so this is where the final piece goes
var gameboard;
var tiles=new Array();//To hold 2-D array of all objects on gameboard
var openSpace;//for quick reference to the open slot
var initFlag=0;//tells us if the initialization is done. Set to one when finished
var shuffleLen=1000;//how many times to shuffle
var height=images.length;//height of the puzzle in rows
var width=images[0].length;//width of the puzzle in rows
var count=0;
var bestScore=50000000;
var finalElement;
/**************************************************************************
Determine if a selected object is moveable based on proximity to open space
**************************************************************************/
function moveable(obj){
	var i;

	if(obj==openSpace){//if they clicked on the openSpace
		return false;
	}

	var row=parseInt(obj.getAttribute('row'));//convert row attribute to an integer
	var col=parseInt(obj.getAttribute('col'));//convert col attribute to an integer

	if((row > 0) && (tiles[row-1][col]==openSpace)){ //if the tile above the current tile is the open space
		return true;
	}
	if((col > 0) && (tiles[row][col-1]==openSpace)){//if the tile to the right of the current tile is the open space
		return true;
	}
	if((row < height-1) && (tiles[row+1][col]==openSpace)){//if the tile below the current tile is the open space
		return true;
	}
	if((col < width-1) && (tiles[row][col+1]==openSpace)){//if the tile to the left of the current tile is the open space
		return true;
	}
	return false;//not next to the open space, so obj is not moveable
}

/**************************************************************************
this function checks which slot they have clicked to see if it is one that
can be swapped with the open space
**************************************************************************/
function move(obj){
	if(moveable(obj)){
		swapWithOpenSpace(obj);
	}
	if(initFlag){
		for(var i=0;i<height;i++){
			for(var j=0;j<width;j++){
				if(moveable(tiles[i][j])){
					tiles[i][j].className="moveable";
				}
				else{
					tiles[i][j].removeAttribute("class");
				}
			}
		}
	}
}

/**************************************************************************
swapWithOpenSpace

This function swaps the open space with the space pointed to by the argument
**************************************************************************/
function swapWithOpenSpace(nodeToSwap){

	if(nodeToSwap==openSpace){//if we are trying to move the open space
		return;//it shouldn't happen, but just in case
	}
	var ossrc = openSpace.getAttribute('src');
	openSpace.setAttribute('src',nodeToSwap.getAttribute('src'));
	nodeToSwap.setAttribute('src', ossrc);
	openSpace=nodeToSwap;//make the one we moved be the open space now

	if(initFlag){//only check order if we are finished shuffling
		count++;
		checkOrder();
	}
}

/**************************************************************************
checkOrder

This function checks to see if the order array is in the order 0,1,2,3,4,5,6,7,8
If so, the puzzle is solved
**************************************************************************/
function checkOrder(){
	var i;
	var j;

	for (i=0;i<height;i++){
		for(j=0;j<width;j++){
			if (tiles[i][j].getAttribute('src') != images[i][j]){
				return false;//use the images order to check if the tiles are in the right order
			}
		}
	}
	/*if we have not returned, the puzzle is solved*/

	tiles[0][0].setAttribute('src', finalElement.src);//add the final piece to make the picture complete

	initFlag=0;

	for(i=0;i<height;i++){
		for(j=0;j<width;j++){
			tiles[i][j].onclick=null;//disable all click handlers
			tiles[i][j].removeAttribute("class");
		}
	}

	if(count<bestScore){
		var best=document.getElementById("best");
		best.setAttribute("value", count);
		best.style.color="black";
		bestScore=count;
	}
	setTimeout(alert, 100, "Congratulations, you solved it! It took you " + count + " moves.");
	//wait 1/10 second to let the final piece appear and then
	//tell the user they solved the puzzle
}

/**************************************************************************
initialize
**************************************************************************/
function initialize(){
	var i;
	var j;

	gameboard=document.getElementById("gameboard");

	var row;

	for(i=0;i<height;i++){
		//append all the needed image nodes.
		//If we don't do this in JS, we'll get unwanted text nodes
		//between the images and the swapping won't work
		row=document.createElement('div');//new row for each div
		row.className="row";
		gameboard.appendChild(row);
		tiles[i]=new Array();

		for(j=0;j<width;j++){
			newElement = document.createElement('img');
			newElement.setAttribute('src', images[i][j]);
			newElement.setAttribute('row', i);
			newElement.setAttribute('col', j);
			newElement.alt='tile';
			row.appendChild(newElement);
			tiles[i][j]=newElement;
		}
	}
	finalElement=document.createElement('img');
	finalElement.setAttribute('src', finalTile);

	openSpace=tiles[0][0];

	var reset=document.getElementById('reset');
	reset.onclick=function(){return resetGame();};

	//give the user a flash of the original image then shuffle them up
	setTimeout(shuffle, 1000);
}

/**************************************************************************
initialize
**************************************************************************/
function shuffle(){

	randomRow = Math.floor((Math.random() * height));
	randomCol = Math.floor((Math.random() * width));

	//shuffleLen=0;//comment out when the comment below is removed for shuffling
	if(shuffleLen>0){
		move(tiles[randomRow][randomCol]);
		shuffleLen--;

		if(shuffleLen%20==0){
			setTimeout(shuffle, 10);
		}
		else{
			shuffle();
		}
	}
	else{//if we've shuffled as many times as we will
		var i;
		var j;

		for(i=0;i<height;i++){
			for(j=0;j<width;j++){
				tiles[i][j].onclick=function(){move(this);};
				/* uncomment to allow the moveable tiles to be highlighted when hovered */
				if(moveable(tiles[i][j])){
					tiles[i][j].className="moveable";
				}
				else{
					tiles[i][j].removeAttribute("class");
				}
			}
		}
		initFlag=1; /*set the initFlag to indicate that initialization is done*/
	}
}

/***************************************************************************************
Resets gameboard without reloading page
***************************************************************************************/
function resetGame(){
	var i;
	var j;

	for(i=0;i<height;i++){
		for(j=0;j<width;j++){
			tiles[i][j].setAttribute("src", images[i][j]);
			tiles[i][j].removeAttribute("class");
		}
	}
	openSpace=tiles[0][0];
	initFlag=0;
	count=0;
	shuffleLen=1000;

	setTimeout(shuffle, 1000);
}

/**************************************************************************
addLoadEvent
**************************************************************************/
function addLoadEvent(func) {
	var oldonload = window.onload;

	if (typeof window.onload != 'function'){
		window.onload = func;
	}
	else{
		window.onload = function() {
			oldonload();
			func();
		}
	}
}

addLoadEvent(initialize);
