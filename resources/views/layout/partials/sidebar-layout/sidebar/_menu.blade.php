<!--begin::sidebar menu-->
<div class="app-sidebar-menu overflow-hidden flex-column-fluid">
    <!--begin::Menu wrapper-->
    <div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper hover-scroll-overlay-y my-5" data-kt-scroll="true"
         data-kt-scroll-activate="true" data-kt-scroll-height="auto"
         data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer"
         data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px" data-kt-scroll-save-state="true">
        <!--begin::Menu-->
        <div class="menu menu-column menu-rounded menu-sub-indention px-3 fw-semibold fs-6" id="#kt_app_sidebar_menu"
             data-kt-menu="true" data-kt-menu-expand="false">
            <!--begin:Menu item-->

            <div class="menu-item">
                <!--begin:Menu link-->
                <a class="menu-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                   href="{{ route('dashboard') }}" href="{{ route('dashboard') }}">
                    <span class="menu-icon">{!! getIcon('element-11', 'fs-2') !!}</span>
                    <span class="menu-title">{{ __('dashboard') }}</span>
                </a>
                <!--end:Menu link-->
            </div>

            <!--end:Menu item-->
            <!--begin:Menu item-->
            <div class="menu-item pt-5">
                <!--begin:Menu content-->
                <div class="menu-content">
                    <span class="menu-heading fw-bold text-uppercase fs-7">{{ __('home') }}</span>
                </div>
                <!--end:Menu content-->
            </div>
            <!--end:Menu item-->
            <!--begin:Menu item-->
            <div data-kt-menu-trigger="click"
                 class="menu-item menu-accordion {{ request()->routeIs('taxpayers.*') ? 'here show' : '' }}">
                <!--begin:Menu link-->
                <span class="menu-link">
                    <span class="menu-icon">{!! getIcon('user', 'fs-2') !!}</span>
                    <span class="menu-title">{{ __('taxpayer') }}</span>
                    <span class="menu-arrow"></span>
                </span>
                <!--end:Menu link-->
                <!--begin:Menu sub-->
                <div class="menu-sub menu-sub-accordion">
                    <div class="menu-item">

                        @can('peut créer un contribuable')
                            <span class="menu-link ">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title" data-bs-toggle="modal"
                                      data-bs-target="#kt_modal_add_taxpayer">{{ __('Nouveau contribuable') }}</span>
                            </span>
                        @endcan

                        <a class="menu-link {{ request()->routeIs('taxpayers.*')  && !request()->has('disable') && !request()->has('state') ? 'active' : '' }}"
                           href="{{ route('taxpayers.index') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Liste des contribuables</span>
                        </a>
                            <a class="menu-link {{ request()->routeIs('taxpayers.*') && request()->has('state')  ? 'active' : '' }}"
                               href="{{ route('taxpayers.index', ['state' => 'at']) }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                                <span class="menu-title">Liste des contribuables en attente de validation</span>
                            </a>
                        <a class="menu-link {{ request()->routeIs('taxpayers.*') && request()->has('disable') && request()->input('disable')==1 ? 'active' : '' }}"
                           href="{{ route('taxpayers.index', ['disable' => true]) }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Liste des contribuables   {{ __('désactiver') }}</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item-->
                </div>
                <!--end:Menu sub-->
            </div>
            <!--end:Menu item-->

            <!--begin:Menu item-->
            <div data-kt-menu-trigger="click"
                 class="menu-item menu-accordion {{ request()->routeIs('invoices.*') ? 'here show' : '' }}">
                <!--begin:Menu link-->
                <span class="menu-link">
                    <span class="menu-icon">{!! getIcon('abstract-10', 'fs-2') !!}</span>
                    <span class="menu-title">{{ __('invoice') }}</span>
                    <span class="menu-arrow"></span>
                </span>
                <div class="menu-sub menu-sub-accordion">
                    <div class="menu-item">
                    <a class="menu-link {{ request()->routeIs('taxpayers.*') && request()->has('rc') && request()->input('rc') =='taxation' ? 'active' : '' }}"
                       href="{{ route('taxpayers.index', ['rc' => 'taxation']) }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                        <span class="menu-title">{{__("Nouvel Taxation")}}</span>
                    </a>

                    </div>
                    <div class="menu-item">
                        <a class="menu-link {{ request()->routeIs('taxpayers.*') && request()->has('rc') && request()->input('rc') =='avis' ? 'active' : '' }}"
                           href="{{ route('taxpayers.index', ['rc' => 'avis']) }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">{{__("Nouvel avis sur titre")}}</span>
                        </a>
                    </div>
                    <div class="menu-item">
                          <span class="menu-link ">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title" data-bs-toggle="modal"
                                      data-bs-target="#kt_modal_add_invoice_no_taxpayer">{{__("Nouvel avis au comptant")}}</span>
                            </span>
                    </div>
                    <div class="menu-item">
                          <span class="menu-link ">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title" data-bs-toggle="modal"
                                      data-bs-target="#kt_modal_add_invoice_no_taxpayer">{{__("Nouvel avis de réduction  ou d’annulation")}}</span>
                            </span>
                    </div>
                </div>
                <!--end:Menu sub-->
            </div>
            <div data-kt-menu-trigger="click"
                 class="menu-item menu-accordion {{ request()->routeIs('invoices.*') ? 'here show' : '' }}">
                <!--begin:Menu link-->
                <span class="menu-link">
                    <span class="menu-icon">{!! getIcon('abstract-26', 'fs-2') !!}</span>
                    <span class="menu-title">{{ __('Gestion des avis') }}</span>
                    <span class="menu-arrow"></span>
                </span>
                <div class="menu-sub menu-sub-accordion">


                    <div class="menu-item">
                        <a class="menu-link {{ request()->routeIs('invoices.*') && request()->has('state') && request()->input('state') == App\Helpers\Constants::INVOICE_STATE_DRAFT_KEY ? 'active' : '' }}"
                           href="{{ route('invoices.index', ['state' =>  App\Helpers\Constants::INVOICE_STATE_DRAFT_KEY,'type' => App\Helpers\Constants::INVOICE_TYPE_TITRE_KEY]) }}">
        <span class="menu-bullet">
            <span class="bullet bullet-dot"></span>
        </span>
                            <span class="menu-title">Validation des avis sur titre</span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a class="menu-link {{ request()->routeIs('invoices.*') && request()->has('state') && request()->input('state') == App\Helpers\Constants::INVOICE_STATE_ACCEPTED_KEY ? 'active' : '' }}"
                           href="{{ route('invoices.index', ['state' =>  App\Helpers\Constants::INVOICE_STATE_ACCEPTED_KEY,'type' => App\Helpers\Constants::INVOICE_TYPE_TITRE_KEY])}}">
        <span class="menu-bullet">
            <span class="bullet bullet-dot"></span>
        </span>
                            <span class="menu-title">Enregistrement  du  N°  d'ordre de recette  des avis sur titre</span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a class="menu-link {{request()->routeIs('invoices.*') && request()->has('state') && request()->input('state') ==   App\Helpers\Constants::INVOICE_STATE_PENDING_KEY ? 'active' : '' }}"
                           href="{{  route('invoices.index', ['state' =>  App\Helpers\Constants::INVOICE_STATE_PENDING_KEY,'type' => App\Helpers\Constants::INVOICE_TYPE_TITRE_KEY]) }}">
        <span class="menu-bullet">
            <span class="bullet bullet-dot"></span>
        </span>
                            <span class="menu-title">Prise en charge/Rejet des avis sur titre</span>
                        </a>
                    </div>

                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('invoices.*') && request()->input('delivery') == App\Helpers\Constants::INVOICE_DELIVERY_NON_LIV_KEY ? 'active' : '' }}"
                           href="{{ route('invoices.index', ['delivery' => App\Helpers\Constants::INVOICE_DELIVERY_NON_LIV_KEY,'type' => App\Helpers\Constants::INVOICE_TYPE_TITRE_KEY  ]) }}">
        <span class="menu-bullet">
            <span class="bullet bullet-dot"></span>
        </span>
                            <span class="menu-title">Distribution des avis sur titre</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('invoices.*') &&  request()->has('delivery')&& request()->input('delivery') ==  App\Helpers\Constants::INVOICE_DELIVERY_LIV_KEY ? 'active' : '' }}"
                           href="{{ route('invoices.index', ['delivery' =>App\Helpers\Constants::INVOICE_DELIVERY_LIV_KEY,'type' => App\Helpers\Constants::INVOICE_TYPE_TITRE_KEY]) }}">
        <span class="menu-bullet">
            <span class="bullet bullet-dot"></span>
        </span>
                            <span class="menu-title">Liste des avis sur titre distribués</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <div class="menu-item">
                        <a class="menu-link {{ request()->routeIs('invoices.*') && !request()->has('delivery')&&!request()->has('state') && request()->input('type') ==  App\Helpers\Constants::INVOICE_TYPE_TITRE_KEY  ? 'active' : '' }}"
                           href="{{ route('invoices.index' ,['type' => App\Helpers\Constants::INVOICE_TYPE_TITRE_KEY ]) }}">
        <span class="menu-bullet">
            <span class="bullet bullet-dot"></span>
        </span>
                            <span class="menu-title">Liste générale des avis sur titre </span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a class="menu-link {{request()->routeIs('invoices.*') && request()->has('state') && request()->input('state') == App\Helpers\Constants::INVOICE_STATE_REJECT_KEY ? 'active' : ''  }}"
                           href="{{ route('invoices.index' ,['state'=>App\Helpers\Constants::INVOICE_STATE_REJECT_KEY,'type' => App\Helpers\Constants::INVOICE_TYPE_TITRE_KEY ]) }}">
        <span class="menu-bullet">
            <span class="bullet bullet-dot"></span>
        </span>
                            <span class="menu-title">Liste des avis rejetés </span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('invoices.*') &&  request()->input('type') ==    App\Helpers\Constants::INVOICE_TYPE_COMPTANT_KEY? 'active' : '' }}"
                           href="{{ route('invoices.index', ['type' =>   App\Helpers\Constants::INVOICE_TYPE_COMPTANT_KEY]) }}">
        <span class="menu-bullet">
            <span class="bullet bullet-dot"></span>
        </span>
                            <span class="menu-title">Liste des avis au comptant</span>
                        </a>
                        <!--end:Menu link-->
                    </div>



                </div>
                <!--end:Menu sub-->
            </div>
            <!--end:Menu item-->

        @can('peut voir le recouvrement')
            <!--begin:Menu item-->
                <div data-kt-menu-trigger="click"
                     class="menu-item menu-accordion {{ request()->routeIs('recoveries.*') ? 'here show' : '' }}">
                    <!--begin:Menu link-->
                    <span class="menu-link">
                        <span class="menu-icon">{!! getIcon('abstract-28', 'fs-2') !!}</span>
                        <span class="menu-title">{{ __('revenue') }}</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <!--end:Menu link-->
                    <!--begin:Menu sub-->
                    <div class="menu-sub menu-sub-accordion">
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link {{ request()->routeIs('recoveries.*') &&  request()->has('delivery')&& request()->input('delivery') ==  App\Helpers\Constants::INVOICE_DELIVERY_LIV_KEY ? 'active' : '' }}"
                               href="{{ route('recoveries.index', ['delivery' =>  App\Helpers\Constants::INVOICE_DELIVERY_LIV_KEY,'to_paid'=>1]) }}">
        <span class="menu-bullet">
            <span class="bullet bullet-dot"></span>
        </span>
                                <span class="menu-title">Liste des avis  à recouvrer</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link {{ request()->routeIs('recoveries.*') &&  !request()->has('delivery') ? 'active' : '' }}"
                               href="{{ route('recoveries.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">{{ __('Liste des recouvrements') }}</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                    </div>
                    <!--end:Menu sub-->
                </div>
                <!--end:Menu item-->
            @endcan

            <div data-kt-menu-trigger="click"
                 class="menu-item menu-accordion {{ request()->routeIs('ticket.*') || request()->routeIs('user-management.users.*')&& request()->has('type') ? 'here show' : '' }}">
                <!--begin:Menu link-->
                <span class="menu-link">
                        <span class="menu-icon">{!! getIcon('abstract-7', 'fs-2') !!}</span>
                        <span class="menu-title">{{ __('Gestion des valeurs inactives') }}</span>
                        <span class="menu-arrow"></span>
                    </span>
                <!--end:Menu link-->
                <!--begin:Menu sub-->
                <div class="menu-sub menu-sub-accordion">

                    <div class="menu-item">
                        <a class="menu-link {{request()->routeIs('ticket.*') && request()->has('autoClick') && request()->input('autoClick') =='addstockbtn' ? 'active' : '' }}"
                           href="{{ route('ticket.stock-requests.index', [ 'autoClick' => 'addstockbtn']) }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">{{ __('new stock request') }}</span>
                        </a>
                    </div>
                    <div class="menu-item">
                          <span class="menu-link ">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title" data-bs-toggle="modal"
                                      data-bs-target="#kt_modal_add_stock_request"  data-kt-action="add_request">{{ __('new stock request') }}</span>
                            </span>
                    </div>
                    <div class="menu-item">
                        <a class="menu-link {{ request()->routeIs('ticket.stock-requests.*') && !request()->has('autoClick') ? 'active' : '' }}"
                           href="{{ route('ticket.stock-requests.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                            <span class="menu-title">{{ __('Stock des valeurs inactives du regisseur') }}
                                </span>
                        </a>
                    </div>

<div class="menu-item">
    <a class="menu-link {{request()->routeIs('ticket.*') && request()->has('autoClick') && request()->input('autoClick') =='addstockbtn' ? 'active' : '' }}"
       href="{{ route('ticket.stock-transfers.index', [ 'autoClick' => 'addstockbtn']) }}">
        <span class="menu-bullet">
            <span class="bullet bullet-dot"></span>
        </span>
        <span class="menu-title">{{ __('new supply') }}</span>
    </a>
</div>
                    <div class="menu-item">
                          <span class="menu-link ">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title" data-bs-toggle="modal"
                                      data-bs-target="#kt_modal_add_stock_transfer" data-kt-action="add_transfer"> {{ __('new supply') }}</span>
                            </span>
                    </div>

                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <a class="menu-link {{ request()->routeIs('ticket.stock-transfers.*') ? 'active' : '' }}"
                           href="{{ route('ticket.stock-transfers.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                            <span class="menu-title">{{ __('Stock des valeurs inactives du collecteur') }}
                                </span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('user-management.users.*') && request()->has('type')&& !request()->has('disable')  ? 'active' : ''}}"
                           href="{{ route('user-management.users.index',['type' => 'col']) }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                            <span class="menu-title">{{ __('Liste des collecteurs') }}</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item-->

                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('user-management.users.*') && request()->has('type')&& request()->has('disable')  ? 'active' : '' }}"
                           href="{{ route('user-management.users.index',['disable' => true,'type' => 'col']) }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                            <span class="menu-title">{{ __('Liste des collecteurs désactivé') }}</span>
                        </a>
                        <!--end:Menu link-->
                    </div>

                </div>
                <!--end:Menu sub-->
            </div>
            @can('peut voir la comptabilité')
                <div data-kt-menu-trigger="click"
                     class="menu-item menu-accordion {{ request()->routeIs('accounts.*') ? 'here show' : '' }}">
                    <!--begin:Menu link-->
                    <span class="menu-link">
                        <span class="menu-icon">{!! getIcon('abstract-27', 'fs-2') !!}</span>
                        <span class="menu-title">{{ __('accounts') }}</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <!--end:Menu link-->
                    <!--begin:Menu sub-->
                    <div class="menu-sub menu-sub-accordion">
                        <!--end:Menu item-->

                        <!--begin:Menu item-->
                    <!-- <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('accounts.accountant-deposits-outright.*') ? 'active' : '' }}"
                                href="{{ route('accounts.accountant-deposits-outright.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">{{ __('Etat de versement du regisseur - Recettes au comptant') }} </span>
                            </a>
                        </div> -->
                        <!--end:Menu item-->


                        <!--begin:Menu item-->

                        <!--end:Menu item-->


                        <!--begin:Menu item-->


                        <div class="menu-item">
                          <span class="menu-link ">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title" data-bs-toggle="modal"
                                data-bs-target="#kt_modal_add_stock_transfer-deposit" data-kt-user-id="{{-- $user->id --}}" data-kt-action="add_deposit">   {{ __('Nouveau versement du Collecteur') }}</span>
                            </span>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('accounts.collector-deposits.*') ? 'active' : '' }}"
                               href="{{ route('accounts.collector-deposits.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">{{ __('Etat de versement du collecteur') }} </span>
                            </a>
                        </div>
                        <!--end:Menu item-->




                        <div class="menu-item">
                          <span class="menu-link ">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title" data-bs-toggle="modal"
                                      data-bs-target="#kt_modal_add_accountant_deposit" data-kt-user-id="TITRE" data-kt-action="add_accountant_deposit">    {{ __('Nouveau versement du Regisseur') }}</span>
                            </span>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('accounts.accountant-deposits-title.*') ? 'active' : '' }}"
                               href="{{ route('accounts.accountant-deposits-title.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">{{ __('Etat de versement du regisseur') }} </span>
                            </a>
                        </div>
                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link {{ request()->routeIs('accounts.ledgers.*') ? 'active' : '' }}"
                               href="{{ route('accounts.ledgers.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">{{ __('Livre - journal de la regie') }}</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                    </div>
                    <!--end:Menu sub-->
                </div>
        @endcan


        <!--begin:Menu item-->
            <div data-kt-menu-trigger="click"
                 class="menu-item menu-accordion {{ request()->routeIs('geolocation.*') ? 'here show' : '' }}">
                <!--begin:Menu link-->
                <span class="menu-link">
                    <span class="menu-icon">{!! getIcon('map', 'fs-2') !!}</span>
                    <span class="menu-title">{{ __('Géolocalisation') }}</span>
                    <span class="menu-arrow"></span>
                </span>
                <!--end:Menu link-->
                <!--begin:Menu sub-->
                <div class="menu-sub menu-sub-accordion">
                    <div class="menu-item">
                    @can('peut voir la geolocalisation d\'un contribuable')
                        <!--begin:Menu link-->
                            <a class="menu-link {{ request()->routeIs('geolocation.zones') ? 'active' : '' }}"
                               href="{{ route('geolocation.taxpayers') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">{{ __('taxpayers') }}</span>
                            </a>
                    @endcan
                    <!--end:Menu link-->
                    </div>
                    <!--end:Menu item-->
                </div>
                <!--end:Menu sub-->
            </div>
            <div class="menu-item">
                <!--begin:Menu link-->
                <a class="menu-link {{ request()->routeIs('prints') ? 'active' : '' }}"
                   href="{{ route('prints') }}" href="{{ route('prints') }}">
                    <span class="menu-icon">{!! getIcon('element-11', 'fs-2') !!}</span>
                    <span class="menu-title">{{ __('Impression') }}</span>
                </a>
                <!--end:Menu link-->
            </div>

            <!--begin:Menu item-->
            <!--end:Menu item-->
            <!--begin:Menu item-->
            @hasanyrole(['administrateur_system','administrateur'])

            <div class="menu-item pt-5">
                <!--begin:Menu content-->
                <div class="menu-content">
                    <span class="menu-heading fw-bold text-uppercase fs-7">{{ __('Paramétrages') }}</span>
                </div>
                <!--end:Menu content-->
            </div>

            <div data-kt-menu-trigger="click"
                 class="menu-item menu-accordion {{ request()->routeIs('taxations.*') ? 'here show' : '' }}">

                    <span class="menu-link">
                        <span class="menu-icon">{!! getIcon('technology-4', 'fs-2') !!}</span>
                        <span class="menu-title">{{ __('taxations') }}</span>
                        <span class="menu-arrow"></span>
                    </span>
                <div class="menu-sub menu-sub-accordion pt-3">
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('taxations.taxlabels.*') ? 'active' : '' }}"
                           href="{{ route('taxations.taxlabels.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                            <span class="menu-title">{{ __('taxlabels') }}</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item-->
                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('taxations.taxables.*') ? 'active' : '' }}"
                           href="{{ route('taxations.taxables.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                            <span class="menu-title">{{ __('taxables') }}</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('taxations.tickets.*') ? 'active' : '' }}"
                           href="{{ route('taxations.tickets.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                            <span class="menu-title">{{ __('tickets') }}</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                </div>
            </div>

            <!--end:Menu item-->

            <!--begin:Menu item-->
            <div data-kt-menu-trigger="click"
                 class="menu-item menu-accordion {{ request()->routeIs('administratives.*') ? 'here show' : '' }}">

                    <span class="menu-link">
                        <span class="menu-icon">{!! getIcon('pointers', 'fs-2') !!}</span>
                        <span class="menu-title">{{ __('Découpage administratif') }}</span>
                        <span class="menu-arrow"></span>
                    </span>
                <div class="menu-sub menu-sub-accordion pt-3">
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link  {{ request()->routeIs('administratives.cantons.*') ? 'active' : '' }}"
                           href="{{ route('administratives.cantons.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                            <span class="menu-title">{{ __('cantons') }}</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item-->
                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('administratives.towns.*') ? 'active' : '' }}"
                           href="{{ route('administratives.towns.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                            <span class="menu-title">{{ __('Villages/Quartiers') }}</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item-->
                    <!--begin:Menu item-->

                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('administratives.zones.*') ? 'active' : '' }}"
                           href="{{ route('administratives.zones.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                            <span class="menu-title">{{ __('zones') }}</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                </div>
            </div>

            <div data-kt-menu-trigger="click"
                 class="menu-item menu-accordion {{ request()->routeIs('economics.*') ? 'here show' : '' }}">
                    <span class="menu-link">
                        <span class="menu-icon">{!! getIcon('abstract-26', 'fs-2') !!}</span>
                        <span class="menu-title">{{ __('Activités économiques') }}</span>
                        <span class="menu-arrow"></span>
                    </span>
                <div class="menu-sub menu-sub-accordion pt-3">
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('economics.categories.*') ? 'active' : '' }}"
                           href="{{ route('economics.categories.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                            <span class="menu-title">{{ __('Catégories') }}</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('economics.activities.*') ? 'active' : '' }}"
                           href="{{ route('economics.activities.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                            <span class="menu-title">{{ __('Activités') }}</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                </div>
            </div>
            <div data-kt-menu-trigger="click"
                 class="menu-item menu-accordion {{ request()->routeIs('settings.*') ? 'here show' : '' }}">
                    <span class="menu-link">
                        <span class="menu-icon">{!! getIcon('information-4', 'fs-2') !!}</span>
                        <span class="menu-title">{{ __('Informations commune') }}</span>
                        <span class="menu-arrow"></span>
                    </span>
                <div class="menu-sub menu-sub-accordion pt-3">
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('settings.communes.*') ? 'active' : '' }}"
                           href="{{ route('settings.communes.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                            <span class="menu-title">{{ __('Voir les informations de la commune') }}</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                </div>
            </div>

            @hasanyrole(['administrateur_system'])
            <div class="menu-item">
                <!--begin:Menu link-->
                <a class="menu-link {{ request()->routeIs('import-view') ? 'active' : '' }}"
                   href="{{ route('import-view') }}">
                    <span class="menu-icon">{!! getIcon('user', 'fs-2') !!}</span>
                    <span class="menu-title">{{ __('Importer des contribuables') }}</span>
                </a>
                <!--end:Menu link-->
            </div>
            @endhasanyrole

            <!--end:Menu item-->


            <!--begin:Menu item-->
            <div data-kt-menu-trigger="click"
                 class="menu-item menu-accordion {{ request()->routeIs('user-management.*')  && !request()->has('type') ? 'here show' : '' }}">
                <!--begin:Menu link-->
                <span class="menu-link">
                        <span class="menu-icon">{!! getIcon('rocket', 'fs-2') !!}</span>
                        <span class="menu-title">{{ __('user management') }}</span>
                        <span class="menu-arrow"></span>
                    </span>
                <!--end:Menu link-->
                <!--begin:Menu sub-->
                <div class="menu-sub menu-sub-accordion">
                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('user-management.users.*') && !request()->has('disable') && !request()->has('type')  ? 'active' : ''}}"
                           href="{{ route('user-management.users.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                            <span class="menu-title">{{ __('Liste des utilisateurs') }}</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item-->

                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('user-management.users.*') && request()->has('disable')&& !request()->has('type')  ? 'active' : '' }}"
                           href="{{ route('user-management.users.index',['disable' => true]) }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                            <span class="menu-title">{{ __('Liste des utilisateurs désactivé') }}</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item-->

                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('user-management.roles.*') ? 'active' : '' }}"
                           href="{{ route('user-management.roles.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                            <span class="menu-title">{{ __('Liste des rôles') }}</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item-->


                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('user-management.permissions.*') ? 'active' : '' }}"
                           href="{{ route('user-management.permissions.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                            <span class="menu-title">{{ __('Liste des permissions') }}</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item-->
                </div>
                <!--end:Menu sub-->
            </div>
            <!--end:Menu item-->

            @endhasanyrole


        </div>
        <!--end::Menu-->
    </div>
    <!--end::Menu wrapper-->
</div>
<!--end::sidebar menu-->
