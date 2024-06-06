document
    .querySelectorAll('[data-kt-action="load_drop"]')
    .forEach(function (element) {
        element.addEventListener("change", function () {
            Livewire.dispatch("load_drop", [this.value]);
        });
    });
