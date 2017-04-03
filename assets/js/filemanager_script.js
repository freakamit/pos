$(function () {

    var baseURL = document.location;
    var sitename = '/pos';
    var App;
    App = {
        init: function () {
            App.initFooterItem();
        },
        initFooterItem: function () {
            $obj = $('.folder-nav li.show').find('a');
            var val = $obj.html();
            var id = $obj.attr('data-id');
            var path = $obj.attr('data-path');
            var len = $('.img-view').length;
            $('.footer-item > span').html(val);
            $('.footer-count').html(len);
            $('.path_name > span').html(path);
            goback();
        },
    }

    App.init();

    //Load Files and Folders on clicking the left navigation
    var page = 1;
    $('.folder-nav li').on('click', function () {
        $('.folder-nav li.show').removeClass('show');
        $(this).addClass('show');
        App.initFooterItem();
        var path = $(this).find('a').attr('data-path');
        $('.path_name > span').html(path);
        page = 1;
        load_filemanager_img(path, page);
    });

    //Initially Hide View More Button is the number of items in viewport is less than 24
    if ($('.img-view').length == 24) {
        $('.window-content').append('<div class="img-view view_more">\n\
                                        <i class="fa fa-eye"></i>\n\
                                        <div class="img-info">View More</div>\n\
                                    </div>');
    }

    //redirect ajax to previous folder after clicking on go_back_btn
    $('body').on('click', '.go_back_btn', function () {
        var path = $(this).attr('data-path');
        $('.path_name > span').html(path);
        load_filemanager_img(path, page);
    });


    //to load more images by clicking on view more button
    $('body').on('click', '.img-view.view_more', function () {
        $(this).remove();
        page++;
        path = $('.folder-nav > li.show').find('a').attr('data-path');
        load_filemanager_img(path, page);
    });


    //select image from viewport 
    $('body').on('click', '.img-view', function () {
        $('.img-view.img-selected').removeClass('img-selected');
        $(this).addClass('img-selected');
        var imgName = $(this).find('div.img-info').html();
        $('.footer-img-name').html(imgName);
    });


    //ajax request to create form
    $('#createFolder').submit(function (event) {
        event.preventDefault();
        var folder_name = $('.folder_name').val(),
                parent_folder = $('.path_name > span').html();
        $.ajax({
            url: baseURL.origin + sitename + '/admin/filemanager/makefolder',
            method: "POST",
            data: {folder_name: folder_name, parent_folder: parent_folder},
            dataType: 'json',
            beforeSend: function () {
                $('.file-manager-window').append('<div class="loader-overlay center"></div>');
                setTimeout(function () {
                    return true;
                }, 800);
            },
            success: function (msg) {
                setTimeout(function () {
                    if (msg.status) {
                        insertHTML(msg.html, page);
                    }
                    $('.session-msg').html(msg.message);
                    $('.make_folder').parent().modal('toggle');
                    $('.folder_name').val('');
                    $('.loader-overlay').fadeOut();
                }, 800);
            }
        });
    });


    //upload image detail
    $('#update_image_detail').on('submit', function (e) {
        e.preventDefault();

        $.ajax({
            url: baseURL.origin + sitename + '/admin/filemanager/update_image_detail',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            beforeSend: function () {
                $('.file-manager-window').append('<div class="loader-overlay center"></div>');
                setTimeout(function () {
                    return true;
                }, 800);
            },
            success: function (msg) {
                setTimeout(function () {
                    if (msg) {
                        load_filemanager_img(msg.path, page);
                    }
                    $('#md-effect-image-detail').modal('toggle');
                }, 800);
            }
        });
    });

    //Right Click context menu for folders only
    $('body').on('contextmenu', '.img-view', function (e) {
        e.preventDefault();
        $('.contextmenu').remove();
        if ($(this).attr('data-type') == 'Folder') {
            $(this).append('<ul class="contextmenu">\n\
                <li><a class="md-effect-rename" data-effect="md-slideDown" href="javascript:void(0)" data-identity="rename">Rename</a></li>\n\
                <li><a href="javascript:void(0)" data-identity="delete">Delete</a></li>\n\
                </ul>');
        } else if ($(this).attr('data-type') == 'Image') {
            $(this).append('<ul class="contextmenu">\n\
                <li><a class="md-effect-rename" data-effect="md-slideDown" href="javascript:void(0)" data-identity="update">Update</a></li>\n\
                <li><a href="javascript:void(0)" data-identity="delete">Delete</a></li>\n\
                </ul>');
        }
    });


    //double click on folder to enter
    $('body').on('dblclick', '.img-view', function () {
        if ($(this).attr('data-type') == 'Folder') {
            var path = $(this).attr('data-path');
            $('.path_name > span').html(path);
            load_filemanager_img(path, page);
        } else if ($(this).attr('data-type') == 'Image') {
            setImgDetail($(this));
        }
    });


    //context menu click identity
    $('body').on('click', '.contextmenu > li', function () {
        $obj = $(this).parent().parent();
        if ($obj.attr('data-type') == 'Folder') {
            if ($(this).find('a').attr('data-identity') == 'rename') {
                $('#md-effect-rename').modal('toggle');
                $('.file_folder_type').html($obj.attr('data-type'));
                $('#renameFolder').find('input.rename_folder_name').val($obj.find('.img-info').html());
                $('.old_name').val($obj.find('.img-info').html());
            } else if ($(this).find('a').attr('data-identity') == 'delete') {
                deletefolder($obj.find('.img-info').html());
            }
        } else if ($obj.attr('data-type') == 'Image') {
            if ($(this).find('a').attr('data-identity') == 'update') {
                setImgDetail($obj);
            } else if ($(this).find('a').attr('data-identity') == 'delete') {
                deleteImage($obj.find('a').attr('data-image_id'));
            }
        }
    });


    //set image detal by selecting image and clicking on i icon
    $('body').on('click', '.update_image', function () {
        $obj = $('body').find('.img-view.img-selected');
        if ($obj.length > 0) {
            setImgDetail($obj);
        } else {
            alert('Image Not Selected For Delete');
        }
    })

    //function to delete by selecting the files/folders and clicking from the top menu
    $('.delete').on('click', function () {
        $obj = $('body').find('.img-view.img-selected');
        if ($obj.length > 0) {
            if ($obj.attr('data-type') == 'Folder') {
                var imgName = $('.img-view.img-selected .img-info').html();
                deletefolder(imgName);
            } else if ($obj.attr('data-type') == 'Image') {
                deleteImage($obj.find('a').attr('data-image_id'));
            }
        } else {
            alert('Folder Not Selected for Delete');
        }
    });

    //deselect selected files/folders from viewport by pressing ESC button
    $(document).keyup(function (e) {
        if (e.keyCode == 27) {
            if ($('body').find('.img-view.img-selected').length > 0) {
                $('.img-view.img-selected').removeClass('img-selected');
            }
        }
    });


    //ajax request to rename folder
    $('#renameFolder').submit(function (e) {
        e.preventDefault();
        var folder_name = $('.rename_folder_name').val(),
                old_name = $('.old_name').val(),
                parent_folder = $('.path_name > span').html();
        $.ajax({
            url: baseURL.origin + sitename + '/admin/filemanager/renamefolder',
            method: "POST",
            data: {old_name: old_name, folder_name: folder_name, parent_folder: parent_folder},
            dataType: 'json',
            beforeSend: function () {
                $('.file-manager-window').append('<div class="loader-overlay center"></div>');
                setTimeout(function () {
                    return true;
                }, 800);
            },
            success: function (msg) {
                setTimeout(function () {
                    if (msg.status) {
                        insertHTML(msg.html, page);
                    }
                    $('.session-msg').html(msg.message);
                    $('.rename_folder').parent().modal('toggle');
                    $('.folder_name').val('');
                    $('.loader-overlay').fadeOut(function () {
                        $(this).remove();
                    });
                }, 800);
            }
        });
    });


    //open image upload modal by clicking on upload image button
    $('.md-effect-image').on('click', function () {
        $('#md-effect-image').modal('toggle');
        $('.upload_path_input').val($('.path_name > span').html());
    });

    //function to delete images
    function deleteImage(id) {
        $.ajax({
            url: baseURL.origin + sitename + '/admin/filemanager/delete_image',
            type: 'POST',
            data: {id: id},
            beforeSend: function () {
                var choice = confirm('Do you really want to delete');
                if (choice === true) {
                    $('.file-manager-window').append('<div class="loader-overlay center"></div>');
                    setTimeout(function () {
                        return true;
                    }, 800);
                } else {
                    return false;
                }
            },
            success: function (msg) {
                setTimeout(function () {
                    if (msg) {
                        $('.img-view.img-selected').fadeOut(function () {
                            $(this).remove();
                        });
                        $('.loader-overlay').fadeOut();
                    }
                }, 800);
            }
        });
    }

    //function to set images data in the form
    function setImgDetail(element) {
        var data = element.find('a').data();
        $('.redirect_url').val($('.path_name > span').html());
        $('.image img').attr('src', data.image);
        $('.url').val(data.image);
        $('.image_id').val(data.image_id);
        $('.image_name').val(data.image_name);
        $('.image_des').val(data.image_description);
        $('.image_caption').val(data.image_caption);
        $('.image_alt').val(data.image_alt_attribute);
        $('.image_delete').attr('data-image_id', data.image_id);
        $("#md-effect-image-detail").attr('class', 'modal fade').addClass(data.effect).modal('show')
    }

    //function to load fiels & folders
    function load_filemanager_img(path, page) {
        $.ajax({
            url: baseURL.origin + sitename + '/admin/filemanager',
            type: 'POST',
            data: {path: path, page: page},
            dataType: 'json',
            beforeSend: function () {
                $('.file-manager-window').append('<div class="loader-overlay center"></div>');
                setTimeout(function () {
                    return true;
                }, 800);
            },
            success: function (msg) {
                setTimeout(function () {
                    if (msg) {
                        insertHTML(msg, page);
                        goback();
                        $('.loader-overlay').fadeOut(function () {
                            $(this).remove();
                        })
                    }
                }, 800);
            }
        });
    }

    //function to check whether to display go back btn or not
    function goback() {
        if ($('body').find('.path_name').length > 0) {
            var path = $('.path_name > span').html();
            var res = path.split("/");
            if (res.length > 2) {
                $('.go_back_btn').show();
                $('.go_back_btn').attr('data-path', res.slice(0, -1).join('/'));
            } else {
                $('.go_back_btn').hide();
            }
        }
    }


    //ajax Success function to show images on viewport
    function insertHTML(msg, page) {
        if (page == 1) {
            $('.window-content').html('');
        }
        var count = msg.folders.length + msg.files.length;
        if (msg.folders.length > 0) {
            for (var i = 0; i < msg.folders.length; i++) {
                $('.window-content').append('<div class="img-view" data-type="Folder" data-path="' + msg.folders[i].location + '">\n\
                            <i class="fa fa-folder-open"></i>\n\
                            <div class="img-info">' + msg.folders[i].name + '</div></div>');
            }
        }
        if (msg.files.length > 0) {
            for (var i = 0; i < msg.files.length; i++) {
                $('.window-content').append('<div class="img-view" data-type="Image"><a href="javascript:void(0)"  data-image="' + msg.files[i].image_path + '" data-image_id="' + msg.files[i].id + '" data-image_name="' + msg.files[i].name + '" data-image_description="' + msg.files[i].description + '" data-image_caption="' + msg.files[i].caption + '" data-image_folder="' + msg.files[i].location + '" data-image_alt_attribute="' + msg.files[i].alt_attribute + '">\n\
                            ' + msg.files[i].image + '\n\
                            <div class="img-info">' + msg.files[i].name + '</div></a></div>');
            }
        }
        if (count == 24) {
            $('.window-content').append('<div class="img-view view_more">\n\
                                        <i class="fa fa-eye"></i>\n\
                                        <div class="img-info">View More</div>\n\
                                    </div>');
        }
        $('.footer-count').html($('.img-view').length);
        $('.loader-overlay').fadeOut(function () {
            $(this).remove();
        });
    }

    $('body').on('click', '.image_delete', function () {
        deleteImage($(this).attr('data-image_id'));
        $('#md-effect-image-detail').modal('toggle');
    });

    //function to delete folder
    function deletefolder(imgInfo) {
        $.ajax({
            url: baseURL.origin + sitename + '/admin/filemanager/deletefolder',
            type: 'POST',
            data: {name: imgInfo},
            beforeSend: function () {
                var choice = confirm('Do you really want to delete');
                if (choice === true) {
                    $('.file-manager-window').append('<div class="loader-overlay center"></div>');
                    setTimeout(function () {
                        return true;
                    }, 800);
                } else {
                    return false;
                }
            },
            success: function (msg) {
                setTimeout(function () {
                    if (msg) {
                        $obj.fadeOut(function () {
                            $(this).remove();
                        });
                        $('.loader-overlay').fadeOut(function () {
                            $(this).remove();
                        });
                    }
                });
            }
        });
    }
});