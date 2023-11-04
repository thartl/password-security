( function( $, wp ) {

  $( document ).ready( function() {

    var $resetPassNote = $( '#resetpassform div.pw-weak' );
    var $adminPassNote = $( 'tr#password ~ tr.pw-weak' );

    if ( ! $resetPassNote.length && ! $adminPassNote.length ){
      return;
    }

    var reqStrength = ( typeof th_pass_strength_setting === 'object'
        && th_pass_strength_setting.strength !== undefined )
        ? parseInt( th_pass_strength_setting.strength )
        : 2;


    /**
     * WordPress default behavior, we bail here.
     */
    if ( reqStrength === 1 ) {
      return; //
    }


    /**
     * At least Medium password is required.
     */
    var strengthLabel = reqStrength === 2 ? 'Medium or better' : 'Strong';

    // Adjust password-strength-related messaging.
    var message = '<span class="emphasis">' + strengthLabel + ' password</span> is required.';
    $resetPassNote.html( '<span class="pass-requirements">' + message + '</span>' );
    $adminPassNote.html( '<th></th><td><span class="pass-requirements">' + message + '</span></td>' );


    /**
     * Only Medium-strength password is required, we bail here.
     */
    if ( reqStrength === 2 ) {
      return;
    }


    /**
     * Strong password is required. Translate Medium score to Weak score.
     */
    wp.passwordStrength = wp.passwordStrength || {};

    var originalMeterFunction = wp.passwordStrength.meter;

    // Override the meter function
    wp.passwordStrength.meter = function( password1, disallowedList, password2 ) {

      var originalScore = originalMeterFunction( password1, disallowedList, password2 );

      if ( originalScore === 3 ) {
        // If the original score is 3 (medium/good), we want to treat it as 2 (weak|very weak/bad).
        return 2;
      }
      else {
        // In all other cases, return the original score unchanged.
        return originalScore;
      }
    };

  } );

} )( jQuery, window.wp );
