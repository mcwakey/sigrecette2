<style>
    .wrapper {
        width: 400px;
        background: #fff;
        border-radius: 2px;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
        position: absolute;
        right: 40%;
        top: 15%;
        z-index: 1000;
    }

    .wrapper.hidden {
        display: none;
    }

    .wrapper header {
        display: flex;
        align-items: center;
        padding: 25px 30px 10px;
        justify-content: space-between;
    }

    header .icons {
        display: flex;
    }

    header .icons span {
        height: 38px;
        width: 38px;
        margin: 0 1px;
        cursor: pointer;
        color: #878787;
        text-align: center;
        line-height: 38px;
        font-size: 1.9rem;
        user-select: none;
        border-radius: 50%;
    }

    .icons span:last-child {
        margin-right: -10px;
    }

    header .icons span:hover {
        background: #f2f2f2;
    }

    header .current-date {
        font-size: 1.2rem;
        font-weight: 500;
    }

    .calendar {
        padding: 20px;
    }

    .calendar ul {
        display: flex;
        flex-wrap: wrap;
        list-style: none;
        text-align: center;
    }

    .calendar .days {
        margin-bottom: 20px;
    }

    .calendar li {
        color: #333;
        width: calc(100% / 7);
        font-size: 1.07rem;
    }

    .calendar .weeks li {
        font-weight: 500;
        cursor: default;
    }

    .calendar .days li {
        z-index: 1;
        cursor: pointer;
        position: relative;
        margin-top: 30px;
    }

    .days li.inactive {
        color: #aaa;
    }

    .days li.active {
        color: #fff;
    }

    .days li::before {
        position: absolute;
        content: "";
        left: 50%;
        top: 50%;
        height: 40px;
        width: 40px;
        z-index: -1;
        border-radius: 50%;
        transform: translate(-50%, -50%);
    }

    .days li.active::before {
        background: #0579ff;
    }

    .days li:not(.active):hover::before {
        background: #f2f2f2;
    }
</style>

<style>
    .wrapper-two {
        width: 400px;
        background: #fff;
        border-radius: 2px;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
        position: absolute;
        right: -10%;
        top: 15%;
        z-index: 1000;
    }

    .wrapper-two.hidden {
        display: none;
    }

    .wrapper-two header {
        display: flex;
        align-items: center;
        padding: 25px 30px 10px;
        justify-content: space-between;
    }

    header .icons-two {
        display: flex;
    }

    header .icons-two span {
        height: 38px;
        width: 38px;
        margin: 0 1px;
        cursor: pointer;
        color: #878787;
        text-align: center;
        line-height: 38px;
        font-size: 1.9rem;
        user-select: none;
        border-radius: 50%;
    }

    .icons-two span:last-child {
        margin-right: -10px;
    }

    header .icons-two span:hover {
        background: #f2f2f2;
    }

    header .current-date-two {
        font-size: 1.2rem;
        font-weight: 500;
    }

    .calendar-two {
        padding: 20px;
    }

    .calendar-two ul {
        display: flex;
        flex-wrap: wrap;
        list-style: none;
        text-align: center;
    }

    .calendar-two .days-two {
        margin-bottom: 20px;
    }

    .calendar-two li {
        color: #333;
        width: calc(100% / 7);
        font-size: 1.07rem;
    }

    .calendar-two .weeks-two li {
        font-weight: 500;
        cursor: default;
    }

    .calendar-two .days-two li {
        z-index: 1;
        cursor: pointer;
        position: relative;
        margin-top: 30px;
    }

    .days-two li.inactive {
        color: #aaa;
    }

    .days-two li.active {
        color: #fff;
    }

    .days-two li::before {
        position: absolute;
        content: "";
        left: 50%;
        top: 50%;
        height: 40px;
        width: 40px;
        z-index: -1;
        border-radius: 50%;
        transform: translate(-50%, -50%);
    }

    .days-two li.active::before {
        background: #0579ff;
    }

    .days-two li:not(.active):hover::before {
        background: #f2f2f2;
    }
</style>

<div class="modal fade" id="kt_modal_add_stock_transfer" tabindex="-1" aria-hidden="true" wire:ignore.self>

    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-1000px">
        <!--begin::Modal content-->
        <div class="modal-content">

            <!--begin::Modal header-->
            <div class="modal-header" id="kt_modal_add_stock_transfer_header">
                @if ($edit_mode==false && $deposit_mode==false)
                    <h2 class="fw-bold">{{ __('Nouvelle alimentation') }}</h2>
            @else
                    <h2 class="fw-bold">{{ __('account state') }}</h2>
                 @endif
                <!--end::Modal title-->
                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
                    {!! getIcon('cross','fs-1') !!}
                </div>
                <!--end::Close-->
            </div>
            <!--end::Modal header-->
            <!--begin::Modal body-->
            <div class="modal-body px-5 my-7">
                <!--begin::Form-->
                <form style="position: relative" id="kt_modal_add_stock_transfer_form" class="form" action="#" wire:submit="submit" enctype="multipart/form-data">

                    <div class="wrapper hidden" id="calendar-one">
                        <header>
                            <p class="current-date"></p>
                            <div class="icons">
                                <span id="prev" class="material-symbols-rounded">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="32" fill="#ccc"
                                        viewBox="0 0 256 256">
                                        <path
                                            d="M165.66,202.34a8,8,0,0,1-11.32,11.32l-80-80a8,8,0,0,1,0-11.32l80-80a8,8,0,0,1,11.32,11.32L91.31,128Z">
                                        </path>
                                    </svg>

                                </span>
                                <span id="next" class="material-symbols-rounded">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="32" fill="#ccc"
                                        viewBox="0 0 256 256">
                                        <path
                                            d="M181.66,133.66l-80,80a8,8,0,0,1-11.32-11.32L164.69,128,90.34,53.66a8,8,0,0,1,11.32-11.32l80,80A8,8,0,0,1,181.66,133.66Z">
                                        </path>
                                    </svg>
                                </span>
                            </div>
                        </header>
                        <div class="calendar">
                            <ul class="weeks">
                                <li>Dim</li>
                                <li>Lun</li>
                                <li>Mar</li>
                                <li>Mer</li>
                                <li>Jeu</li>
                                <li>Ven</li>
                                <li>Sam</li>
                            </ul>
                            <ul class="days"></ul>
                        </div>
                    </div>

                    <div class="wrapper-two hidden" id="calendar-two">
                        <header>
                            <p class="current-date-two"></p>
                            <div class="icons-two">
                                <span id="prev-two" class="material-symbols-rounded">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="32" fill="#ccc"
                                        viewBox="0 0 256 256">
                                        <path
                                            d="M165.66,202.34a8,8,0,0,1-11.32,11.32l-80-80a8,8,0,0,1,0-11.32l80-80a8,8,0,0,1,11.32,11.32L91.31,128Z">
                                        </path>
                                    </svg>

                                </span>
                                <span id="next-two" class="material-symbols-rounded">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="32" fill="#ccc"
                                        viewBox="0 0 256 256">
                                        <path
                                            d="M181.66,133.66l-80,80a8,8,0,0,1-11.32-11.32L164.69,128,90.34,53.66a8,8,0,0,1,11.32-11.32l80,80A8,8,0,0,1,181.66,133.66Z">
                                        </path>
                                    </svg>
                                </span>
                            </div>
                        </header>
                        <div class="calendar-two">
                            <ul class="weeks-two">
                                <li>Dim</li>
                                <li>Lun</li>
                                <li>Mar</li>
                                <li>Mer</li>
                                <li>Jeu</li>
                                <li>Ven</li>
                                <li>Sam</li>
                            </ul>
                            <ul class="days-two"></ul>
                        </div>
                    </div>

                    <input type="hidden" wire:model="stock_transfer_id" name="stock_transfer_id"  value=""/>
                    <input type="hidden" wire:model="user_id" name="user_id" value=""/>
                    <input type="hidden" wire:model="collector_id" name="collector_id" value=""/>
                    <!--begin::Scroll-->
                    <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_add_stock_transfer_scroll" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_stock_transfer_header" data-kt-scroll-wrappers="#kt_modal_add_stock_transfer_scroll" data-kt-scroll-offset="300px">

                        <!--begin::Input group-->


                        @if ($edit_mode==false && $deposit_mode==false)
                        <div class="row mb-7" style="position: relative">
                            <div class="col-md-3">

                                <label class="required fs-6 fw-semibold mb-2">{{ __('collector') }}</label>

                                <select data-kt-action="load_drop" wire:model="collector_id" name="collector_id" class="form-select" data-dropdown-parent="#kt_modal_add_stock_transfer">
                                    <option>{{ __('select an option') }}</option>
                                    @foreach($collectors as $collector)
                                    <option value="{{ $collector->id}}">{{ $collector->user_name}}</option>
                                    @endforeach
                                </select>


                                <!--end::Input-->
                                @error('collector_id')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-3">

                                <label class="required fs-6 fw-semibold mb-2">{{ __('req no') }}</label>

                                <select data-kt-action="load_drop" wire:model.live="trans_no" name="trans_no" class="form-select" data-dropdown-parent="#kt_modal_add_stock_transfer" data-kt-user-id ="">
                                    <option>{{ __('select an option') }}</option>
                                    @foreach($request_nos as $request_no)
                                    <option value="{{ $request_no->req_no}}">{{ $request_no->req_no}}</option>
                                    @endforeach
                                </select>

                                <!-- <input  data-kt-action="load_drop" type="text" wire:model.live="trans_no" name="trans_no" class="form-control mb-3 mb-lg-0" placeholder="{{ __('req no') }}" readonly/> -->
                                <!--end::Input-->
                                @error('trans_no')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-3">
                                <label class="required fs-6 fw-semibold mb-2">{{ __('Début période de collecte') }}</label>
                                <div class="input-group" id="kt_td_picker_simple" data-td-target-input="nearest"
                                    data-td-target-toggle="nearest">
                                    <span id="date-picker-btn-one" class="input-group-text" data-td-target="#kt_td_picker_basic"
                                        data-td-toggle="datetimepicker">
                                        <i class="ki-duotone ki-calendar fs-2"><span class="path1"></span><span class="path2"></span></i>
                                    </span>
                                    <input readonly id="date-picker-input-one" type="text" wire:model.live="period_from_a" name="period_from" class="form-control mb-3 mb-lg-0" placeholder="{{ __('Date') }}" />
                                </div>
                                @error('period_from')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-3">

                                <label class="required fs-6 fw-semibold mb-2">{{ __('Fin période de collecte') }}</label>
                                <div class="input-group" id="kt_td_picker_simple" data-td-target-input="nearest"
                                data-td-target-toggle="nearest">
                                    <span id="date-picker-btn-two" class="input-group-text" data-td-target="#kt_td_picker_basic"
                                        data-td-toggle="datetimepicker">
                                        <i class="ki-duotone ki-calendar fs-2"><span class="path1"></span><span class="path2"></span></i>
                                    </span>
                                    <input id="date-picker-input-two" type="text" wire:model.live="period_to_a" name="period_to" class="form-control mb-3 mb-lg-0" placeholder="{{ __('Date') }}" />
                                </div>
                                <!--end::Input-->
                                @error('period_to')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                        </div>
                        @endif
                        <div class="separator saperator-dashed my-3"></div>
                        @if (!$edit_mode)

                        <div class="row mb-7">
                            <div class="col-md-8">
                                <!--begin::Label-->
                                <label class="required fs-6 fw-semibold mb-2">{{ __('tickets') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->

                                <select data-kt-action="load_drop" wire:model="stock_request_id" name="stock_request_id" class="form-select" data-dropdown-parent="#kt_modal_add_stock_transfer">
                                    <option>{{ __('select an option') }}</option>
                                    @foreach($stock_requests as $request)
                                        <option value="{{ $request->id}}">{{ $request->taxable->name." (".$request->taxable->tariff." FCFA) [No: " .$request->start_no. "-" .$request->end_no."]" }}</option>
                                    @endforeach
                                </select>

                                @error('taxable_id')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-4">
                                <label  class="required fs-6 fw-semibold mb-2">{{ __('Quantité restante') }} </label>
                                <input  data-kt-action="load_drop" type="text" wire:model.live="remaining_qty" name="remaining_qty" class="form-control mb-3 mb-lg-0" placeholder="{{ __('0') }}" readonly/>
                            </div>
                        </div>

                        <div class="separator saperator-dashed my-3"></div>

                        <div class="row mb-5">
                            <div class="col-md-3">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold mb-2">{{ __('start no') }}</label>
                                <!--end::Label-->
                                <!--begin::Input data-kt-action="load_drop" -->
                                <input type="text" wire:model="start_no" name="start_no" class="form-control  mb-3 mb-lg-0" placeholder="{{ __('start no') }}" data-kt-action="change_qty" />
                                <!--end::Input-->
                                @error('start_no')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-3">
                                <!--begin::Label-->
                                <label class="fw-semibold fs-6 mb-2">{{ __('end no') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" wire:model="end_no" name="end_no" class="form-control  mb-3 mb-lg-0" placeholder="{{ __('end no') }}" data-kt-action="change_qty" />
                                <!--end::Input-->
                                @error('end_no')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-3">
                                <!--begin::Label-->
                                <label class="fw-semibold fs-6 mb-2">{{ __('qty') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" wire:model="qty" name="qty" class="form-control  mb-3 mb-lg-0" placeholder="{{ __('qty') }}" data-kt-action="change_qty"/>
                                <!--end::Input-->
                                @error('qty')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-3">
                                <!--begin::Label-->
                                <label class="fw-semibold fs-6 mb-2">{{ __('total') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" wire:model="total" name="total" class="form-control  mb-3 mb-lg-0" placeholder="{{ __('total') }}" readonly />
                                <!--end::Input-->
                                @error('total')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="row mb-7">

                            @if ($deposit_mode)
                            <div class="col-md-6">
                                <!--begin::Label-->
                                <label class="required fw-semibold fs-6 mb-2">{{ __('code') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <!-- <input type="text" wire:model="code" name="code" class="form-control  mb-3 mb-lg-0" placeholder="{{ __('code') }}" data-kt-action="change_qty" /> -->
                                <select wire:model="code" name="code" class="form-select" data-dropdown-parent="#kt_modal_add_stock_transfer">
                                    <option>{{ __('select an option') }}</option>
                                    @foreach($taxlabel_list as $taxlabel)<option value="{{ $taxlabel->code}}">{{ $taxlabel->code." -- ".$taxlabel->name }}</option>@endforeach

                                </select>
                                <!--end::Input-->
                                @error('code')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-3">
                                <!--begin::Label-->
                                <label class="required fs-6 fw-semibold mb-2">{{ __('reference no') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->

                                <input type="text" wire:model="taxlabel_id" name="taxlabel_id" class="form-control mb-3 mb-lg-0" placeholder="{{ __('reference no') }}"/>

                                <!-- <select wire:model="taxlabel_id" name="taxlabel_id" class="form-select" data-dropdown-parent="#kt_modal_add_stock_transfer">
                                    <option>{{ __('select an option') }}</option>
                                    <option value="TICKET">TICKET</option>
                                    <option value="TIMBRE">TIMBRE</option>
                                </select> -->
                                <!--end::Input-->
                                @error('taxlabel_id')
                                <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            @endif

                            <div class="col-md-3">
                                <!--begin::Label-->
                                <!-- <label class="fw-semibold fs-6 mb-2">{{ __('empty') }}.</label> -->
                                <!--end::Label-->
                                <!--begin::Input-->
                                <button type="submit" class="btn btn-success mt-8" data-kt-taxpayer-taxables-modal-action="submit">
                                    <span class="indicator-label" wire:loading.remove>{{ __('add') }}</span>
                                    <span class="indicator-progress" wire:loading wire:target="submit">
                                    {{ __('chargenment ...') }}
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                    </span>
                                </button>

                                <!--end::Input-->
                            </div>
                        </div>
                        @endif

                        <div class="separator separator-content separator-dashed my-3">
                            <span class="w-250px text-gray-500 fw-semibold fs-7">{{ __('request summary') }}</span>
                        </div>

                        <table class="table g-5 gs-0 mb-0 fw-bolder text-gray-700" data-kt-element="items">
                        <thead class="border-bottom border-gray-200 fs-7 fw-bold">
                                            <tr class="text-start text-muted text-uppercase gs-0">
                                                <th class="min-w-50px">{{ __('ticket') }}</th>
                                                <th class="min-w-50px">{{ __('tariff') }}</th>
                                                <th class="min-w-50px">{{ __('qty') }}</th>
                                                <th class="min-w-50px">{{ __('amount') }}</th>
                                                <th class="min-w-50px">{{ __('num') }}</th>
                                                <th class="min-w-50px">{{ __('action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody class="fs-6 fw-semibold text-gray-600">
                                        @foreach($stock_transfers as $stock_transfer)
                                        <tr class="border-bottom border-bottom-dashed" data-kt-element="item">
                                            <td>
                                                {{ $stock_transfer->taxable->name }}
                                            </td>
                                            <td class="ps-0">
                                                {{ $stock_transfer->taxable->tariff }}
                                            </td>
                                            <td>
                                                {{ $stock_transfer->qty }}
                                            </td>
                                            <td>
                                                {{ $stock_transfer->qty*$stock_transfer->taxable->tariff }}
                                            </td>
                                            <td>
                                                {{ $stock_transfer->start_no." - ".$stock_transfer->end_no }}
                                            </td>
                                            <td>
                                                <button type="button" wire:click="deleteStockRequest({{ $stock_transfer->id }})"  class="btn btn-sm btn-outline btn-outline-dashed btn-outline-danger btn-active-light-danger me-1" data-bs-toggle="tooltip" title="Suprimer">
                                                    <span class="indicator-label">  <span class="indicator-label">
                                                        <i class="ki-duotone ki-trash">
                                                             <span class="path1"></span>
                                                             <span class="path2"></span>
                                                             <span class="path3"></span>
                                                             <span class="path4"></span>
                                                             <span class="path5"></span>
                                                            </i>
                                                    </span></span>
                                                    <!-- <span class="indicator-progress" wire:loading >
                                    {{ __('chargenment ...') }} -->
                                        <!-- <span class="spinner-border spinner-border-sm align-middle ms-2"></span> -->
                                    <!-- </span> -->
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                </tbody>
                            </table>

                        <!--end::Input group-->
                    </div>
                    <!--end::Scroll-->
                    <!--begin::Actions-->
                    <div class="text-center pt-15">
                    <button type="reset" class="btn btn-light me-5" data-bs-dismiss="modal" aria-label="Close" wire:loading.attr="disabled">{{ __('close') }}</button>
                        @if ($edit_mode)
                            <button type="submit" class="btn btn-danger" data-kt-taxpayer-taxables-modal-action="submit">
                                <span class="indicator-label" wire:loading.remove>{{ __('faire etat de compte du collecteur') }}</span>
                                <span class="indicator-progress" wire:loading wire:target="submit">
                                {{ __('chargenment ...') }}
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                            </button>
                        @endif
                    </div>
                    <!--end::Actions-->
                </form>
                <!--end::Form-->
            </div>
            <!--end::Modal body-->
        </div>
        <!--end::Modal content-->
    </div>
    <!--end::Modal dialog-->
</div>

@push('scripts')


<script>


let datePickerbtn = document.getElementById("date-picker-btn-one");
let datePickerInput = document.getElementById("date-picker-input-one");
let calendar = document.getElementById("calendar-one");

const daysTag = document.querySelector(".days"),
    currentDate = document.querySelector(".current-date"),
    prevNextIcon = document.querySelectorAll(".icons span");

// getting new date, current year and month
let date = new Date(),
    currYear = date.getFullYear(),
    currMonth = date.getMonth();

// storing full name of all months in array
const months = [
    "Janv",
    "Févr",
    "Mars",
    "Avr",
    "Mai",
    "Juin",
    "Juil",
    "Août",
    "Sept",
    "Oct",
    "Nov",
    "Déc",
];

const renderCalendar = () => {
    let firstDayofMonth = new Date(currYear, currMonth, 1).getDay(), // getting first day of month
        lastDateofMonth = new Date(currYear, currMonth + 1, 0).getDate(), // getting last date of month
        lastDayofMonth = new Date(
            currYear,
            currMonth,
            lastDateofMonth
        ).getDay(), // getting last day of month
        lastDateofLastMonth = new Date(currYear, currMonth, 0).getDate(); // getting last date of previous month

    let liTag = "";
    let today = null;

    for (let i = firstDayofMonth; i > 0; i--) {
        // creating li of previous month last days
        liTag += `<li class="inactive">${lastDateofLastMonth - i + 1}</li>`;
    }

    for (let i = 1; i <= lastDateofMonth; i++) {
        // creating li of all days of current month
        // adding active class to li if the current day, month, and year matched
        let isToday =
            i === date.getDate() &&
            currMonth === new Date().getMonth() &&
            currYear === new Date().getFullYear()
                ? "active"
                : "";

        liTag += `<li class="${isToday}">${i}</li>`;

        if (today === null) {
            today =
                i === date.getDate() &&
                currMonth === new Date().getMonth() &&
                currYear === new Date().getFullYear()
                    ? i
                    : null;
        }
    }

    for (let i = lastDayofMonth; i < 6; i++) {
        // creating li of next month first days
        liTag += `<li class="inactive">${i - lastDayofMonth + 1}</li>`;
    }
    currentDate.innerText = `${months[currMonth]} ${currYear}`; // passing current mon and yr as currentDate text
    daysTag.innerHTML = liTag;

    datePickerInput.value = `${currYear}-${
        currMonth + 1 < 10 ? "0" + (currMonth + 1) : currMonth + 1
    }-${today ?? 1}`;

    let inputEvent = new Event("input", {
        bubbles: true,
        cancelable: true,
    });

    datePickerInput.dispatchEvent(inputEvent);
};

prevNextIcon?.forEach((icon) => {
    // getting prev and next icons
    icon.addEventListener("click", () => {
        // adding click event on both icons
        // if clicked icon is previous icon then decrement current month by 1 else increment it by 1
        currMonth = icon.id === "prev" ? currMonth - 1 : currMonth + 1;

        if (currMonth < 0 || currMonth > 11) {
            // if current month is less than 0 or greater than 11
            // creating a new date of current year & month and pass it as date value
            date = new Date(currYear, currMonth, new Date().getDate());
            currYear = date.getFullYear(); // updating current year with new date year
            currMonth = date.getMonth(); // updating current month with new date month
        } else {
            date = new Date(); // pass the current date as date value
        }
        renderCalendar(); // calling renderCalendar function

        daysTag.children.forEach((day) => {
            day.addEventListener("click", () => {
                datePickerInput.value = `${currYear}-${
                    currMonth + 1 < 10 ? "0" + (currMonth + 1) : currMonth + 1
                }-${day.innerText}`;

                let inputEvent = new Event("input", {
                    bubbles: true,
                    cancelable: true,
                });

                datePickerInput.dispatchEvent(inputEvent);

                calendar.classList.add("hidden");
            });
        });
    });
});

const openCalendar = () => {
    calendar.classList.toggle("hidden");
    renderCalendar();

    daysTag.children.forEach((day) => {
        day.addEventListener("click", () => {
            datePickerInput.value = `${currYear}-${
                currMonth + 1 < 10 ? "0" + (currMonth + 1) : currMonth + 1
            }-${day.innerText}`;

            let inputEvent = new Event("input", {
                bubbles: true,
                cancelable: true,
            });

            datePickerInput.dispatchEvent(inputEvent);

            calendar.classList.add("hidden");
        });
    });
};

datePickerbtn?.addEventListener("click", () => {
    calendarTwo.classList.add("hidden");
    openCalendar();
});



let datePickerbtnTwo = document.getElementById("date-picker-btn-two");
let datePickerInputTwo = document.getElementById("date-picker-input-two");
let calendarTwo = document.getElementById("calendar-two");

const daysTagTwo = document.querySelector(".days-two"),
    currentDateTwo = document.querySelector(".current-date-two"),
    prevNextIconTwo = document.querySelectorAll(".icons-two span");

// getting new date, current year and month
let dateTwo = new Date(),
    currYearTwo = date.getFullYear(),
    currMonthTwo = date.getMonth();

// storing full name of all months in array
const monthsTwo = [
    "Janv",
    "Févr",
    "Mars",
    "Avr",
    "Mai",
    "Juin",
    "Juil",
    "Août",
    "Sept",
    "Oct",
    "Nov",
    "Déc",
];

const renderCalendarTwo = () => {
    let firstDayofMonth = new Date(currYearTwo, currMonthTwo, 1).getDay(), // getting first day of month
        lastDateofMonth = new Date(currYearTwo, currMonthTwo + 1, 0).getDate(), // getting last date of month
        lastDayofMonth = new Date(
            currYearTwo,
            currMonthTwo,
            lastDateofMonth
        ).getDay(), // getting last day of month
        lastDateofLastMonth = new Date(currYearTwo, currMonthTwo, 0).getDate(); // getting last date of previous month

    let liTag = "";
    let today = null;

    for (let i = firstDayofMonth; i > 0; i--) {
        // creating li of previous month last days
        liTag += `<li class="inactive">${lastDateofLastMonth - i + 1}</li>`;
    }

    for (let i = 1; i <= lastDateofMonth; i++) {
        // creating li of all days of current month
        // adding active class to li if the current day, month, and year matched
        let isToday =
            i === date.getDate() &&
            currMonthTwo === new Date().getMonth() &&
            currYearTwo === new Date().getFullYear()
                ? "active"
                : "";

        liTag += `<li class="${isToday}">${i}</li>`;

        if (today === null) {
            today =
                i === date.getDate() &&
                currMonthTwo === new Date().getMonth() &&
                currYearTwo === new Date().getFullYear()
                    ? i
                    : null;
        }
    }

    for (let i = lastDayofMonth; i < 6; i++) {
        // creating li of next month first days
        liTag += `<li class="inactive">${i - lastDayofMonth + 1}</li>`;
    }
    currentDateTwo.innerText = `${monthsTwo[currMonthTwo]} ${currYearTwo}`; // passing current mon and yr as currentDate text
    daysTagTwo.innerHTML = liTag;

    datePickerInputTwo.value = `${currYearTwo}-${
        currMonthTwo + 1 < 10 ? "0" + (currMonthTwo + 1) : currMonthTwo + 1
    }-${today?? 1}`;

    let inputEvent = new Event("input", {
        bubbles: true,
        cancelable: true,
    });

    datePickerInputTwo.dispatchEvent(inputEvent);
};

prevNextIconTwo?.forEach((icon) => {
    // getting prev and next icons
    icon.addEventListener("click", () => {
        // adding click event on both icons
        // if clicked icon is previous icon then decrement current month by 1 else increment it by 1
        currMonthTwo = icon.id === "prev-two" ? currMonthTwo - 1 : currMonthTwo + 1;

        if (currMonthTwo < 0 || currMonthTwo > 11) {
            // if current month is less than 0 or greater than 11
            // creating a new date of current year & month and pass it as date value
            date = new Date(currYearTwo, currMonthTwo, new Date().getDate());
            currYearTwo = date.getFullYear(); // updating current year with new date year
            currMonthTwo = date.getMonth(); // updating current month with new date month
        } else {
            date = new Date(); // pass the current date as date value
        }
        renderCalendarTwo(); // calling renderCalendar function

        daysTagTwo.children.forEach((day) => {
            day.addEventListener("click", () => {
                datePickerInputTwo.value = `${currYearTwo}-${
                    currMonthTwo + 1 < 10 ? "0" + (currMonthTwo + 1) : currMonthTwo + 1
                }-${day.innerText}`;

                let inputEvent = new Event("input", {
                    bubbles: true,
                    cancelable: true,
                });

                datePickerInputTwo.dispatchEvent(inputEvent);

                calendarTwo.classList.add("hidden");
            });
        });
    });
});

const openCalendarTwo = () => {
    calendarTwo.classList.toggle("hidden");
    renderCalendarTwo();

    daysTagTwo.children.forEach((day) => {
        day.addEventListener("click", () => {
            datePickerInputTwo.value = `${currYearTwo}-${
                currMonthTwo + 1 < 10 ? "0" + (currMonthTwo + 1) : currMonthTwo + 1
            }-${day.innerText}`;

            let inputEvent = new Event("input", {
                bubbles: true,
                cancelable: true,
            });

            datePickerInputTwo.dispatchEvent(inputEvent);

            calendarTwo.classList.add("hidden");
        });
    });
};

datePickerbtnTwo?.addEventListener("click", () => {
    calendar.classList.add("hidden");
    openCalendarTwo();
});

datePickerbtnTwo?.addEventListener('click', function(event){
    event.stopPropagation();
});

calendarTwo?.addEventListener('click', function(event){
    event.stopPropagation();
});

document?.addEventListener('click', function(event){
    let target = event.target;
    if(target != calendarTwo && target != datePickerbtnTwo){
        calendarTwo?.classList.add('hidden');
    }
});

calendar?.addEventListener('click', function(event){
    event.stopPropagation();
});

datePickerbtn?.addEventListener('click', function(event){
    event.stopPropagation();
});

document?.addEventListener('click', function(event){
    let target = event.target;
    if(target != calendar && target != datePickerbtn){
        calendar?.classList.add('hidden');
    }
});

</script>
@endpush
