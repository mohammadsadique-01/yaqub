function showAlert(type, message) {

    let alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    let icon = type === 'success' 
        ? 'fa-check-circle' 
        : 'fa-exclamation-triangle';

    let html = `
        <div class="alert ${alertClass} alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="fas ${icon}"></i> ${message}
        </div>
    `;

    $('#alertContainer').html(html);

    setTimeout(function() {
        $('#alertContainer .alert').alert('close');
    }, 3000);
}