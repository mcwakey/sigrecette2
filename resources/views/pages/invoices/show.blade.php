<x-default-layout>

    @section('title')
    {{ __('taxpayers information') }}
    @endsection

    @section('breadcrumbs')
        {{-- Breadcrumbs::render('invoices.show', $invoice) --}}
    @endsection

    <!--begin::Layout-->
    <div class="d-flex flex-column flex-lg-row">
        <!--begin::Sidebar-->
        <div class="flex-column flex-lg-row-auto w-lg-250px w-xl-350px mb-10">
            <!--begin::Card-->
            <div class="card mb-5 mb-xl-8">
                <!--begin::Card body-->
                <div class="card-body">
                    <!--begin::Summary-->
                    <!--begin::User Info-->
                    <div class="d-flex flex-center flex-column py-5">
                        <!--begin::Avatar-->
                        <div class="symbol symbol-100px symbol-circle mb-7">
                            @if($invoice->profile_photo_url)
                                <img src="{{ $user->profile_photo_url }}" alt="image"/>
                            @else
                                <div class="symbol-label fs-3 {{ app(\App\Actions\GetThemeType::class)->handle('bg-light-? text-?', $invoice->name) }}">
                                    {{ substr($invoice->name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <!--end::Avatar-->
                        <!--begin::Name-->
                        <a href="#" class="fs-3 text-gray-800 text-hover-success fw-bold mb-3">{{ $invoice->name }}</a>
                        <!--end::Name-->
                        <!--begin::Position-->
                        <div class="mb-9">
                            {{-- @foreach($user->roles as $role) --}}
                                <!--begin::Badge-->
                                <div class="badge badge-lg badge-light-danger d-inline">{{ $invoice->gender}}</div>
                                <!--begin::Badge-->
                            {{-- @endforeach --}}
                        </div>
                        <!--end::Position-->
                        <!--begin::Info-->
                        <!--begin::Info heading-->
                        <!-- <div class="fw-bold mb-3">Outstanding Balance
                            <span class="ms-2" ddata-bs-toggle="popover" data-bs-trigger="hover" data-bs-html="true" data-bs-content="Number of support tickets assigned, closed and pending this week.">
                                <i class="ki-duotone ki-information fs-7">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                            </span>
                        </div> -->
                        <!--end::Info heading-->
                        <!-- <div class="d-flex flex-wrap flex-center"> -->
                            <!--begin::Stats-->
                            <!-- <div class="border border-gray-300 border-dashed rounded py-3 px-3 mb-3">
                                <div class="fs-4 fw-bold text-gray-700">
                                    <span class="w-75px">243 000</span>
                                    <i class="ki-duotone ki-arrow-up fs-3 text-success">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                </div>
                                <div class="fw-semibold text-muted">Total</div>
                            </div> -->
                            <!--end::Stats-->
                            <!--begin::Stats-->
                            <!-- <div class="border border-gray-300 border-dashed rounded py-3 px-3 mx-4 mb-3">
                                <div class="fs-4 fw-bold text-gray-700">
                                    <span class="w-50px">56 000</span>
                                    <i class="ki-duotone ki-arrow-down fs-3 text-danger">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                </div>
                                <div class="fw-semibold text-muted">Paid</div>
                            </div> -->
                            <!--end::Stats-->
                            <!--begin::Stats-->
                            <!-- <div class="border border-gray-300 border-dashed rounded py-3 px-3 mb-3">
                                <div class="fs-4 fw-bold text-gray-700">
                                    <span class="w-50px">188</span>
                                    <i class="ki-duotone ki-arrow-up fs-3 text-success">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                </div>
                                <div class="fw-semibold text-muted">Open</div>
                            </div> -->
                            <!--end::Stats-->
                        <!-- </div> -->
                        <!--end::Info-->
                    </div>
                    <!--end::User Info-->
                    <!--end::Summary-->
                    <!--begin::Details toggle-->
                    <div class="d-flex flex-stack fs-4 py-3">
                        <div class="fw-bold rotate collapsible" data-bs-toggle="collapse" href="#kt_user_view_details" role="button" aria-expanded="false" aria-controls="kt_user_view_details">{{ __('details') }}
                            <span class="ms-2 rotate-180">
                                <i class="ki-duotone ki-down fs-3"></i>
                            </span>
                        </div>
                        <span data-bs-toggle="tooltip" data-bs-trigger="hover" title="{{ __('edit Taxpayers details') }}">
                            <a href="#" class="btn btn-sm btn-light-success" data-kt-user-id="{{ $invoice->id }}" data-bs-toggle="modal" data-bs-target="#kt_modal_add_invoice" data-kt-action="update_row">{{ __('edit') }}</a>
                        </span>
                    </div>

                    <!--begin::Modal-->
                    <livewire:taxpayer.add-taxpayer-modal></livewire:taxpayer.add-taxpayer-modal>
                    <!--end::Modal-->

                
                    <!--end::Details toggle-->
                    <div class="separator"></div>
                    <!--begin::Details content-->
                    <div id="kt_user_view_details" class="collapse show">
                        <div class="pb-5 fs-6">
                            <!--begin::Details item-->
                            <div class="fw-bold mt-5">{{ __('account id') }}</div>
                            <div class="text-gray-600">{{ $invoice->tnif }}</div>
                            <!--begin::Details item-->
                            <!--begin::Details item-->
                            <div class="fw-bold mt-5">{{ __('mobilephone') }}</div>
                            <div class="text-gray-600">{{ $invoice->mobilephone}} / {{ $invoice->telephone}}</div>
                            <!--begin::Details item-->
                            <!--begin::Details item-->
                            <div class="fw-bold mt-5">{{ __('email') }}</div>
                            <div class="text-gray-600">
                                <a href="#" class="text-gray-600 text-hover-primary">{{ $invoice->email }}</a>
                            </div>
                            <!--begin::Details item-->
                            <!--begin::Details item-->
                            <div class="fw-bold mt-5">{{ __('address') }}</div>
                            <div class="text-gray-600">{{ $invoice->address }}
                                <br />{{ $invoice->erea }},
                                <br />{{ $invoice->town }},
                                <br />{{ $invoice->canton }}.
                            </div>
                            <!--begin::Details item-->
                            <!--begin::Details item-->
                            <div class="fw-bold mt-5">{{ __('zone') }}</div>
                            <div class="text-gray-600"><span class="badge badge-light-info">Zone {{ $invoice->zone_id }}</span></div>
                            <!--begin::Details item-->
                            <!--begin::Details item-->
                            <div class="fw-bold mt-5">{{ __('joined date') }}</div>
                            <div class="text-gray-600">{{ $invoice->created_at->format('d M Y') }}</div>
                            <!--begin::Details item-->
                        </div>
                    </div>
                    <!--end::Details content-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Card-->
        </div>
        <!--end::Sidebar-->
        <!--begin::Content-->
        <div class="flex-lg-row-fluid ms-lg-15">
            <!--begin:::Tabs-->
            <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-8">
                <!--begin:::Tab item-->
                <li class="nav-item">
                    <a class="nav-link text-active-default pb-4 active" data-bs-toggle="tab" href="#kt_user_view_overview_tab">{{ __('overview') }}</a>
                </li>
                <!--end:::Tab item-->
                <!--begin:::Tab item-->
                <li class="nav-item ms-auto">
                    <!--begin::Action menu-->
                    <a href="#" class="btn btn-success ps-7" data-kt-menu-trigger="click" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">{{ __('actions') }}
                        <i class="ki-duotone ki-down fs-2 me-0"></i></a>
                    <!--begin::Menu-->
                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold py-4 w-250px fs-6" data-kt-menu="true">
                        <!--begin::Menu item-->
                        <div class="menu-item px-5">
                            <div class="menu-content text-muted pb-2 px-5 fs-7 text-uppercase">{{ __('payments') }}</div>
                        </div>
                        <!--end::Menu item-->
                        <!--begin::Menu item-->
                        <div class="menu-item px-5">
                            <a href="#" class="menu-link px-5">{{ __('create asset') }}</a>
                        </div>
                        <!--end::Menu item-->
                        <!--begin::Menu item-->
                        <div class="menu-item px-5">
                            <a href="#" class="menu-link px-5">{{ __('create invoice') }}</a>
                        </div>
                        <!--end::Menu item-->
                        <!--begin::Menu item-->
                        <div class="menu-item px-5">
                            <a href="#" class="menu-link flex-stack px-5">{{ __('create payment') }}
                                <span class="ms-2" data-bs-toggle="tooltip" title="Specify a target name for future usage and reference">
                                    <i class="ki-duotone ki-information fs-7">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i>
                                </span></a>
                        </div>
                        <!--end::Menu item-->
                        <!--begin::Menu item-->
                        <!--begin::Menu separator-->
                        <div class="separator my-3"></div>
                        <!--end::Menu separator-->
                        <!--begin::Menu item-->
                        <div class="menu-item px-5">
                            <div class="menu-content text-muted pb-2 px-5 fs-7 text-uppercase">{{ __('account') }}</div>
                        </div>
                        <!--end::Menu item-->
                        <!--begin::Menu item-->
                        <div class="menu-item px-5">
                            <a href="#" class="menu-link px-5">{{ __('reports') }}</a>
                        </div>
                        <!--end::Menu item-->
                        <!--begin::Menu item-->
                        <div class="menu-item px-5 my-1">
                            <a href="#" class="menu-link px-5">{{ __('account settings') }}</a>
                        </div>
                        <!--end::Menu item-->
                        <!--begin::Menu item-->
                        <div class="menu-item px-5">
                            <a href="#" class="menu-link text-danger px-5">{{ __('delete invoice') }}</a>
                        </div>
                        <!--end::Menu item-->
                    </div>
                    <!--end::Menu-->
                    <!--end::Menu-->
                </li>
                <!--end:::Tab item-->
            </ul>
            <!--end:::Tabs-->
            <!--begin:::Tab content-->
            <div class="tab-content" id="myTabContent">
                <!--begin:::Tab pane-->
                <div class="tab-pane fade show active" id="kt_user_view_overview_tab" role="tabpanel">
                    <!--begin::Card-->
                    <div class="card card-flush mb-6 mb-xl-9">
                        <!--begin::Card body-->
                        <div class="card-body p-9 pt-4">
                            <!--begin::Tab Content-->
                            <div class="tab-content">
                                <!--begin::Day-->
                                <div id="kt_schedule_day_1" class="tab-pane fade show active">
                                    <!--begin::Time-->

       <br/><br/>
                <!--begin::Form-->
                <form id="kt_modal_add_taxpayer_form" class="form" action="#" wire:submit="submit" enctype="multipart/form-data">
                    <input type="hidden" wire:model="invoice_id" name="invoice_id" value="{{ $invoice->id }}"/>
                    <!--begin::Scroll-->
                    <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_add_taxpayer_scroll" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_taxpayer_header" data-kt-scroll-wrappers="#kt_modal_add_taxpayer_scroll" data-kt-scroll-offset="300px">
                        

                    <div class="d-flex flex-column align-items-start flex-xxl-row">
                        <div class="d-flex align-items-center flex-equal fw-row me-4 order-2" data-bs-toggle="tooltip" data-bs-trigger="hover" title="Specify invoice date">
                            <div class="fs-6 fw-bolder text-gray-700 text-nowrap">Date:</div>
                            <div class="position-relative d-flex align-items-center w-150px">
                                <input class="form-control form-control-transparent fw-bolder pe-5" placeholder="Select date" name="invoice_date" />
                                <span class="svg-icon svg-icon-2 position-absolute ms-4 end-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="black" />
                                    </svg>
                                </span>
                            </div>
                            <!-- <div class="fs-6 fw-bolder text-gray-700 text-nowrap">Month:</div>
                            <div class="position-relative d-flex align-items-center w-150px">
                                <input class="form-control form-control-transparent fw-bolder pe-5" placeholder="Select date" name="invoice_date" />
                                <select class="form-control form-control-transparent fw-bolder pe-5" >
                                    <option>-Select-</option>
                                </select>
                            </div> -->
                        </div>
                        
                        <div class="d-flex flex-center flex-equal fw-row text-nowrap order-1 order-xxl-2 me-4" data-bs-toggle="tooltip" data-bs-trigger="hover" title="Enter invoice number">
                            <span class="fs-2x fw-bolder text-gray-800">Invoice #</span>
                            <input type="text" class="form-control form-control-flush fw-bolder text-muted fs-3 w-125px" value="0003/2023" placehoder="..." />
                        </div>
                    
                        <div class="d-flex align-items-center justify-content-end flex-equal order-3 fw-row" data-bs-toggle="tooltip" data-bs-trigger="hover" title="Specify invoice due date">
                            <div class="fs-6 fw-bolder text-gray-700 text-nowrap">Due Date:</div>
                            <div class="position-relative d-flex align-items-center w-150px">
                                <input class="form-control form-control-transparent fw-bolder pe-5" placeholder="Select date" name="invoice_due_date" />
                                <span class="svg-icon svg-icon-2 position-absolute end-0 ms-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="black" />
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </div>
                        <!--begin::Input group-->
                        
					<div class="separator separator-dashed my-10"></div>
                        <br/><br/>
                        <div class="row mb-7">
                            <div class="col-md-6">
                                <!--begin::Label-->
                                <label class="required fw-semibold fs-6 mb-2">{{ __('fullname') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" wire:model="name" name="name" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="{{ __('fullname') }}"/>
                                <!--end::Input-->
                                @error('name')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold mb-2">{{ __('nic') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                
                                <input type="text" wire:model="nic" name="nic" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="{{ __('nic') }}"/>
                                <!--end::Input-->
                                @error('nic')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="row mb-7">
                            <div class="col-md-6">
                                <!--begin::Label-->
                                <label class="required fw-semibold fs-6 mb-2">{{ __('mobilephone') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" wire:model="mobilephone" name="mobilephone" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="9123456789"/>
                                <!--end::Input-->
                                @error('mobilephone')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6">
                                <!--begin::Label-->
                                <label class="required fw-semibold fs-6 mb-2">{{ __('zone') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <select wire:model="zone_id" name="zone_id" class="form-select form-select-solid" data-dropdown-parent="#kt_modal_add_taxpayer" >
                                    <option>{{ __('select zone') }}</option>
                                    
                                </select>
                                <!--end::Input-->
                                @error('zone_id')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="row mb-7">
                            <div class="col-md-4">
                                <!--begin::Label-->
                                <label class="required fw-semibold fs-6 mb-2">{{ __('canton') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <!-- <select aria-label="" data-control="select2" data-placeholder="Select a District..." class="form-select form-select-solid"
                                    data-dropdown-parent="#kt_modal_add_taxpayer">
                                </select> -->

                                <select wire:model="canton" name="canton" class="form-select form-select-solid" data-allow-clear="true">
                                    <option>{{ __('select district') }}</option>
                                    
                                </select>

                                <!--end::Input-->
                                @error('canton')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-4">
                                <!--begin::Label-->
                                <label class="required fw-semibold fs-6 mb-2">{{ __('town') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <select wire:model="town" name="town" class="form-select form-select-solid" data-dropdown-parent="#kt_modal_add_taxpayer">
                                <option>{{ __('select town') }}Select a town</option>
                                
                                </select>
                                <!--end::Input-->
                                @error('town')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-4">
                                <!--begin::Label-->
                                <label class="required fw-semibold fs-6 mb-2">{{ __('erea') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <select wire:model="erea" name="erea" class="form-select form-select-solid"
                                    data-dropdown-parent="#kt_modal_add_taxpayer">
                                    <option>{{ __('select erea') }}</option>
                                    
                                </select>
                                <!--end::Input-->
                            </div>
                        </div>
                        <div class="fv-row mb-7">
                            <!--begin::Label-->
                            <label class="required fw-semibold fs-6 mb-2">{{ __('email') }}</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="email" wire:model="email" name="email" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="example@domain.com"/>
                            <!--end::Input-->
                            @error('email')
                            <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="row mb-7">
                            <div class="col-md-6">
                                <!--begin::Label-->
                                <label class="required fw-semibold fs-6 mb-2">{{ __('address') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <textarea wire:model="address" name="address" class="form-control form-control-solid rounded-3" placeholder="{{ __('address') }}"></textarea>
                                <!--end::Input-->
                                @error('address')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6">
                                <!--begin::Label-->
                                <label class="required fw-semibold fs-6 mb-2">{{ __('latitude') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" wire:model="latitude" name="latitude" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="0.123456789"/>
                                <!--end::Input-->
                                @error('latitude')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        

                        
														<div class="table-responsive mb-10">
															<!--begin::Table-->
															<table class="table g-5 gs-0 mb-0 fw-bolder text-gray-700" data-kt-element="items">
																<!--begin::Table head-->
																<thead>
																	<tr class="border-bottom fs-7 fw-bolder text-gray-700 text-uppercase">
																		<th class="min-w-300px w-475px">Item</th>
																		<th class="min-w-100px w-100px">QTY</th>
																		<th class="min-w-150px w-150px">Price</th>
																		<th class="min-w-100px w-150px text-end">Total</th>
																	</tr>
																</thead>
																<tbody>
																	<tr class="border-bottom border-bottom-dashed" data-kt-element="item">
																		<td class="pe-7">
																			<input type="text" class="form-control form-control-solid mb-2" name="name" placeholder="Libellé de la recette" value=""/>
																			<input type="text" class="form-control form-control-solid" name="description" placeholder="Matière taxable" value=""/>
																		</td>
																		<!-- <td class="pe-7">
																			<input type="number" class="form-control form-control-solid mb-2" name="quantity" placeholder="1" value="1" data-kt-element="quantity"/>
																			<input type="text" class="form-control form-control-solid" name="mesure" placeholder="m3" value="m3"/>
																		</td> -->
																		<td class="ps-0">
																			<input class="form-control form-control-solid mb-2" type="number" min="1" name="quantity" placeholder="1" value="" data-kt-element="quantity" />
																			<input class="form-control form-control-solid" type="text" name="mesure" placeholder="Unité d’assiette" value=""/>
																		</td>
																		<td>
																			<input type="text" class="form-control form-control-solid mb-2 text-end" name="price" placeholder="0.00" value="0.00" data-kt-element="price" />
																			<input type="text" class="form-control form-control-solid text-end" name="taxation" placeholder="Taxation/an" value=""/>
																		</td>
																		<td class="pt-8 text-end text-nowrap">FCFA
																		<span data-kt-element="total">0.00</span></td>
																		<!-- <td class="pt-5 text-end">
																			<button type="button" class="btn btn-sm btn-icon btn-active-color-primary" data-kt-element="remove-item">
																				<span class="svg-icon svg-icon-3">
																					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
																						<path d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z" fill="black" />
																						<path opacity="0.5" d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z" fill="black" />
																						<path opacity="0.5" d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z" fill="black" />
																					</svg>
																				</span>
																			</button>
																		</td> -->
																	</tr>
																</tbody>
																<tfoot>
																	<tr class="border-top border-top-dashed align-top fs-6 fw-bolder text-gray-700">
																		<th class="text-primary">
																			<!-- <button class="btn btn-link py-1" data-kt-element="add-item">Add item</button> -->
																		</th>
																		<th colspan="2" class="border-bottom border-bottom-dashed ps-0">
																			<div class="d-flex flex-column align-items-start">
																				<div class="fs-5">Subtotal</div>
																			</div>
																		</th>
																		<th colspan="2" class="border-bottom border-bottom-dashed text-end">FCFA
																		<span data-kt-element="sub-total">0.00</span></th>
																	</tr>
																	<tr class="align-top fw-bolder text-gray-700">
																		<th></th>
																		<th colspan="2" class="fs-4 ps-0">Total</th>
																		<th colspan="2" class="text-end fs-4 text-nowrap">FCFA
																		<span data-kt-element="grand-total">0.00</span></th>
																	</tr>
																</tfoot>
															</table>
														</div>
														<table class="table d-none" data-kt-element="item-template">
															<tr class="border-bottom border-bottom-dashed" data-kt-element="item">
																<td class="pe-7">
																	<input type="text" class="form-control form-control-solid mb-2" name="name[]" placeholder="Item name" />
																	<input type="text" class="form-control form-control-solid" name="description[]" placeholder="Description" />
																</td>
																<td class="ps-0">
																	<input class="form-control form-control-solid" type="number" min="1" name="quantity[]" placeholder="1" data-kt-element="quantity" />
																</td>
																<td>
																	<input type="text" class="form-control form-control-solid text-end" name="price[]" placeholder="0.00" data-kt-element="price" />
																</td>
																<td class="pt-8 text-end">FCFA
																<span data-kt-element="total">0.00</span></td>
																<td class="pt-5 text-end">
																	<button type="button" class="btn btn-sm btn-icon btn-active-color-primary" data-kt-element="remove-item">
																		<span class="svg-icon svg-icon-3">
																			<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
																				<path d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z" fill="black" />
																				<path opacity="0.5" d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z" fill="black" />
																				<path opacity="0.5" d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z" fill="black" />
																			</svg>
																		</span>
																	</button>
																</td>
															</tr>
														</table>
														<table class="table d-none" data-kt-element="empty-template">
															<tr data-kt-element="empty">
																<th colspan="5" class="text-muted text-center py-10">No items</th>
															</tr>
														</table>
														<div class="mb-0">
															<label class="form-label fs-6 fw-bolder text-gray-700">Notes</label>
															<textarea name="notes" class="form-control form-control-solid" rows="3" placeholder="Thanks for your business"></textarea>
														</div>
                        
                        <div class="separator saperator-dashed my-5"></div>
                        <!--end::Input group-->
                    </div>
                    <!--end::Scroll-->
                    <!--begin::Actions-->
                    <div class="text-center pt-15">
                        <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="Close" wire:loading.attr="disabled">{{ __('cancel') }}</button>
                        <button type="submit" class="btn btn-success" data-kt-taxpayers-modal-action="submit">
                            <span class="indicator-label" wire:loading.remove>{{ __('submit') }}</span>
                            <span class="indicator-progress" wire:loading wire:target="submit">
                            {{ __('please wait') }}
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                    <!--end::Actions-->
                </form>
                <!--end::Form-->
                                    <!--end::Time-->
                                </div>
                                <!--end::Day-->
                            </div>
                            <!--end::Tab Content-->
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Card-->
                    <!--begin::Tasks-->
                    <!--end::Tasks-->
                </div>
            </div>
            <!--end:::Tab content-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Layout-->
    <!--begin::Modals-->
    <!--begin::Modal - Update user details-->
    {{-- @include('pages/taxpayers/modals/_update-details') --}}
    <!--end::Modal - Update user details-->
    <!--begin::Modal - Add schedule-->
    {{-- @include('pages/taxpayers/modals/_add-schedule') --}}
    <!--end::Modal - Add schedule-->
    <!--begin::Modal - Add one time password-->
    {{-- @include('pages/taxpayers/modals/_add-one-time-password') --}}
    <!--end::Modal - Add one time password-->
    <!--begin::Modal - Update email-->
    {{-- @include('pages/taxpayers/modals/_update-email') --}}
    <!--end::Modal - Update email-->
    <!--begin::Modal - Update password-->
    {{-- @include('pages/taxpayers/modals/_update-password') --}}
    <!--end::Modal - Update password-->
    <!--begin::Modal - Update role-->
    {{-- @include('pages/taxpayers/modals/_update-role') --}}
    <!--end::Modal - Update role-->
    <!--begin::Modal - Add auth app-->
    {{-- @include('pages/taxpayers/modals/_add-auth-app') --}}
    <!--end::Modal - Add auth app-->
    <!--begin::Modal - Add task-->
    {{-- @include('pages/taxpayers/modals/_add-task') --}}
    <!--end::Modal - Add task-->
    <!--end::Modals-->


</x-default-layout>
