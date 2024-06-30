// Initialize KTMenu
KTMenu.init();

// Add click event listener to delete buttons
document.querySelectorAll('[data-kt-action="delete_row"]').forEach(function (element) {
    element.addEventListener('click', function () {
        Swal.fire({
            text: 'Are you sure you want to remove?',
            icon: 'warning',
            buttonsStyling: false,
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
            customClass: {
                confirmButton: 'btn btn-danger',
                cancelButton: 'btn btn-secondary',
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('delete_taxpayer_taxable', [this.getAttribute('data-kt-user-id')]);
            }
        });
    });
});



// Add click event listener to update buttons
document.querySelectorAll('[data-kt-action="update_taxpayer"]').forEach(function (element) {
    element.addEventListener('click', function () {
        Livewire.dispatch('update_taxpayer', [this.getAttribute('data-kt-user-id')]);
    });
});

// // Add click event listener to update buttons
// document.querySelectorAll('[data-kt-action="update_taxpayer"]').forEach(function (element) {
//     element.addEventListener('click', function () {
//         Livewire.dispatch('update_taxpayer', [this.getAttribute('data-kt-user-id')]);
//     });
// });
//console.log(document.querySelectorAll('[data-kt-action="update_qty"]'))


// Add click event listener to update buttons
document.querySelectorAll('[data-kt-action="add_taxable"]').forEach(function (element) {
    element.addEventListener('change', function () {
        console.log('add_taxable');
        Livewire.dispatch('add_taxable', [this.value]);
    });
});

// Add click event listener to update buttons
document.querySelectorAll('[data-kt-action="add_taxpayer_taxable"]').forEach(function (element) {
    element.addEventListener('click', function () {
        console.log('add_taxpayer_taxable');
        Livewire.dispatch('add_taxpayer_taxable', [this.getAttribute('data-kt-user-id')]);
    });
});


// // Add click event listener to update buttons
// document.querySelectorAll('[data-kt-action="load_invoice"]').forEach(function (element) {
//     element.addEventListener('change', function () {
//         console.log(this.value);
//         var value = this.value;
//         Livewire.dispatch('load_invoice', [this.getAttribute('data-kt-user-id'), value]);
//     });
// });


// Add click event listener to update buttons
document.querySelectorAll('[data-kt-action="load_invoice"]').forEach(function (element) {
    element.addEventListener('change', function () {
        //console.log(this.value);
        //var value = value;
        Livewire.dispatch('load_invoice', [this.value]);
    });
});


// Add click event listener to update buttons
document.querySelectorAll('[data-kt-action="load_drop"]').forEach(function (element) {
    element.addEventListener('input', function () {
        if(this.value && !isNaN(parseFloat(this.value))){
            Livewire.dispatch('load_drop', [this.value]);
        }
    });
});



// Add click event listener to update buttons
document.querySelectorAll('[data-kt-action="change_tarrif"]').forEach(function (element) {
    element.addEventListener('input', function () {
        console.log(this.value)
        if(this.value && !isNaN(parseFloat(this.value))){
            Livewire.dispatch('change_tarrif', [this.value]);
        }
    });
});

// // Add click event listener to update buttons
// document.querySelectorAll('[data-kt-action="load_dropa"]').forEach(function (element) {
//     element.addEventListener('change', function () {
//         console.log(this.value);
//         Livewire.dispatch('load_dropa', [this.value]);
//     });
// });


// Add change event listener to checkbox inputs
document.querySelectorAll('[data-kt-action="update_checkbox"]').forEach(function (element) {
    element.addEventListener('change', function () {
        // Get the value of the checkbox
        //var value = this.checked;


        //dd(this.checked);
        // Dispatch a Livewire event with the checkbox value
        Livewire.dispatch('update_checkbox', [this.getAttribute('data-kt-user-id')]);
    });
});


// Add click event listener to update buttons
document.querySelectorAll('[data-kt-action="update_taxable"]').forEach(function (element) {
    element.addEventListener('click', function () {
        Livewire.dispatch('update_taxable', [this.getAttribute('data-kt-user-id')]);
    });
});

// Add click event listener to update buttons
document.querySelectorAll('[data-kt-action="add_invoice"]').forEach(function (element) {
    element.addEventListener('click', function () {
        console.log(this.value);
        Livewire.dispatch('add_invoice', [this.getAttribute('data-kt-user-id')]);
    });
});

// Add click event listener to update buttons
document.querySelectorAll('[data-kt-action="add_no_invoice"]').forEach(function (element) {
    element.addEventListener('click', function () {
        // console.log(this.value);
        Livewire.dispatch('add_no_invoice', [this.getAttribute('data-kt-user-id')]);
    });
});

// Add click event listener to update buttons
document.querySelectorAll('[data-kt-action="view_invoice"]').forEach(function (element) {
    element.addEventListener('click', function () {
        Livewire.dispatch('view_invoice', [this.getAttribute('data-kt-user-id')]);
    });
});

// Add click event listener to update buttons
document.querySelectorAll('[data-kt-action="update_invoice"]').forEach(function (element) {
    element.addEventListener('click', function () {
        Livewire.dispatch('update_invoice', [this.getAttribute('data-kt-user-id')]);
    });
});

// Add click event listener to update buttons
document.querySelectorAll('[data-kt-action="update_payment"]').forEach(function (element) {
    element.addEventListener('click', function () {
        console.log([this.getAttribute('data-kt-user-id')])
        Livewire.dispatch('update_payment', [this.getAttribute('data-kt-user-id')]);
    });
});

// Add click event listener to update buttons
document.querySelectorAll('[data-kt-action="update_status"]').forEach(function (element) {
    element.addEventListener('click', function () {
        Livewire.dispatch('update_status', [this.getAttribute('data-kt-user-id')]);
    });
});
document.querySelectorAll('[data-kt-action="update_payment_amount"]').forEach(function (element) {
    element.addEventListener('change', function () {
        Livewire.dispatch('update_payment_amount', [this.value]);

    });
})
// Add click event listener to update buttons
document.querySelectorAll('[data-kt-action="auto_invoice"]').forEach(function (element) {
    element.addEventListener('change', function () {
        console.log(this.value);
        Livewire.dispatch('auto_invoice', [this.value]);
    });
});

// // Add click event listener to update buttons
// document.querySelectorAll('[data-kt-action="add_invoice"]').forEach(function (element) {
//     element.addEventListener('click', function () {
//         Livewire.dispatch('add_invoice', [this.getAttribute('data-kt-user-id')]);
//     });
// });

// Add click event listener to update buttons
// document.querySelectorAll('[data-kt-action="update_row"]').forEach(function (element) {
//     element.addEventListener('click', function () {
//         Livewire.dispatch('update_user', [this.getAttribute('data-kt-user-id')]);
//     });
// });

// Listen for 'success' event emitted by Livewire
Livewire.on('success', (message) => {
    // Reload the users-table datatable
    LaravelDataTables['taxables-table'].ajax.reload();
});

// // Listen for 'success' event emitted by Livewire
// Livewire.on('success', (message) => {
//     // Reload the users-table datatable
//     'kt_table_taxpayer_invoices'.ajax.reload();
// });



let datePickerbtn = document.getElementById("date-picker-btn");
let datePickerInput = document.getElementById("date-picker-input");
let calendar = document.getElementById("calendar");

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
    openCalendar();
});

calendar.addEventListener('click', function(event){
    event.stopPropagation();
});

datePickerbtn.addEventListener('click', function(event){
    event.stopPropagation();
});

document.addEventListener('click', function(event){
    let target = event.target;
    if(target != calendar && target != datePickerbtn){
        calendar.classList.add('hidden');
    }
});

