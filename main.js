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

const repleaceBytesToGigaBytes = function () {
	const bytesClass = document.querySelectorAll(".bytes");
	bytesClass.forEach(element => {
		// console.log(Math.round(parseInt(element.innerText)/1024/1024/1024));
		// console.log((parseInt(element.innerText) / ‭1073741824));‬
		if (element.innerText == "") {
			//does nothing
		} else {
			element.innerText = Math.round(parseInt(element.innerText)/1024/1024/1024) + " GB";
		}
	});
}

repleaceBytesToGigaBytes();