// Initialize KTMenu
KTMenu.init();

// Add click event listener to delete buttons
document.querySelectorAll('[data-kt-action="delete_row"]').forEach(function (element) {
    element.addEventListener('click', function () {
        Swal.fire({
            text: 'Êtes-vous sûr de vouloir supprimer ce paiement ?',
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
                Livewire.dispatch('delete_payment', [this.getAttribute('data-kt-user-id')]);
            }
        });
    });
});

// Add click event listener to update buttons
document.querySelectorAll('[data-kt-action="update_row"]').forEach(function (element) {
    element.addEventListener('click', function () {
        Livewire.dispatch('update_user', [this.getAttribute('data-kt-user-id')]);
    });
});


document.querySelectorAll('[data-kt-action="update_payment_status"]').forEach(function (element) {
    element.addEventListener('click', function () {
        Livewire.dispatch('update_payment_status', [this.getAttribute('data-kt-user-id')]);
    });
});


document.querySelectorAll('[data-kt-action="update_invoice"]').forEach(function (element) {
    element.addEventListener('click', function () {
        console.log([this.getAttribute('data-kt-user-id')])
        Livewire.dispatch('update_invoice', [this.getAttribute('data-kt-user-id')]);
    });
});

// Listen for 'success' event emitted by Livewire
Livewire.on('success', (message) => {
    // Reload the users-table datatable
    LaravelDataTables['recoveries-table'].ajax.reload();
});
