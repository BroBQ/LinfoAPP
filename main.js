const resetButton = document.createElement("button")
resetButton.textContent = "Refresh page"
document.body.appendChild(resetButton)

const refresh = () => {
	location.reload()
}

resetButton.addEventListener("click", refresh)

const mouseover = function () {
	const element = this.children;
	for (let index = 0; index < element.length; index++) {
		element[index].style.backgroundColor = "yellow";
		element[index].style.fontWeight = "bold";
	}
}
const mouseout = function () {
	const element = this.children;
	for (let index = 0; index < element.length; index++) {
		element[index].style.backgroundColor = "";
		element[index].style.fontWeight = "";
	}
}

const allTr = document.querySelectorAll("tr");
allTr.forEach(element => {
	element.addEventListener("mouseover", mouseover)
	element.addEventListener("mouseout", mouseout)
});

function loadJSON(callback) {   

    var xobj = new XMLHttpRequest();
	xobj.overrideMimeType("application/json");
    xobj.open('GET', 'config.json', true);
    xobj.onreadystatechange = function () {
          if (xobj.readyState == 4 && xobj.status == "200") {
            // Required use of an anonymous callback as .open will NOT return a value but simply returns undefined in asynchronous mode
            callback(xobj.responseText);
          }
    };
    xobj.send(null);  
}

let configuration = "";

function init() {
	loadJSON(function(response) {
	 // Parse JSON string into object
	   configuration = JSON.parse(response);
	});
}

let size = "";

const repleaceBytes = function () {
	const bytesClass = document.querySelectorAll(".bytes");
	bytesClass.forEach(element => {
		// console.log(Math.round(parseInt(element.innerText)/1024/1024/1024));
		// console.log((parseInt(element.innerText) / ‭1073741824));‬
		if (element.innerText == "") {
			//does nothing
		} else {			
			switch (document.querySelector("select").value)
			{
				case "GiB":
				console.log("why?");
					element.innerText = Math.round(parseInt(element.innerText)/1024/1024/1024) + " GiB";
				break;
				case "MiB":
					element.innerText = Math.round(parseInt(element.innerText)/1024/1024) + " MiB";
				break;
				case "kiB":
					element.innerText = Math.round(parseInt(element.innerText)/1024) + " kiB";
				break;
				default:
				break;
			}
//			element.innerText = Math.round(parseInt(element.innerText)/1024/1024/1024) + " GB";
		}
	});
}

// const readConfig = () => {
// 	fetch('config.txt')
// 	.then(response => response.text())
// 	.then(text => {
// 		document.querySelector("select").value=text;
// 		size=text;
// 		// console.log(text);
// 	});
// }

const changedSelect = () => {
	console.log(this);
}

init();

const setSelectValue = () => {
	document.querySelector("select").value=configuration.bytes;
}
// setTimeout(document.querySelector("select").value=configuration.bytes, 1000);
// readConfig();
setTimeout(setSelectValue, 250);
setTimeout(repleaceBytes, 20000);