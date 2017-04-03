$(function() {
    var baseURL = document.location;
    var sitename = '/cheran';
    $('body').on('click', '.select-item a', function() {
        $('#career_id').val($(this).attr('data-id'));
    });
//Plugin Initialization
    $('.datepicker').datepicker();
    $('form').parsley();
//admission form student submit function
//    $('body').on('click', _apply_now', function(e) {
//        e.preventDefault();
//        var name = $('.admission_name').val();
//        var email = $('.admission_email').val();
//        var phone = $('.admission_phone').val();
//        var level_id = $('.select_course option:selected').val();
//        var checked = $('.checkbox').prop("checked");
//        if (checked) {
//            var agree_term = 1;
//        }
//        else {
//            agree_term = 0;
//        }
//        var upload_path = 'uploads/documents'
//        var message = $('.message').val();
//        var images = [];
//        $('#files li.success').each(function() {
//            var img = $(this).text();
//            images.push(img);
//        });
//        $.ajax({
//            url: baseURL.origin + sitename + '/frontend/process_admission',
//            type: "POST",
//            data: {name: name, email: email, phone: phone, level_id: level_id, agree_term: agree_term, message:message,images: images.toString(), path: upload_path},
//            dataType: 'html',
//            success: function(data) {
//                setTimeout(function() {
//                    $('.admission_message').show();
//                    if (data ==1) {
//                       $('.admission_message').html('You have successfully applied for course.');
//                    }else{
//                        $('.admission_message').html('Unable to submit. Please fill the form properly.');
//                    }
//                    $('.admisson_message').fadeOut(5000);
//                }, 800);
//            }
//        });
//
//    });


//for filtering level on selecting course on admission form
    $('.select_program_level').change(function() {
        var program_level_id = $(this).find('option:selected').val();
        $li = $('.select_course_admission');
        $.ajax({
            url: baseURL.origin + sitename + '/frontend/get_courses_admission_for_admission_form',
            type: "POST",
            data: {program_level_id: program_level_id},
            dataType: 'html',
            success: function(msg) {
                if (msg) {
                    $('.select_course_admission option').remove();
                    $('.select_course_admission').append(msg);
                } else {
                    alert('Data Not Available');
                    $('.loader-overlay').fadeOut();
                }
            },
        });
    });
//for filtering level on selecting course on admission form
    $('.select_course_admission').change(function() {
        var program_level = $('.select_program_level option:selected').val();
        if (program_level == '') {
            alert('Please Select Program Level');
        }

        var course_id = $(this).find('option:selected').val();
        $li = $('.select_level_admission');
        $.ajax({
            url: baseURL.origin + sitename + '/frontend/get_level_by_course',
            type: "POST",
            data: {program_level: program_level, course_id: course_id},
            dataType: 'html',
            beforeSend: function() {
                $li.append('<div class="loader-overlay"></div>');
                setTimeout(function() {
                    return true;
                }, 800);
            },
            success: function(msg) {
                if (msg) {
                    $('.select_level_admission option').remove();
                    $('.select_level_admission').append(msg);
                    $('.loader-overlay').fadeOut();
                } else {
                    alert('Data Not Available');
                    $('.loader-overlay').fadeOut();
                }
            },
        });
    });
//contact us form ajax 
    $('.contact-us').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: baseURL.origin + sitename + '/frontend/process',
            type: 'POST',
            data: $(this).serialize(),
            beforeSend: function() {
                $('.alert-message').show();
                if ($('.newsletter').prop('checked')) {
                    return true
                }
            },
            success: function(msg) {
                if (msg) {
                    $('.alert-message').html(msg);
                    //$('.alert-message').delay(3000).fadeOut(800);
                }
            }
        });
    });
//generate note based on programs
    $('body').on('change', '.select_level_admission', function() {
        $.ajax({
            url: baseURL.origin + sitename + '/frontend/get_admission_notice',
            type: 'POST',
            data: {id: $(this).val()},
            success: function(msg) {
                if (msg) {
                    $('.admission_note').html('<h3>Required Documents</h3>' + msg);
                }
            },
        });
    });
    $('.request_info_pdf').click(function() {
        var url = baseURL.origin + sitename + '/frontend/pdf/request_info';
        window.open(url, '_blank');
    });
//apply now course detail page
    $('body').on('click', '.btn-course-apply-now', function(e) {
        var url = baseURL.origin + sitename + '/frontend/get_admission_level_by_id';
        var level_id = $(this).attr('data-id');
        $.ajax({
            url: url,
            type: "POST",
            data: {id: level_id},
            dataType: 'json',
            success: function(msg) {
// console.log("Message")
                console.log('messageb' + msg);
                if (msg) {
                    for (i = 0; i < msg.length; i++) {
                        if (i == 0) {
                            $('.select_program_level option').remove();
                            $('.select_program_level').append(msg[0]);
                        }
                        if (i == 1) {
                            $('.select_course_admission option').remove();
                            $('.select_course_admission').append(msg[1]);
                        }
                        if (i == 2) {
                            $('.select_level_admission option').remove();
                            $('.select_level_admission').append(msg[2]);
                        }
                    }
                } else {
                    alert('Data Not Available');
                }
            },
        });
    });
   
});