KTMenu.init();
document.querySelectorAll('[data-kt-action="delete_taxpayer"]').forEach(function (element) {
    element.addEventListener('click', function () {
        Swal.fire({
            text: 'Êtes-vous sûr de vouloir désactiver ce contribuable?',
            icon: 'warning',
            buttonsStyling: false,
            showCancelButton: true,
            confirmButtonText: 'Oui',
            cancelButtonText: 'Non',
            customClass: {
                confirmButton: 'btn btn-danger',
                cancelButton: 'btn btn-secondary',
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('delete_taxpayer', [this.getAttribute('data-kt-user-id')]);
            }
        });
    });
});
document.querySelectorAll('[data-kt-action="restore_taxpayer"]').forEach(function (element) {
    element.addEventListener('click', function () {
        Swal.fire({
            text: 'Êtes-vous sûr de vouloir réactiver ce contribuable  ?',
            icon: 'warning',
            buttonsStyling: false,
            showCancelButton: true,
            confirmButtonText: 'Oui',
            cancelButtonText: 'Non',
            customClass: {
                confirmButton: 'btn btn-danger',
                cancelButton: 'btn btn-secondary',
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('restore_taxpayer', [this.getAttribute('data-kt-user-id')]);
            }
        });
    });
});



document.querySelectorAll('[data-kt-action="update_taxpayer"]').forEach(function (element) {
    element.addEventListener('click', function () {
        Livewire.dispatch('update_taxpayer', [this.getAttribute('data-kt-user-id')]);
    });
});


document.querySelectorAll('[data-kt-action="update_invoice"]').forEach(function (element) {
    element.addEventListener('click', function () {
        console.log([this.getAttribute('data-kt-user-id')])
        Livewire.dispatch('update_invoice', [this.getAttribute('data-kt-user-id')]);
    });
});


// Add click event listener to update buttons
document.querySelectorAll('[data-kt-action="load_drop"]').forEach(function (element) {
    element.addEventListener('change', function () {
        Livewire.dispatch('load_drop', [this.value]);
    });
});

// Listen for 'success' event emitted by Livewire
Livewire.on('success', (message) => {
    // Reload the users-table datatable
    LaravelDataTables['users-table'].ajax.reload();
});

document.querySelectorAll('[data-kt-action="close_taxpayer_modal"]')?.forEach(function (element) {
    element?.addEventListener('click', function () {
        Livewire.dispatch('close_taxpayer_modal', []);
    });
});

