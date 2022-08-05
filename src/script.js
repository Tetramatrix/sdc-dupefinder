jQuery(document).ready(function($){

  // AJAX url
  var ajax_url = skincaredupes.ajax_url;
  
  // Fetch All records (AJAX request without parameter)
  var data = {
    'action': 'df_next'
  };
  
  $.ajax({
    url: ajax_url,
    type: 'post',
    data: data,
    dataType: 'json',
    success: function(response){
			console.log(response);
    }
  });
  
  
    $.ajax({
        url: ajax_url,
        type: 'post',
        data : {action: 'df_next2'},
        success: function(results){
            console.log(results);
            //jQuery("#testresults").html(results);            
        },
        error: function(errorThrown){console.log(errorThrown);}
    });// end of ajax

});