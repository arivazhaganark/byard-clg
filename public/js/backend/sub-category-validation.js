var app_root = $('#admin_access_url').val();

$(document).ready(function () {

    $('input, textarea').blur(function() {
       var value = $.trim( $(this).val() );
       $(this).val( value );
    });

    $('#add_sub_category').validate({
        onkeyup: false,
        errorClass: 'error',
        validClass: 'valid',
         rules: {
            category_name: {
                required: true,
                remote:
                {
                    type: 'POST',
                    url : app_root+'/sub_category/exist_check',
                    data: {'_token': $('input[name=_token]').val(), 'id' : $("#hid_update_id").val(), 'category_id' : $("#hid_category_id").val()},
                    async: false
                }
            },
        },

        messages: {
            category_name: {
                required: 'Sub Category is required',
                remote : 'Sub Category already exists.'
            },
        },
        highlight: function (element) {
            $(element).closest('div').addClass("f_error");
        },
        unhighlight: function (element) {
            $(element).closest('div').removeClass("f_error");
        },
        errorPlacement: function (error, element) {
            $(element).closest('div').append(error);
        }
    });


    $('#edit_sub_category').validate({
        onkeyup: false,
        errorClass: 'error',
        validClass: 'valid',
         rules: {
            category_name: {
                required: true,
                remote:
                {
                    type: 'POST',
                    url : app_root+'/sub_category/exist_check',
                    data: {'_token': $('input[name=_token]').val(), 'id' : $("#hid_update_id").val(), 'category_id' : $("#hid_category_id").val()},
                    async: false
                }
            },
        },

        messages: {
            category_name: {
                required: 'Sub Category is required',
                remote : 'Sub Category already exists.'
            },
        },
        highlight: function (element) {
            $(element).closest('div').addClass("f_error");
        },
        unhighlight: function (element) {
            $(element).closest('div').removeClass("f_error");
        },
        errorPlacement: function (error, element) {
            $(element).closest('div').append(error);
        }
    });

});


$("#btn_add_sub_category").click(function () {
    $('#add_sub_category').validate();
    var validated = $('#add_sub_category').valid();
    if (validated == true)
    {
      $("#btn_add_sub_category").attr('disabled', true);
      $.ajax({
          type: "POST",
          url: app_root + "/sub_category/"+$("#hid_category_id").val()+"/store",
          data: $('#add_sub_category').serialize(),
          success: function (objResponse) {
              showNotification(colorName, 'Sub Category has been added succesfully.', placementFrom, placementAlign, animateEnter, animateExit);
              $("#btn_add_sub_category").attr('disabled', false);
              $("#category_name").val("");
          },
          error: function (response) {
              showNotification(errorcolorName, 'Sub Category submission failed. Please try again.', placementFrom, placementAlign, animateEnter, animateExit);
              $("#btn_add_sub_category").attr('disabled', false);
          }
      });
    }
});
$("#btn_update_sub_category").click(function () {
    $('#edit_sub_category').validate();
    var validated = $('#edit_sub_category').valid();
    if (validated == true)
    {
      $("#btn_update_sub_category").attr('disabled', true);
      $.ajax({
          type: "POST",
          url: app_root + "/sub_category/"+$("#hid_category_id").val()+"/update",
          data: $('#edit_sub_category').serialize(),
          success: function (objResponse) {
              showNotification(colorName, 'Sub Category has been updated succesfully.', placementFrom, placementAlign, animateEnter, animateExit);
              $("#btn_update_sub_category").attr('disabled', false);
          },
          error: function (response) {
              showNotification(errorcolorName, 'Sub Category submission failed. Please try again.', placementFrom, placementAlign, animateEnter, animateExit);
              $("#btn_update_sub_category").attr('disabled', false);
          }
      });
    }
});
