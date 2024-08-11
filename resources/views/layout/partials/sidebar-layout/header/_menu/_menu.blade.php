@php
    use Carbon\Carbon;
    $year = \App\Models\Year::getActiveYear();
    $month = Carbon::createFromFormat('m', $year->current_month)->monthName;
@endphp
<!--begin::Menu wrapper-->
<div class="app-header-menu app-header-mobile-drawer align-items-stretch" data-kt-drawer="true"
    data-kt-drawer-name="app-header-menu" data-kt-drawer-activate="{default: true, lg: false}"
    data-kt-drawer-overlay="true" data-kt-drawer-width="250px" data-kt-drawer-direction="end"
    data-kt-drawer-toggle="#kt_app_header_menu_toggle" data-kt-swapper="true"
    data-kt-swapper-mode="{default: 'append', lg: 'prepend'}"
    data-kt-swapper-parent="{default: '#kt_app_body', lg: '#kt_app_header_wrapper'}">


    <style>
        .wrapper-three {
            width: 420px;
            background: #fff;
            border-radius: 2px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
            position: absolute;
            left: -1px;
            top: 100px;
            z-index: 1000;
            border-radius: 6px;
        }

        .wrapper-three.hidden {
            display: none;
        }

        .wrapper-three header {
            display: flex;
            align-items: center;
            padding: 25px 30px 10px;
            justify-content: space-between;
        }

        header .icons-three {
            display: flex;
        }

        header .icons-three span {
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

        .icons-three span:last-child {
            margin-right: -10px;
        }

        header .icons-three span:hover {
            background: #f2f2f2;
        }

        header .current-date-three {
            font-size: 1.2rem;
            font-weight: 500;
        }

        .calendar-three {
            padding: 20px;
        }

        .calendar-three ul {
            display: flex;
            flex-wrap: wrap;
            list-style: none;
            text-align: center;
        }

        .calendar-three .days-three {
            margin-bottom: 20px;
        }

        .calendar-three li {
            color: #333;
            width: calc(100% / 7);
            font-size: 1.07rem;
        }

        .calendar-three .weeks-three li {
            font-weight: 500;
            cursor: default;
        }

        .calendar-three .days-three li {
            z-index: 1;
            cursor: pointer;
            position: relative;
            margin-top: 30px;
        }

        .days-three li.inactive {
            color: #aaa;
        }

        .days-three li.active {
            color: #fff;
        }

        .days-three li::before {
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

        .days-three li.active::before {
            background: #0579ff;
        }

        .days-three li:not(.active):hover::before {
            background: #f2f2f2;
        }
    </style>

    <style>
        .wrapper-four {
            width: 420px;
            background: #fff;
            border-radius: 2px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
            position: absolute;
            left: -1px;
            top: 100px;
            z-index: 1000;
            border-radius: 6px;
        }

        .wrapper-four.hidden {
            display: none;
        }

        .wrapper-four header {
            display: flex;
            align-items: center;
            padding: 25px 30px 10px;
            justify-content: space-between;
        }

        header .icons-four {
            display: flex;
        }

        header .icons-four span {
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

        .icons-four span:last-child {
            margin-right: -10px;
        }

        header .icons-four span:hover {
            background: #f2f2f2;
        }

        header .current-date-four {
            font-size: 1.2rem;
            font-weight: 500;
        }

        .calendar-four {
            padding: 20px;
        }

        .calendar-four ul {
            display: flex;
            flex-wrap: wrap;
            list-style: none;
            text-align: center;
        }

        .calendar-four .days-four {
            margin-bottom: 20px;
        }

        .calendar-four li {
            color: #333;
            width: calc(100% / 7);
            font-size: 1.07rem;
        }

        .calendar-four .weeks-four li {
            font-weight: 500;
            cursor: default;
        }

        .calendar-four .days-four li {
            z-index: 1;
            cursor: pointer;
            position: relative;
            margin-top: 30px;
        }

        .days-four li.inactive {
            color: #aaa;
        }

        .days-four li.active {
            color: #fff;
        }

        .days-four li::before {
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

        .days-four li.active::before {
            background: #0579ff;
        }

        .days-four li:not(.active):hover::before {
            background: #f2f2f2;
        }
    </style>

    <!--begin::Menu-->
    <div class="menu menu-rounded menu-column menu-lg-row my-5 my-lg-0 align-items-stretch fw-semibold px-2 px-lg-0"
        id="kt_app_header_menu" data-kt-menu="true" style="position: relative">
        <!--begin:Menu item-->
        <div id="stats-date-btn" data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
            data-kt-menu-placement="bottom-start"
            class="menu-item here show menu-here-bg menu-lg-down-accordion me-0 me-lg-2">
            <!--begin:Menu link-->
            <span class="menu-link">
                {{--  --}}
                <span class="menu-title">
                    <span>
                        @if ($commune != null)
                            <img src="{{ $commune->getImageUrlAttributeDirect() }}" alt="Logo"
                                style="width: 30px; height: 30px;">
                    </span>
                    {{ $commune->name }}
                    <span style="display:block;margin:0px 4px 0px 4px">
                        |
                    </span>
                    @endif
                    <span class="text-gray-500 text-hover-primary"></span>
                    Année d'exercice:{{ ' ' . $year->name }}, Mois:{{ ' ' . $month }}
                </span>
                {{--  --}}
                <span class="menu-arrow d-lg-none"></span>
            </span>
            <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown p-0 w-100 w-lg-850px"></div>


        </div>

        <div class="shadow-sm bg-white" id="stats-date"
            style="position:fixed;max-width:620px;display:none;align-items:center;height:100px;padding:16px 20px;gap:16px;top:60px;border-radius:6px;">

            <div>
                <label class="required fs-6 fw-semibold mb-2" style="display: block">{{ __('Debut période') }}</label>
                <div class="input-group" id="kt_td_picker_simple" data-td-target-input="nearest"
                    data-td-target-toggle="nearest">
                    <span id="date-picker-btn-three" class="input-group-text" data-td-target="#kt_td_picker_basic"
                        data-td-toggle="datetimepicker">
                        <i class="ki-duotone ki-calendar fs-2"><span class="path1"></span><span
                                class="path2"></span></i>
                    </span>
                    <input id="date-picker-input-three" type="text" wire:model="period_from" name="period_from"
                        class="form-control mb-3 mb-lg-0" placeholder="{{ __('Date') }}" />
                </div>

                <div class="wrapper-three hidden" id="calendar-three">
                    <header>
                        <p class="current-date-three"></p>
                        <div class="icons-three">
                            <span id="prev-three" class="material-symbols-rounded">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="32" fill="#ccc"
                                    viewBox="0 0 256 256">
                                    <path
                                        d="M165.66,202.34a8,8,0,0,1-11.32,11.32l-80-80a8,8,0,0,1,0-11.32l80-80a8,8,0,0,1,11.32,11.32L91.31,128Z">
                                    </path>
                                </svg>

                            </span>
                            <span id="next-three" class="material-symbols-rounded">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="32" fill="#ccc"
                                    viewBox="0 0 256 256">
                                    <path
                                        d="M181.66,133.66l-80,80a8,8,0,0,1-11.32-11.32L164.69,128,90.34,53.66a8,8,0,0,1,11.32-11.32l80,80A8,8,0,0,1,181.66,133.66Z">
                                    </path>
                                </svg>
                            </span>
                        </div>
                    </header>
                    <div class="calendar-three">
                        <ul class="weeks-three">
                            <li>Dim</li>
                            <li>Lun</li>
                            <li>Mar</li>
                            <li>Mer</li>
                            <li>Jeu</li>
                            <li>Ven</li>
                            <li>Sam</li>
                        </ul>
                        <ul class="days-three"></ul>
                    </div>
                </div>

            </div>

            <div>
                <label class="required fs-6 fw-semibold mb-2" style="display: block">{{ __('Fin période') }}</label>
                <div class="input-group" id="kt_td_picker_simple" data-td-target-input="nearest"
                    data-td-target-toggle="nearest">
                    <span id="date-picker-btn-four" class="input-group-text" data-td-target="#kt_td_picker_basic"
                        data-td-toggle="datetimepicker">
                        <i class="ki-duotone ki-calendar fs-2"><span class="path1"></span><span
                                class="path2"></span></i>
                    </span>
                    <input id="date-picker-input-four" type="text" wire:model="period_to" name="period_to"
                        class="form-control mb-3 mb-lg-0" placeholder="{{ __('Date') }}" />
                </div>

                <div class="wrapper-four hidden" id="calendar-four">
                    <header>
                        <p class="current-date-four"></p>
                        <div class="icons-four">
                            <span id="prev-four" class="material-symbols-rounded">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="32" fill="#ccc"
                                    viewBox="0 0 256 256">
                                    <path
                                        d="M165.66,202.34a8,8,0,0,1-11.32,11.32l-80-80a8,8,0,0,1,0-11.32l80-80a8,8,0,0,1,11.32,11.32L91.31,128Z">
                                    </path>
                                </svg>

                            </span>
                            <span id="next-four" class="material-symbols-rounded">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="32" fill="#ccc"
                                    viewBox="0 0 256 256">
                                    <path
                                        d="M181.66,133.66l-80,80a8,8,0,0,1-11.32-11.32L164.69,128,90.34,53.66a8,8,0,0,1,11.32-11.32l80,80A8,8,0,0,1,181.66,133.66Z">
                                    </path>
                                </svg>
                            </span>
                        </div>
                    </header>
                    <div class="calendar-four">
                        <ul class="weeks-four">
                            <li>Dim</li>
                            <li>Lun</li>
                            <li>Mar</li>
                            <li>Mer</li>
                            <li>Jeu</li>
                            <li>Ven</li>
                            <li>Sam</li>
                        </ul>
                        <ul class="days-four"></ul>
                    </div>
                </div>

            </div>

            <div class="d-flex">
                <button id="search-btn" href="" type="submit" class="btn btn-primary mt-8"
                    style="margin-right: 4px;">
                    <span class="indicator-label" wire:loading.remove>{{ __('Soumettre') }}</span>
                </button>

                <button href="/geolocation/taxpayers" type="submit" class="btn badge-light mt-8">
                    <span class="indicator-label" wire:loading.remove>{{ __('Rénitialiser') }}</span>
                </button>
            </div>

        </div>

    </div>




    <!--end::Menu-->
    @push('scripts')
        <script async>
            let statsDateBtn = document.getElementById('stats-date-btn');
            let showStatsDate = true;
            let statsDate = document.getElementById('stats-date');

            
            statsDateBtn?.addEventListener('click', (event) => {
                // event.stopPropagation();
                if (!showStatsDate) {
                    statsDate.style.display = 'none';
                    showStatsDate = true;
                } else {
                    statsDate.style.display = 'flex';
                    showStatsDate = false;
                }
            });
            
            // statsDate.addEventListener('click', (event) => {
            //     event.stopPropagation();
            // });

            // document.addEventListener('click', () => {
            //     let target = event.target;
            //     if (target != statsDate && target != statsDateBtn) {
            //         statsDate.style.display = 'none';
            //         showStatsDate = true;
            //     }
            // });

            let datePickerbtnThree = document.getElementById("date-picker-btn-three");
            let datePickerInputThree = document.getElementById("date-picker-input-three");
            let calendarThree = document.getElementById("calendar-three");

            const daysTagThree = document.querySelector(".days-three"),
                currentDateThree = document.querySelector(".current-date-three"),
                prevNextIconThree = document.querySelectorAll(".icons-three span");

            // getting new date, current year and month
            let date = new Date(),
                currYearThree = date.getFullYear(),
                currMonthThree = date.getMonth();

            // storing full name of all months in array
            const monthsThree = [
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

            const renderCalendarThree = () => {
                let firstDayofMonth = new Date(currYearThree, currMonthThree, 1).getDay(), // getting first day of month
                    lastDateofMonth = new Date(currYearThree, currMonthThree + 1, 0)
                .getDate(), // getting last date of month
                    lastDayofMonth = new Date(
                        currYearThree,
                        currMonthThree,
                        lastDateofMonth
                    ).getDay(), // getting last day of month
                    lastDateofLastMonth = new Date(currYearThree, currMonthThree, 0)
                    .getDate(); // getting last date of previous month

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
                        currMonthThree === new Date().getMonth() &&
                        currYearThree === new Date().getFullYear() ?
                        "active" :
                        "";

                    liTag += `<li class="${isToday}">${i}</li>`;

                    if (today === null) {
                        today =
                            i === date.getDate() &&
                            currMonthThree === new Date().getMonth() &&
                            currYearThree === new Date().getFullYear() ?
                            i :
                            null;
                    }
                }

                for (let i = lastDayofMonth; i < 6; i++) {
                    // creating li of next month first days
                    liTag += `<li class="inactive">${i - lastDayofMonth + 1}</li>`;
                }
                currentDateThree.innerText =
                    `${monthsThree[currMonthThree]} ${currYearThree}`; // passing current mon and yr as currentDate text
                daysTagThree.innerHTML = liTag;

                datePickerInputThree.value = `${currYearThree}-${
        currMonthThree + 1 < 10 ? "0" + (currMonthThree + 1) : currMonthThree + 1
    }-${today?? 1}`;

                let inputEvent = new Event("input", {
                    bubbles: true,
                    cancelable: true,
                });

                datePickerInputThree.dispatchEvent(inputEvent);
            };

            prevNextIconThree?.forEach((icon) => {
                // getting prev and next icons
                icon.addEventListener("click", () => {
                    // adding click event on both icons
                    // if clicked icon is previous icon then decrement current month by 1 else increment it by 1
                    currMonthThree = icon.id === "prev-three" ? currMonthThree - 1 : currMonthThree + 1;

                    if (currMonthThree < 0 || currMonthThree > 11) {
                        // if current month is less than 0 or greater than 11
                        // creating a new date of current year & month and pass it as date value
                        date = new Date(currYearThree, currMonthThree, new Date().getDate());
                        currYearThree = date.getFullYear(); // updating current year with new date year
                        currMonthThree = date.getMonth(); // updating current month with new date month
                    } else {
                        date = new Date(); // pass the current date as date value
                    }
                    renderCalendarThree(); // calling renderCalendar function

                    daysTagThree.children.forEach((day) => {
                        day.addEventListener("click", () => {
                            datePickerInputThree.value = `${currYearThree}-${
                    currMonthThree + 1 < 10 ? "0" + (currMonthThree + 1) : currMonthThree + 1
                }-${day.innerText}`;

                            let inputEvent = new Event("input", {
                                bubbles: true,
                                cancelable: true,
                            });

                            datePickerInputThree.dispatchEvent(inputEvent);

                            calendarThree.classList.add("hidden");
                        });
                    });
                });
            });

            const openCalendarThree = () => {
                calendarThree.classList.toggle("hidden");
                renderCalendarThree();

                daysTagThree.children.forEach((day) => {
                    day.addEventListener("click", () => {
                        datePickerInputThree.value = `${currYearThree}-${
                currMonthThree + 1 < 10 ? "0" + (currMonthThree + 1) : currMonthThree + 1
            }-${day.innerText}`;

                        let inputEvent = new Event("input", {
                            bubbles: true,
                            cancelable: true,
                        });

                        datePickerInputThree.dispatchEvent(inputEvent);

                        calendarThree.classList.add("hidden");
                    });
                });
            };

            datePickerbtnThree?.addEventListener("click", () => {
                calendarThree.classList.add("hidden");
                calendarFour.classList.add("hidden");
                openCalendarThree();
            });

            datePickerbtnThree?.addEventListener('click', function(event) {
                event.stopPropagation();
            });

            calendarThree?.addEventListener('click', function(event) {
                event.stopPropagation();
            });

            document?.addEventListener('click', function(event) {
                let target = event.target;
                if (target != calendarThree && target != datePickerbtnThree) {
                    calendarThree?.classList.add('hidden');
                }
            });


            let datePickerbtnFour = document.getElementById("date-picker-btn-four");
            let datePickerInputFour = document.getElementById("date-picker-input-four");
            let calendarFour = document.getElementById("calendar-four");

            const daysTagFour = document.querySelector(".days-four"),
                currentDateFour = document.querySelector(".current-date-four"),
                prevNextIconFour = document.querySelectorAll(".icons-four span");

            // getting new date, current year and month
            let dateFour = new Date(),
                currYearFour = date.getFullYear(),
                currMonthFour = date.getMonth();

            // storing full name of all months in array
            const monthsFour = [
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

            const renderCalendarFour = () => {
                let firstDayofMonth = new Date(currYearFour, currMonthFour, 1).getDay(), // getting first day of month
                    lastDateofMonth = new Date(currYearFour, currMonthFour + 1, 0)
                .getDate(), // getting last date of month
                    lastDayofMonth = new Date(
                        currYearFour,
                        currMonthFour,
                        lastDateofMonth
                    ).getDay(), // getting last day of month
                    lastDateofLastMonth = new Date(currYearFour, currMonthFour, 0)
                    .getDate(); // getting last date of previous month

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
                        currMonthFour === new Date().getMonth() &&
                        currYearFour === new Date().getFullYear() ?
                        "active" :
                        "";

                    liTag += `<li class="${isToday}">${i}</li>`;

                    if (today === null) {
                        today =
                            i === date.getDate() &&
                            currMonthFour === new Date().getMonth() &&
                            currYearFour === new Date().getFullYear() ?
                            i :
                            null;
                    }
                }

                for (let i = lastDayofMonth; i < 6; i++) {
                    // creating li of next month first days
                    liTag += `<li class="inactive">${i - lastDayofMonth + 1}</li>`;
                }
                currentDateFour.innerText =
                    `${monthsFour[currMonthFour]} ${currYearFour}`; // passing current mon and yr as currentDate text
                daysTagFour.innerHTML = liTag;

                datePickerInputFour.value = `${currYearFour}-${
        currMonthFour + 1 < 10 ? "0" + (currMonthFour + 1) : currMonthFour + 1
    }-${today?? 1}`;

                let inputEvent = new Event("input", {
                    bubbles: true,
                    cancelable: true,
                });

                datePickerInputFour.dispatchEvent(inputEvent);
            };

            prevNextIconFour?.forEach((icon) => {
                // getting prev and next icons
                icon.addEventListener("click", () => {
                    // adding click event on both icons
                    // if clicked icon is previous icon then decrement current month by 1 else increment it by 1
                    currMonthFour = icon.id === "prev-four" ? currMonthFour - 1 : currMonthFour + 1;

                    if (currMonthFour < 0 || currMonthFour > 11) {
                        // if current month is less than 0 or greater than 11
                        // creating a new date of current year & month and pass it as date value
                        date = new Date(currYearFour, currMonthFour, new Date().getDate());
                        currYearFour = date.getFullYear(); // updating current year with new date year
                        currMonthFour = date.getMonth(); // updating current month with new date month
                    } else {
                        date = new Date(); // pass the current date as date value
                    }
                    renderCalendarFour(); // calling renderCalendar function

                    daysTagFour.children.forEach((day) => {
                        day.addEventListener("click", () => {
                            datePickerInputFour.value = `${currYearFour}-${
                    currMonthFour + 1 < 10 ? "0" + (currMonthFour + 1) : currMonthFour + 1
                }-${day.innerText}`;

                            let inputEvent = new Event("input", {
                                bubbles: true,
                                cancelable: true,
                            });

                            datePickerInputFour.dispatchEvent(inputEvent);

                            calendarFour.classList.add("hidden");
                        });
                    });
                });
            });

            const openCalendarFour = () => {
                calendarFour.classList.toggle("hidden");
                renderCalendarFour();

                daysTagFour.children.forEach((day) => {
                    day.addEventListener("click", () => {
                        datePickerInputFour.value = `${currYearFour}-${
                currMonthFour + 1 < 10 ? "0" + (currMonthFour + 1) : currMonthFour + 1
            }-${day.innerText}`;

                        let inputEvent = new Event("input", {
                            bubbles: true,
                            cancelable: true,
                        });

                        datePickerInputFour.dispatchEvent(inputEvent);

                        calendarFour.classList.add("hidden");
                    });
                });
            };

            datePickerbtnFour?.addEventListener("click", () => {
                calendarThree.classList.add("hidden");
                calendarFour.classList.add("hidden");
                openCalendarFour();
            });

            datePickerbtnFour?.addEventListener('click', function(event) {
                event.stopPropagation();
            });

            calendarFour?.addEventListener('click', function(event) {
                event.stopPropagation();
            });

            document?.addEventListener('click', function(event) {
                let target = event.target;
                if (target != calendarFour && target != datePickerbtnFour) {
                    calendarFour?.classList.add('hidden');
                }
            });

        </script>
    @endpush
</div>
