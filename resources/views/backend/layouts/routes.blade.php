<script>
    window.APP = {
        BASE_URL: "{{ url('/') }}",
        routes: {
            deleteLocation: "{{ route('locations.destroy', ':id') }}",
            locationsList: "{{ url('/locations/list') }}",
            villagesList: "{{ url('/villages/list') }}",
            debitorUpdate: "{{ route('debitor.update', ':id') }}",
            locationUpdate: "{{ route('locations.update', ':id') }}",
            debitorSites: "{{ route('debitor.sites', ':id') }}",

        }
    };
</script>