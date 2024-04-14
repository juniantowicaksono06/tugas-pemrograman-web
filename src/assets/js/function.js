function showLoading() {
    let loadingContainer = document.getElementsByClassName('loading-container')[0]
    loadingContainer.classList.add("show")
    loadingContainer.classList.remove("hide")
}

function hideLoading() {
    let loadingContainer = document.getElementsByClassName('loading-container')[0]
    loadingContainer.classList.add("hide")
    loadingContainer.classList.remove("show")
}