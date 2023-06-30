let file;

function listUpdated() {
    $("#updateList").removeClass("loading");
    $("#updateList").addClass("success");
    $("#updateList").html(`
        Lista atualizada
    `);
}

function updatingList() {
    $("#updateList").removeClass("success");
    $("#updateList").addClass("loading");
    $("#updateList").html(`
        Atualizando
        <svg aria-hidden="true" class="spinner-loading-sm svgLoading" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
            <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
        </svg>
    `);
}

function openInputModal() {
    fadeIn({ element: "#inputModal", show: true });
}

function closeInputModal() {
    fadeOut({ element: "#inputModal", hide: true });
}

function handleWithImport() {
    $("#arquivoImportado").show();
    $("#nomeArquivoImportado").show();
    $("#nomeArquivoImportado").text(file.name);
    $("#labelImportarArquivo").text("Escolher outro arquivo");
}

function importFile(e) {
    const acceptedTypes = [
        "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
    ];

    if (acceptedTypes.some((item) => item === e.target.files[0].type)) {
        file = e.target.files[0];
        handleWithImport();
        return;
    }

    pushError("Tipo de arquivo não aceito");
    file = undefined;
}

function renderList(data) {
    $("#excelList").html(data);
    $(".excelItemDeleteBtn").on("click", function (e) {
        $("#baseModalTitle").text("Você deseja excluir o arquivo?");
        $("#baseModalDescription").text(
            "Isso vai ajudar a liberar espeaço no sistema, mas você não poderá reverter essa ação e ter seu arquivo novamente."
        );
        $("#baseModalNextBtn").on("click", function () {
            $(this).off("click");
            deleteFile(e.target.dataset.id);
        });
        baseModalOpen();
    });
}

function deleteFile(id) {
    $.ajax({
        url: `deletarArquivo/${id}`,
        type: "DELETE",
        contentType: false,
        processData: false,
        beforeSend: function () {
            baseModalClose();
            loading(true);
        },
        success: function (data) {
            renderList(data);
        },
        error: function (error) {
            const errorFormatted = errorHandler(error);
            pushError(errorFormatted);
        },
        complete: function () {
            loading(false);
        },
    });
}

function getImports() {
    $.ajax({
        url: "importacoes/",
        type: "GET",
        data: {},
        contentType: false,
        processData: false,
        beforeSend: function () {
            updatingList();
        },
        success: function (data) {
            renderList(data);
        },
        error: function (error) {
            const errorFormatted = errorHandler(error);
            pushError(errorFormatted);
        },
        complete: function () {
            listUpdated();
        },
    });
}

function sendImport() {
    const column = $("#columnInput").val();

    if (!column) {
        return pushError("Você precisa colocar uma coluna");
    }

    const formData = new FormData();

    formData.append("column", column);
    formData.append("excel", file);

    $.ajax({
        url: "importar/",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        beforeSend: function () {
            loading(true);
        },
        error: function (error) {
            const errorFormatted = errorHandler(error);
            pushError(errorFormatted);
        },
        complete: function () {
            closeInputModal();
            file = undefined;
            $("#arquivoImportado").hide();
            $("#nomeArquivoImportado").text("");
            $("#nomeArquivoImportado").hide();
            $("#labelImportarArquivo").text("Escolher arquivo");
            $("#columnInput").val("");
            loading(false);
        },
    });
}

$("#importarArquivo").on("change", importFile);
$("#definirInputBtn").on("click", openInputModal);
$("#inputModalFinalizar").on("click", sendImport);
$(document).on("DOMContentLoaded", async function () {
    await getImports();

    setInterval(() => {
        getImports();
    }, 5000);
});
