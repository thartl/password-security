( function( $, wp ) {

  $( document ).ready( function() {

    const $allowWeakControl = $( '#resetpassform div.pw-weak, tr#password ~ tr.pw-weak' );

    if ( !$allowWeakControl.length ) {
      return;
    }

    const reqStrength = ( typeof th_pass_strength_setting === 'object'
        && th_pass_strength_setting.strength !== undefined )
        ? parseInt( th_pass_strength_setting.strength )
        : 2;


    /**
     * WordPress default behavior, we bail here.
     */
    if ( reqStrength === 1 ) {
      return;
    }


    /**
     * At least Medium password is required.
     */
    // Remove ability to bypass security requirements.
    $allowWeakControl.html( '' );


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

    const originalMeterFunction = wp.passwordStrength.meter;

    // Override the meter function
    wp.passwordStrength.meter = function( password1, disallowedList, password2 ) {

      const originalScore = originalMeterFunction( password1, disallowedList, password2 );

      if ( originalScore === 3 ) {
        // If the original score is 3 (medium/good), we want to treat it as 2 (weak/bad).
        return 2;
      }
      else {
        // In all other cases, return the original score unchanged.
        return originalScore;
      }
    };

  } );

} )( jQuery, window.wp );
