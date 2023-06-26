let file;

function abrirModalInput() {
    $("#inputModal").show();
}

function lidarComImportacao() {
    $("#arquivoImportado").show();
    $("#nomeArquivoImportado").show();
    $("#nomeArquivoImportado").text(file.name);
    $("#labelImportarArquivo").text("Escolher outro arquivo");
}

function importarArquivo(e) {
    const acceptedTypes = [
        "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
    ];

    if (acceptedTypes.some((item) => item === e.target.files[0].type)) {
        file = e.target.files[0];
        lidarComImportacao();
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

function pegarImportacoes() {
    $.ajax({
        url: "importacoes/",
        type: "GET",
        data: {},
        contentType: false,
        processData: false,
        beforeSend: function () {
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
pegarImportacoes();

function enviarImportacao() {
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
        success: function (data) {
            console.log(data);
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

$("#importarArquivo").on("change", importarArquivo);
$("#definirInputBtn").on("click", abrirModalInput);
$("#inputModalFinalizar").on("click", enviarImportacao);
