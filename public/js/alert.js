function showMsg(type, message) {
    return Swal.fire({

        icon: type,
        title: message,
        theme: "auto",

        showConfirmButton: false,
        timer: 2500,
        timerProgressBar: true,

        toast: true,
        position: "top"

    });
}

function showLoading(message = "Loading...") {
    Swal.fire({
        title: message,
        theme: "auto",
        showConfirmButton: false,
        allowEscapeKey: false,
        toast: true,
        // position: "bottom-end",
        position: "top",
        didOpen: () => {
            Swal.showLoading();
        }
    });
}

function hideLoading() {
    Swal.close();
}