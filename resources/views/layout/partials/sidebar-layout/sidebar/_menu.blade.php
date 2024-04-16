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
                                    data-bs-target="#kt_modal_add_taxpayer">{{ __('new taxpayer') }}</span>
                            </span>
                        @endcan

                        <a class="menu-link {{ request()->routeIs('taxpayers.*') ? 'active' : '' }}"
                            href="{{ route('taxpayers.index') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Liste des contribuables</span>
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
                class="menu-item menu-accordion {{ request()->routeIs('invoices') ? 'here show' : '' }}">
                <!--begin:Menu link-->
                <span class="menu-link">
                    <span class="menu-icon">{!! getIcon('abstract-26', 'fs-2') !!}</span>
                    <span class="menu-title">{{ __('invoice') }}</span>
                    <span class="menu-arrow"></span>
                </span>
                <!--end:Menu link-->
                <!--begin:Menu sub-->
                <div class="menu-sub menu-sub-accordion">
                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('invoices.*') && !request()->has('notDelivery') ? 'active' : '' }}"
                           href="{{ route('invoices.index') }}">
        <span class="menu-bullet">
            <span class="bullet bullet-dot"></span>
        </span>
                            <span class="menu-title">Liste des avis</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('invoices.*') && request()->input('notDelivery') == 1 ? 'active' : '' }}"
                           href="{{ route('invoices.index', ['notDelivery' => true]) }}">
        <span class="menu-bullet">
            <span class="bullet bullet-dot"></span>
        </span>
                            <span class="menu-title">Liste des avis non distribués</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('invoices.*') &&  request()->has('notDelivery')&& request()->input('notDelivery') == 0 ? 'active' : '' }}"
                           href="{{ route('invoices.index', ['notDelivery' => false]) }}">
        <span class="menu-bullet">
            <span class="bullet bullet-dot"></span>
        </span>
                            <span class="menu-title">Liste des avis distribués</span>
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
                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link {{ request()->routeIs('recoveries.*') ? 'active' : '' }}"
                                href="{{ route('recoveries.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Liste des recouvrements</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                    </div>
                    <!--end:Menu sub-->
                </div>
                <!--end:Menu item-->
            @endcan

            @can('peut voir la comptabilité')
                <!--end:Menu item-->
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

                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('accounts.stock-requests.*') ? 'active' : '' }}"
                                href="{{ route('accounts.stock-requests.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">{{ __('Comptabilité des valeurs inactives du regisseur') }}
                                </span>
                            </a>
                        </div>
                        <!--end:Menu item-->
                        <!--end:Menu item-->

                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('accounts.accountant-deposits-title.*') ? 'active' : '' }}"
                                href="{{ route('accounts.accountant-deposits-title.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">{{ __('Etat de versement du regisseur - Recettes sur titre') }} </span>
                            </a>
                        </div>
                        <!--end:Menu item-->

                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('accounts.accountant-deposits-outright.*') ? 'active' : '' }}"
                                href="{{ route('accounts.accountant-deposits-outright.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">{{ __('Etat de versement du regisseur - Recettes au comptant') }} </span>
                            </a>
                        </div>
                        <!--end:Menu item-->


                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('accounts.stock-transfers.*') ? 'active' : '' }}"
                                href="{{ route('accounts.stock-transfers.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">{{ __('Comptabilité des valeurs inactives du collecteur') }}
                                </span>
                            </a>
                        </div>
                        <!--end:Menu item-->


                        <!--begin:Menu item-->
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
                <!--end:Menu item-->
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
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('geolocation.zones') ? 'active' : '' }}"
                            href="{{ route('geolocation.zones') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">{{ __('zones') }}</span>
                        </a>

                        @can('peut voir la geolocalisation d\'un utilisateur')
                            <a class="menu-link {{ request()->routeIs('geolocation.users') ? 'active' : '' }}"
                                href="{{ route('geolocation.users') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">{{ __('users') }}</span>
                            </a>
                        @endcan
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item-->
                </div>
                <!--end:Menu sub-->
            </div>
            <!--end:Menu item-->

            <!--begin:Menu item-->
            <!--end:Menu item-->
            <!--begin:Menu item-->
            @can('peut accedeé aux paramétrages du système')

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
                        <span class="menu-icon">{!! getIcon('abstract-26', 'fs-2') !!}</span>
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
                            <span class="menu-bullet">
                                <span class="menu-icon">{!! getIcon('user', 'fs-2') !!}</span>
                            </span>
                            <span class="menu-title">{{ __('Importer des contribuables') }}</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                @endhasanyrole

                <!--end:Menu item-->


                <!--begin:Menu item-->
                <div data-kt-menu-trigger="click"
                    class="menu-item menu-accordion {{ request()->routeIs('user-management.*') ? 'here show' : '' }}">
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
                            <a class="menu-link {{ request()->routeIs('user-management.users.*') ? 'active' : '' }}"
                                href="{{ route('user-management.users.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">{{ __('users') }}</span>
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
                                <span class="menu-title">{{ __('roles') }}</span>
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
                                <span class="menu-title">{{ __('permissions') }}</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->
                    </div>
                    <!--end:Menu sub-->
                </div>
                <!--end:Menu item-->

            @endcan


        </div>
        <!--end::Menu-->
    </div>
    <!--end::Menu wrapper-->
</div>
<!--end::sidebar menu-->
