<x-default-layout>

    @section('title')
        {{ __('zones') }}
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

            let markerCluster = new L.markerClusterGroup();
        </script>

        <script type="text/javascript">
            // const colors = [
            //     'blue',
            //     'red',
            //     'orange',
            //     'yellow',
            //     'green',
            //     'crimson',
            //     'purple',
            //     'cyan',
            //     'magenta',
            //     'lime',
            //     'pink',
            //     'teal',
            //     'indigo',
            //     'coral',
            //     'lavender'
            // ];
                        
            // Convert Laravel object to JSON object
            let zones = @json($zones);


            // const createZonePolygonCoordinates = (zone) => {
            //     let {longitude,latitude} = zone;
            //     longitude = JSON.parse(longitude);
            //     latitude = JSON.parse(latitude);

            //     const size = longitude.length;
            //     let coordinates = [];

            //     for (let i = 0; i < size; i++) {
            //         coordinates.push([longitude[i],latitude[i]]);
            //     }

            //     return coordinates;
            // }

            zones.forEach((zone,index) => {
                const taxpayers = zone.taxpayers;
                taxpayers.forEach((taxpayer) => {
                    if(parseFloat(taxpayer.latitude) && parseFloat(taxpayer.longitude)) {
                        let marker = L.marker([taxpayer.latitude,taxpayer.longitude]);
        
                        marker.bindPopup(`
                            Contribuable : ${taxpayer.name} <br>
                            Ville : ${taxpayer.town.name} <br>
                            Canton : ${taxpayer.town.canton.name} <br>
                            Zone : ${taxpayer.erea.name} <br>
                        `);

                        markerCluster.addLayer(marker);
                    }
                });
            });

            map_render.addLayer(markerCluster);

            // zones.forEach((zone,index) => {
            //     let coordinates = createZonePolygonCoordinates(zone);
            //     L.polygon(coordinates, {color: colors[index]}).addTo(map_render);
            //     map_render.fitBounds(coordinates);
            // });

            // console.log(zones);
        </script>
    @endpush
</x-default-layout>
