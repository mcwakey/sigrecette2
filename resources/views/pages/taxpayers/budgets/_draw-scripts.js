KTMenu.init();


Livewire.on('success', (message) => {
    // Reload the users-table datatable
    LaravelDataTables['budgets-table'].ajax.reload();
});


