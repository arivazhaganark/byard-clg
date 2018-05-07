var app_root = $('#admin_access_url').val();

$(document).ready(function () {

    $('input, textarea').blur(function() {
       var value = $.trim( $(this).val() );
       $(this).val( value );
    });

    $('#add_category').validate({
        onkeyup: false,
        errorClass: 'error',
        validClass: 'valid',
         rules: {
            category_name: {
                required: true,
                remote:
                {
                    type: 'POST',
                    url : app_root+'/category/exist_check',
                    data: {'_token': $('input[name=_token]').val(), 'id' : $("#hid_update_id").val()},
                    async: false
                }
            },
        },

        messages: {
            category_name: {
                required: 'Category is required',
                remote : 'Category already exists.'
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


    $('#edit_category').validate({
        onkeyup: false,
        errorClass: 'error',
        validClass: 'valid',
         rules: {
            category_name: {
                required: true,
                remote:
                {
                    type: 'POST',
                    url : app_root+'/category/exist_check',
                    data: {'_token': $('input[name=_token]').val(), 'id' : $("#hid_update_id").val()},
                    async: false
                }
            },
        },

        messages: {
            category_name: {
                required: 'Category is required',
                remote : 'Category already exists.'
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


$("#btn_add_category").click(function () {
    $('#add_category').validate();
    var validated = $('#add_category').valid();
    if (validated == true)
    {
      $("#btn_add_category").attr('disabled', true);
      $.ajax({
          type: "POST",
          url: app_root + "/category/store",
          data: $('#add_category').serialize(),
          success: function (objResponse) {
              showNotification(colorName, 'Category has been added succesfully.', placementFrom, placementAlign, animateEnter, animateExit);
              $("#btn_add_category").attr('disabled', false);
              $("#category_name").val("");
          },
          error: function (response) {
              showNotification(errorcolorName, 'Category submission failed. Please try again.', placementFrom, placementAlign, animateEnter, animateExit);
              $("#btn_add_category").attr('disabled', false);
          }
      });
    }
});
$("#btn_update_category").click(function () {
    $('#edit_category').validate();
    var validated = $('#edit_category').valid();
    if (validated == true)
    {
      $("#btn_update_category").attr('disabled', true);
      $.ajax({
          type: "POST",
          url: app_root + "/category/update",
          data: $('#edit_category').serialize(),
          success: function (objResponse) {
              showNotification(colorName, 'Category has been updated succesfully.', placementFrom, placementAlign, animateEnter, animateExit);
              $("#btn_update_category").attr('disabled', false);
          },
          error: function (response) {
              showNotification(errorcolorName, 'Category submission failed. Please try again.', placementFrom, placementAlign, animateEnter, animateExit);
              $("#btn_update_category").attr('disabled', false);
          }
      });
    }
});
