jQuery(function() {
  jQuery("#block_countries_block_countries_select_all").click(function() {
    if (jQuery(this).is(':checked')) {
      jQuery("ul.options input:not(#block_countries_block_countries_select_all)").attr("checked", "checked");  
    } else {
      jQuery("ul.options input:not(#block_countries_block_countries_select_all)").removeAttr("checked");
    }    
  });
});