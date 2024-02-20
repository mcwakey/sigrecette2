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

// Add click event listener to update buttons
document.querySelectorAll('[data-kt-action="load_taxables"]').forEach(function (element) {
    element.addEventListener('click', function () {
        console.log('load_taxables');
        Livewire.dispatch('load_taxables', [this.getAttribute('data-kt-user-id')]);
    });
});

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
        Livewire.dispatch('add_invoice', [this.getAttribute('data-kt-user-id')]);
    });
});

// Add click event listener to update buttons
document.querySelectorAll('[data-kt-action="update_invoice"]').forEach(function (element) {
    element.addEventListener('click', function () {
        Livewire.dispatch('update_invoice', [this.getAttribute('data-kt-user-id')]);
    });
});

// // Add click event listener to update buttons
// document.querySelectorAll('[data-kt-action="add_invoice"]').forEach(function (element) {
//     element.addEventListener('click', function () {
//         Livewire.dispatch('add_invoice', [this.getAttribute('data-kt-user-id')]);
//     });
// });

// Add click event listener to update buttons
document.querySelectorAll('[data-kt-action="add_taxable"]').forEach(function (element) {
    element.addEventListener('click', function () {
        Livewire.dispatch('add_taxable', [this.getAttribute('data-kt-user-id')]);
    });
});

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
