<div class="wrap">
  <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
  <form method="post" action="">
    <?php wp_nonce_field('trufi_api_options_nonce', 'trufi_api_options_nonce'); ?>
    <table class="form-table">
      <tr>
        <th scope="row">
          <label for="trufi_site_title"><?php _e('Trufi site title', 'trufi-maps'); ?></label>
        </th>
        <td>
          <input type="text" name="trufi_site_title" id="trufi_site_title" value="<?php echo esc_attr($site_title); ?>" class="regular-text">
          <p class="description"><?php _e('Enter the title of the trufi site.', 'trufi-maps'); ?></p>
        </td>
      </tr>
      <tr>
        <th scope="row">
          <label for="trufi_site_description"><?php _e('Site description', 'trufi-maps'); ?></label>
        </th>
        <td>
          <input type="text" name="trufi_site_description" id="trufi_site_description" value="<?php echo esc_attr($site_description); ?>" class="large-text">
          <p class="description"><?php _e('Enter the description of the trufi site.', 'trufi-maps'); ?></p>
        </td>
      </tr>
      <tr>
        <th scope="row">
          <label for="trufi_api_url"><?php _e('API url', 'trufi-maps'); ?></label>
        </th>
        <td>
          <input type="text" name="trufi_api_url" id="trufi_api_url" value="<?php echo esc_attr($api_url); ?>" class="large-text">
          <p class="description"><?php _e('Enter the URL of the Trufi Graphql API.', 'trufi-maps'); ?></p>
        </td>
      </tr>
      <tr>
        <th scope="row">
          <label for="trufi_line_color"><?php _e('Line color', 'trufi-maps'); ?></label>
        </th>
        <td>
          <input type="text" name="trufi_line_color" id="trufi_line_color" value="<?php echo esc_attr($line_color); ?>" class="line-color-field">
          <p class="description"><?php _e('Choose a color for the track line.', 'trufi-maps'); ?></p>
        </td>
      </tr>
      <tr>
        <th scope="row">
          <label for="trufi_line_weight"><?php _e('Line Weight', 'trufi-maps'); ?></label>
        </th>
        <td>
          <input type="number" name="trufi_line_weight" id="trufi_line_weight" value="<?php echo esc_attr($line_weight); ?>" class="small-text" min="1" max="10">
          <p class="description"><?php _e('Enter the line weight (1-10).', 'trufi-maps'); ?></p>
        </td>
      </tr>

    </table>
    <?php submit_button(__('Save Changes', 'trufi-maps')); ?>
  </form>
</div>
<script>
  jQuery(document).ready(function($) {
    $('.line-color-field').wpColorPicker();
  });
</script>