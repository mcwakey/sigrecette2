<form id="kt_modal_add_delivery" wire:submit.prevent="submit" class="form px-7 py-5" data-kt-menu-id="kt_modal_add_delivery "
    style="position: relative" data-bs-backdrop='static'>

    <style>
        .wrapper {
            width: 400px;
            background: #fff;
            border-radius: 2px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
            position: absolute;
            left: -130%;
            top: 0;
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

    <!--begin::Input group-->
    <div class="fv-row mb-5">
        <!--begin::Label-->

        <label class="required fw-semibold fs-6 mb-2">{{ __('delivery date') }}</label>
        <!--end::Label-->
        <!--begin::Input-->
        <input type="hidden" wire:model="invoice_id" name="invoice_id"
            class="form-control form-control-solid mb-3 mb-lg-0" placeholder="{{ __('invoice_id') }}" />



        <div class="input-group" id="kt_td_picker_simple" data-td-target-input="nearest"
            data-td-target-toggle="nearest">
            <span id="date-picker-btn" class="input-group-text" data-td-target="#kt_td_picker_basic"
                data-td-toggle="datetimepicker">
                <i class="ki-duotone ki-calendar fs-2"><span class="path1"></span><span class="path2"></span></i>
            </span>
            <input READONLY id="date-picker-input" wire:model="delivery_date" value="" name="delivery_date"
                id="kt_td_picker_basic_input" type="text" class="form-control" data-td-target="#kt_td_picker_basic"
                placeholder="yyyy-MM-dd" />
        </div>
        <label class="required fw-semibold fs-6 mb-2">{{ __('Nom  du réceptionnaire') }}</label>
        <!--end::Label-->
        <!--begin::Input-->
        <!--end::Label-->
        <!--begin::Input-->
        <input type="text" wire:model="delivery_to" name="delivery_to" class="form-control  mb-3 mb-lg-0"
            placeholder="{{ __('commune_name') }}" />
        <!--end::Input-->
        @error('delivery_to')
            <span class="text-danger">{{ $message }}</span>
        @enderror


        <!--end::Input-->
    </div>
    <!--end::Input group-->
    <!--begin::Actions-->
    <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-sm btn-success">
            <span wire:loading.remove>{{ __('apply') }}</span>
            <span wire:loading>{{ __('loading') }}</span>
        </button>
    </div>
    <!--end::Actions-->


    <div class="wrapper hidden" id="calendar">
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

</form>
