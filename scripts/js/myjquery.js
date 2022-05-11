
  jQuery(document).ready(function($) {


    $( ".btn-group" ).click(function() {
       
        district_id = $(this).attr('id');
        
        console.log( 'clicked!' + district_id + ', ' + jp.nonce);
        $.get(
              jp.ajaxURL, {
                  'action': 'jp_ajax_test',
                  'nonce' : jp.nonce,
                  'district' : district_id
              },
              function( response ) {

                var x = document.getElementById("ajax-target2");
                x.remove();
                    $( '#ajax-target' ).append( response );
              }
          );
        }
       );
       
});