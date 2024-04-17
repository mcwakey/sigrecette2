<x-default-layout>

    @section('title')
        {{ __('Géolocalisation des contribuables') }}
    @endsection

    <div class="card">
        <!--begin::Card header-->
        <div class="card-header border-0 pt-6">
            <!--begin::Card title-->
            <div class="card-title">
                <div class="d-flex align-items-center">
                    <div class="d-flex align-items-center ms-5">
                        <a href="#" id="kt_horizontal_search_advanced_link" data-kt-rotate="true"
                            class="btn btn-outline btn-outline-dashed btn-outline-secondary btn-active-light-secondary me-5 rotate"
                            data-bs-toggle="collapse" data-bs-target="#kt_advanced_search_form">
                            {{ __('advanced search') }} <i
                                class="ki-duotone ki-black-right-line fs-2 rotate-270 ms-3"><span
                                    class="path1"></span><span class="path2"></span></i></a>
                    </div>
                    <!--end:Action-->
                </div>
            </div>
        </div>
        <!--end::Card header-->

        <!--begin::Card body-->
        <div class="card-body py-4">

            <form action="#">
                <div class="collapse" id="kt_advanced_search_form">
                    <!--begin::Separator-->
                    <div class="separator separator-dashed mt-5 mb-5"></div>
                    <!--end::Separator-->
                    <!--begin::Row-->
                    <div class="row g-8 mb-8 ">
                        <!--begin::Col-->
                        <div class="col-xxl-3">
                            <label class="fs-6 form-label fw-bold text-dark">{{ __('taxpayer') }}</label>
                            <input type="text" class="form-control" value="{{ request()->input('taxpayer', '') }}"
                                id="taxpayer" />
                        </div>
                        <!--end::Col-->

                        <!--begin::Col-->
                        <div class="col-xxl-3">
                            <label class="fs-6 form-label fw-bold text-dark">{{ __('Status') }}</label>
                            <!--begin::Select-->
                            <select class="form-select" id="status">
                                <option value=""></option>
                                <option value="OWING" {{ request()->input('status') === 'OWING' ? 'selected' : '' }}>
                                    Facturé et Non payé</option>
                                <option value="PART PAID"
                                    {{ request()->input('status') === 'PART PAID' ? 'selected' : '' }}>Facturé et
                                    Partiellement payé</option>
                                <option value="PAID" {{ request()->input('status') === 'PAID' ? 'selected' : '' }}>
                                    Facturé et Payé</option>
                                <option value="{{ null }}"
                                    {{ request()->input('status') === null ? 'selected' : '' }}>Non Facturé</option>
                            </select>
                            <!--end::Select-->
                        </div>

                        <!--begin::Col-->
                        <div class="col-xxl-3">
                            <label class="fs-6 form-label fw-bold text-dark">{{ __('zone') }}</label>
                            <!--begin::Select-->
                            <select class="form-select" id="zones">
                                <option value=""></option>
                                @foreach ($zones as $zone)
                                    <option value="{{ $zone->id }}"
                                        {{ request()->input('zone') == $zone->id ? 'selected' : '' }}>
                                        {{ $zone->name }}</option>
                                @endforeach
                            </select>
                            <!--end::Select-->
                        </div>

                        <!--begin::Actions-->
                        <div class="col-xxl-3 d-flex justify-space-arround">
                            <div class="d-flex w-100">
                                <a id="search-btn" href="" type="submit" class="btn btn-success mt-8"
                                    style="margin-right: 4px;">
                                    <span class="indicator-label" wire:loading.remove>{{ __('Rechercher') }}</span>
                                </a>

                                <a href="/geolocation/taxpayers" type="submit" class="btn btn-light-warning mt-8">
                                    <span class="indicator-label" wire:loading.remove>{{ __('Rénitialiser') }}</span>
                                </a>
                            </div>
                        </div>


                        <!--end::Actions-->

                    </div>
                    <!--end::Row-->

                    <div class="separator separator-dashed mt-5 mb-5"></div>
                </div>

            </form>
            <!--end::Table-->
            <!--begin::Item-->
            <div class="card-body">
                <div id="location_map" class="w-100 rounded" style="height: calc(100vh - 200px)"></div>
            </div>
        </div>
        <!--end::Card body-->
    </div>

    <div class="card-body d-flex flex-column">
        <!--end::Item-->
    </div>

    <style>
        .legend {
            width: 320px;
            background: white;
            padding: 10px 12px;
            border-radius: 6px;
        }

        .legend .title {
            font-size: 18px;
            font-weight: 500;
            display: block;
            margin-bottom: -16px;
        }

        .legend .detail {
            margin-left: 5px;
            margin-bottom: -10px;
            display: flex;
            align-items: center;
        }

        .legend .detail:last-child {
            margin-bottom: 0px;
        }

        .legend .text {
            font-size: 16px;
            font-weight: 500;
        }

        .legend .img {
            margin-right: 4px;
            min-width: 20px;
        }
    </style>

    @push('scripts')
        <script>
            let mapRender = L.map('location_map').setView([8.2, 1.1], 8); // Set initial coordinates and zoom level

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
            }).addTo(mapRender);

            let legend = L.control({
                position: 'bottomright'
            });

            let markerCluster = new L.markerClusterGroup();

            const getTaxpayerIconUrl = (icon) => `http://127.0.0.1:8000/assets/media/icons/${icon}`;

            let taxpayerGreen = L.icon({
                iconUrl: getTaxpayerIconUrl('taxpayer-green.svg'),
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });

            let taxpayerOrange = L.icon({
                iconUrl: getTaxpayerIconUrl('taxpayer-orange.svg'),
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });

            let taxpayerBlue = L.icon({
                iconUrl: getTaxpayerIconUrl('taxpayer-blue.svg'),
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });

            let taxpayerRed = L.icon({
                iconUrl: getTaxpayerIconUrl('taxpayer-red.svg'),
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });
        </script>

        <script type="text/javascript">
            // Convert Laravel object to JSON object
            let taxpayers = @json($taxpayers);

            let commune = @json($commune);

            const createZonePolygonCoordinates = (zone) => {
                zone = JSON.parse(zone);

                let coordinates = [];

                for (let i = 0; i < zone.length; i++) {
                    coordinates.push([zone[i][1], zone[i][0]]);
                }

                return coordinates;


            }


            taxpayers.forEach((taxpayer) => {
                if (parseFloat(taxpayer.latitude) && parseFloat(taxpayer.longitude)) {

                    let marker = null;
                    let taxpayerTaxable = taxpayer.taxpayer_taxables;

                    if (taxpayer.invoices.length) {
                        let {
                            invoices
                        } = taxpayer;
                        let icon = null;

                        invoices.forEach(invoice => {
                            if (invoice.pay_status == 'OWING') {
                                icon = taxpayerRed;
                                return;
                            } else if (invoice.pay_status == 'PART PAID') {
                                icon = taxpayerOrange;
                            } else {
                                icon = taxpayerGreen;
                            }
                        });

                        marker = L.marker([taxpayer.latitude, taxpayer.longitude], {
                            icon: icon
                        });

                    } else {
                        marker = L.marker([taxpayer.latitude, taxpayer.longitude], {
                            icon: taxpayerBlue
                        });
                    }

                    marker.bindPopup(`
                            <div style="width:480px;min-height:200px;border-radius:8px;">
                                <div style="padding:10px;text-align:center;display:flex;align-items:flex-start;flex-direction:column;">
                                   
                                    <div style="margin-bottom:6px;display:flex;justify-content:space-between;width:100%;align-items:center;">
                                        <h2 class="text-dark">Informations du contribuable</h2 class="text-dark">
                                        <a class="badge pt-2 pb-2 bg-secondary" href="/taxpayers/${taxpayer.id}" class="">Afficher</a>
                                    </div>

                                    <div style="margin-bottom:6px;">
                                        <span style="font-weight:600;font-size:15px;"> Nom complet </span> 
                                        <span style="font-size:15px"> : ${taxpayer.name}</span>
                                    </div>

                                    <div style="margin-bottom:6px;">
                                        <span style="font-weight:600;font-size:15px;"> Téléphone </span> 
                                        <span style="font-size:15px"> : ${taxpayer.mobilephone}</span>
                                    </div>

                                    <div style="margin-bottom:6px;">
                                        <span style="font-weight:600;font-size:15px;"> Adresse </span> 
                                        <span style="font-size:15px"> : ${taxpayer.address}</span>
                                    </div>

                                    <div style="margin-bottom:6px;">
                                        <span style="font-weight:600;font-size:15px;">Ville</span> 
                                        <span style="font-size:15px">: ${taxpayer.town.name}</span>
                                    </div>

                                    <div style="margin-bottom:6px;">
                                        <span style="font-weight:600;font-size:15px;">Canton</span>
                                        <span style="font-size:15px">: ${taxpayer.town.canton.name}</span>
                                    </div>

                                    <div style="margin-bottom:6px;">
                                        <span style="font-weight:600;font-size:15px;">Zone</span>
                                        <span style="font-size:15px"> : ${taxpayer.zone.name}</span>
                                    </div>  
                                    
                                    <div style="margin-bottom:6px;">
                                        <span style="font-weight:600;font-size:15px;">Quartié</span>
                                        <span style="font-size:15px"> : ${taxpayer.erea ? taxpayer.erea.name : '---'}</span>
                                    </div> 
                                </div>
                            </div>
                        `, {
                        maxWidth: "auto",
                    });

                    markerCluster.addLayer(marker);
                }
            });

            mapRender.addLayer(markerCluster);

            legend.onAdd = function(map) {
                let div = L.DomUtil.create('div', 'info legend');
                let labels = [
                    '<div class="legend"><strong class="title">Légende : contribuable</strong><div class="hr"></div></div>'
                ];
                let status = ['OWING', 'PART PAID', 'PAID', null];


                for (let i = 0; i < status.length; i++) {
                    if (status[i] == 'OWING') {
                        div.innerHTML += labels.push(
                            `<div class="detail"><img class="img" src="${getTaxpayerIconUrl('taxpayer-red.svg')}"/> <span class="text">Facturé et Non payé</span></div>`
                        );
                    } else if (status[i] == 'PART PAID') {
                        div.innerHTML += labels.push(
                            `<div class="detail"><img class="img" src="${getTaxpayerIconUrl('taxpayer-orange.svg')}"/> <span class="text">Facturé et Partiellement payé</span></div>`
                        );
                    } else if (status[i] == 'PAID') {
                        div.innerHTML += labels.push(
                            `<div class="detail"><img class="img" src="${getTaxpayerIconUrl('taxpayer-green.svg')}"/> <span class="text">Facturé et Payé</span></div>`
                        );
                    } else if (status[i] == null) {
                        div.innerHTML += labels.push(
                            `<div class="detail"><img class="img" src="${getTaxpayerIconUrl('taxpayer-blue.svg')}"/> <span class="text">Non Facturé</span></div>`
                        );
                    }
                }

                div.innerHTML = labels.join('<br>');
                return div;

            };

            legend.addTo(mapRender);

            let coordinates = createZonePolygonCoordinates(commune.limit_json);

            L.polygon(coordinates, {
                color: 'blue'
            }).addTo(mapRender);

            mapRender.flyTo([commune.longitude, commune.latitude], 11, {
                duration: 8, // Animation duration in seconds
                easeLinearity: 0.5, // Animation easing factor (0.5 for a smooth effect)
            });

            let searchBtn = document.getElementById('search-btn');
            let zones = document.getElementById('zones');
            let status = document.getElementById('status');
            let taxpayer = document.getElementById('taxpayer');

            function submitSearch() {
                let params = [];
                let url = '/geolocation/taxpayers?';

                // Ajouter la valeur de la zone si elle est sélectionnée
                if (zones.value !== '') {
                    params.push('zone=' + encodeURIComponent(zones.value));
                }

                // Ajouter la valeur de l'état si elle est sélectionnée
                if (status.value !== '') {
                    params.push('status=' + encodeURIComponent(status.value));
                }

                // Ajouter la valeur du contribuable si elle est saisie
                if (taxpayer.value !== '') {
                    params.push('taxpayer=' + encodeURIComponent(taxpayer.value));
                }

                // Concaténer les paramètres de la requête
                url += params.join('&');

                // Rediriger vers l'URL de recherche
                searchBtn.setAttribute('href', url);
            }

            // Écouter les changements sur les champs de recherche et soumettre la recherche
            taxpayer.addEventListener('input', () => {
                submitSearch()
            });
            zones.addEventListener('change', () => {
                submitSearch()
            });
            status.addEventListener('change', () => {
                submitSearch()
            });
        </script>
    @endpush
</x-default-layout>
