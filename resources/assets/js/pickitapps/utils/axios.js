export function responseBodyHandling(data, pageReload = false) {
    if (data.message.length === 0) {
        toastr.success('Operation Succeed!');
        if (pageReload) {
            window.location.reload();
        }
    } else {
        toastr.info(data.message);
    }
}

export function catchErrorHandling(error) {
    if (error.response) {
        if (error.response.data && error.response.data.message !== undefined) {
            toastr.error(error.response.data.message);
        } else {
            toastr.error('Internal Server Error\nPlease contact with Admin.');
        }
    } else {
        toastr.error('Something went wrong, please try again after refresh the page.');
    }
}
