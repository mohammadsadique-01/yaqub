function loadLocations(selectedId = null) {
    $.get(window.APP.routes.locationsList, function (locations) {
        let options = `<option value="">-- Create Location --</option>`;

        locations.forEach(location => {
            options += `
                <option 
                    value="${location.id}"
                    data-district="${location.district ?? ''}"
                    data-state="${location.state ?? ''}"
                    data-state-code="${location.state_code ?? ''}"
                    ${selectedId == location.id ? 'selected' : ''}
                >
                    ${location.district}, ${location.state}
                </option>
            `;
        });

        $('#locationSelect').html(options);
        $('#deleteLocationBtn').prop('disabled', !$('#locationSelect').val());
        $('#villageInput').prop('disabled', $('#locationSelect').val());
    });
}

// Auto-fill on select
const locationSelect = document.getElementById('locationSelect');
if (locationSelect) {
    locationSelect.addEventListener('change', function () {
        const selected = this.options[this.selectedIndex];
        const locationId = this.value;

        $('#district').val(selected?.dataset?.district || '');
        $('#state').val(selected?.dataset?.state || '');
        $('#state_code').val(selected?.dataset?.stateCode || '');

        $('#deleteLocationBtn').prop('disabled', !locationId);

        if (locationId) {
            selectedVillageId = null;

            loadVillages(locationId);
        } else {
            // clear villages if no location
            $('#villageSelect').html('<option value="">-- Create Village --</option>');
        }

        $('#villageInput').val('').prop('disabled', false);
    });
}

// Delete location
$(document).on('click', '#deleteLocationBtn', function () {
    const locationId = $('#locationSelect').val();

    if (!locationId) {
        alert('Please select a location first');
        return;
    }

    if (!confirm('Are you sure you want to delete this location?')) {
        return;
    }

    const url = window.APP.routes.deleteLocation.replace(':id', locationId);

    $.ajax({
        url: url,
        type: 'POST',
        data: { _method: 'DELETE' },
        success: function (res) {
            loadLocations();
            $('#district, #state, #state_code').val('');

            showAlert('success', res.message);
            toastr.success( res.message);
        },
        error: function (xhr) {
            let errorMsg = xhr.responseJSON?.message || 'Something went wrong';

            showAlert('error', errorMsg);
            toastr.error(errorMsg);
        }
    });
});


function loadVillages(locationId) {
    if (!locationId) {
        $('#villageSelect')
            .html('<option value="">-- Create Village --</option>')
            .prop('disabled', true);
        return;
    }

    $.get(window.APP.routes.villagesList, { location_id: locationId }, function (villages) {

        let options = `<option value="">-- Create Village --</option>`;

        villages.forEach(village => {
            const selected = (selectedVillageId && selectedVillageId == village.id)
                ? 'selected'
                : '';

            options += `
                <option value="${village.id}" ${selected}>
                    ${village.village_name}
                </option>
            `;
        });

        $('#villageSelect')
            .html(options)
            .prop('disabled', false);
    });
}
