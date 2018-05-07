function onChatUploadClick() {
    $('#uploadfile-file-chat').trigger('click');
}
$(document).ready(function () {
    var lession_fileName = '';
    $('#event_upload').fileupload({
        dataType: 'JSON',
        beforeSend: function () {
            $("#attach_area").show();
        },
        add: function (e, data) {
            var fileSize = formatFileSize(data.files[0].size);
            lession_fileName = data.files[0].name;
            var split_filename = trimFileName(lession_fileName);
            split_filename = split_filename.split(".");
            var total_piece = split_filename.length;
            file_extension = split_filename[total_piece - 1].toLowerCase();
            filesizeKb = (data.files[0].size) / 1024;
            filesizeMb = (data.files[0].size) / (1024 * 1024);

            var error_msg = '';
            if (file_extension == 'png' || file_extension == 'gif' || file_extension == 'jpg') {
                if (filesizeMb > 5) {
                    error_msg = 'File size should less than 5 mb';
                }
            } else {
                error_msg = "Invalid file format";
            }
            if (error_msg != '') {
                toastr["error"](error_msg, "Error");
            } else {
                jqXHR = data.submit();
            }
        },
        progressall: function (e, data) {
            $("#attach_area").show();
        },
        done: function (event, data1) {
            $("#attach_area").hide();
        },
        fail: function (e, data) {
            $("#attach_area").hide();
        },
        success: function (d) {
            if (d.status == "success") {
                $("#file_upload_result").html('<img src="' + d.location + '" width="100" height="100">');
                $("#hid_file_name").val(d.filename);
                $("#hid_file_name-error").hide();
            } else {
                var error = "File upload failed. Please try again.";
                toastr["error"](error, "Error");
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            if (errorThrown === 'abort') {
                var error = "File upload failed. Please try again.";
                toastr["error"](error, "Error");
            }
            $("#attach_area").hide();
        }
    });
});

function formatFileSize(bytes) {
    if (typeof bytes !== 'number') {
        return '';
    }
    if (bytes >= 1000000000) {
        return (bytes / 1000000000).toFixed(2) + ' GB';
    }
    if (bytes >= 1000000) {
        return (bytes / 1000000).toFixed(2) + ' MB';
    }
    return (bytes / 1000).toFixed(2) + ' KB';
}

function trimFileName(file_name) {
    var trimed_filename = file_name.split(' ').join('-');
    trimed_filename = trimed_filename.split('&').join('-');
    trimed_filename = trimed_filename.split(';').join('-');
    trimed_filename = trimed_filename.split(':').join('-');
    trimed_filename = trimed_filename.split('/').join('-');
    trimed_filename = trimed_filename.split('{').join('-');
    trimed_filename = trimed_filename.split('}').join('-');
    trimed_filename = trimed_filename.split('(').join('-');
    trimed_filename = trimed_filename.split(')').join('-');
    trimed_filename = trimed_filename.split('\'').join('-');
    trimed_filename = trimed_filename.split('"').join('-');
    return trimed_filename;
}
