(function($){

    var RippleSettingsManager = (function(){

        var initialize = function(){

            // Get value for JS hidden in HTML used by JS module
            var jsData = $.parseJSON($("#js-data").val());

            // Bind every switch button which allow to activate/deactivate a widget
            bindWidgetActivation();

            /*******************************************
             * CSS Theme UI manager
             *******************************************/
            (function(){

                if(jsData){

                    // JS for related content module
                    if(jsData.module_name === "ripple_semantic_related_content"){

                        var $customCssSwitch = $("#custom_css"); // The switch button to allow css edition
                        var $customCssField  = $("#related_post_css"); // The CSS edit field
                        var $restoreCssLink  = $("#restore_default_css"); // The control to reset the CSS to the initial value

                        /**
                         * Managing the behaviour of the "Custom CSS" switch button which allow the edition
                         * of the CSS thanks to a textarea
                         */
                        var configureCssThemeUI = function(checked){
                            // Disable / enable text field depending on the chosen theme
                            if(checked) {
                                $customCssField.prop('readonly', false);
                                $restoreCssLink.show();
                            }
                            else{
                                $customCssField.prop('readonly', true);
                                $restoreCssLink.hide();
                            }
                        };
                        $customCssSwitch.on("change", function(){
                            configureCssThemeUI(this.checked);
                        });
                        $customCssSwitch.trigger("change");

                        /**
                         * Managing the behaviour of the control restoring the initial value of the custom CSS.
                         * Restore default value link on click event
                         */
                        $restoreCssLink.on("click", function(e){
                            e.preventDefault();
                            var confirmText = $restoreCssLink.data("confirmText");

                            swal({
                                title: "Are you sure ?",
                                text: confirmText,
                                buttons: true,
                                type: 'warning',
                                dangerMode: true
                            }).then(function(value) {
                                if (value) {
                                    // Get the default CSS stored in /admin/css/default-custom.css
                                    var defaultCss = $restoreCssLink.data("default-css");
                                    $customCssField.val((defaultCss));
                                }
                            });

                        });

                    }

                    else if(jsData.module_name === "ripple_breadcrumbs"){}

                }

            }());

        };

        /**
         * This method bind every switch button that allow to activate a widget
         * When activating a widget (switching to "on") the form content appears
         * When deactivating a widget (switching to off) the form content is hidden
         * Note :
         * - The switch button must carry the class "ripple-activate-widget"
         * - The switch button should carry the data-toggle property and set it with the ID of the HTML part to show/hide when switching
         * - The content to be hidden/shown while switching must carry the ID mentioned above.
         */
        var bindWidgetActivation = function(){
            var $activateSwitchButtons = $(".ripple-activate-widget");
            $activateSwitchButtons.each(function(){
                var $switchBtn = $(this);
                $switchBtn.on("change", function(){
                    var $toggle = $("#"+$switchBtn.data("toggle"));
                    $toggle.toggle(this.checked);
                });
                $switchBtn.trigger("change")
            });
        };

        return{
            initialize : initialize
        };

    }());

    var SwalAlertManager = (function(){

        var initialize = function()
        {
            var $swalHelpLink = $(".swal-help-link");
            $swalHelpLink.on("click", function(){
                swal.fire({
                    title: $(this).data("title"),
                    html: $(this).data("content"),
                    buttons: false,
                    type: 'info',
                    width: 960,
                    dangerMode: false
                })
            })
        };

        return{
            initialize : initialize
        };
    }());

    // Initializing stuffs
    $(document).ready(function(){
        RippleSettingsManager.initialize();
        SwalAlertManager.initialize();
    });
}(jQuery));


