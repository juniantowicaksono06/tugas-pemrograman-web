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

function clearError() {
    let elements = document.querySelectorAll('.error');
    elements.forEach((element) => {
        element.innerText = "";
    })
}

function showToast(message, type, callbackAfter = null, timer = 3000) {
    if(callbackAfter == null) {
        Swal.fire({
            toast: true,
            icon: type,
            position: 'top-end',
            title: message,
            timer: timer,
            showConfirmButton: false
        })
    }
    else {
        Swal.fire({
            toast: true,
            icon: type,
            position: 'top-end',
            title: message,
            timer: timer,
            showConfirmButton: false,
            didClose: callbackAfter
        })
    }
}

function showAlert(message, type) {
    Swal.fire({
        icon: type,
        title: message    
    })
}