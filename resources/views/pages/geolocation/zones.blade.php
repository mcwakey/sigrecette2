<x-default-layout>

    @section('title')
        {{ __('zones') }}
    @endsection

    {{-- {{dd($taxpayers[0]->zone->longitude)}} --}}

    <div class="card">
        <!--begin::Card header-->
        <div class="card-header border-0 pt-6">
            <!--begin::Card title-->
            <div class="card-title">
                <div class="d-flex align-items-center">
                    <!--begin::Input group-->
                    <div class="d-flex align-items-center position-relative my-1">
                        {!! getIcon('magnifier', 'fs-3 position-absolute ms-5') !!}
                        <input type="text" data-kt-invoice-table-filter="search" class="form-control w-250px ps-13" placeholder="{{ __('search') }}" id="mySearchInput" />
                    </div>
                    <!--end::Input group-->
                    <!--begin:Action-->
                    <div class="d-flex align-items-center ms-5">
                        <a href="#" id="kt_horizontal_search_advanced_link" data-kt-rotate="true" class="btn btn-outline btn-outline-dashed btn-outline-secondary btn-active-light-secondary me-5 rotate" data-bs-toggle="collapse" data-bs-target="#kt_advanced_search_form">
                            {{ __('advanced search') }} <i class="ki-duotone ki-black-right-line fs-2 rotate-270 ms-3"><span class="path1"></span><span class="path2"></span></i></a>
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
                    <div class="row g-8 mb-8">
                        <!--begin::Col-->
                        <div class="col-xxl-2">
                            <label class="fs-6 form-label fw-bold text-dark">{{ __('taxpayer') }}</label>
                            <input type="text" class="form-control" name="tags" id="mySearchOne" />
                        </div>
                        <!--end::Col-->
                        <!--begin::Col-->
                        <div class="col-xxl-2">
                            <label class="fs-6 form-label fw-bold text-dark">{{ __('zone') }}</label>
                            <!--begin::Select-->
                            <select class="form-select" id="mySearchFive">
                                <option value=""></option>
                            </select>
                            <!--end::Select-->
                        </div>
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

    @push('scripts')
        <script>
            let map_render = L.map('location_map').setView([8.2, 1.1], 8); // Set initial coordinates and zoom level
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
            }).addTo(map_render);

            let markerCluster = new L.markerClusterGroup();


            let taxpayerGreen = L.icon({
                iconUrl: 'http://127.0.0.1:8000/assets/media/icons/taxpayer-green.svg',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });

            let taxpayerOrange = L.icon({
                iconUrl: 'http://127.0.0.1:8000/assets/media/icons/taxpayer-orange.svg',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });

            let taxpayerBlue = L.icon({
                iconUrl: 'http://127.0.0.1:8000/assets/media/icons/taxpayer-blue.svg',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });

            let taxpayerRed = L.icon({
                iconUrl: 'http://127.0.0.1:8000/assets/media/icons/taxpayer-red.svg',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });
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

                        let marker = null;

                        popupContent = `
                            Contribuable : ${taxpayer.name} <br>
                            Ville : ${taxpayer.town.name} <br>
                            Canton : ${taxpayer.town.canton.name} <br>
                            Zone : ${taxpayer.erea.name} <br>
                        `;

                        if (taxpayer.invoices.length) {
                            let {invoices} = taxpayer;
                            let icon = null;

                            invoices.forEach(invoice => {
                                if(invoice.pay_status == 'OWING'){
                                    icon = taxpayerRed;
                                    return;
                                }
                                else if(invoice.pay_status == 'PART PAID'){
                                    icon = taxpayerOrange;
                                }else{
                                    icon = taxpayerGreen;
                                }
                            });

                            marker = L.marker([taxpayer.latitude, taxpayer.longitude], { icon: icon });
                                
                        }else{
                           marker = L.marker([taxpayer.latitude, taxpayer.longitude], { icon: taxpayerBlue });
                        }

                        // let marker = L.marker([taxpayer.latitude,taxpayer.longitude]);
        
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
