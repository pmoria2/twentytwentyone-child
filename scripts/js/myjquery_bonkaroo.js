
  jQuery(document).ready(function($) {


    $( ".btn-group" ).click(function() {
       
        stateCd = $(this).attr('id');
        
        console.log( 'clicked!' + stateCd + ', ' + jp.nonce);
        $.get(
              jp.ajaxURL, {
                  'action': 'jp_ajax_test',
                  'nonce' : jp.nonce,
                  'state' : stateCd
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