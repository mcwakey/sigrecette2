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
document.querySelectorAll('[data-kt-action="load_drop"]').forEach(function (element) {
    element.addEventListener('change', function () {
        console.log('load_taxables', this.value);
        Livewire.dispatch('load_drop', [this.value]);
    });
});



// Add click event listener to update buttons
document.querySelectorAll('[data-kt-action="change_qty"]').forEach(function (element) {
    element.addEventListener('blur', function () {
        console.log('change_qty', this.value);
        Livewire.dispatch('change_qty', [this.value]);
    });
});

// Add click event listener to update buttons
document.querySelectorAll('[data-kt-action="add_request"]').forEach(function (element) {
    element.addEventListener('click', function () {
        console.log(this.getAttribute('data-kt-user-id'));
        Livewire.dispatch('add_request', [this.getAttribute('data-kt-user-id')]);
    });
});

// Add click event listener to update buttons
document.querySelectorAll('[data-kt-action="update_request"]').forEach(function (element) {
    element.addEventListener('click', function () {
        console.log(this.getAttribute('data-kt-user-id'));
        Livewire.dispatch('update_request', [this.getAttribute('data-kt-user-id')]);
    });
});

// Add click event listener to update buttons
document.querySelectorAll('[data-kt-action="update_request_status"]').forEach(function (element) {
    element.addEventListener('click', function () {
        console.log([this.getAttribute('data-kt-user-id')]);
        Livewire.dispatch('update_request_status', [this.getAttribute('data-kt-user-id')]);
    });
});

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
