function successToastr(message){
    toastr.success(message, 'Success', {
        closeButton: true,
        progressBar: true,
        positionClass: 'toast-top-right',
        preventDuplicates: true,
        showMethod: 'slideDown',
        hideMethod: 'slideUp',
        rtl: false
    });
    // $(document).Toasts('create', {
    //     class: 'bg-success',
    //     title: 'Success',
    //     autohide: true, // Enable auto-hide
    //     delay: 2000, // Duration in milliseconds (2 seconds in this example)
    //     position: 'topRight', // Position the toast at top right
    //     body: message
    // });
}
function errorToastr(message){
    toastr.error( message , 'Error', {
        closeButton: true,
        progressBar: true,
        positionClass: 'toast-top-right',
        preventDuplicates: true,
        showMethod: 'slideDown',
        hideMethod: 'slideUp',

    });
    // $(document).Toasts('create', {
    //     class: 'bg-danger',
    //     title: 'Error',
    //     autohide: true, // Enable auto-hide
    //     delay: 2000, // Duration in milliseconds (2 seconds in this example)
    //     position: 'topRight', // Position the toast at top right
    //     body: message
    // });
}