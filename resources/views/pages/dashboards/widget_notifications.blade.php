<div class="card menu-column" style="background-color: white;height:calc(100% - 10px);width:100%;" >
    <!--begin::Heading-->

    <div style="opacity: 0;pointer-events:none;">
        <audio id="notif-sound2">
            <source src="/assets/media/audio/notif.wav">
        </audio>
    </div>

    <div class="d-flex flex-column bgi-no-repeat rounded-top">
        <!--begin::Title-->
        <h3 class=" fw-semibold px-9 mt-10 -mb-4">Notifications
            <span class="fs-8 opacity-75 ps-3 badge badge-light-danger"
                id="notif-counter2">{{ count(Auth::user()->unreadNotifications) }}</span>
        </h3>
        <!--end::Title-->
        <!--begin::Tabs-->
        <ul class="nav nav-line-tabs nav-line-tabs-2x nav-stretch fw-semibold px-9">
            <li class="nav-item">
                <a class="nav-link opacity-state-100 pb-4 active" data-bs-toggle="tab"
                    href="#kt_topbar_notifications_1">En attente</a>
            </li>
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

                <div id="notif-template2"></div>

                @if (count(Auth::user()->unreadNotifications) > 0)

                    <div id="first-notif-template2">
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
                                                href="/taxpayers/{{ $taxpayer->id ?? null }}/?&invoice_tab=true&notif_id={{ $notification->id }}"
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
                                                href="/taxpayers/{{ $taxpayer->id ?? null }}/?&invoice_tab=true&notif_id={{ $notification->id }}"
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
                    <p id="not-notif2" style="margin-top: 10px">Aucune notification.</p>
                @endif

                <!--end::Item-->
            </div>
            <!--end::Items-->
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
        </div>
        <!--end::Tab panel-->

    </div>
    <!--end::Tab content-->
</div>
<!--end::Menu-->

@push('scripts')
    <script>
        let notifTemplateDiv2 = document.getElementById('notif-template2');
        let notNotif2 = document.getElementById('not-notif2');
        let firstNotifTemplateDiv2 = document.getElementById('first-notif-template2');
        let globalNotif2 = document.querySelector('[data-globalnotif="true"]');
        let notifSpan2 = globalNotif2.querySelector('span');

        let notifSound2 = document.getElementById('notif-sound2');

        if (!localStorage.getItem('notifSize')) {
            localStorage.setItem('notifSize', 0);
        }

        let notifCounter2 = document.getElementById('notif-counter2');

        function getTitleByNotifType2(type, id) {

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

        function createNotifTemplate2(data, size) {
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
								${getTitleByNotifType2(element.notification.data.type,element.notification.data.invoice_id)}
							</a>
							<div class="text-gray-500 fs-7">Montant : ${element.notification.data.amount} FCFA - ${element.date}</div>
						</div>
					</div>
				</div>
				<span class="badge badge-light fs-8"></span>
				`;
            });

            if (notifTemplateDiv2 && size > 0) {
                firstNotifTemplateDiv2?.classList.add('d-none');
                notifTemplateDiv2.innerHTML = html;

                notifSpan2.classList.remove('d-none');
                if (notNotif2) {
                    notNotif2.innerHTML = '';
                }
            } else if (size == 0 && !firstNotifTemplateDiv2) {
                notNotif2.innerHTML = 'Aucune notification.';

            } else {
                notifSpan2.classList.add('d-none');
                firstNotifTemplateDiv2?.classList.remove('d-none');
            }
        }

        function render2(notifData) {
            let {
                data,
                size
            } = notifData[0];

            if (parseInt(localStorage.getItem('notifSize')) < size) {
                localStorage.setItem('notifSize', size);
                notifCounter2.innerHTML = size;
                notifSound2.play();
            }

            createNotifTemplate2(data, size);
        }

        async function makeNotifRequest2() {
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
                    render2(response);
                })
                .catch((error) => {
                    console.error(error);
                });
        }

        setInterval(async () => {
            await makeNotifRequest2()
        }, 5000);


        // Vérifie si le navigateur prend en charge l'API MediaDevices
        if (navigator.mediaDevices && navigator.mediaDevices.enumerateDevices) {
            // Vérifie si l'utilisateur a déjà accordé les permissions pour le microphone
            navigator.mediaDevices.enumerateDevices()
                .then(function(devices) {
                    const hasMicrophone = devices.some(function(device) {
                        return device.kind === 'audioinput';
                    });

                    if (!hasMicrophone) {
                        // Demande l'autorisation d'accéder au microphone
                        navigator.mediaDevices.getUserMedia({
                                audio: true
                            })
                            .then(function(stream) {
                                // L'utilisateur a autorisé l'accès au microphone
                                console.log('Accès au microphone autorisé !');
                            })
                            .catch(function(error) {
                                // L'utilisateur a refusé l'accès au microphone ou une erreur est survenue
                                console.error('Accès au microphone refusé ou erreur : ', error);
                            });
                    } else {
                        // L'utilisateur a déjà accordé les permissions pour le microphone
                        console.log('L\'utilisateur a déjà accordé les permissions pour le microphone.');
                    }
                })
                .catch(function(error) {
                    // Une erreur est survenue lors de la vérification des périphériques
                    console.error('Erreur lors de la vérification des périphériques : ', error);
                });
        } else {
            // Le navigateur ne prend pas en charge l'API MediaDevices
            console.log("Votre navigateur ne prend pas en charge l'API MediaDevices.");
        }
    </script>
@endpush
