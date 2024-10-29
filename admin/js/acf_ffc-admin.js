(function ($) {
    'use strict';

    $(window).load(function () {

        $('.js-create').on('click', function () {
            removeErrorsAndSuccesses();
            $('.js-documentation').removeClass('active');
            $('.js-templates').removeClass('active');
            $(this).addClass('active');
            $('.acf-documentation').hide();
            $('.acf-templates').hide();
            $('.acf_form').show();

        });

        $('.js-templates').on('click', function () {
            removeErrorsAndSuccesses();
            $('.js-create').removeClass('active');
            $('.js-documentation').removeClass('active');
            $(this).addClass('active');
            $('.acf_form').hide();
            $('.acf-documentation').hide();
            $('.acf-templates').show();

        });

        $('.js-documentation').on('click', function () {
            removeErrorsAndSuccesses();
            $('.js-create').removeClass('active');
            $('.js-templates').removeClass('active');
            $(this).addClass('active');
            $('.acf_form').hide();
            $('.acf-templates').hide();
            $('.acf-documentation').show();


        });


        $('.js_check_all').on('click', function () {

            if ($('.js_check_all').is(':checked')) {
                $('.js_checkbox').prop('checked', true);
            } else {
                $('.js_checkbox').prop('checked', false);
            }

        });

        function generateHtml(data) {
            var html = "";
            for (var key in data) {
                if (data.hasOwnProperty(key)) {
                    // console.log(key + " -> " + data[key]);
                    html += "<li>" + data[key] + "<a class='js-delete' data-filename='" + key + "'>delete</a></li>";
                }
            }
            return (html);
        }

        function destroyHtml(className) {
            $('.' + className).children('li').remove();
        }

        function renderHtml(className, html) {
            $('.' + className).append(html);
        }

        function updateTemplatesList() {
            $.ajax({
                url: ajax_object.ajaxurl, // this is the object instantiated in wp_localize_script function
                type: 'POST',
                data: {
                    action: 'list_templates'
                },
                success: function (data) {
                    var data = JSON.parse(data);
                    destroyHtml('templatesList');
                    var html = generateHtml(data);
                    renderHtml('templatesList', html);
                    //deleteElement();
                },
                error: function () {
                    console.log('Error!');
                }
            });
        }

        function isAtLeastOneCheckboxChecked() {
            var isChecked = false;
            $('.js_checkbox').each(function () {
                if ($(this).is(":checked")) {
                    isChecked = true;
                    return isChecked;
                }
            });
            return isChecked;
        }

        function resetForm() {
            $('.js_check_all').prop("checked", false);
            $('.js_checkbox').prop("checked", false);
            $('#acf_ffc_template_name').val('Flexy');
        }

        function showError(type) {
            $('.success').removeClass('show');

            switch (type) {
                case 'inputFields':
                    $('.emptyFelds').addClass('show');
                    break;
                default:
                    $('.error').addClass('show');
            }

        }
        function removeErrorsAndSuccesses() {
            $('.success').removeClass('show');
            $('.error').removeClass('show');
        }

        function showSuccess(type) {
            if (type == "delete") {
                $('.success.deleting').addClass('show');  
            } else {
                $('.success.creating').addClass('show');
                $('.error').removeClass('show'); 
            }
            
        }

        $('.acf_form').on('submit', function (e) {

            // Prevent form submission
            e.preventDefault();

            if (!isAtLeastOneCheckboxChecked()) {
                showError('emptyFelds');
                return;
            }

            var $form = $(e.target),
                fv = $form.data('formValidation');

            // Use Ajax to submit form data
            $.ajax({
                url: ajax_object.ajaxurl, // this is the object instantiated in wp_localize_script function
                type: 'POST',
                data: {
                    action: 'create_new_group',
                    data: $form.serialize()
                },
                success: function () {
                    showSuccess();
                    updateTemplatesList();
                    resetForm();
                },
                error: function () {
                    showError();
                }
            });

        });

        function deleteElementDom(data_filename) {
            $("[data-filename='" + data_filename + "']").parent().remove();
        }


        $('body').on('click', '.js-delete', function () {

            var popUpConfirm = confirm("Are you sure?");
            if (!popUpConfirm) {
                return;
            }
            var filename = $(this).data('filename');

            deleteElementDom(filename);

            //Use Ajax to submit form data
            $.ajax({
                url: ajax_object.ajaxurl, // this is the object instantiated in wp_localize_script function
                type: 'POST',
                data: {
                    action: 'create_new_delete',
                    data: {
                        'filename': filename
                    }
                },
                success: function () {

                    showSuccess('delete');

                },
                error: function () {
                    showError();
                }
            });

        });

    });

})(jQuery);