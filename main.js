const resetButton = document.createElement("button")
resetButton.textContent = "Refresh page"
document.body.appendChild(resetButton)

const refresh = () => {
	location.reload()
}

resetButton.addEventListener("click", refresh)