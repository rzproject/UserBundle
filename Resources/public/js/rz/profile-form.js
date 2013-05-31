jQuery(document).ready(function(){
    rz_user_profile_datepicker.init();
    rz_user_profile_selectpicker.init();
});

var rz_user_profile_datepicker = {
    init: function() {
        jQuery('.rz-datepicker').datepicker({autoclose: true});
    },
    initById: function(id, options) {
        jQuery('#'+id).datepicker(options);
    }
}

//* select
var rz_user_profile_selectpicker = {
    init: function() {
        jQuery('.selectpicker').selectpicker();
    },
    initById: function(id, options){
        jQuery("#"+id).selectpicker(options ? options : null);
    }
}
