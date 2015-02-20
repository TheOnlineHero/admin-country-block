jQuery(function() {
  jQuery("#block_countries_block_countries_select_all").click(function() {
    if (jQuery(this).is(':checked')) {
      jQuery("#block_countries_form ul.options input:not(#block_countries_block_countries_select_all)").attr("checked", "checked");  
    } else {
      jQuery("#block_countries_form ul.options input:not(#block_countries_block_countries_select_all)").removeAttr("checked");
    }    
  });
	jQuery("#block_countries_block_countries_select_all").parent().css("float", "none");
});