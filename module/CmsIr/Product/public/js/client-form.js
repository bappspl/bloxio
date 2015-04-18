$(function () {
    $('#upload').uploadifive({
        'auto'             : false,
        'formData'         : {
            'files': $('#filename').val()
        },
        'method'   : 'post',
        'multi'         : false,
        'queueID'          : 'queue',
        'uploadScript'     : '/cms-ir/client/upload',
        'onUploadComplete' : function(file, data) {
            $('#filename').val(data);

            if($('#filename').val().length > 0) {
                var filename = $('#filename').val();
                $('.files img').remove();
                $('.files').append('<img src="/files/client/'+data+'" class="thumb" />')
            }
        }
    });

    if($('#filename').val().length > 0) {
        var filename = $('#filename').val();
        $('.files').append('<img src="/files/client/'+filename+'" class="thumb" />')
    }

});