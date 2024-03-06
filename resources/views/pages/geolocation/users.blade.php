<x-default-layout>

    @section('title')
        {{ __('Users Geolocation') }}
    @endsection

    {{-- {{dd($taxpayers[0]->zone->longitude)}} --}}

    <div class="card-body d-flex flex-column">
        <!--begin::Item-->
        <div class="card-body">
            <div id="location_map" class="w-100 rounded" style="height: calc(100vh - 200px)"></div>
        </div>
        <!--end::Item-->
    </div>

    @push('scripts')
        <script>
            let map_render = L.map('location_map').setView([8.2, 1.1], 8); // Set initial coordinates and zoom level
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
            }).addTo(map_render);
        </script>

        <script type="text/javascript">

            let users = @json($users);

            const colors = [
                'blue',
                'red',
                'orange',
                'yellow',
                'green',
                'crimson',
                'purple',
                'cyan',
                'magenta',
                'lime',
                'pink',
                'teal',
                'indigo',
                'coral',
                'lavender'
            ];
            
            const createZonePolylineCoordinates = (longitude,latitude) => {
                longitude = JSON.parse(longitude);
                latitude = JSON.parse(latitude);

                const size = longitude.length;
                let coordinates = [];

                for (let i = 0; i < size; i++) {
                    coordinates.push([longitude[i],latitude[i]]);
                }

                return coordinates;
            }

            users.forEach((user,index) => {
                if(user.latitude && user.longitude) {
                    let coordinates = createZonePolylineCoordinates(user.longitude,user.latitude);
                    L.polyline(coordinates, {color: colors[index]}).addTo(map_render)
                    map_render.fitBounds(coordinates);
                }
            });


            console.log(users);
        </script>
    @endpush
</x-default-layout>
