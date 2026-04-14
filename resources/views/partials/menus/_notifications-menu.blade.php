<div class="menu menu-sub menu-sub-dropdown menu-column w-350px w-lg-375px" data-kt-menu="true" id="kt_menu_notifications">
    <!--begin::Heading-->

    <div style="opacity: 0;pointer-events:none;">
        <audio id="notif-sound">
            <source src="/assets/media/audio/notif.wav">
        </audio>
    </div>

    <div class="d-flex flex-column bgi-no-repeat rounded-top">
        <!--begin::Title-->
        <div class="d-flex align-items-center justify-content-between px-9 mt-10 mb-0">
            <h3 class="fw-semibold mb-0">
                Notifications
                <span class="fs-8 opacity-75 ps-3 badge badge-light-danger"
                    id="notif-counter">{{ count(Auth::user()->unreadNotifications) }}
                </span>
            </h3>
            <button type="button" class="btn btn-sm btn-light-primary py-1 px-3 fs-8" id="mark-all-read-btn"
                title="Tout marquer comme lu">
                <i class="ki-duotone ki-double-check fs-6"><span class="path1"></span><span class="path2"></span></i> Tout lu
            </button>
        </div>
        <!--end::Title-->
        <!--begin::Tabs-->
        <ul class="nav nav-line-tabs nav-line-tabs-2x nav-stretch fw-semibold px-9">
            <li class="nav-item">
                <a class="nav-link opacity-state-100 pb-4 active" data-bs-toggle="tab"
                    href="#kt_topbar_notifications_1">En attente</a>
            </li>
            <li class="nav-item">
                <a class="nav-link opacity-state-100 pb-4 " data-bs-toggle="tab" href="#kt_topbar_notifications_2">
                    Déjà consulté
                </a>
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

                <div id="notif-template"></div>

                @if (count(Auth::user()->unreadNotifications) > 0)

                    <div id="first-notif-template">
                        @foreach (Auth::user()->unreadNotifications as $notification)
                            <div class="d-flex flex-stack py-4">

                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-35px me-4">
                                        <span class="symbol-label bg-light-primary">{!! getIcon('abstract-28', 'fs-2 text-primary') !!}</span>
                                    </div>

                                    @if ($notification->data['type'] === 'invoice_paid')
                                        <div class="mb-0 me-2">

                                            <a data-notif="true"
                                               href="/invoices?invoice_id={{ $notification->data['invoice_id']  ?? null }}&?&invoice_tab=true&notif_id={{ $notification->id }}"
                                                class="fs-6 text-gray-800 text-hover-primary fw-bold">
                                                Paiement : {{ $notification->data['invoice_id'] }} - ajouté

                                            </a>
                                            <div class="text-gray-500 fs-7">
                                                {{ 'Montant :' . $notification->data['amount'] . ' FCFA - ' . $notification->created_at->diffForHumans() }}
                                            </div>

                                        </div>
                                    @else
                                        <div class="mb-0 me-2">
                                            @if ($notification->data['type'] !== 'file_is_ready')
                                                <a data-notif="true"
                                                   href="/invoices?invoice_id={{ $notification->data['invoice_id']  ?? null }}&?&invoice_tab=true&notif_id={{ $notification->id }}"
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
                                            @else
                                                <a href="{{ route('download.file', ['filename' => $notification->data['file_name'],'id'=>$notification->id]) }}"                                                   class="fs-6 text-gray-800 text-hover-primary fw-bold" target="_blank">
                                                    Télécharger le fichier
                                                </a>
                                            @endif

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



                                <div>
                                    @if ($notification->data['type'] !== 'file_is_ready')
                                        @php
                                            $taxpayer = \App\Models\Taxpayer::find($notification->data['taxpayer_id']);
                                        @endphp
                                        @if ($notification->data['type'] === 'invoice_paid')
                                            <div class="mb-0 me-2">
                                                <a data-notif="true"
                                                   href="/invoices?invoice_id={{ $notification->data['invoice_id']  ?? null }}&?&invoice_tab=true&notif_id={{ $notification->id }}"
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
                                                   href="/invoices?invoice_id={{ $notification->data['invoice_id']  ?? null }}&?&invoice_tab=true&notif_id={{ $notification->id }}"
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

        if (!localStorage.getItem('notifSize')) {
            localStorage.setItem('notifSize', 0);
        }

        let notifCounter = document.getElementById('notif-counter');

        function getTitleByNotifType(type, id) {

            if (type !== 'file_ready') {
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
            }else {
                return `Ficher Disponible`
            }

        }

        function createNotifTemplate(data, size) {
            let html = ``;
            data.forEach(element => {
                let notifData = element.notification.data;
                let linkHtml;
                if (notifData.type === 'file_is_ready') {
                    linkHtml = `
                        <a href="/download/${encodeURIComponent(notifData.file_name)}/${encodeURIComponent(element.notification.id)}" class="fs-6 text-gray-800 text-hover-primary fw-bold" target="_blank">
                            Télécharger le fichier
                        </a>
                        <div class="text-gray-500 fs-7">${element.date}</div>`;
                } else {
                    linkHtml = `
                        <a data-notif="true" href="/invoices?invoice_id=${notifData.invoice_id}&notif_id=${element.notification.id}" class="fs-6 text-gray-800 text-hover-primary fw-bold">
                            ${getTitleByNotifType(notifData.type, notifData.invoice_id)}
                        </a>
                        <div class="text-gray-500 fs-7">Montant : ${notifData.amount} FCFA - ${element.date}</div>`;
                }
                html += `
				<div class="d-flex flex-stack py-4">
					<div class="d-flex align-items-center">
						<div class="symbol  symbol-35px me-4">
							<span class="symbol-label bg-light-primary">
								<span class="ki-duotone ki-abstract-28 fs-2 text-primary"><span class="path1"></span><span class="path2"></span></span>
							</span>
						</div>

						<div class="mb-0 me-2">
							${linkHtml}
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
                if (notNotif) {
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

            if (parseInt(localStorage.getItem('notifSize')) < size) {
                // Check for new file_is_ready notifications
                data.forEach(function(item) {
                    if (item.notification && item.notification.data && item.notification.data.type === 'file_is_ready') {
                        showFileReadyAlert(item.notification.data.file_name, item.notification.id);
                    }
                });

                localStorage.setItem('notifSize', size);
                notifCounter.innerHTML = size;
                notifSound.play();
            }

            createNotifTemplate(data, size);
        }

        function showFileReadyAlert(fileName, notifId) {
            let downloadUrl = `/download/${encodeURIComponent(fileName)}/${encodeURIComponent(notifId)}`;
            let alertHtml = `
                <div class="alert alert-success alert-dismissible d-flex align-items-center position-fixed bottom-0 end-0 m-4 shadow-lg" role="alert" style="z-index: 9999; min-width: 350px;">
                    <i class="ki-duotone ki-file-down fs-2hx text-success me-4"><span class="path1"></span><span class="path2"></span></i>
                    <div>
                        <h4 class="alert-heading fs-6 fw-bold mb-1">Fichier prêt !</h4>
                        <p class="mb-2 fs-7">Votre fichier ZIP est prêt au téléchargement.</p>
                        <a href="${downloadUrl}" target="_blank" class="btn btn-sm btn-success">Télécharger</a>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>`;
            document.body.insertAdjacentHTML('beforeend', alertHtml);
            setTimeout(function() {
                let alert = document.querySelector('.alert.position-fixed.bottom-0.end-0');
                if (alert) alert.remove();
            }, 30000);
        }

        async function makeNotifRequest() {
            try {
                const response = await fetch('/api/v1/user/notifications', {
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'Accept': 'application/json',
                    },
                    credentials: 'same-origin',
                });

                if (response.status === 401 || response.status === 419) {
                    return; // Session expired, skip silently
                }

                if (!response.ok) {
                    return; // Non-critical, skip silently
                }

                const data = await response.json();
                render(data);
            } catch (error) {
                // Network error, skip silently
            }
        }

        setInterval(async () => {
            await makeNotifRequest()
        }, 10000);

        // Mark all notifications as read
        document.getElementById('mark-all-read-btn').addEventListener('click', async function() {
            try {
                const response = await fetch('/api/v1/user/notifications/mark-all-read', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'Accept': 'application/json',
                    },
                    credentials: 'same-origin',
                });
                if (response.ok) {
                    notifCounter.innerHTML = '0';
                    localStorage.setItem('notifSize', 0);
                    if (firstNotifTemplateDiv) firstNotifTemplateDiv.innerHTML = '';
                    if (notifTemplateDiv) notifTemplateDiv.innerHTML = '';
                    if (notNotif) notNotif.innerHTML = 'Aucune notification.';
                    notifSpan.classList.add('d-none');
                }
            } catch (e) { }
        });


        if (navigator.mediaDevices && navigator.mediaDevices.enumerateDevices) {
            navigator.mediaDevices.enumerateDevices()
                .then(function(devices) {
                    const hasMicrophone = devices.some(function(device) {
                        return device.kind === 'audioinput';
                    });

                    if (!hasMicrophone) {
                        navigator.mediaDevices.getUserMedia({
                                audio: true
                            })
                            .then(function(stream) {
                                console.log('Accès au microphone autorisé !');
                            })
                            .catch(function(error) {
                                console.error('Accès au microphone refusé ou erreur : ', error);
                            });
                    } else {
                        console.log('L\'utilisateur a déjà accordé les permissions pour le microphone.');
                    }
                })
                .catch(function(error) {
                    console.error('Erreur lors de la vérification des périphériques : ', error);
                });
        } else {
            console.log("Votre navigateur ne prend pas en charge l'API MediaDevices.");
        }
    </script>
@endpush
