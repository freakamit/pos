$(function () {
var baseURL = document.location;
        var sitename = '/cheran';
        
        
        
        $('.add-college-data').on('click', function () {
$('.college-map-data-wrap').append('<div class="college-map-data">\n\
                    <div class="col-lg-10">\n\
                    <div class="form-group">\n\
                    <label class="control-label">College Name</label>\n\
                    <div class="" style="position: relative">\n\
                    <input type="text" id="param_college" name="params[college][]" label="College Name" class="form-control" parsley-required="true">\n\
                    </div>\n\
                    </div>\n\
                    </div>\n\
                    <div class="col-lg-2">\n\
                    <div class="form-group">\n\
                    <label class="control-label"></label>\n\
                    <div class="" style="position: relative">\n\
                    <button type="button" class="btn btn-danger remove-college-data">Remove</button>\n\
                    </div>\n\
                    </div>\n\
                    </div>\n\
                    <div class="col-lg-6">\n\
                    <div class="form-group">\n\
                    <label class="control-label">Latitude</label>\n\
                    <div class="" style="position: relative">\n\
                    <input type="text" id="param_latitude" name="params[latitude][]" label="Latitude" class="form-control" parsley-required="true">\n\
                    </div>\n\
                    </div>\n\
                    </div>\n\
                    <div class="col-lg-6">\n\
                    <div class="form-group">\n\
                    <label class="control-label">Longitude</label>\n\
                    <div class="" style="position: relative">\n\
                    <input type="text" id="param_longitude" name="params[longitude][]" label="Longitude" class="form-control" parsley-required="true">\n\
                    </div>\n\
                    </div>\n\
                    </div>\n\
                    <div class="clearfix"></div>\n\
                    </div><hr>');
        });
        $('body').on('click', '.remove-college-data', function(){
$obj = $(this).parent().parent().parent().parent();
        $obj.next().fadeOut(function(){
$(this).remove();
});
        $obj.fadeOut(function(){
        $(this).remove();
        });
        });
});