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

function vsprintf(template, values) {
    return template.replace(/%s/g, () => values.shift());
}

function showToast(message, icon, callbackAfter = null, timer = 3000) {
    if(callbackAfter == null) {
        Swal.fire({
            toast: true,
            icon: icon,
            position: 'top-end',
            title: message,
            timer: timer,
            showConfirmButton: false
        })
    }
    else {
        Swal.fire({
            toast: true,
            icon: icon,
            position: 'top-end',
            title: message,
            timer: timer,
            showConfirmButton: false,
            didClose: callbackAfter
        })
    }
}

function showAlert(message, icon) {
    Swal.fire({
        icon: icon,
        title: message    
    })
}

function showPrompt(title, message, icon = "warning", callback = null) {
    Swal.fire({
        title: title,
        text: message,
        icon: icon,
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ya"
      }).then((result) => {
        if (result.isConfirmed) {
            if(callback != null) {
                if(callback instanceof Function) {
                    callback()
                }
            }
        //   Swal.fire({
        //     title: "Deleted!",
        //     text: "Your file has been deleted.",
        //     icon: "success"
        //   });
        }
      });
}