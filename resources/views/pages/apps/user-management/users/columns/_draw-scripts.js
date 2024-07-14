// Initialize KTMenu
KTMenu.init();

// Add click event listener to delete buttons
document.querySelectorAll('[data-kt-action="delete_row"]')?.forEach(function (element) {
    element.addEventListener('click', function () {
        Swal.fire({
            text: 'Voulez-vous supprimer ce utilisateur?',
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


document.querySelectorAll('[data-kt-action="disabeld_row"]')?.forEach(function (element) {
    element.addEventListener('click', function () {
        Swal.fire({
            text: 'Voulez-vous désactiver ce utilisateur?',
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
                Livewire.dispatch('disabeld_row', [this.getAttribute('data-kt-user-id')]);
            }
        });
    });
});


document.querySelectorAll('[data-kt-action="restore_row"]')?.forEach(function (element) {
    element.addEventListener('click', function () {
        Swal.fire({
            text: 'Voulez-vous activer ce utilisateur?',
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
                Livewire.dispatch('restore_row', [this.getAttribute('data-kt-user-id')]);
            }
        });
    });
});


// Add click event listener to update buttons
document.querySelectorAll('[data-kt-action="update_row"]')?.forEach(function (element) {
    element.addEventListener('click', function () {
        Livewire.dispatch('update_user', [this.getAttribute('data-kt-user-id')]);
    });
});

document.querySelectorAll('[data-kt-action="close_user_modal"]')?.forEach(function(element){
    element.addEventListener('click', function () {
        Livewire.dispatch('close_user_modal', []);
    });
});


// Listen for 'success' event emitted by Livewire
Livewire.on('success', (message) => {
    // Reload the users-table datatable
    LaravelDataTables['users-table'].ajax.reload();
});
