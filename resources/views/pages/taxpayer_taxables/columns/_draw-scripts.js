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
                Livewire.dispatch('delete_user', [this.getAttribute('data-kt-user-id')]);
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
    element.addEventListener('change', function () {
        console.log('load_taxables', this.value);
        Livewire.dispatch('load_drop', [this.value]);
    });
});



// Add click event listener to update buttons
document.querySelectorAll('[data-kt-action="change_tarrif"]').forEach(function (element) {
    element.addEventListener('change', function () {
        console.log('change_tarrif', this.value);
        Livewire.dispatch('change_tarrif', [this.value]);
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
