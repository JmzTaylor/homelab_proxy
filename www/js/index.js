function loadFrame(link, settings) {
    if (settings === '0') {
        $('#main_content').html("<iframe src='" + link + "'></iframe>");
    } else {
        window.open(link, '_blank');
    }
}

$('.dropdown-menu li a').on('click', function () {
    $("#tile_image").val("img/" + $(this).text().trim() + ".png");
    $("#image_preview").attr("src", "img/" + $(this).text().trim() + ".png");
});

function launchSettings() {
    $.ajax({
        url: '/getSettings',
        type: 'get',
        dataType: 'JSON',
        success: function (response) {
            $("#settingsModal option[id='" + response.launchMethod + "']").attr("selected", "selected");
            $('#settingsModal').modal('show');
        }
    });
}

function deleteItem(id) {
    $('#deleteModal').modal("show");
    $("#deleteButton").click(function () {
        $.ajax({
            url: '/deleteTile/' + id,
            type: 'get',
            dataType: 'JSON',
            success: function (response) {
                if (response['success']) {
                    window.location.href = "/";
                }
            }
        });
    });
}

function launchEdit(id) {
    $("#form").attr('action', '/updateTile');
    $.ajax({
        url: '/getTile/' + id,
        type: 'get',
        dataType: 'JSON',
        success: function (response) {
            $("#id").val(response['id']);
            $('#image_preview').attr('src', response['tile_image']);
            $('#tile_image').val(response['tile_image']);
            $("#tile_name").val(response['tile_name']);
            $("#tile_url").val(response['tile_url']);
            $("#tile_order").val(response['tile_order']);
            $('#addTileModal').modal('show');
        }
    });
}

function setTheme() {
    let mode = themeConfig.getTheme() === 'dark';
    if (mode) {
        $("#dark_mode").prop('checked', true);
    } else {
        $("#dark_mode").prop('checked', false);
    }
}

$('input[name=dark_mode]').change(function () {
    themeConfig.setTheme($(this).is(':checked') ? 'dark' : 'light');
    setTheme();
});

$(function () {
    setTheme();
    $('#closeAdd').on('click', function () {
        $("#form").trigger('reset');
        $("#image_preview").attr("src", "");
        $('input').val('');
    });
    $('[data-toggle="tooltip"]').tooltip();

    $(".file").change(function() {
        let formData = new FormData();
        let file = $('#fileUploader')[0].files[0];
        formData.append('file', file);
        $.ajax({
            url : '/uploadImage',
            type : 'POST',
            data : formData,
            processData: false,
            contentType: false,
            success : function(data) {
                $("#tile_image").val("img/" + file.name);
                $("#image_preview").attr("src", "img/" + file.name);
            }
        });
    });
});

Sortable.create(sortableList, {
    animation: 100,
    group: 'list-1',
    draggable: '.card',
    handle: '.bi-grip-horizontal',
    sort: true,
    filter: '.sortable-disabled',
    chosenClass: 'active',
    dataIdAttr: 'data-id',
    onEnd: function () {
        $.ajax({
            type: "POST",
            url: "/updateOrder",
            data: {data: this.toArray()}
        });
    },
});