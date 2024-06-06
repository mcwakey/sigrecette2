var element = document.querySelector("#kt_stepper_add_invoice_general");

var stepper = new KTStepper(element);

stepper.on("kt.stepper.next", function () {
    stepper.goNext();
});
stepper.on("kt.stepper.previous", function () {
    stepper.goPrevious();
});
