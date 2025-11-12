var lang = {"field_required":"This field is required","provide_valid_email":"Please enter a valid email address","password_validation_msg":"Your password is not secure.","pwd_not_match":"Password did not match.","delete_message":"Are you sure to delete this record?","no_record_selected":"Please select atleast one record.","remove":"remove","cancel":"Cancel"};

$(".tag-it").tagit();
$(".tag-it-capital").tagit({
    allowSpaces: true,
    preprocessTag: function(val) {
      if (!val) { return ''; }
      return val[0].toUpperCase() + val.slice(1, val.length);
    }
});
// $(".tag-it-capital").tagit();

$('input[name="daterange"]').daterangepicker({
    autoUpdateInput: false,
    locale: {
        format: 'DD/MM/YYYY', // Set date format
        cancelLabel: 'Clear'
    }
});

$('input[name="daterange"]').on('apply.daterangepicker', function(ev, picker) {
    $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
});

$('input[name="daterange"]').on('cancel.daterangepicker', function(ev, picker) {
    $(this).val('');
});

$('#print-orders').on('click', function() {
    if(!confirm('Are you sure to the selected range orders?')) return false;
    $(this).prop('disabled', true);
    window.open(admin_url + '/orders/bulk-export?d=' + $('input[name="daterange"]').val());
});

$.validator.setDefaults({ 
    errorClass: 'text-danger',
    errorElement: 'small',
    errorPlacement: function(error, element) {
        inputGroup = element.parent('.input-group');
        if(inputGroup.length > 0)
        {
            inputGroup.next('small').remove();
            error.insertAfter( inputGroup );
        }
        else
        {
            element.next('small').remove();
            error.insertAfter( element );   
        }

    },
    success: function(label) {
        label.remove();
    }
});

$.validator.addMethod("pwcheck", function(value) {
    return /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*#?&])[A-Za-z\d$@$!%*#?&]{8,}$/.test(value);
});
$.extend($.validator.messages, {
    required: 'This field is required',
    email: 'Email address is invalid'
});

var urlregx = new RegExp("^[http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/|www\.)[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)]?$");

$.validator.addMethod("urlcheck", function(value) {
 if(value != ''){
    return urlregx.test(value);
}
return true;
});

// Generate a password string
function random_string(limit){
    limit = limit > 0 ? limit : 10;
    var possible = '';
    possible += 'abcdefghijklmnopqrstuvwxyz';
    possible += 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    possible += '0123456789';
    possible += '![]{}()%&*$#^<>~@|';

    var text = '';
    for(var i=0; i < limit; i++) {
        text += possible.charAt(Math.floor(Math.random() * possible.length));
    }
    return text;
}


if($('#editor1').length) {   
    init_editor('#editor1');
}
if($('#editor2').length) {   
    init_editor('#editor2');
}

function set_notification(type, text, placementFrom, placementAlign, animateEnter, animateExit)
{

    if(type == 'success')
        var colorName = 'bg-green';
    else if(type == 'error')
        var colorName = 'bg-red';
    else
        var colorName = 'bg-black';

    if (!placementFrom) { placementFrom = 'bottom'; }
    if (!placementAlign) { placementAlign = 'right'; }
    if (!animateEnter) { animateEnter = 'animated fadeInDown'; }
    if (!animateExit) { animateExit = 'animated fadeOutUp'; }


    var allowDismiss = true;

    $.notify({
        message: text
    },
    {
        type: colorName,
        allow_dismiss: allowDismiss,
        newest_on_top: true,
        timer: 500000,
        offset: {
            "x": 30,
            "y": 50
        },
        placement: {
            from: placementFrom,
            align: placementAlign
        },
        animate: {
            enter: animateEnter,
            exit: animateExit
        },
        template: '<div data-notify="container" class="bootstrap-notify-container alert alert-dismissible {0} ' + (allowDismiss ? "p-r-35" : "") + '" role="alert">' +
        '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
        '<span data-notify="icon"></span> ' +
        '<span data-notify="title">{1}</span> ' +
        '<span data-notify="message">{2}</span>' +
        '<div class="progress" data-notify="progressbar">' +
        '<div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +
        '</div>' +
        '<a href="{3}" target="{4}" data-notify="url"></a>' +
        '</div>'
    });
}

if($('#success_alert').length)
{
    msg = $('#success_alert').html().trim();
    set_notification('success', msg);
}
if($('#error_alert').length)
{
    msg = $('#error_alert').html().trim();
    set_notification('error', msg);
}

$(".owl-carousel").owlCarousel({
        items: 1,
        loop: true,
        autoplay: false,
        autoplayTimeout: 3000,
        autoplaySpeed: 1000,
        dots: true,
        nav: false,
        navText: ['<i class="fal fa-chevron-left"></i>','<i class="fal fa-chevron-right"></i>']
    });
$('[required]').prev('.form-control-label').append('<span class="text-danger">*</span>');

/** Select Boxes **/
function initSelectpicker(sel) {
    $(sel).not('.no-selectpicker').selectpicker({
        liveSearch: true,
        liveSearchStyle: 'contains',
        showContent: true,
        dropdownAlignRight: 'auto'
    })
}
initSelectpicker('select');
/** Select Boxes **/

/** Password **/
$('body').on('click', '.passwordGroup .regeneratePassword', function() {
    var input = $(this).parents('.passwordGroup').find('input');
    input.val(random_string(20));
});

$('body').on('click', '.passwordGroup .viewPassword', function() {
    var input = $(this).parents('.passwordGroup').find('input');
    if(input.attr('type') == 'password')
    {
        input.attr('type', 'text');
        $(this).children('i').removeClass('fa-eye').addClass('fa-eye-slash');
    }
    else
    {
        input.attr('type', 'password');   
        $(this).children('i').removeClass('fa-eye-slash').addClass('fa-eye');
    }
});

$('#sendPasswordEmail').on('change', function(){
    if($(this).is(':checked'))
    {
        $('.passwordGroup input').removeAttr('required');
        $('.passwordGroup').addClass('d-none');
    }
    else
    {
        $('.passwordGroup input').attr('required', 'required');
        $('.passwordGroup').removeClass('d-none');   
    }
});
/** Password **/

/** Filter Dropdown **/
$('body').on('click', '.filter-dropdown .dropdown-btn', function() {
    $(this).next().toggle();
});
$('body').on('click', '.filter-dropdown .closeit', function() {
    $(this).parents('.dropdown-menu').hide();
});
/** Filter Dropdown **/

$('body').on('click', '.row-edit', function(){

    tr = $(this).parents('tr');
    tr.find('.e-title').toggle();
    tr.find('.e-edit').toggle();
});
$('body').on('click', '.e-edit button', function(){
    tr = $(this).parents('tr');
    edit = $(this).parents('.e-edit');
    $.ajax({
        url: edit.attr('action'),
        type: 'post',
        data: {title: edit.find('input').val()},
        success: function(resp)
        {
            if(resp)
            {
                resp = JSON.parse(resp);
                tr.find('.e-title').html(resp.title);
                tr.find('.e-title').show();
                tr.find('.e-edit').hide();
            }
        }
    });
});

function bulk_actions(url, action)
{
    if($('table.listing-table').find('.listing_check:checked').length > 0)
    {
        if(action == 'delete')
            var confirmation = lang.delete_message;
        else
            var confirmation = 'Are you sure to perform this action?';

        if(confirm(confirmation))
        {
            ids = [];
            $('table.listing-table').find('.listing_check').each(function(){
                if($(this).is(':checked'))
                    ids.push($(this).val());
            });
            if(ids.length > 0)
            {
                $.ajax({
                    url: url,
                    type: 'post',
                    data: {
                        ids : ids,
                        _token: csrf_token()
                    },
                    success: function(resp){
                        if(resp.status == 'success')
                        {
                            window.location.reload();
                        }
                        else
                        {
                            set_notification('error', resp.message);
                        }
                    }
                })
            }
            else
            {
                set_notification('error', lang.no_record_selected);
            }
        }
    }
    else
    {
        set_notification('error', lang.no_record_selected);
    }
}

function switch_action(url, that)
{
    $.ajax({
        url: url,
        type: 'post',
        data: {
            flag: $(that).is(':checked') ? 1 : 0,
            _token: csrf_token()
        },
        success: function(resp){

        }
    })
}

function updateSelectOptions(select, options, value)
{
    html = '<option value=""></option>';
    for(i in options)
    {
        html += '<option'+(value && value == options[i].id ? ' selected' : '')+' value="'+options[i].id+'">'+options[i].title+'</option>';
    }
    select.html(html);
    select.selectpicker('refresh');
}

function save_product_category(that)
{

    if(!$(that).is(':disabled'))
    {
        box = $(that).parents('.category-group');
        buttons = $(that).parent().children();
        input = $(that).parents('.category-add').find('input[type=text]');
        title = input.val();
        select = box.find('.category-select select');

        buttons.prop('disabled', true);
        $.ajax({
            url: site_url + 'products/add_category',
            data: {title: title},
            type: 'post',
            success: function(resp){
                if(resp)
                {
                    updateSelectOptions(select, resp.categories, resp.insert_id);
                    toggleCategoryInline(box);
                    set_notification('success', resp.message);
                }
                else
                {
                    set_notification('error', resp.message);
                }
            }
        })
    }
}
function toggleCategoryInline(categoryGroup)
{
    categoryGroup.find('.category-add input').val('');
    categoryGroup.find('.category-add button').prop('disabled', false);
    categoryGroup.find('.category-select').toggleClass('hidden');
    categoryGroup.find('.category-add').toggleClass('hidden');
    if(!categoryGroup.find('.category-add').hasClass('hidden')) {
        categoryGroup.find('.category-add input').focus();
    }
    else {
        categoryGroup.find('.category-select select').focus();
    }
}

function render_add_edit_item_html(data)
{
    html = '';
    html += '<tr class="item-row" data-id="'+data.product.id+'">'+
    '<td width="5%"><i class="material-icons move_lines">drag_indicator</i><input type="hidden" name="data['+data.product.id+'][product_id]" value="'+data.product.id+'"></td>'+
    '<td width="45%"><p>'+data.product.title+'<input type="hidden" name="data['+data.product.id+'][subject]" value="'+data.product.title+'"></p><p><small>'+data.product.description+'</small><input type="hidden" name="data['+data.product.id+'][description]" value="'+data.product.description+'"></p></td>'+
    '<td width="20%"><div class="input_n_dropdown"><input type="number" class="form-control amount" name="data['+data.product.id+'][amount]" value="1.00"><div class="btn-group-vertical"><input type="hidden" class="form-control unit" name="data['+data.product.id+'][unit]" value="'+data.product.measurement_unit+'"><div class="btn-group"><button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">'+data.product.measurement_unit+'<span class="caret"></span></button><ul class="dropdown-menu">';
    for(k in data.measurements){
        html += '<li><a href="javascript:;" data-value="'+data.measurements[k].unit+'">'+data.measurements[k].name + '(' + data.measurements[k].unit + ')'+'</a></li>'
    }
    html += '</ul></div></div></div></td><td width="15%"><input type="number" class="form-control price" name="data['+data.product.id+'][price]" placeholder="'+data.currency_symbol+'" value="'+data.product.price+'"></td>'+
    '<td class="text-center"  width="10%">'+data.currency_symbol+' <span class="row_total">0</span></td><th width="5%" class="text-right" data-toggle="tooltip" data-title="'+lang.remove+'"><a href="javascript:;" onclick="remove_add_edit_entry(this)" class="col-pink" ><i class="material-icons">cancel</i></a></th></tr>';
    return html;
}

var itemSelectReq;
function make_add_edit_item_entry(id)
{
    if(itemSelectReq && itemSelectReq.readyState != 4)
        itemSelectReq.abort();

    itemSelectReq = $.ajax({
        url: site_url + 'products/get_details/' + id,
        success: function(resp){
            $('._add_edit_items table tbody tr.empty').remove();
            html = render_add_edit_item_html(resp);
            $('._add_edit_items table tbody').prepend(html);
            $('#item_select').selectpicker('val', '');
            $('._add_edit_items table tbody [data-toggle=tooltip]').tooltip();
            calculate_total();
        }
    });
}

function remove_add_edit_entry(that)
{
    $(that).parents('.item-row').remove();
}

function calculate_total()
{
    var row_total;
    $('._add_edit_items table tbody tr.item-row').each(function(){
        amount = $(this).find('.amount').val() * 1;
        price = $(this).find('.price').val() * 1;
        $(this).find('.row_total').text(amount * price);
        row_total += amount * price;
    });
}

// Add the following code if you want the name of the file appear on select
$(".custom-file-input").on("change", function() {
    var fileName = $(this).val().split("\\").pop();
    $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
});

$(function () {
    $('[data-toggle=tooltip]').tooltip();
    $('.form-validation').validate();
    $('#sign_in').validate();
    $('.add_product_form').validate();
    $('#forgot_password').validate();
    $('#site_settings').validate({
     rules: {
        "stripe_key": {
            required: true
        },
        "stripe_public_key": {
            required: true
        },
        "service_charge_markup":{
            required: true
        }
    }

});


    $('#isAdmin').on('click', function(){
        if($(this).is(':checked'))
            $('#permissionTable').addClass('d-none')
        else
            $('#permissionTable').removeClass('d-none')
    })
    $('#recover-password').validate({
        rules: {
            "new_password": {
                required: true,
                pwcheck: true
            },
            confirm_password : {
                equalTo : "[name=new_password]"
            }
        },
        messages: {
            'new_password' : {
                pwcheck: lang.password_validation_msg
            },
            'confirm_password' : {
                equalTo: lang.pwd_not_match
            }
        }
    });
    $('#edit_profile').validate();   
    $("#change_password").validate({
        rules: {
            "new_password": {
                required: true,
                pwcheck: true
            },
            confirm_password : {
                equalTo : "[name=new_password]"
            }
        },
        messages: {
            'new_password' : {
                pwcheck: lang.password_validation_msg
            },
            'confirm_password' : {
                equalTo: lang.pwd_not_match
            }
        }
    }); 
    

    $('#add_staff').validate({
        rules: {
            password: {
                pwcheck: true
            }
        },
        messages: {
            password : {
                pwcheck: lang.password_validation_msg
            }
        }
    });
    $('#reset_password').validate({
        rules: {
            password: {
                pwcheck: true
            }
        },
        messages: {
            password : {
                pwcheck: lang.password_validation_msg
            }
        }
    });

    $('body').on('click', '.listing-table .mark_all', function(){
        $('.listing-table .listing_check').prop('checked', $(this).is(':checked'));
    });
    $('body').on('click', '._delete', function(){
        if(!confirm(lang.delete_message))
            return false;
        window.location.href = $(this).attr('data-link');
    });

    // Enable in case ajax paniations working
    if($('.listing-table .ajaxPaginationEnabled').length > 0)
    {
        var tableReq;

        function get_table_listing(table, data)
        {
            if(!table.hasClass('processing'))
            {
                url = table.find('.loader').attr('data-url');
                page = table.find('.loader').attr('data-page');
                if(page != "")
                {
                    table.find('.loader').removeClass('hidden');
                    table.addClass('processing');
                    next_page = (page*1+1);
                    
                    search = table.parents('.listing-block').find('.listing-search').val();
                    sort = table.find('thead i.active').length ? table.find('thead i.active').attr('data-field') : '';
                    direction = table.find('thead i.active').length ? table.find('thead i.active').attr('data-sort') : '';

                    filters = $('#filters-form').length ? $('#filters-form').serialize() : '';

                    data = 'page=' + next_page + '&sort=' + sort + '&direction=' + direction + '&search=' + search + (filters ? '&' + filters : '');
                    if(tableReq && tableReq.readyState != 4)
                    {
                        tableReq.abort();
                    }
                    tableReq = $.ajax({
                        url: url,
                        data: data,
                        success: function(resp){
                            table.find('tbody').append(resp.html);
                            table.find('.loader').attr('data-page', resp.page);
                            table.find('.loader').attr('data-counter', resp.counter);
                            table.find('.loader').attr('data-total', resp.count);
                            if(resp.pagination_counter >= resp.count)
                            {
                                table.find('.loader').addClass('hidden');
                                table.find('.loader').attr('data-page', '');
                            }
                            table.removeClass('processing');
                        }
                    });
                }
            }
        }


        $(window).scroll(function(){
           if( $(this).scrollTop() > ($(document).height()-$(window).height() - 50) )
           {
            table = $('.listing-table');
            get_table_listing(table);
        }
    });
    }

    //Sorting Tables
    $('.listing-table thead th:not(:first-child)').on('click', function(){
        field = $(this).find('i').attr('data-field');
        sort = $(this).find('i').attr('data-sort');
        direction =  sort && sort == 'asc' ? 'desc' : 'asc';
        icon = sort && sort == 'asc' ? 'fa-sort-up' : 'fa-sort-down';

        if($('.listing-table .ajaxPaginationEnabled').length > 0 && $(this).find('i').length > 0)
        {
            // ajax pagination table  case
            $(this).parents('thead').find('i').removeClass('active').removeClass('fa-sort').removeClass('fa-sort-up').removeClass('fa-sort-down');
            $(this).parents('thead').find('i').addClass('fa-sort');
            $(this).find('i').attr('data-sort', direction);
            $(this).find('i').addClass('active');
            $(this).find('i').addClass(icon);
            loader = $(this).parents('table').find('tfoot .loader')
            loader.attr('data-page', 0);
            loader.removeClass('hidden');

            table = $(this).parents('table');
            table.find('tbody').html('');
            get_table_listing(table);
        }
        else if($(this).find('i').length > 0)
        {
            // refresh pagination table  case
            search = $(this).parents('.listing-block').find('.listing-search').val();
            window.location.href = current_url + "?search=" + search + '&sort=' + field + '&direction=' + direction;
        }
    });

    $('body').on('keyup', '.listing-search', function(event){
        if($('.listing-table .ajaxPaginationEnabled').length > 0)
        {
            table = $(this).parents('.listing-block').find('.listing-table');
            loader = table.find('tfoot .loader')
            loader.attr('data-page', 0);
            loader.removeClass('hidden');
            table.removeClass('processing');
            table.find('tbody').html('');
            get_table_listing(table);
        }
        else if(event.which === 13) 
        {
            // refresh pagination table  case
            search = $(this).val();
            window.location.href = current_url + "?search=" + search;
        }
    });

    $('body').on('click', '.add_category_inline, .cancel_category_inline', function(){
        selectbox = $(this).parents('.category-group');
        toggleCategoryInline(selectbox);
    });

    // __add_edit_items functions
    $('body').on('submit', '#add_product .add_product_form', function(){
        button = $(this).find('.modal-footer button');
        button.prop('disabled', true);
        $.ajax({
            url: site_url + 'products/add',
            type: 'post',
            data: $(this).serialize(),
            success: function(resp){
                button.prop('disabled', false);
                if($('#item_select').length)
                {
                    $('.add_item_dropdown').html(resp.products_view);
                    $('#item_select').selectpicker();
                    make_add_edit_item_entry(resp.insert_id)
                }
            }
        });
        return false;
    });

    $('body').on('changed.bs.select', '#item_select', function(){
        make_add_edit_item_entry($(this).val());
    });
    $('body').on('click', '.input_n_dropdown .dropdown-menu li a', function(){
        value = $(this).attr('data-value');
        block = $(this).parents('.btn-group-vertical');
        block.find('.dropdown-toggle').html(value + ' <span class="caret"></span>');
        block.find('input').val(value).change();
    });
    $('body').on('keyup change', '._add_edit_items table tbody tr.item-row .amount, ._add_edit_items table tbody tr.item-row .unit, ._add_edit_items table tbody tr.item-row .price', function(){
        calculate_total();
    });
});

if($('#dropzone').length)
{
    Dropzone.autoDiscover = false;
    url = $('#dropzone').attr('data-url');
    var myDropzone = new Dropzone("div#dropzone", { url: url});
    console.log(myDropzone);
    myDropzone.on("complete", function(file) {
      myDropzone.removeFile(file);
  });
}

$('.delete_product_image').on('click', function(){
    if(confirm('Are you sure to delete this image ?'))
    {
        id = $(this).attr('data-id');
        that = $(this);
        $.ajax({
            url: site_url + 'products/delete_product_image/' + id,
            type: 'post',
            success: function(resp){
                if(resp.status == 'success')
                { 
                    that.closest('div').remove();
                    set_notification('success', resp.message);
                }
                else
                {
                    set_notification('error', resp.message);   
                }
            }
        });
    }
});

var stateAjax;
$('#state_id').on('change', function(){
    if(stateAjax && stateAjax.readyState != 4)
    {
        stateAjax.abort();
    }
    if($(this).val() && $('select#city_id').length)
    {
        stateAjax = $.ajax({
            url: site_url + 'admin/states/cities_dropdown/' + $(this).val(),
            success: function(resp) {
                $('select#city_id').html(resp);
                $('select#city_id').selectpicker('refresh');
            }
        });
    }
    else
    {
        $('select#city_id').html('');
    }
});

$('body').on('change','#profile_img', function (e) {

    if($(this).attr('data-id') && $(this).attr('data-url'))
    {
        $('#loading-image').show();
        var fd = new FormData();
        var files = $('#profile_img')[0].files[0];
        fd.append('file', files);
        fd.append('_token', csrf_token());
        fd.append('id', $(this).attr('data-id'));

        $.ajax({
            url: $(this).attr('data-url'),
            type: 'POST',
            data: fd,
            contentType: false,
            processData: false,
            success: function ( data ) {
                if(data.status == 'success' && data.picture != "")
                {
                    $('.prof_image_sidebar img').attr('src', data.picture);
                    set_notification('success', data.message);
                }
                else
                {
                    set_notification('error', data.message);
                }
            },
            complete: function(){           
                $("#loading-image").hide();
                $('#profile_img').val(' ');   
            }
        });
    }
});

/** Upload File Script **/
const initImageUploader = function(that, callback) {
    console.log(`that`, that);
    var that = $(that);
    var parent = that.parents('.upload-image-section');
    var uploadSection = parent.find('.upload-section');
    var textArea = parent.find('textarea');
    var showSection = parent.find('.show-section');
    var fixedEditSection = parent.find('.fixed-edit-section');
    var progerssBar = parent.find('.progress-bar');
    var isMultiple = parent.attr('data-multiple') == 'true' ? true : false;
    var fileType = parent.attr('data-type');
    var path = parent.attr('data-path');
    var resizeLarge = parent.attr('data-resize-large');
    resizeLarge = resizeLarge ? resizeLarge : "";
    var resizeMedium = parent.attr('data-resize-medium');
    resizeMedium = resizeMedium ? resizeMedium : "";
    var resizeSmall = parent.attr('data-resize-small');
    resizeSmall = resizeSmall ? resizeSmall : "";
    
    if(fileType && path)
    {
        parent.find('input[type=hidden]').val('');
        var form = $('#fileUploadForm');
        form.find('input[name=file_type]').val(fileType);
        form.find('input[name=path]').val(path);
        form.find('input[name=resize_large]').val(resizeLarge);
        form.find('input[name=resize_medium]').val(resizeMedium);
        form.find('input[name=resize_small]').val(resizeSmall);
    
        $('#fileUploadForm input[type=file]').val('');
        $('#fileUploadForm input').click();
        
        $('#fileUploadForm input').unbind('change');
        
        $('#fileUploadForm input').on('change', function() {
            $('#fileUploadForm').ajaxSubmit({
                beforeSend: function() {
                    progerssBar.parent().removeClass('d-none');
                    progerssBar.css('width', '0');
                },
                uploadProgress: function(event, position, total, percentComplete) {
                    progerssBar.css('width', percentComplete + '%');
                },
                success: function(response) {
                    if(response.status == 'success')
                    {
                        if(callback) {
                            callback(response);
                        }

                        if(showSection && showSection.length > 0)
                        {
                            if(!isMultiple)
                            {
                                if(fileType == 'image')
                                    showSection.html('<div class="single-image"><a href="javascript:;" class="fileRemover single-cross image" data-path="'+response.path+'"><i class="fas fa-times"></i></a><img src="'+response.url+'"></div>');
                                else
                                    showSection.html('<div class="single-file"><a href="'+site_url + response.path +'" target="_blank"><i class="fas fa-file"></i>'+response.name+'</a><a href="javascript:; file" class="fileRemover single-cross file" data-path="'+response.path+'"><i class="fas fa-times-circle"></i></a></div>');
                                uploadSection.addClass('d-none');
                                fixedEditSection.addClass('d-none');
                            }
                            else
                            {
                                if(fileType == 'image')
                                    showSection.prepend('<div class="single-image"><a href="javascript:;" class="fileRemover single-cross image" data-path="'+response.path+'"><i class="fas fa-times"></i></a><img src="'+response.url+'"></div>');
                                else
                                    showSection.prepend('<div class="single-file"><a href="'+site_url + response.path +'" target="_blank"><i class="fas fa-file"></i>'+response.name+'</a><a href="javascript:; file" class="fileRemover single-cross file" data-path="'+response.path+'"><i class="fas fa-times-circle"></i></a></div>');
                            }
                            showSection.removeClass('d-none');
                        }
                        updateFileValues(textArea, fileType, isMultiple);
                    }
                    else
                    {
                        set_notification('error', response.message);
                    }
                },
                complete: function() {
                    progerssBar.css('width', '100%');
                }
            });
        });
    }
    else
    {
        set_notification('error', 'File path and type are missing.');
    }
}

$('body').on('click', '.upload-image-section .button-ref button', function(){
    initImageUploader(this)
});

$('body').on('click', '.upload-image-section .fileRemover', function() {
    var parent = $(this).parents('.upload-image-section');
    var uploadSection = parent.find('.upload-section');
    var showSection = parent.find('.show-section');
    var progerssBar = parent.find('.progress-bar');
    if(confirm("Are you sure to delete this file ?"))
    {
        var relation = $(this).attr('data-relation') ? $(this).attr('data-relation') : null;
        var id = $(this).attr('data-id') ? $(this).attr('data-id') : null;
        var isMultiple = !$(this).hasClass('single-cross');
        var that = $(this);
        var path = $(this).attr('data-path');
        if(relation && id)
        {
            $.ajax({
                url: admin_url + '/actions/removeFile',
                type: "post",
                data: {
                    "_token": csrf_token(),
                    "file": path,
                    "relation": relation,
                    "id": id
                },
                success: function(resp) {
                    that.parent().remove();
                    if(!isMultiple)
                    {
                        uploadSection.removeClass('d-none');
                        showSection.addClass('d-none');
                        progerssBar.css('width', 0);
                    }
                }
            });
        }
        else
        {
            $(this).parents('.upload-image-section').find('textarea').val('');
            $(this).parents('.single-image').remove();
        }
    }
});

function updateFileValues(textArea, fileType, isMultiple)
{
    if(isMultiple)
    {
        files = [];
        textArea.next('.show-section').find('.fileRemover').each(function() {
            var file = $(this).attr('data-path');
            files.push(file);
        });
        textArea.val(files.length > 0 ? JSON.stringify(files) : "");
    }
    else
    {
        textArea.val(textArea.next('.show-section').find('.fileRemover').attr('data-path'));
    }
}

/** Upload File Script **/

function getComments(lastId = null) {
    $.ajax({
        url: admin_url + '/' + ($('#trip-comments').attr('data-module') ? $('#trip-comments').attr('data-module') : 'trips') + '/' + $('#trip-comments').attr('data-id') + '/comments',
        data: { last_id: lastId },
        success: function (resp) {
            $('.load-more-remarks i').remove();
            if(resp.count > 0) {
                $('.comments-empty-state').addClass('d-none')
            }
            else {
                $('.comments-empty-state').removeClass('d-none')
            }
            $('#total-comments').html(resp.count * 1 > 0 ? resp.count : 'No ');
            if (lastId) {
                $('#trip-comments').append(resp.html);
            }
            else {
                $('#trip-comments').html(resp.html);
            }
            if (resp.count * 1 < resp.pagination_counter) {
                $('.load-more-remarks').addClass('d-none');
            }
            else {
                $('.load-more-remarks').removeClass('d-none');
            }
        }
    });
}
window.getComments = getComments;

if ($('#trip-comments').attr('data-id')) {
    getComments();
}

$('body').on('click', '#post-comments #save-comment', function () {
    if ($('#save-comment').find('i').length > 0) return false;

    $('#post-comments .error').addClass('d-none');
    if ((!$('#post-comments').find('.datetimepicker').length || $('#post-comments').find('.datetimepicker').val()) && (!$('#post-comments').find('select').length || $('#post-comments').find('select').val()) && $('#post-comments').find('textarea').val()) {
        $('#save-comment').prepend('<i class="fa fa-spin fa-spinner"></i>');
        $.ajax({
            url: admin_url + '/' + ($('#trip-comments').attr('data-module') ? $('#trip-comments').attr('data-module') : 'trips') + '/' + $('#post-comments').find('input[type=hidden]').val() + '/comments',
            type: 'post',
            data: $('#post-comments input[name="date_time"]').length  ? {
                category: $('#post-comments').find('select').length ? $('#post-comments').find('select').val() : null,
                comment: $('#post-comments').find('textarea').val(),
                date_time: $('#post-comments input[name="date_time"]').length ? $('#post-comments input[name="date_time"]').val() : null,
                _token: csrf_token()
            }: {
                category: $('#post-comments').find('select').length ? $('#post-comments').find('select').val() : null,
                comment: $('#post-comments').find('textarea').val(),
                _token: csrf_token()
            },
            success: function (resp) {
                $('#save-comment').find('i').remove();
                if (resp && resp.status == 'success') {
                    // $('#post-comments').slideToggle();
                    $('#post-comments textarea').val('');
                    $('#post-comments input[name="date_time"]').val('');
                    window.getComments();
                }
                else if (resp && resp.status == 'error') {
                    $('#post-comments .error').removeClass('d-none').html(resp.message);
                }
            }
        });
    }
    else {
        $('#post-comments .error').removeClass('d-none').html('Please fill in all details to post.');
    }
});

$('body').on('click', '.load-more-remarks', function () {
    $('.load-more-remarks').prepend('<i class="fa fa-spin fa-spinner"></i> ');
    window.getComments($('#trip-comments .comment:last-child').attr('data-id'));
});

$('#post-comments select').on('change', function () {
    if ($(this).val())
        $('#post-comments .autofill').removeClass('d-none');
    else
        $('#post-comments .autofill').addClass('d-none');
});

$('.post-comments .autofill a').on('click', function () {
    let parent = $(this).parents('.post-comments');
    if (parent.find('select').val()) {
        $('#autofill-responses .modal-body').html('<p class="text-center"><i class="fa fa-spin fa-spinner"></i></p>');
        $('#autofill-responses').modal('show');
        $.ajax({
            url: admin_url + '/trips/comments/autofill',
            data: { category: parent.find('select').val() },
            success: function (resp) {
                let html = "";
                if (resp && resp.status == 'success') {

                    for (let i in resp.autofills) {
                        html += '<div class="row"><div class="col-md-10"><h4>' + resp.autofills[i].title + '</h4><p>' + resp.autofills[i].description + '</p></div><div class="col-md-2"><button type="button" class="btn btn-primary select-autofill">Select</button></div></div>';
                    }
                    $('#autofill-responses .modal-body').html('<div class="row"><div class="col-md-12">' + (html && html.trim() ? html.trim() : '<p class="text-center">No record available!</p>') + '</div></div>');
                }
            }
        })
    }
});

$('body').on('click', '.remarks-edit', function () {
    let id = $(this).attr('data-id');
    let category = $(this).attr('data-category');
    $('#remarsk-update input[name=id]').val(id);
    if($('#remarsk-update input[name="date_time"]').length > 0) {
        $('#remarsk-update input[name="date_time"]').val($(this).attr('date-time'));
    }
    $('#remarsk-update select[name=remarks_category]').val(category).selectpicker('refresh');
    $('#remarsk-update textarea').val($(this).parents('.comment').find('.c-tex').text());
    $('#remarsk-update').modal('show');
});
$('body').on('click', '.remarks-edit', function () {
    let id = $(this).attr('data-id');
    let category = $(this).attr('data-category');
    $('#remarsk-update input[name=id]').val(id);
    if($('#remarsk-update input[name="date_time"]').length > 0) {
        $('#remarsk-update input[name="date_time"]').val($(this).attr('date-time'));
    }
    $('#remarsk-update select[name=remarks_category]').val(category).selectpicker('refresh');
    $('#remarsk-update textarea').val($(this).parents('.comment').find('.c-tex').text().trim());
    $('#remarsk-update').modal('show');
});
$('#remarsk-update #update-comment').on('click', function () {
    $.ajax({
        url: admin_url + '/' + ($('#trip-comments').attr('data-module') ? $('#trip-comments').attr('data-module') : 'trips') + '/' + $('#remarsk-update').find('input[name=id]').val() + '/update-comments',
        type: 'post',
        data: {
            comment: $('#remarsk-update').find('textarea').val(),
            category: $('#remarsk-update').find('select').val(),
            _token: csrf_token()
        },
        success: function (resp) {
            if (resp.status == 'success') {
                $('.comment[data-id=' + resp.id + ']').replaceWith(resp.html);
                $('#remarsk-update').modal('hide');
                set_notification('success', resp.message);
            }
            else if (resp.message) {
                set_notification('error', resp.message);
            }
        }
    })
});
$('body').on('click', '.remarks-delete', function () {
    if (confirm('Are you sure to delete this comment?')) {
        let id = $(this).attr('data-id');
        $.ajax({
            url: admin_url + '/' + ($('#trip-comments').attr('data-module') ? $('#trip-comments').attr('data-module') : 'trips') + '/' + id + '/delete-comment',
            type: 'post',
            data: {
                _token: csrf_token()
            },
            success: function (resp) {
                if (resp.status == 'success') {
                    $('.comment[data-id=' + id + ']').remove();
                    set_notification('success', resp.message);
                }
                else if (resp.message) {
                    set_notification('error', resp.message);
                }
            }
        });
    }
});

$('.color-picker').spectrum({
    showPaletteOnly: true,
    showPalette: true,
    palette: [
        ['#000000', '#ffffff', '#ff0000', '#00ff00', '#0000ff']
    ]
});

$('#ship-products').on('click', async function() {
    if($('#ordered-products .listing_check:checked').length > 0)
    {
        if(!$('#ship-options').val()) {
            set_notification('error', 'Please select ship option.'); return false;
        }

        let noOfParcels = $('#parcel').val() ? $('#parcel').val()  : 1;
        let shipOption = $('#ship-options').val();
        let shipped = [];
        $('#ordered-products .listing_check:checked').each(function() {
            shipped.push($(this).val());
        });
        let id = $('#ordered-products').attr('data-id');
        let response = null;
        if(shipOption == 'DPD')
        {
            response = await fetch(shop_url + `/create-dpd-shipment?ids=${id}&parcels=${noOfParcels}`);
            response = await response.json();
        }
        else
        {
            response = await fetch(admin_url + '/orders/'+id+'/ship', 
                {
                    method: 'POST',
                    headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(
                        {
                            _token: csrf_token(),
                            options: shipOption,
                            parcel: $('#parcel').val() ? $('#parcel').val()  : 1,
                            ship: shipped,
                            total: $('#ordered-products .listing_check').length
                        }
                    )
                }
            );
            response = await response.json();
        }
        
        if(response && response.status)
        {
            set_notification('success', response.message);
            window.location.reload();
        }
        else if(response && response.message)
        {
            set_notification('error', response.message);
        }
    }
    else
    {
        set_notification('error', 'Please select atleast one product to ship.');
    }
});

let offerCases = function() {
    if($('#case-2:checked, #case-3:checked').is(':checked') && $('#case-2:checked, #case-3:checked').val() == 'case-3')
    {
        $('#case-2-offers').addClass('d-none');
        $('#case-2-offers [required]').val('').attr('required', '');
        $('#case-3-offers').removeClass('d-none');
        $('#case-3-offers [required]').attr('required', 'required');
    }
    else
    {
        $('#case-3-offers').addClass('d-none');
        $('#case-3-offers [required]').val('').attr('required', '');
        $('#case-2-offers').removeClass('d-none');
        $('#case-2-offers [required]').attr('required', 'required');
    }
}

if($('#case-2, #case-3').length)
{
    $('#case-2, #case-3').on('change', offerCases);
    offerCases();
}

if($('.delivery-fields').length)
{
    let df = function() {
        if($('input[name=free_delivery]').is(':checked'))
        {
            $('.delivery-fields').removeClass('d-none');
            $('.delivery-fields input[name=min_cart_price]').attr('required', 'required');
        }
        else
        {
            $('.delivery-fields').addClass('d-none');
            $('.delivery-fields input[name=min_cart_price]').attr('required', '');            
        }
    }
    $('input[name=free_delivery]').on('change', df)
    df();
}

if($('.free-logo-fields').length)
{
    let fl = function() {
        if($('input[name=free_logo]').is(':checked'))
        {
            $('.free-logo-fields').removeClass('d-none');
            $('.free-logo-fields input[name=min_cart_price]').attr('required', 'required');
        }
        else
        {
            $('.free-logo-fields').addClass('d-none');
            $('.free-logo-fields input[name=min_cart_price]').attr('required', '');
            
        }
    }
    $('input[name=free_logo]').on('change', fl)
    fl();
}

function downloadLabel(id, sm)
{
    window.open(shop_url + `/dpd-label?sn=${sm}&id=${id}`);
}