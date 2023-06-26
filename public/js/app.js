$("form").on("submit", function () {
    $(".input-error").removeClass("input-error");
    $(".errorMessage").text("");
});

function pushError(error) {
    if (!error) {
        $("#alert-container").hide();
        console.error(
            "Erro ao chamar pushError: Você precisa de um erro para fazer o pushError"
        );
        return;
    }
    $("#alert-container").show();

    let id = Math.random().toString().replace(".", "").replace(/\D/g, "");

    do {
        id = Math.random().toString().replace(".", "").replace(/\D/g, "");
    } while ($(`.alertKey-${id}`).length);

    $("#alert-container").append(`
        <div class="errorAlert alert alertOpen alertKey-${id}">${error}</div>
    `);

    setTimeout(() => {
        $(`.alertKey-${id}`).removeClass("alertOpen").addClass("alertClose");

        setTimeout(() => {
            $(`.alertKey-${id}`).remove();

            if (!$(".alert").length) {
                $("#alert-container").hide();
            }
        }, 500);
    }, 3000);
}

function loading(action) {
    if (!!action) {
        if ($("#loading").css("display") === "flex") {
            return $("#loading").hide();
        }

        return $("#loading").css("display", "flex");
    }

    $("#loading").addClass("loadingFadeOut");

    setTimeout(() => {
        $("#loading").removeClass("loadingFadeOut");
        $("#loading").hide();
    }, 500);
}

$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});

function errorHandler(error) {
    if (error.responseJSON.errors) {
        return error.responseJSON.errors[
            Object.keys(error.responseJSON.errors)[0]
        ][0];
    }
    if (error.responseJSON.message) {
        return error.responseJSON.message;
    }
    return "Algo deu errado, tente novamente";
}

function fadeIn({ element, show = false }) {
    const el = $(element);

    if (!el) {
        return console.error(`Elemento ${element} não foi encontrado`);
    }

    el.removeClass("fadeIn");
    el.removeClass("fadeOut");

    if (show) {
        el.show();
    }

    el.addClass("fadeIn");
    setTimeout(() => {
        el.removeClass("fadeIn");
    }, 500);
}

function fadeOut({ element, hide = false }) {
    const el = $(element);

    if (!el) {
        return console.error(`Elemento ${element} não foi encontrado`);
    }

    el.removeClass("fadeOut");
    el.removeClass("fadeIn");

    el.addClass("fadeOut");
    setTimeout(() => {
        el.removeClass("fadeOut");
        if (hide) {
            el.hide();
        }
    }, 500);
}

// COMEÇO: componente modal base
function baseModalClose() {
    fadeOut({ element: "#baseModalContainer", hide: true });
}
function baseModalOpen() {
    fadeIn({ element: "#baseModalContainer", show: true });
}
// FIM: componente modal base
