(function($) {

    /**
     * This module allow to manage post type forms
     * On the dashboard page, there is one form per post type. Each form contains several options, which are stored independently in ajax
     *
     * @returns {{initialize: initialize}}
     * @constructor
     */
    var PostTypeManager = function () {

        var $this;
        var postTypeName;

        var initialize = function ($module)
        {
            if ($module.length) {
                $this = $module;
                postTypeName = $this.find("input[name='post_type_name']").val();
                bindActions();
            }
        };

        /**
         * Bind the form input so they send a request when the user "change" their value
         */
        var bindActions = function()
        {
            var $form = $this.find("form");

            var  $formCheckboxes = $form.find("input[type='checkbox']");
            $formCheckboxes.on("change", function(e)
            {
                e.preventDefault();

                var $input = $(this); // The input that will fire the request
                var $spinner = $("<div />").addClass("spinner"); // The spinner to show durring the request execution
                var nonceFieldName = postTypeName+"['nonce']";

                // Firing the request only if one is not in progress
                if(!$input.data("is-processing"))
                {
                    // Tell the UI that the request is processing
                    $input.data("is-processing", true);

                    // Show the spinner as a user feedback
                    $input.after($spinner).fadeOut("fast", function () {
                        $spinner.addClass("is-active");
                    });

                    // Requested value
                    var requestedValue = $input.prop("checked");

                    // Actually launch the request
                    $.ajax({
                        url: ajax_object.ajax_url,
                        method: 'POST',
                        data: {
                            action: $form.find("input[name='action']").val(),
                            post_type_name: postTypeName,
                            attribute: $input.prop("name"),
                            value: $input.prop("checked"),
                            nonce: $form.find("input[name=\""+nonceFieldName+"\"]").val(),
                            dataType: "json"
                        },
                        success: function () {
                            $input.prop("checked", requestedValue);  // ensure the checkbox is (un)checked right
                            // No message, nothing else, should be enough :)
                        },
                        error: function (response) {
                            $input.prop("checked", !requestedValue); // ensure the checkbox is (un)checked right

                            // Manage error message
                            var errorTitle, errorMsg = "";
                            try {
                                var responseText = $.parseJSON( response.responseText );
                                if (responseText.data.error_msg) {
                                    errorMsg = responseText.data.error_msg;
                                }
                                if (responseText.data.error_title) {
                                    errorTitle = responseText.data.error_title;
                                }
                            }
                            catch(error) {}

                            // Display the message box
                            swal({
                                type: 'error',
                                title: errorTitle,
                                text: errorMsg,
                                showConfirmButton: false
                            })
                        },
                        complete: function ()
                        {
                            // Tell the UI the request is done
                            $input.data("is-processing", false);

                            // Hide the spinner
                            $spinner.fadeOut("fast", function () { $input.fadeIn(); }).remove();
                        }
                    });
                }
            });
        };

        return {
            initialize: initialize
        }

    };

    jQuery(document).ready(function($) {

        // Initializing a the post type manager for each post type
        $(".ripple-post-type").each(function(){
            var $postTypeManager = new PostTypeManager();
            $postTypeManager.initialize($(this))
        });

        // Make the post type form container a jquery-ui-tabs
        $( "#ripple-post-type-form-container" ).tabs().fadeIn();

    });


}(jQuery));