document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".quantity-controls").forEach(function (control) {
        const input = control.querySelector(".quantity-input");
        const minusBtn = control.querySelector(".quantity-btn.minus");
        const plusBtn = control.querySelector(".quantity-btn.plus");

        minusBtn.addEventListener("click", function () {
            let currentValue = parseInt(input.value) || 1;
            if (currentValue > 1) {
                input.value = currentValue - 1;
            }
        });

        plusBtn.addEventListener("click", function () {
            let currentValue = parseInt(input.value) || 1;
            input.value = currentValue + 1;
        });
    });
});