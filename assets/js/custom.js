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

    $(".md-effect").click(function (event) {
        event.preventDefault();
        var id = $(this).attr('data-id');

        $('#modal_input_id').val(id)
        var data = $(this).data();
        $("#md-effect").attr('class', 'modal fade').addClass(data.effect).modal('show')
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
                    $('.search-list').html('<div class="loader-overlay-2"></div>');
                    $('.search-list').css({'height': '70px'});
                    setTimeout(function () {
                        return true;
                    }, 800);
                }
            },
            success: function (data) {
                if (data) {
                    setTimeout(function () {
                        $('.loader-overlay-2').fadeOut();
                        $('.search-list').css({'height': 'auto'});
                        $('.search-list').html(data);
                    }, 800);
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
            beforeSend: function () {
                $('.total-detail-wrap').append('<div class="loader-overlay-2"></div>');
                setTimeout(function () {
                    return true;
                }, 800);
            },
            success: function (msg) {
                if (msg) {
                    setTimeout(function () {
                        $('.loader-overlay-2').hide();
                        $('.grand-total').val(msg);
                        $('.grand-total-disp').html(msg);
                    }, 800);
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
            beforeSend: function () {
                $('.customer-list').html('<div class="loader-overlay-2"></div>');
                $('.customer-list').css({'height': '70px'});
                setTimeout(function () {
                    return true;
                }, 800);
            },
            success: function (msg) {
                if (msg) {
                    setTimeout(function () {
                        $('.loader-overlay-2').fadeOut();
                        $('.customer-list').css({'height': 'auto'});
                        $('.customer-list').html(msg);
                    }, 800);
                }
            }
        });
    });

    $('body').on('change', '.tax_checkbox,.service_checkbox', function () {
        cart_final_price();
    });

    $('body').on('click', '.customer-list ul li', function () {
        $('.customer-list').html('');
        $('.customer').val('');

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

    $('.received_amt').keyup(function () {
        var grand_total = $('.grand-total').val();
        var change;

        change = $(this).val() - grand_total;

        $('.change_amt').val(change.toFixed((2)));

    });

    $('.confirm-order').on('click', function () {
        var error = '';
        if ($('.order_list tr').length == 0) {
            error += 'No Item on the list<br>';
        }
        if ($('.received_amt').val() == '') {
            error += 'Please Enter amount received';
        }
        if (error == '') {
            $('.modal-title').html('Confirm Order');
            $('.alert-message-modal').html('Are you sure you want to place an order?');
            $('#md-confirm').modal('show');
        } else {
            $('.modal-title').html('Error');
            $('.alert-message-modal').html(error);
            $('#md-alert').modal('show');
        }
    });

    $('body').on('click', '.confirm_cancel', function () {
        $('#md-confirm').hide();
    });

    $('body').on('click', '.confirm_btn', function (e) {
        e.preventDefault();

        $.ajax({
            url: baseURL.origin + sitename + '/admin/checkout/success',
            data: $('#order_form').serialize(),
            type: 'POST',
            success: function (msg) {
                if (msg) {
                    location.reload();
                }
            }
        });
    });

});