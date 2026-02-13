$(document).ready(function () {

    loadItems();

    function loadItems() {
        $.ajax({
            url: '/items',
            type: 'GET',
            success: function (response) {

                let select = $('#itemSelect');
                select.empty().append('<option value="">Select Item</option>');

                $.each(response.data, function (key, item) {
                    select.append(
                        `<option value="${item.id}">${item.name}</option>`
                    );
                });
            }
        });
    }

    // ADD
    $('#addItem').click(function () {

        let name = $('#itemInput').val();

        if (!name) {
            alert('Enter item name');
            return;
        }

        $.ajax({
            url: '/items',
            type: 'POST',
            data: { name: name },
            success: function (response) {
                $('#itemInput').val('');
                loadItems();
                showMessage('success', response.message);
            },

            error: function (xhr) {
                showMessage('danger', xhr.responseJSON?.message || 'Add failed');
            }
        });
    });

    // EDIT
    $('#editItem').click(function () {

        let id = $('#itemSelect').val();
        let name = $('#itemInput').val();

        if (!id) {
            alert('Select item first');
            return;
        }

        $.ajax({
            url: '/items/' + id,
            type: 'PUT',
            data: { name: name },
            success: function (response) {
                $('#itemInput').val('');
                loadItems();
                showMessage('success', response.message);
            },

            error: function (xhr) {
                showMessage('danger', xhr.responseJSON?.message || 'Update failed');
            }
        });
    });

    // DELETE
    $('#deleteItem').click(function () {

        let id = $('#itemSelect').val();

        if (!id) {
            alert('Select item first');
            return;
        }

        if (!confirm('Are you sure you want to delete this item?')) {
            return;
        }

        $.ajax({
            url: '/items/' + id,
            type: 'DELETE',
            success: function (response) {
                loadItems();
                $('#itemInput').val('');
                showMessage('success', response.message);
            },

            error: function (xhr) {
                showMessage('danger', xhr.responseJSON?.message || 'Delete failed');
            }
        });
    });

    function showMessage(type, message) {

        let alertClass = type === 'success' ? 'alert-success' : 'alert-danger';

        $('#form-errors').html(`
            <div class="alert ${alertClass} alert-dismissible fade show">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                ${message}
            </div>
        `);
    }

});
