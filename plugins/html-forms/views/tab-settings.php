<h2><?php echo __( 'Form Settings', 'html-forms' ); ?></h2>

<?php if ( ! defined( 'HF_PREMIUM_VERSION' ) ) : ?>
    <p class="hf-premium">
        <?php echo sprintf( __('Enable admin notifications and set a form submission limit with <a href="%s">HTML Forms Premium</a>', 'html-forms' ), 'https://htmlformsplugin.com/premium/#utm_source=wp-plugin&amp;utm_medium=html-forms&amp;utm_campaign=actions-tab' ); ?>.
    </p>
<?php endif; ?>

<table class="form-table">
    <tr valign="top">
        <th scope="row"><?php _e( 'Save Form Submissions?', 'html-forms' ); ?></th>
        <td>
            <label><input type="radio" name="form[settings][save_submissions]" value="1" <?php checked( $form->settings['save_submissions'], 1 ); ?>> <?php _e( 'Yes' ); ?></label> &nbsp;
            <label><input type="radio"  name="form[settings][save_submissions]" value="0"  <?php checked( $form->settings['save_submissions'], 0 ); ?>> <?php _e( 'No' ); ?></label>

            <p class="description"><?php _e( 'Select "Yes" to store successful form submissions.', 'html-forms' ); ?></p>
        </td>
    </tr>

    <tr valign="top">
        <th scope="row"><?php _e( 'Hide Form After a Successful Sign-Up?', 'html-forms' ); ?></th>
        <td class="nowrap">
            <label>
                <input type="radio" name="form[settings][hide_after_success]" value="1" <?php checked( $form->settings['hide_after_success'], 1 ); ?> />&rlm;
                <?php _e( 'Yes' ); ?>
            </label> &nbsp;
            <label>
                <input type="radio" name="form[settings][hide_after_success]" value="0" <?php checked( $form->settings['hide_after_success'], 0 ); ?> />&rlm;
                <?php _e( 'No' ); ?>
            </label>
            <p class="description">
                <?php _e(' Select "Yes" to hide the form fields after a successful sign-up.', 'html-forms' ); ?>
            </p>
        </td>
    </tr>

    <tr valign="top">
        <th scope="row"><label for="hf_form_redirect"><?php _e( 'Redirect to URL After Successful Sign-Ups', 'html-forms' ); ?></label></th>
        <td>
            <input type="text" class="widefat" name="form[settings][redirect_url]" id="hf_form_redirect" placeholder="<?php printf( __( 'Example: %s', 'html-forms' ), esc_attr( site_url( '/thank-you/' ) ) ); ?>" value="<?php echo esc_attr( $form->settings['redirect_url'] ); ?>" />
            <p class="description">
                <?php _e( 'Leave empty or enter <code>0</code> for no redirect. Otherwise, use complete (absolute) URLs, including <code>http://</code>.', 'html-forms' ); ?>
            </p>
            <p class="description">
                <?php _e( 'You can use the following variables in the redirect URL: ', 'html-forms' ); ?><span class="hf-field-names"></span>
            </p>
        </td>
    </tr>

    <?php
    /**
    * Runs after the form settings are printed.
    *
    * @param $form
    */
    do_action( 'hf_output_form_settings', $form ); ?>

</table>

<?php submit_button(); ?>
