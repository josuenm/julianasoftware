function handleSwitchInput() {
    const disabled = $(this).hasClass("disabled");
    if (disabled) return;

    $(this).toggleClass("active");
    const inputId = $(this).data("input");
    $(`#${inputId}`).val($(this).hasClass("active"));
}

$(document).on("DOMContentLoaded", function () {
    $(".switch-input").on("click", handleSwitchInput);
});
