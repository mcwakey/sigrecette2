<x-default-layout>

    @section('title')
        {{ __('Taxpayers Geolocation') }}
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
            let taxpayers = @json($taxpayers);

            let taxpayerGreen = L.icon({
                iconUrl: 'http://127.0.0.1:2000/storage/icons/taxpayer-green.svg',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });

            let taxpayerOrange = L.icon({
                iconUrl: 'http://127.0.0.1:2000/storage/icons/taxpayer-orange.svg',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });

            let taxpayerBlue = L.icon({
                iconUrl: 'http://127.0.0.1:2000/storage/icons/taxpayer-blue.svg',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });

            let taxpayerRed = L.icon({
                iconUrl: 'http://127.0.0.1:2000/storage/icons/taxpayer-red.svg',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });

            const generateRandomGeoLocations = (count) => {
                var locations = [];

                for (var i = 0; i < count; i++) {
                    // Générer des coordonnées géographiques aléatoires dans une plage réaliste
                    var latitude = (Math.random() * (90 - (-90)) - 90).toFixed(6);
                    var longitude = (Math.random() * (180 - (-180)) - 180).toFixed(6);

                    locations.push({latitude: parseFloat(latitude), longitude: parseFloat(longitude)});
                }

                return locations;
            }


            let randomGeoLocation = generateRandomGeoLocations(taxpayers.length);
                        
            // Convert Laravel object to JSON object
            let zones = @json($zones);

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

            // Construction d'un rectangle a base des cordonnées (nord-est) 
            // et (sud-est) de la zone du contribuable 

                var latlngs = [[51.1242, -5.0981], [41.3712, 9.6627]];

                // Créer le polygone et l'ajouter à la carte
                L.rectangle(latlngs, {color: 'blue'}).addTo(map_render);

                // Centrer la carte sur le polygone de la zone
                map_render.fitBounds(latlngs);




            // ------------------------------------------------------------

            // // Construction d'un rectangle a partir d'une distance maximale en kilomètres
            // // de la zone du contribuale 

            // let distanceMaxKm = 5; // Distance maximale en kilomètres de la zone

            // // Convertir la distance maximale en degrés d'arc
            // let distanceMaxDegrees = distanceMaxKm / 111.12; // Approximation en utilisant la longueur d'un degré de latitude (~111.12 km)
            // let latlngs = [
            //     [51.1242 - distanceMaxDegrees, -5.0981 - distanceMaxDegrees],
            //     [41.3712 + distanceMaxDegrees, 9.6627 - distanceMaxDegrees]
            // ];

            // // Créer le polygone et l'ajouter à la carte
            // L.rectangle(latlngs, {color: 'blue'}).addTo(map_render);

            // // Centrer la carte sur le polygone de la zone
            // map_render.fitBounds(latlngs);


        </script>
    @endpush
</x-default-layout>
