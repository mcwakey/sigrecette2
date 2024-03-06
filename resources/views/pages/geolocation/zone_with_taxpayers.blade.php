<x-default-layout>

    @if ($zone)
        @section('title')
        {{ __($zone?->name.' : Taxpayers') }}
        @endsection
        
        {{-- {{dd($taxpayers[0]->zone->longitude)}} --}}
        
        <div class="card-body d-flex flex-column">
            <!--begin::Item-->
            <div class="card-body">
                <div id="location_map" class="w-100 rounded" style="height: calc(100vh - 200px)"></div>
            </div>
            <!--end::Item-->
        </div>
        
    @else
        <div style="display: flex;justify-content:center;align-items:center;min-height:70vh">
            <p style="font-size: 18px">Zone not found</p>
        </div>
    @endif

    @push('scripts')
        <script>
            let map_render = L.map('location_map').setView([8.2, 1.1], 8); // Set initial coordinates and zoom level
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
            }).addTo(map_render);
        </script>

        <script type="text/javascript">

        const colors = [
            'blue',
            'red',
            'orange',
            'yellow',
            'green',
            'crimson',
        ];
                        
            // Convert Laravel object to JSON object
            let zone = @json($zone);

            const {taxpayers} = zone;

            const createZonePolygonCoordinates = (zone) => {
                let {longitude,latitude} = zone;
                longitude = JSON.parse(longitude);
                latitude = JSON.parse(latitude);

                const size = longitude.length;
                let coordinates = [];

                for (let i = 0; i < size; i++) {
                    coordinates.push([longitude[i],latitude[i]]);
                }

                return coordinates;
            }


            taxpayers.forEach((taxpayer,index) => {
            
                if(taxpayer.latitude && taxpayer.longitude) {
                    let latitude = parseFloat(taxpayer.latitude.trim());
                    let longitude = parseFloat(taxpayer.longitude.trim()); 
                    
                    var popupContent = `
                        <strong>${taxpayer.name}</strong><br>
                        Mobile Phone: ${taxpayer.mobilephone}<br>
                        Canton: ${taxpayer.town.canton.name}<br>
                        Town: ${taxpayer.town.name}<br>
                        Erea: ${taxpayer.erea.name}<br>
                        Address: ${taxpayer.address}
                    `;

                    // Add the marker to the map with a custom popup
                    L.marker([48.8600,2.3500]).addTo(map_render).bindPopup(popupContent);

                    // Animate the map to the marker's position with a specific zoom level
                    map_render.flyTo([48.8600,2.3500], 13, {
                        duration: 8, // Animation duration in seconds
                        easeLinearity: 0.5, // Animation easing factor (0.5 for a smooth effect)
                    });
                }
                
            });

            let coordinates = createZonePolygonCoordinates(zone);

            // Créer le polygone et l'ajouter à la carte
            L.polygon(coordinates, {color: 'blue'}).addTo(map_render);

            // Centrer la carte sur le polygone de la zone
            map_render.fitBounds(coordinates);

        </script>
    @endpush
</x-default-layout>
