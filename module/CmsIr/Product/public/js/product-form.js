$(function () {
    $('#product_main_photo').uploadifive({
        'auto'             : false,
        'formData'         : {
            'files': $('#filename_main_photo').val()
        },
        'method'   : 'post',
        'multi'         : false,
        'queueID'          : 'queue',
        'uploadScript'     : '/cms-ir/product/upload-main',
        'onUploadComplete' : function(file, data) {
            $('#filename_main_photo').val(data);

            if($('#filename_main_photo').val().length > 0) {
                var filename = $('#filename_main_photo').val();
                $('.files img').remove();
                $('.files').append('<img src="/files/product/'+data+'" class="thumb" />')
            }
        }
    });

    $('#product_files').uploadifive({
        'auto'             : false,
        'formData'         : {
            'files': $('#filename_gallery').val()
        },
        'method'   : 'post',
        'multi'         : true,
        'queueID'          : 'queue_2',
        'uploadScript'     : '/cms-ir/product/upload-gallery',
        'onUploadComplete' : function(file, data) {
            $('#filename_gallery').val(data);

            if($('#filename_gallery').val().length > 0) {
                var filename = $('#filename').val();
                $('.files_2').append('<div class="deletePhoto">  <i class="fa fa-times" data-toggle="tooltip" title="Usuń zdjęcie"></i> <img src="/temp_files/product/'+data+'" class="thumb" /> </div>')
            }

            $('.deletePhoto i').on('click', function () {
                var id = 0;
                var fullPathToImage = $(this).next().attr('src');

                if($(this).parent().is("[id]"))
                {
                    id = $(this).parent().attr('id');
                }
                $cache = $(this);
                $.ajax({
                    type: "POST",
                    url: "/cms-ir/product/delete-photo",
                    dataType : 'json',
                    data: {
                        id: id,
                        filePath: fullPathToImage
                    },
                    success: function(json)
                    {
                        $cache.parent().remove();
                    }
                });

            });
        }
    });

    if($('#filename_main_photo').val().length > 0) {
        var filename = $('#filename_main_photo').val();
        $('.files').append('<img src="/files/product/'+filename+'" class="thumb" />')
    }

    $('.deletePhoto i').on('click', function () {
        var id = 0;
        var fullPathToImage = $(this).next().attr('src');

        if($(this).parent().is("[id]"))
        {
            id = $(this).parent().attr('id');
        }
        $cache = $(this);
        $.ajax({
            type: "POST",
            url: "/cms-ir/product/delete-photo",
            dataType : 'json',
            data: {
                id: id,
                filePath: fullPathToImage
            },
            success: function(json)
            {
                $cache.parent().remove();
            }
        });

    });

});