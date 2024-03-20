// Initialize KTMenu
KTMenu.init();

// Add click event listener to delete buttons
document.querySelectorAll('[data-kt-action="delete_taxpayer"]').forEach(function (element) {
    element.addEventListener('click', function () {
        Swal.fire({
            text: 'Êtes-vous sûr de vouloir supprimer ?',
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

// Add click event listener to update buttons
document.querySelectorAll('[data-kt-action="update_invoice"]').forEach(function (element) {
    element.addEventListener('click', function () {
        Livewire.dispatch('update_invoice', [this.getAttribute('data-kt-user-id')]);
    });
});

// // Add click event listener to update buttons
// document.querySelectorAll('[data-kt-action="load_invoice"]').forEach(function (element) {
//     element.addEventListener('change', function () {
//         console.log(this.value);
//         Livewire.dispatch('load_invoice', [this.getAttribute('data-kt-user-id')]);
//     });
// });


// Add click event listener to update buttons
document.querySelectorAll('[data-kt-action="load_drop"]').forEach(function (element) {
    element.addEventListener('change', function () {
        console.log(this.value)
        Livewire.dispatch('load_drop', [this.value]);
    });
});

// Listen for 'success' event emitted by Livewire
Livewire.on('success', (message) => {
    // Reload the users-table datatable
    LaravelDataTables['users-table'].ajax.reload();
});
