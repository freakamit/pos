$(function () {
    var baseURL = document.location;

    var sitename = '/pos';

    function checkClass($className) {
        return $('body').find($className).length;
    }

    function checkId($idName) {
        return $('body').find($idName).length;
    }

    $('body').on('change', '.nav_group', function () {
        var val = $(this).val();
        if (val == 'others') {
            $(this).next().html('<input type="text" class="form-control" name="navigation_group">');
        }
    });

    $('body').on('change', '.link_type_option', function () {
        var val = $(this).val();
        $obj = $('#link_type').parent().parent();

        $.ajax({
            url: baseURL.origin + sitename + '/admin/navigation/link_type_request/' + val,
            data: '',
            type: 'POST',
            dataType: 'json',
            beforeSend: function () {
                $obj.css({'position': 'relative'});
                $obj.find('label').prepend('<div class="loader-overlay inline"></div>');
                setTimeout(function () {
                    return true;
                }, 800);
            },
            success: function (msg) {
                setTimeout(function () {
                    $obj.html('');
                    $(msg).appendTo($obj);
                    $('.selectpicker').selectpicker();
                }, 800);
            }
        });
    });

    //function to display product attribute field
    $('body').on('change', '.attr_set', function (event) {
        var val = $(this).val();
        if (checkClass('.edit-attrib') > 0) {
            $('.edit-attrib-btn').remove();
            $('.edit-attrib').parent().parent().remove();
        }
        $obj = $('#attr_list').parent().parent();


        $.ajax({
            url: baseURL.origin + sitename + '/admin/product/product_attr_request/' + val,
            type: 'POST',
            dataType: 'json',
            beforeSend: function () {
                $obj.css({'position': 'relative'});
                $obj.find('label').prepend('<div class="loader-overlay inline"></div>');
                setTimeout(function () {
                    return true;
                }, 800);
            },
            success: function (msg) {
                setTimeout(function () {
                    $obj.html('');
                    $obj.append(msg);
                }, 800);
            }
        });
    });
    var prod_attr = 1;
    $('.add_product_type').live('click', function () {
        var attr_id = $(this).attr('data-id');
        var edit_count = $(this).attr('data-count');
        if (jQuery.type(edit_count) === "undefined") {

        } else {
            prod_attr = edit_count;
            prod_attr++;
            $('.add_product_type').attr('data-count', prod_attr);
        }
        var html = '';
        html += '<div class="product_type_wrap_' + prod_attr + '">';
        html += '<div class="col-md-4"><input type="text" placeholder="Enter Product Type" class="form-control attr_list" parsley-required="true" name="attr[' + attr_id + '][option][]" value=""></div>';
        html += '<div class=col-md-4><input type="text" placeholder="Price Amount" class="form-control attr_list" parsley-required="true" name="attr[' + attr_id + '][value][]" value=""></div>';
        html += '<div class=col-md-4><span class="btn btn-danger remove_product_type" data-id = "' + prod_attr + '">Remove</span></div>';
        html += '</div>';

        if (jQuery.type(edit_count) === "undefined" || edit_count == '') {
            prod_attr++;
            $('.product_type_wrap').append(html);
        } else {
            $('.product_type_wrap').last().append(html);
        }
    });

    $('.remove_product_type').live('click', function () {
        var id = $(this).attr('data-id');
        $('.product_type_wrap_' + id).remove();
    });


    $('.slug_title').on('keyup', function () {
        var value = $(this).val();

        var slug = convert_to_slug(value);

        $('.slug').val(slug);
        $('.nav_url').val(slug);

    });

    $('.ad_type').on('change', function () {

        var val = $(this).val();
        if (val == 1) {
            $('.ad_image').show();
            $('.google_ad').hide();
        } else if (val == 2) {
            $('.ad_image').hide();
            $('.google_ad').show();
        } else {
            $('.ad_image,.google_ad').hide();
        }

    });

    $(window).load(function () {
        var val = $('.ad_type').val();
        if (val == 1) {
            $('.ad_image').show();
            $('.google_ad').hide();
        } else if (val == 2) {
            $('.ad_image').hide();
            $('.google_ad').show();
        } else {
            $('.ad_image,.google_ad').hide();
        }
    });

    function convert_to_slug(str) {
        var $slug = '';
        var trimmed = $.trim(str);
        $slug = trimmed.replace(/[^a-z0-9-]/gi, '_').replace(/-+/g, '_').replace(/^-|-$/g, '');
        return $slug.toLowerCase();
    }

    $('.multiselect').multiSelect();
    $('#data-table').dataTable({
        "aoColumnDefs": [{
            "bSortable": false,
            "aTargets": ["no-sort"]
        }]
    });

    $('table[data-provide="data-table"]').dataTable();

    $(".sortable").sortable({
        update: function () {
            var i = 0;
            var count = 1;

            var object = {};

            $obj = $(this).children('tr');
            $obj.each(function () {
                var id = $(this).attr('data-value');
                object[i] = [['id', id], ['position', count]]
                i++;
                count++;
            });

            $.ajax({
                type: 'POST',
                url: baseURL.origin + sitename + '/admin/dashboard/slider_sort',
                data: {'position': object},
                success: function (msg) {
                    if (msg == 'success') {
                        $('.slider_pos_apply').show();
                    }
                }
            });

        }
    });

    $('body').on('click', '.slider_pos_apply', function () {
        location.reload(true);
    });
    $("#sortable").disableSelection();

    var ckeditor = checkId('.ckeditor');
    if (ckeditor) {
        $('.ckeditor').each(function () {
            var id = $(this).attr('id');
            CKEDITOR.replace(id,
                {
                    startupFocus: false,
                    uiColor: '#FFFFFF'
                });
        });
    }

    $('body').on('change', '.country', function () {
        $div = $('.states').parent();

        $.ajax({
            url: baseURL.origin + sitename + '/admin/users/get_state_city/states/' + $(this).val(),
            type: 'POST',
            dataType: 'json',
            beforeSend: function () {
                $div.append('<div class="loader-overlay"></div>');
                setTimeout(function () {
                    return true;
                }, 800);
            },
            success: function (msg) {
                setTimeout(function () {
                    if (msg.status) {
                        $('.states').remove();
                        $div.prepend(msg.value);
                        $('.selectpicker').selectpicker();
                        $('.loader-overlay').fadeOut();
                    }
                }, 800);
            }
        });
    });

    $('body').on('change', '.states', function () {
        $div = $('.city').parent();
        $.ajax({
            url: baseURL.origin + sitename + '/admin/users/get_state_city/city/' + $(this).val(),
            type: 'POST',
            dataType: 'json',
            beforeSend: function () {
                $div.append('<div class="loader-overlay"></div>');
                setTimeout(function () {
                    return true;
                }, 800);
            },
            success: function (msg) {
                setTimeout(function () {
                    if (msg.status) {
                        $('.city').remove();
                        $div.prepend(msg.value);
                        $('.selectpicker').selectpicker();
                        $('.loader-overlay').fadeOut();
                    }
                }, 800);
            }
        });
    });

    $('body').on('change', '.ad_position', function () {
        var pos = $(this).val();
        if (pos == 2) {
            $('.help-block').html('[Dimension: {Width: 614px | Height: 486px}]');
        } else if (pos == 4) {
            $('.help-block').html('[Dimension: {Width: 1270px | Height: 200px}]');
        } else if (pos == 3) {
            $('.help-block').html('[Dimension: {Width: 790px | Height: 200px}]');
        } else {
            $('.help-block').html('[Dimension: Any]');
        }
    });

    $('body').on('change', '.nav_group', function () {

        $div = $('.nav_parent').parent();

        $.ajax({
            url: baseURL.origin + sitename + '/admin/navigation/get_nav_group/' + $(this).val(),
            dataType: 'json',
            beforeSend: function () {
                $div.append('<div class="loader-overlay"></div>');
                setTimeout(function () {
                    return true;
                }, 800);
            },
            success: function (msg) {
                setTimeout(function () {
                    if (msg.status) {
                        $('.nav_parent').remove();
                        $div.prepend(msg.value);
                        $('.selectpicker').selectpicker();
                        $('.loader-overlay').fadeOut();
                    }
                }, 800);
            },
        });
    });

    //notification review from notificaiton list of logged in users
    $('span.tooltip-area').on('click', 'a.notification_review', function (e) {
        e.preventDefault();
        var userId = $('.active_user_id').val();
        var notification_id = $(this).attr('class').split(' ')[4].split('_')[2].toString();
        var url_link = $('.notification_link_' + notification_id).text();
        var redirect_url = baseURL.origin + sitename + url_link;
        $.ajax({
            url: baseURL.origin + sitename + '/admin/notification/change_status',
            data: {user_id: userId, id: notification_id},
            type: 'POST',
            success: function (data) {
                window.location.href = redirect_url;
            },
        });
    });

    $('.confirm_review').click(function (e) {
        e.preventDefault();
        var userId = $(this).attr('data-user');
        var notification_id = $(this).attr('class').split(' ')[4].split('_')[2].toString();
        var url_link = $(this).attr('href');
        var redirect_url = url_link;
        $.ajax({
            url: baseURL.origin + sitename + '/admin/notification/change_status',
            data: {user_id: userId, id: notification_id},
            type: 'POST',
            success: function (data) {
                window.location.href = redirect_url;
            },
        });

    });

    $(".md-effect").click(function (event) {
        event.preventDefault();
        var id = $(this).attr('data-id');

        $('#modal_input_id').val(id)
        var data = $(this).data();
        $("#md-effect").attr('class', 'modal fade').addClass(data.effect).modal('show')
    });


    $(".geo-location").click(function (event) {
        event.preventDefault();
        var id = $(this).attr('data-id');
        var latitude = $(this).attr('data-latitude');
        $('#modal_input_id').val(id)
        var data = $(this).data();
        if (latitude == '') {
            $("#geo-location").attr('class', 'modal fade').addClass(data.effect).modal('show');
            getLocation();
        }
    });

    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition);
        } else {
            $('#geo-message').html("Geolocation is not supported by this browser.");
        }
    }

    function showPosition(position) {
        var latitude = position.coords.latitude;
        var longitude = position.coords.longitude;
        $('.modal-scrollable #geo-location .geolocation_latitude').val(latitude);
        $('.modal-scrollable #geo-location .geolocation_longitude').val(longitude);
    }

    $('body').on('change', '.order_status_switch', function () {
        var status = 0;
        if ($(this).attr('checked') == 'checked') {
            status = 1;
        }
        $.ajax({
            url: baseURL.origin + sitename + '/admin/dashboard/order_status',
            data: {status: status},
            type: 'POST',
            beforeSend: function () {
                var choice = confirm('Are You Sure You Want to Disable Order?');

                if (choice == true) {
                    return true;
                } else {
                    return false;
                }
            }
        });
    });

    $('body').on('click', '.delete-btn', function () {
        $('#md-delete').modal('toggle');
        var url = $(this).attr('data-url');
        $('.delete_confirm').attr('href', url);
    });

    $('body').on('click', '.delete_confirm', function () {
        var url = $(this).attr('href');
        document.location.href = url;
    });

    $('body').on('click', '.delete_cancel', function () {
        $('#md-delete').modal('hide');
    });

    $('body').on('change', '.module_status', function () {
        change_module_status($(this).data('id'));
    });
    $('body').on('click', '.change_module_status_btn', function () {
        change_module_status($(this).data('id'));
    });
    function change_module_status(id) {
        $.ajax({
            url: baseURL.origin + sitename + '/admin/modules_management/change_status/' + id,
            beforeSend: function () {
                var choice = confirm('Do you really want to change status');
                if (choice == true) {
                    return true;
                } else {
                    return false;
                }
            },
            success: function (msg) {
                if (msg) {
                    document.location.reload();
                }
            }
        });
    }

    $('.check_all').on('click', function (e) {
        if ($(this).is(':checked')) {
            $('.checkboxopt').prop('checked', true);
        } else {
            $('.checkboxopt').prop('checked', false);
        }
    });

    $('.view_more_btn').click(function () {
        $(this).parent().find('.desc_div').css({
            'height': 'auto',
            'overflow': 'visible'
        });
        $(this).hide();
    });

    check_table_list();
    $('body').on('keyup', '.item_name_order', function () {
        var val = $(this).val();

        $.ajax({
            url: baseURL.origin + sitename + '/admin/order/get_item',
            data: {item: val},
            type: 'POST',
            beforeSend: function () {
                if (val == '') {
                    $('.search-list').html('');
                    return false;
                } else {
                    $('.search-list').html('<div class="loader-overlay"></div>');
                    setTimeout(function () {
                        return true;
                    }, 800);
                }
            },
            success: function (data) {
                if (data) {
                    $('.search-list').html(data);
                }
            }
        });
    });

    $('body').on('click', '.search-list ul li', function () {
        $('.search-list').html('');
        $('.item_name_order').val('');

        var id = $(this).find('a').attr('data-id');
        $.ajax({
            url: baseURL.origin + sitename + '/admin/cart/add',
            type: 'POST',
            data: {id: id},
            dataType: 'json',
            success: function (data) {
                if (data) {
                    $('.order_list').html(data.html);
                    cart_final_price();
                }
            },
        })

    });

    function check_table_list() {
        var count = $('.order_list tr').length;
        if (count == 0) {
            $('.order_list').append('<tr class="empty_list"><td colspan="6"><strong>No Item on the list</strong></td></tr>');
        } else {
            $('.empty_list').remove();
        }
    }

    $('body').on('click', '.remove_item', function () {
        $obj = $(this);
        $.ajax({
            url: baseURL.origin + sitename + '/admin/cart/remove',
            data: {rowid: $(this).attr('data-id')},
            type: 'POST',
            success: function (msg) {
                if (msg == 'success') {
                    $obj.parent().parent().fadeOut(function () {
                        $(this).remove();
                        cart_final_price();
                    });
                }
            }
        });
    });

    function cart_final_price() {
        var tax, service;
        if ($('.tax_checkbox').prop('checked')) {
            tax = $('.tax').val();
        } else {
            tax = 0;
        }
        if ($('.service_checkbox').prop('checked')) {
            service = $('.service').val();
        } else {
            service = 0;
        }
        $.ajax({
            url: baseURL.origin + sitename + '/admin/cart/cart_final_price',
            type: 'POST',
            data: {
                discount: $('.discount').val(),
                delivery: $('.delivery').val(),
                tax: tax,
                service: service
            },
            success: function (msg) {
                if (msg) {
                    $('.grand-total').val(msg);
                    $('.grand-total-disp').html(msg);
                }
            }
        });
    }

    $('body').on('keypress', '.qty-input', function (e) {
        if (e.keyCode == 13) {
            $.ajax({
                url: baseURL.origin + sitename + '/admin/cart/update',
                data: {qty: $(this).val(), key: $(this).attr('data-key')},
                type: 'POST',
                dataType: 'json',
                success: function (msg) {
                    if (msg) {
                        $('.order_list').html(msg.html);
                        cart_final_price();
                    }
                }
            });
        }
    });

    $('#order_form').on('submit', function (e) {
        e.preventDefault();
    });

    $('body').on('keyup', '.discount, .tax, .delivery, .service', function () {
        cart_final_price();
    });

    $('body').on('keyup', '.customer', function () {
        var val = $(this).val();

        $.ajax({
            url: baseURL.origin + sitename + '/admin/order/customer_list',
            data: {name: val},
            type: 'POST',
            success: function (msg) {
                if (msg) {
                    $('.customer-list').html(msg);
                }
            }
        });
    });

    $('body').on('change', '.tax_checkbox,.service_checkbox', function () {
        cart_final_price();
    });

    $('body').on('click', '.customer-list ul li', function () {
        $('.customer-list').html('');

        var id = $(this).find('a').attr('data-id');

        $.ajax({
            url: baseURL.origin + sitename + '/admin/order/get_customer/' + id,
            success: function (msg) {
                if (msg) {
                    $('.customer_detail').html(msg);
                }
            }
        });
    });

    $('.payment_type').on('change', function () {
        var val = $(this).val();

        switch (val) {
            case 'CASH':
                break;
            case 'CARD':
                $('.payment_type_ref').html('<div class="form-group">' +
                    '<label class="control-label">Card Number:</label>' +
                    '<div><input type="text" name="payment_type_ref" class="form-control" placeholder="Please Enter Card Number" required></div>' +
                    '</div>');
                break;
            case 'PAYTM':
                $('.payment_type_ref').html('<div class="form-group">' +
                    '<label class="control-label">PayTM Number:</label>' +
                    '<div><input type="text" name="payment_type_ref" class="form-control" placeholder="Please Enter PayTm Number" required></div>' +
                    '</div>');
                break;
        }
    });


    $('.confirm-order').on('click', function () {
        $.ajax({
            url: baseURL.origin + sitename + '/admin/checkout/success',
            data: $('#order_form').serialize(),
            type: 'POST',
            beforeSend: function () {
                console.log($('.order_list tr').length);
            },
            success: function () {

            }
        });
    });
});