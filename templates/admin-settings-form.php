<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    <form method="post" action="">
        <?php wp_nonce_field('trufi_api_options_nonce', 'trufi_api_options_nonce'); ?>
        <table class="form-table">
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
                    <label for="trufi_site_description"><?php _e('Marketing Call to Action', 'trufi-maps'); ?></label>
                </th>
                <td>
                    <input type="text" name="trufi_site_description" id="trufi_site_description" value="<?php echo esc_attr($site_description); ?>"
                           maxlength="200" class="large-text">
                    <p class="description"><span id="chars_left">200</span> <?php _e('characters remaining', 'trufi-maps'); ?></p>
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
                    <input type="number" name="trufi_line_weight" id="trufi_line_weight" value="<?php echo esc_attr($line_weight); ?>" class="small-text"
                           min="1" max="10">
                    <p class="description"><?php _e('Enter the line weight (1-10).', 'trufi-maps'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="trufi_google_play_url"><?php _e('Google Play Store URL', 'trufi-maps'); ?></label>
                </th>
                <td>
                    <input type="text" name="trufi_google_play_url" id="trufi_google_play_url" value="<?php echo esc_attr($google_play_url); ?>"
                           class="large-text">
                    <p class="description"><?php _e('Enter the URL of your app in the Google Play Store.', 'trufi-maps'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="trufi_apple_store_url"><?php _e('Apple Store URL', 'trufi-maps'); ?></label>
                </th>
                <td>
                    <input type="text" name="trufi_apple_store_url" id="trufi_apple_store_url" value="<?php echo esc_attr($apple_store_url); ?>"
                           class="large-text">
                    <p class="description"><?php _e('Enter the URL of your app in the Apple App Store.', 'trufi-maps'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="trufi_google_play_image"><?php _e('Google Play Store Image', 'trufi-maps'); ?></label>
                </th>
                <td>
                    <input type="hidden" name="trufi_google_play_image" id="trufi_google_play_image" value="<?php echo esc_attr($google_play_image); ?>">
                    <button type="button" class="upload_image_button button"><?php _e('Upload Image', 'trufi-maps'); ?></button>
                    <p class="description"><?php _e('Upload an image for the Google Play Store button.', 'trufi-maps'); ?></p>
                    <img id="trufi_google_play_image_preview" src="<?php echo esc_attr($google_play_image); ?>"
                         alt="Google Play Store Image"
                         style="<?php echo(empty($google_play_image) ? 'display: none;' : ''); ?> max-width: 200px; max-height: 200px;">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="trufi_apple_store_image"><?php _e('Apple Store Image', 'trufi-maps'); ?></label>
                </th>
                <td>
                    <input type="hidden" name="trufi_apple_store_image" id="trufi_apple_store_image" value="<?php echo esc_attr($apple_store_image); ?>">
                    <button type="button" class="upload_image_button button"><?php _e('Upload Image', 'trufi-maps'); ?></button>
                    <p class="description"><?php _e('Upload an image for the Apple Store button.', 'trufi-maps'); ?></p>
                    <img id="trufi_apple_store_image_preview" src="<?php echo esc_attr($apple_store_image); ?>"
                         alt="Apple Store Image"
                         style="<?php echo(empty($apple_store_image) ? 'display: none;' : ''); ?> max-width: 200px; max-height: 200px;">
                </td>
            </tr>
            <?php if (!wp_is_block_theme()) : ?>
                <tr>
                    <th scope="row">
                        <label for="trufi_map_page_template"><?php _e('Page Template for Maps', 'trufi-maps'); ?></label>
                    </th>
                    <td>
                        <select name="trufi_map_page_template" id="trufi_map_page_template">
                            <?php
                            $templates = get_page_templates();
                            ksort($templates);

                            foreach (array_keys($templates) as $template) {
                                $selected = selected($map_page_template, $templates[$template], false);
                                echo "\n\t<option value='" . esc_attr($templates[$template]) . "' $selected>" . esc_html($template) . '</option>';
                            }
                            ?>
                        </select>
                        <p class="description"><?php _e('Choose the page template to use for the map pages.', 'trufi-maps'); ?></p>
                    </td>
                </tr>
            <?php else: ?>
                <input type="hidden" name="trufi_map_page_template" id="trufi_map_page_template" value="default">
            <?php endif; ?>
            <tr>
                <th scope="row">
                    <label for="trufi_cache_ttl"><?php _e('Cache TTL (Hours)', 'trufi-maps'); ?></label>
                </th>
                <td>
                    <input type="number" name="trufi_cache_ttl" id="trufi_cache_ttl" value="<?php echo esc_attr($cache_ttl); ?>" class="small-text">
                    <p class="description"><?php _e('Enter the time to live (in hours) for the sitemap cache. Set to 0 to disable.', 'trufi-maps'); ?></p>
                </td>
            </tr>
        </table>
        <?php submit_button(__('Save Changes', 'trufi-maps')); ?>
    </form>
</div>
