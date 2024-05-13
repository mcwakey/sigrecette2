<div class="menu menu-sub menu-sub-dropdown menu-column w-350px w-lg-375px" data-kt-menu="true" id="kt_menu_notifications">
    <!--begin::Heading-->

	<div style="opacity: 0;pointer-events:none;">
		<audio id="notif-sound">
			<source src="/assets/media/audio/notif.wav" >
		</audio>
	</div>

    <div class="d-flex flex-column bgi-no-repeat rounded-top">
        <!--begin::Title-->
        <h3 class=" fw-semibold px-9 mt-10 -mb-4">Notifications
            <span class="fs-8 opacity-75 ps-3 badge badge-light-danger" id="notif-counter">{{ count(Auth::user()->unreadNotifications) }}</span>
        </h3>
        <!--end::Title-->
        <!--begin::Tabs-->
        <ul class="nav nav-line-tabs nav-line-tabs-2x nav-stretch fw-semibold px-9">
            <li class="nav-item">
                <a class="nav-link opacity-state-100 pb-4 active" data-bs-toggle="tab"
                    href="#kt_topbar_notifications_1">En attente</a>
            </li>
            <li class="nav-item">
                <a class="nav-link opacity-state-100 pb-4 " data-bs-toggle="tab" href="#kt_topbar_notifications_2">
					Déja consulté
				</a>
            </li>
            {{-- <li class="nav-item">
				<a class="nav-link text-white opacity-75 opacity-state-100 pb-4" data-bs-toggle="tab" href="#kt_topbar_notifications_3">Logs</a>
			</li> --}}
        </ul>
        <!--end::Tabs-->
    </div>
    <!--end::Heading-->
    <!--begin::Tab content-->
    <div class="tab-content">
        <!--begin::Tab panel-->
        <div class="tab-pane fade active show" id="kt_topbar_notifications_1" role="tabpanel">
            <!--begin::Items-->
            <div class="scroll-y mh-325px my-5 px-8">

                <div id="notif-template"></div>

                @if (count(Auth::user()->unreadNotifications) > 0)

                    <div id="first-notif-template">
                        @foreach (Auth::user()->unreadNotifications as $notification)
                            <div class="d-flex flex-stack py-4">

                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-35px me-4">
                                        <span class="symbol-label bg-light-primary">{!! getIcon('abstract-28', 'fs-2 text-primary') !!}</span>
                                    </div>

                                    @php
                                        $taxpayer = \App\Models\Taxpayer::find($notification->data['taxpayer_id']);
                                    @endphp


                                    @if ($notification->data['type'] === 'invoice_paid')
                                        <div class="mb-0 me-2">

											<a data-notif="true"
                                                href="/taxpayers/{{ $taxpayer->id }}/?&invoice_tab=true&notif_id={{ $notification->id }}"
                                                class="fs-6 text-gray-800 text-hover-primary fw-bold">
                                                    Paiement : {{ $notification->data['invoice_id'] }} - ajouté
                                          
                                            </a>
                                            <div class="text-gray-500 fs-7">
                                                {{ 'Montant :' . $notification->data['amount'] . ' FCFA - ' . $notification->created_at->diffForHumans() }}
                                            </div>

                                        </div>
                                    @else
                                        <div class="mb-0 me-2">
                                            <a data-notif="true"
                                                href="/taxpayers/{{ $taxpayer->id }}/?&invoice_tab=true&notif_id={{ $notification->id }}"
                                                class="fs-6 text-gray-800 text-hover-primary fw-bold">
                                                @if ($notification->data['type'] === 'invoice_created')
                                                    Avis : {{ $notification->data['invoice_id'] }} - créer
                                                @elseif($notification->data['type'] === 'invoice_accepted')
                                                    Avis : {{ $notification->data['invoice_id'] }} - accepter
                                                @elseif($notification->data['type'] === 'invoice_approved')
                                                    Avis : {{ $notification->data['invoice_id'] }} - pris en charge
                                                @else
                                                    Avis : {{ $notification->data['invoice_id'] }} - rejeter
                                                @endif
                                            </a>
                                            <div class="text-gray-500 fs-7">
                                                {{ 'Montant :' . $notification->data['amount'] . ' FCFA - ' . $notification->created_at->diffForHumans() }}
                                            </div>
                                        </div>
                                    @endif

                                </div>
                                <!--end::Title-->
                            </div>
                            <!--end::Section-->

                            <!--begin::Label-->
                            <span class="badge badge-light fs-8"></span>
                            <!--end::Label-->
                            <!-- Ajoutez d'autres conditions pour d'autres types de notification -->
                        @endforeach
                    </div>
                @else
                    <p id="not-notif">Aucune notification.</p>
                @endif

                <!--end::Item-->
            </div>
            <!--end::Items-->
            <!--begin::View more-->
            <div class="py-3 text-center border-top">
                <a href="#" class="btn btn-color-gray-600 btn-active-color-primary">Voir plus!
                    {!! getIcon('arrow-right', 'fs-5') !!}</a>
            </div>
            <!--end::View more-->
        </div>
        <!--end::Tab panel-->


        <!--begin::Tab panel-->
        <div class="tab-pane fade show" id="kt_topbar_notifications_2" role="tabpanel">
            <div class="scroll-y mh-325px my-5 px-8">

                <!--begin::Wrapper-->
                @if (count(Auth::user()->readNotifications) > 0)

                    @foreach (Auth::user()->readNotifications as $notification)
                        <div class="d-flex flex-stack py-4">
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-35px me-4">
                                    <span class="symbol-label bg-light-primary">{!! getIcon('abstract-28', 'fs-2 text-primary') !!}</span>
                                </div>

                                @php
                                    $taxpayer = \App\Models\Taxpayer::find($notification->data['taxpayer_id']);
                                @endphp

                                <div>

                                    @if ($notification->data['type'] === 'invoice_paid')
                                        <div class="mb-0 me-2">
                                            <a data-notif="true"
                                                href="/taxpayers/{{ $taxpayer->id }}/?&invoice_tab=true&notif_id={{ $notification->id }}"
                                                class="fs-6 text-gray-800 text-hover-primary fw-bold">
                                                    Paiement : {{ $notification->data['invoice_id'] }} - ajouté
                                            </a>
                                            <div class="text-gray-500 fs-7">
                                                {{ 'montant :' . $notification->data['amount'] . ' FCFA - ' . $notification->created_at->diffForHumans() }}
                                            </div>
                                        </div>
                                    @else
                                        <div class="mb-0 me-2">
                                            <a data-notif="true"
                                                href="/taxpayers/{{ $taxpayer->id }}/?&invoice_tab=true&notif_id={{ $notification->id }}"
                                                class="fs-6 text-gray-800 text-hover-primary">
                                                @if ($notification->data['type'] === 'invoice_created')
                                                    Avis : {{ $notification->data['invoice_id'] }} - créer
                                                @elseif($notification->data['type'] === 'invoice_accepted')
                                                    Avis : {{ $notification->data['invoice_id'] }} - accepter
                                                @elseif($notification->data['type'] === 'invoice_approved')
                                                    Avis : {{ $notification->data['invoice_id'] }} - pris en charge
                                                @else
                                                    Avis : {{ $notification->data['invoice_id'] }} - rejeter
                                                @endif
                                            </a>
                                            <div class="text-gray-500 fs-7">
                                                {{ 'Montant :' . $notification->data['amount'] . ' FCFA - ' . $notification->created_at->diffForHumans() }}
                                            </div>
                                        </div>
                                    @endif

                                </div>


                                <!--end::Title-->
                            </div>
                            <!--end::Section-->

                            <!--begin::Label-->
                            <span class="badge badge-light fs-8"></span>
                            <!--end::Label-->
                        </div>


                        <!-- Ajoutez d'autres conditions pour d'autres types de notification -->
                    @endforeach
                @else
                    <p>Aucune notification.</p>
                @endif

                <!--end::Wrapper-->
            </div>

            <!--begin::View more-->
            <div class="py-3 text-center border-top">
                <a href="#" class="btn btn-color-gray-600 btn-active-color-primary">Voir plus!
                    {!! getIcon('arrow-right', 'fs-5') !!}</a>
            </div>
            <!--end::View more-->

        </div>
        <!--end::Tab panel-->



        <!--begin::Tab panel-->
        <div class="tab-pane fade" id="kt_topbar_notifications_3" role="tabpanel">
            <!--begin::Items-->
            <div class="scroll-y mh-325px my-5 px-8">
                <!--begin::Item-->
                <div class="d-flex flex-stack py-4">
                    <!--begin::Section-->
                    <div class="d-flex align-items-center me-2">
                        <!--begin::Code-->
                        <span class="w-70px badge badge-light-success me-4">200 OK</span>
                        <!--end::Code-->
                        <!--begin::Title-->
                        <a href="#" class="text-gray-800 text-hover-primary fw-semibold">New order</a>
                        <!--end::Title-->
                    </div>
                    <!--end::Section-->
                    <!--begin::Label-->
                    <span class="badge badge-light fs-8">Just now</span>
                    <!--end::Label-->
                </div>
                <!--end::Item-->
                <!--begin::Item-->
                <div class="d-flex flex-stack py-4">
                    <!--begin::Section-->
                    <div class="d-flex align-items-center me-2">
                        <!--begin::Code-->
                        <span class="w-70px badge badge-light-danger me-4">500 ERR</span>
                        <!--end::Code-->
                        <!--begin::Title-->
                        <a href="#" class="text-gray-800 text-hover-primary fw-semibold">New customer</a>
                        <!--end::Title-->
                    </div>
                    <!--end::Section-->
                    <!--begin::Label-->
                    <span class="badge badge-light fs-8">2 hrs</span>
                    <!--end::Label-->
                </div>
                <!--end::Item-->
                <!--begin::Item-->
                <div class="d-flex flex-stack py-4">
                    <!--begin::Section-->
                    <div class="d-flex align-items-center me-2">
                        <!--begin::Code-->
                        <span class="w-70px badge badge-light-success me-4">200 OK</span>
                        <!--end::Code-->
                        <!--begin::Title-->
                        <a href="#" class="text-gray-800 text-hover-primary fw-semibold">Payment process</a>
                        <!--end::Title-->
                    </div>
                    <!--end::Section-->
                    <!--begin::Label-->
                    <span class="badge badge-light fs-8">5 hrs</span>
                    <!--end::Label-->
                </div>
                <!--end::Item-->
                <!--begin::Item-->
                <div class="d-flex flex-stack py-4">
                    <!--begin::Section-->
                    <div class="d-flex align-items-center me-2">
                        <!--begin::Code-->
                        <span class="w-70px badge badge-light-warning me-4">300 WRN</span>
                        <!--end::Code-->
                        <!--begin::Title-->
                        <a href="#" class="text-gray-800 text-hover-primary fw-semibold">Search query</a>
                        <!--end::Title-->
                    </div>
                    <!--end::Section-->
                    <!--begin::Label-->
                    <span class="badge badge-light fs-8">2 days</span>
                    <!--end::Label-->
                </div>
                <!--end::Item-->
                <!--begin::Item-->
                <div class="d-flex flex-stack py-4">
                    <!--begin::Section-->
                    <div class="d-flex align-items-center me-2">
                        <!--begin::Code-->
                        <span class="w-70px badge badge-light-success me-4">200 OK</span>
                        <!--end::Code-->
                        <!--begin::Title-->
                        <a href="#" class="text-gray-800 text-hover-primary fw-semibold">API connection</a>
                        <!--end::Title-->
                    </div>
                    <!--end::Section-->
                    <!--begin::Label-->
                    <span class="badge badge-light fs-8">1 week</span>
                    <!--end::Label-->
                </div>
                <!--end::Item-->
                <!--begin::Item-->
                <div class="d-flex flex-stack py-4">
                    <!--begin::Section-->
                    <div class="d-flex align-items-center me-2">
                        <!--begin::Code-->
                        <span class="w-70px badge badge-light-success me-4">200 OK</span>
                        <!--end::Code-->
                        <!--begin::Title-->
                        <a href="#" class="text-gray-800 text-hover-primary fw-semibold">Database restore</a>
                        <!--end::Title-->
                    </div>
                    <!--end::Section-->
                    <!--begin::Label-->
                    <span class="badge badge-light fs-8">Mar 5</span>
                    <!--end::Label-->
                </div>
                <!--end::Item-->
                <!--begin::Item-->
                <div class="d-flex flex-stack py-4">
                    <!--begin::Section-->
                    <div class="d-flex align-items-center me-2">
                        <!--begin::Code-->
                        <span class="w-70px badge badge-light-warning me-4">300 WRN</span>
                        <!--end::Code-->
                        <!--begin::Title-->
                        <a href="#" class="text-gray-800 text-hover-primary fw-semibold">System update</a>
                        <!--end::Title-->
                    </div>
                    <!--end::Section-->
                    <!--begin::Label-->
                    <span class="badge badge-light fs-8">May 15</span>
                    <!--end::Label-->
                </div>
                <!--end::Item-->
                <!--begin::Item-->
                <div class="d-flex flex-stack py-4">
                    <!--begin::Section-->
                    <div class="d-flex align-items-center me-2">
                        <!--begin::Code-->
                        <span class="w-70px badge badge-light-warning me-4">300 WRN</span>
                        <!--end::Code-->
                        <!--begin::Title-->
                        <a href="#" class="text-gray-800 text-hover-primary fw-semibold">Server OS update</a>
                        <!--end::Title-->
                    </div>
                    <!--end::Section-->
                    <!--begin::Label-->
                    <span class="badge badge-light fs-8">Apr 3</span>
                    <!--end::Label-->
                </div>
                <!--end::Item-->
                <!--begin::Item-->
                <div class="d-flex flex-stack py-4">
                    <!--begin::Section-->
                    <div class="d-flex align-items-center me-2">
                        <!--begin::Code-->
                        <span class="w-70px badge badge-light-warning me-4">300 WRN</span>
                        <!--end::Code-->
                        <!--begin::Title-->
                        <a href="#" class="text-gray-800 text-hover-primary fw-semibold">API rollback</a>
                        <!--end::Title-->
                    </div>
                    <!--end::Section-->
                    <!--begin::Label-->
                    <span class="badge badge-light fs-8">Jun 30</span>
                    <!--end::Label-->
                </div>
                <!--end::Item-->
                <!--begin::Item-->
                <div class="d-flex flex-stack py-4">
                    <!--begin::Section-->
                    <div class="d-flex align-items-center me-2">
                        <!--begin::Code-->
                        <span class="w-70px badge badge-light-danger me-4">500 ERR</span>
                        <!--end::Code-->
                        <!--begin::Title-->
                        <a href="#" class="text-gray-800 text-hover-primary fw-semibold">Refund process</a>
                        <!--end::Title-->
                    </div>
                    <!--end::Section-->
                    <!--begin::Label-->
                    <span class="badge badge-light fs-8">Jul 10</span>
                    <!--end::Label-->
                </div>
                <!--end::Item-->
                <!--begin::Item-->
                <div class="d-flex flex-stack py-4">
                    <!--begin::Section-->
                    <div class="d-flex align-items-center me-2">
                        <!--begin::Code-->
                        <span class="w-70px badge badge-light-danger me-4">500 ERR</span>
                        <!--end::Code-->
                        <!--begin::Title-->
                        <a href="#" class="text-gray-800 text-hover-primary fw-semibold">Withdrawal process</a>
                        <!--end::Title-->
                    </div>
                    <!--end::Section-->
                    <!--begin::Label-->
                    <span class="badge badge-light fs-8">Sep 10</span>
                    <!--end::Label-->
                </div>
                <!--end::Item-->
                <!--begin::Item-->
                <div class="d-flex flex-stack py-4">
                    <!--begin::Section-->
                    <div class="d-flex align-items-center me-2">
                        <!--begin::Code-->
                        <span class="w-70px badge badge-light-danger me-4">500 ERR</span>
                        <!--end::Code-->
                        <!--begin::Title-->
                        <a href="#" class="text-gray-800 text-hover-primary fw-semibold">Mail tasks</a>
                        <!--end::Title-->
                    </div>
                    <!--end::Section-->
                    <!--begin::Label-->
                    <span class="badge badge-light fs-8">Dec 10</span>
                    <!--end::Label-->
                </div>
                <!--end::Item-->
            </div>
            <!--end::Items-->
            <!--begin::View more-->
            <div class="py-3 text-center border-top">
                <a href="#" class="btn btn-color-gray-600 btn-active-color-primary">View All
                    {!! getIcon('arrow-right', 'fs-5') !!}</a>
            </div>
            <!--end::View more-->
        </div>
        <!--end::Tab panel-->
    </div>
    <!--end::Tab content-->
</div>
<!--end::Menu-->

@push('scripts')
    <script>
        let notifTemplateDiv = document.getElementById('notif-template');
        let notNotif = document.getElementById('not-notif');
        let firstNotifTemplateDiv = document.getElementById('first-notif-template');
        let globalNotif = document.querySelector('[data-globalnotif="true"]');
        let notifSpan = globalNotif.querySelector('span');
		
		let notifSound = document.getElementById('notif-sound');

		if(!localStorage.getItem('notifSize')){
			localStorage.setItem('notifSize', 0);
		}

		let notifCounter = document.getElementById('notif-counter');

        function getTitleByNotifType(type, id) {

            if (type === 'invoice_paid') {
                return `Paiement : ${id} - ajouter`
            } else if (type === 'invoice_created') {
                return `Avis : ${id} - créer`
            } else if (type === 'invoice_accepted') {
                return `Avis : ${id} - accepter`
            } else if (type === 'invoice_approved') {
                return `Avis : ${id} - pris en charge`
            } else {
                return `Avis : ${id} - rejeter`
            }
        }

        function createNotifTemplate(data, size) {
            let html = ``;

            data.forEach(element => {
                html += `
				<div class="d-flex flex-stack py-4">
					<div class="d-flex align-items-center">
						<div class="symbol  symbol-35px me-4">
							<span class="symbol-label bg-light-primary">
								<span class="ki-duotone ki-abstract-28 fs-2 text-primary"><span class="path1"></span><span class="path2"></span></span>
							</span>
						</div>

						<div class="mb-0 me-2">
							<a data-notif="true" href="/taxpayers/${element.taxpayer.id}/?&invoice_tab=true&notif_id=${element.notification.id}" class="fs-6 text-gray-800 text-hover-primary fw-bold">
								${getTitleByNotifType(element.notification.data.type,element.notification.data.invoice_id)}
							</a>
							<div class="text-gray-500 fs-7">Montant : ${element.notification.data.amount} FCFA - ${element.date}</div>
						</div>
					</div>
				</div>
				<span class="badge badge-light fs-8"></span>
				`;
            });

            if (notifTemplateDiv && size > 0) {
                firstNotifTemplateDiv?.classList.add('d-none');
                notifTemplateDiv.innerHTML = html;

                notifSpan.classList.remove('d-none');
				if(notNotif){
					notNotif.innerHTML = '';
				}
            } else if (size == 0 && !firstNotifTemplateDiv) {
                notNotif.innerHTML = 'Aucune notification.';

            } else {
                notifSpan.classList.add('d-none');
                firstNotifTemplateDiv?.classList.remove('d-none');
            }
        }

        function render(notifData) {
            let {
                data,
                size
            } = notifData[0];

			if(parseInt(localStorage.getItem('notifSize')) < size){
				localStorage.setItem('notifSize',size);
				notifCounter.innerHTML = size;
				notifSound.play();
			}

            createNotifTemplate(data, size);
        }

        async function makeNotifRequest() {
            let request = new Request('/api/v1/user/notifications', {
                method: "POST",
            });

            fetch(request)
                .then((response) => {
                    if (response.status === 200) {
                        return response.json();
                    } else {
                        throw new Error("Something went wrong on API server!");
                    }
                })
                .then((response) => {
                    render(response);
                })
                .catch((error) => {
                    console.error(error);
                });
        }

        setInterval(async () => {
           await makeNotifRequest()
        }, 5000);
    </script>
@endpush
