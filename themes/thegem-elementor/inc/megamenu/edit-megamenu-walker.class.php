<?php
/**
 * TheGem Mega Menu Edit Walker class.
 *
 */

/**
 * Create HTML list of nav menu input items.
 *
 * @package WordPress
 * @since 3.0.0
 * @uses Walker_Nav_Menu_Edit
 */
class TheGem_Edit_Mega_Menu_Walker extends Walker_Nav_Menu_Edit {

	/**
	 * Start the element output.
	 *
	 * @see Walker_Nav_Menu_Edit::start_el()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item   Menu item data object.
	 * @param int	$depth  Depth of menu item. Used for padding.
	 * @param array  $args   Not used.
	 * @param int	$id	 Not used.
	 */
	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		// vars
		$item_output = '';

		// First, make item with standard class
		parent::start_el( $item_output, $item, $depth, $args, $id );

		ob_start();
		if (!isset($item->thegem_mega_menu))
			$item->thegem_mega_menu = $item->thegem_mega_menu_default;

		$mega_menu_container_classes = array( 'thegem-megamenu-fields' );
		if ( $item->thegem_mega_menu['enable'] ) {
			$mega_menu_container_classes[] = 'field-thegem-megamenu-enabled thegem-megamenu-source-'.$item->thegem_mega_menu['source'];
			if ($item->thegem_mega_menu['template_width'] == 'custom') {
				$mega_menu_container_classes[] = 'thegem-edit-menu-template-width-custom';
			}
		}

		$mega_menu_container_classes = implode( ' ', $mega_menu_container_classes );

		$item_id = esc_attr( $item->ID );
		?>

				<div class="wrapper-thegem-mobile-clickable" style="clear: both;">
					<p class="field-thegem-mobile-clickable">
						<label for="edit-thegem-mobile-clickable-<?php echo esc_attr($item_id); ?>">
							<input id="edit-thegem-mobile-clickable-<?php echo esc_attr($item_id); ?>" type="checkbox" class="thegem-edit-thegem-mobile-clickable" name="thegem_mobile_clickable[<?php echo esc_attr($item_id); ?>]" <?php checked( $item->thegem_mobile_clickable ); ?>/>
							<?php esc_html_e( 'Make clickable on mobile', 'thegem' ); ?>
							<br>
							<span class="description">
								<?php esc_html_e( 'Activates page loading on mobiles by clicking on this menu item for default built-in mobile navigation type.', 'thegem' ); ?>
							</span>
						</label>
					</p>
				</div>

				<div class="wrapper-thegem-mobile-collapsible" style="clear: both;">
					<p class="field-thegem-mobile-collapsible">
						<label for="edit-thegem-mobile-collapsible-<?php echo esc_attr($item_id); ?>">
							<input id="edit-thegem-mobile-collapsible-<?php echo esc_attr($item_id); ?>" type="checkbox" class="thegem-edit-thegem-mobile-collapsible" name="thegem_mobile_collapsible[<?php echo esc_attr($item_id); ?>]" <?php checked( $item->thegem_mobile_collapsible ); ?>/>
							<?php esc_html_e( 'Open submenu on mobile', 'thegem' ); ?>
							<br>
							<span class="description">
								<?php esc_html_e( 'Activates opening of submenu by clicking on this menu item for overlay, left to right and top to bottom built-in mobile navigation types.', 'thegem' ); ?>
							</span>
						</label>
					</p>
				</div>

				<!-- TheGem Mega Menu Start -->

				<div class="<?php echo esc_attr( $mega_menu_container_classes ); ?>" style="clear: both;">

					<p class="field-thegem-megamenu-icon description">
						<label for="edit-thegem_mega_menu_icon-<?php echo esc_attr($item_id); ?>">
							<?php esc_html_e( 'Icon', 'thegem' ); ?><br />
							<input id="edit-thegem_mega_menu_icon-<?php echo esc_attr($item_id); ?>" class="thegem-edit-menu-item-icon" type="text" name="thegem_mega_menu_icon[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_html( $item->thegem_mega_menu['icon']); ?>"/><br />
							<span class="description">
								<?php esc_html_e('Enter icon code', 'thegem'); ?>:
								<a class="gem-icon-info gem-icon-info-fontawesome" href="<?php echo esc_url(thegem_user_icons_info_link('fontawesome')); ?>" onclick="tb_show('<?php esc_attr_e('Icons info', 'thegem'); ?>', this.href+'?TB_iframe=true'); return false;"><?php esc_html_e('Show FontAwesome Icon Codes', 'thegem'); ?></a>
							</span>
						</label>
					</p>

					<!-- first level -->
					<p class="field-thegem-megamenu-enable">
						<label for="edit-thegem_mega_menu_enable-<?php echo esc_attr($item_id); ?>">
							<input id="edit-thegem_mega_menu_enable-<?php echo esc_attr($item_id); ?>" type="checkbox" class="thegem-edit-menu-item-icon-enable" name="thegem_mega_menu_enable[<?php echo esc_attr($item_id); ?>]" <?php checked( $item->thegem_mega_menu['enable'] ); ?>/>
							<?php _e( 'Enable Mega Menu', 'thegem' ); ?>
						</label>
					</p>

					<p class="field-thegem-megamenu-source description">
						<label for="edit-thegem_mega_menu_source-<?php echo esc_attr($item_id); ?>">
							<?php _e('Mega Menu Source', 'thegem'); ?><br/>
							<select name="thegem_mega_menu_source[<?php echo esc_attr($item_id); ?>]"
									id="edit-thegem_mega_menu_source-<?php echo esc_attr($item_id); ?>"
									class="thegem-edit-menu-item-source">
								<?php foreach ($item->thegem_mega_menu_sources_values as $value => $title): ?>
									<option value="<?php echo esc_attr($value); ?>" <?php selected($value, $item->thegem_mega_menu['source']); ?>><?php echo esc_html($title); ?></option>
								<?php endforeach; ?>
							</select>
						</label>
					</p>

					<p class="field-thegem-megamenu-template description">
						<label for="edit-thegem_mega_menu_template-<?php echo esc_attr($item_id); ?>">
							<?php _e('Select Mega Menu Template', 'thegem'); ?><br/>
							<select name="thegem_mega_menu_template[<?php echo esc_attr($item_id); ?>]"
									id="edit-thegem_mega_menu_template-<?php echo esc_attr($item_id); ?>"
									class="thegem-edit-menu-item-template">
								<?php
								$getTemplatesArray = thegem_get_megamenus_list();
								foreach ($getTemplatesArray as $value => $data): ?>
									<option value="<?php echo esc_attr($value); ?>" data-etit-link="<?php echo esc_url($data['edit']); ?>" <?php selected($value, $item->thegem_mega_menu['template']); ?>><?php echo esc_html($data['label']); ?></option>
								<?php endforeach; ?>
							</select>
						</label>
						<button type="button" class="edit-template-link">
							Edit Template
						</button>
					</p>

					<p class="field-thegem-megamenu-template description">
						<a href="<?=admin_url('edit.php?post_type=thegem_templates&action=thegem_templates_new&_wpnonce='.wp_create_nonce( 'thegem_templates_new' ).'&template_type=megamenu')?>" target="_blank">Create New Template</a>
						<span class="meta-sep hide-if-no-js"> | </span>
						<a href="<?=admin_url('edit.php?post_type=thegem_templates&templates_type=megamenu#open-modal-import')?>" target="_blank">Import Pre-Built Template</a>
					</p>

					<p class="field-thegem-megamenu-template-width description">
						<label for="edit-thegem_mega_menu_template_width-<?php echo esc_attr($item_id); ?>">
							<?php _e('Width', 'thegem'); ?><br/>
							<select name="thegem_mega_menu_template_width[<?php echo esc_attr($item_id); ?>]"
									id="edit-thegem_mega_menu_template_width-<?php echo esc_attr($item_id); ?>"
									class="thegem-edit-menu-template-width">
								<?php foreach ($item->thegem_mega_menu_template_width_values as $value => $title): ?>
									<option value="<?php echo esc_attr($value); ?>" <?php selected($value, $item->thegem_mega_menu['template_width']); ?>><?php echo esc_html($title); ?></option>
								<?php endforeach; ?>
							</select>
						</label>
					</p>

					<p class="field-thegem-megamenu-template-width-custom description">
						<label for="edit-thegem_mega_menu_template_width_custom-<?php echo esc_attr($item_id); ?>">
							<?php esc_html_e('Custom Width', 'thegem'); ?><br/>
							<input id="edit-thegem_mega_menu_template_width_custom-<?php echo esc_attr($item_id); ?>"
								   class="thegem-edit-menu-item-template-width-custom" type="text"
								   name="thegem_mega_menu_template_width_custom[<?php echo esc_attr($item_id); ?>]"
								   value="<?php echo esc_html($item->thegem_mega_menu['template_width_custom']); ?>"/>
						</label>
					</p>

					<p class="field-thegem-megamenu-template-framing description">
						<label for="edit-thegem_mega_menu_template_framing-<?php echo esc_attr($item_id); ?>">
							<?php _e('Framing', 'thegem'); ?><br/>
							<select name="thegem_mega_menu_template_framing[<?php echo esc_attr($item_id); ?>]"
									id="edit-thegem_mega_menu_template_framing-<?php echo esc_attr($item_id); ?>"
									class="thegem-edit-menu-template-framing">
								<?php foreach ($item->thegem_mega_menu_template_framing_values as $value => $title): ?>
									<option value="<?php echo esc_attr($value); ?>" <?php selected($value, $item->thegem_mega_menu['template_framing']); ?>><?php echo esc_html($title); ?></option>
								<?php endforeach; ?>
							</select>
						</label>
					</p>

					<p class="field-thegem-megamenu-template-ajax">
						<label for="edit-thegem_mega_menu_template_ajax-<?php echo esc_attr($item_id); ?>">
							<input id="edit-thegem_mega_menu_template_ajax-<?php echo esc_attr($item_id); ?>" type="checkbox" class="thegem-edit-menu-item-icon-mega-template-ajax" name="thegem_mega_menu_template_ajax[<?php echo esc_attr($item_id); ?>]" <?php checked( $item->thegem_mega_menu['template_ajax'] ); ?>/>
							<?php esc_html_e( 'Enable AJAX loading', 'thegem' ); ?>
							<br><span class="description">
								<?php esc_html_e( 'Activates inline loading of styles. Use in case JavaScript delay is activated in one-click optimization.', 'thegem' ); ?>
							</span>
						</label>
					</p>

					<p class="field-thegem-megamenu-style description">
						<label for="edit-thegem_mega_menu_style-<?php echo esc_attr($item_id); ?>">
							<?php _e( 'Mega Menu Style', 'thegem' ); ?><br />
							<select name="thegem_mega_menu_style[<?php echo esc_attr($item_id); ?>]" id="edit-thegem_mega_menu_style-<?php echo esc_attr($item_id); ?>">
								<?php foreach( $item->thegem_mega_menu_styles_values as $value=>$title): ?>
									<option value="<?php echo esc_attr($value); ?>" <?php selected($value, $item->thegem_mega_menu['style']); ?>><?php echo esc_html($title); ?></option>
								<?php endforeach; ?>
							</select>
						</label>
					</p>

					<p class="field-thegem-megamenu-masonry">
						<label for="edit-thegem_mega_menu_masonry-<?php echo esc_attr($item_id); ?>">
							<input id="edit-thegem_mega_menu_masonry-<?php echo esc_attr($item_id); ?>" type="checkbox" class="thegem-edit-menu-item-icon-mega-masonry" name="thegem_mega_menu_masonry[<?php echo esc_attr($item_id); ?>]" <?php checked( $item->thegem_mega_menu['masonry'] ); ?>/>
							<?php esc_html_e( 'Mega Menu Masonry Style', 'thegem' ); ?>
						</label>
					</p>

					<p class="field-thegem-megamenu-columns description">
						<label for="edit-thegem_mega_menu_columns-<?php echo esc_attr($item_id); ?>">
							<?php esc_html_e( 'Number of columns: ', 'thegem' ); ?><br />
							<select name="thegem_mega_menu_columns[<?php echo esc_attr($item_id); ?>]" for="edit-thegem_mega_menu_columns-<?php echo esc_attr($item_id); ?>">
								<?php foreach( $item->thegem_mega_menu_columns_values as $value=>$title): ?>
									<option value="<?php echo esc_attr($value); ?>" <?php selected($value, $item->thegem_mega_menu['columns']); ?>><?php echo esc_html($title); ?></option>
								<?php endforeach; ?>
							</select>
						</label>
					</p>

					<p class="field-thegem-megamenu-image description">
						<label for="edit-thegem_mega_menu_image-<?php echo esc_attr($item_id); ?>">
							<?php esc_html_e( 'Background image', 'thegem' ); ?><br />
							<input id="edit-thegem_mega_menu_image-<?php echo esc_attr($item_id); ?>" class= "thegem-edit-menu-item-image picture-select" type="text" name="thegem_mega_menu_image[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_html( $item->thegem_mega_menu['image'] ); ?>"/>
							<button class="picture-select-button"><?php esc_html_e( 'Select', 'thegem' ); ?></button>
						</label>
					</p>

					<p class="field-thegem-megamenu-image-position description">
						<label for="edit-thegem_mega_menu_image_position-<?php echo esc_attr($item_id); ?>">
							<?php esc_html_e( 'Position: ', 'thegem' ); ?><br />
							<select name="thegem_mega_menu_image_position[<?php echo esc_attr($item_id); ?>]" for="edit-thegem_mega_menu_image_position-<?php echo esc_attr($item_id); ?>">
								<?php foreach( $item->thegem_mega_menu_image_position_values as $value=>$title): ?>
									<option value="<?php echo esc_attr($value); ?>" <?php selected($value, $item->thegem_mega_menu['image_position']); ?>><?php echo esc_html($title); ?></option>
								<?php endforeach; ?>
							</select>
						</label>
					</p>

					<fieldset class="fieldset-thegem-megamenu-padding">
						<legend><?php esc_html_e( 'Padding: ', 'thegem' ); ?></legend>

						<p class="field-thegem-megamenu-padding-left description description-thin">
							<label for="edit-thegem_mega_menu_padding_left-<?php echo esc_attr($item_id); ?>">
								<?php esc_html_e( 'Left', 'thegem' ); ?><br />
								<input id="edit-thegem_mega_menu_padding_left-<?php echo esc_attr($item_id); ?>" class="thegem-edit-menu-item-padding-left" type="text" name="thegem_mega_menu_padding_left[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_html( $item->thegem_mega_menu['padding_left'] ); ?>"/>
							</label>
						</p>

						<p class="field-thegem-megamenu-padding-right description description-thin">
							<label for="edit-thegem_mega_menu_padding_right-<?php echo esc_attr($item_id); ?>">
								<?php esc_html_e( 'Right', 'thegem' ); ?><br />
								<input id="edit-thegem_mega_menu_padding_right-<?php echo esc_attr($item_id); ?>" class="thegem-edit-menu-item-padding-right" type="text" name="thegem_mega_menu_padding_right[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_html( $item->thegem_mega_menu['padding_right'] ); ?>"/>
							</label>
						</p>

						<p class="field-thegem-megamenu-padding-top description description-thin">
							<label for="edit-thegem_mega_menu_padding_top-<?php echo esc_attr($item_id); ?>">
								<?php esc_html_e( 'Top', 'thegem' ); ?><br />
								<input id="edit-thegem_mega_menu_padding_top-<?php echo esc_attr($item_id); ?>" class="thegem-edit-menu-item-padding-top" type="text" name="thegem_mega_menu_padding_top[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_html( $item->thegem_mega_menu['padding_top'] ); ?>"/>
							</label>
						</p>

						<p class="field-thegem-megamenu-padding-bottom description description-thin">
							<label for="edit-thegem_mega_menu_padding_bottom-<?php echo esc_attr($item_id); ?>">
								<?php esc_html_e( 'Bottom', 'thegem' ); ?><br />
								<input id="edit-thegem_mega_menu_padding_bottom-<?php echo esc_attr($item_id); ?>" class="thegem-edit-menu-item-padding-bottom" type="text" name="thegem_mega_menu_padding_bottom[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_html( $item->thegem_mega_menu['padding_bottom'] ); ?>"/>
							</label>
						</p>
						<br class="clear" />
					</fieldset>

					<!-- second level -->
					<p class="field-thegem-megamenu-width description">
						<label for="edit-thegem_mega_menu_width-<?php echo esc_attr($item_id); ?>">
							<?php esc_html_e( 'Column width', 'thegem' ); ?><br />
							<input id="edit-thegem_mega_menu_width-<?php echo esc_attr($item_id); ?>" class= "thegem-edit-menu-item-width" type="text" name="thegem_mega_menu_width[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_html( $item->thegem_mega_menu['width'] ); ?>"/>
						</label>
					</p>

					<p class="field-thegem-megamenu-not-link">
						<label for="edit-thegem_mega_menu_not_link-<?php echo esc_attr($item_id); ?>">
							<input id="edit-thegem_mega_menu_not_link-<?php echo esc_attr($item_id); ?>" type="checkbox" class="thegem-edit-menu-item-not-link" name="thegem_mega_menu_not_link[<?php echo esc_attr($item_id); ?>]" <?php checked( $item->thegem_mega_menu['not_link'] ); ?>/>
							<?php esc_html_e( 'Don\'t link', 'thegem' ); ?>
						</label>
					</p>

					<p class="field-thegem-megamenu-not-show">
						<label for="edit-thegem_mega_menu_not_show-<?php echo esc_attr($item_id); ?>">
							<input id="edit-thegem_mega_menu_not_show-<?php echo esc_attr($item_id); ?>" type="checkbox" class="thegem-edit-menu-item-not-show" name="thegem_mega_menu_not_show[<?php echo esc_attr($item_id); ?>]" <?php checked( $item->thegem_mega_menu['not_show'] ); ?>/>
							<?php esc_html_e( 'Don\'t show', 'thegem' ); ?>
						</label>
					</p>

					<p class="field-thegem-megamenu-new-row">
						<label for="edit-thegem_mega_menu_new_row-<?php echo esc_attr($item_id); ?>">
							<input id="edit-thegem_mega_menu_new_row-<?php echo esc_attr($item_id); ?>" type="checkbox" class="thegem-edit-menu-item-new-root" name="thegem_mega_menu_new_row[<?php echo esc_attr($item_id); ?>]" <?php checked( $item->thegem_mega_menu['new_row'] ); ?>/>
							<?php esc_html_e( 'This item should start a new row', 'thegem' ); ?>
						</label>
					</p>

					<!-- third level -->
					<p class="field-thegem-megamenu-label description">
						<label for="edit-thegem_mega_menu_label-<?php echo esc_attr($item_id); ?>">
							<?php esc_html_e( 'Label', 'thegem' ); ?><br />
							<input id="edit-thegem_mega_menu_label-<?php echo esc_attr($item_id); ?>" class= "thegem-edit-menu-item-label" type="text" name="thegem_mega_menu_label[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_html( $item->thegem_mega_menu['label'] ); ?>"/>
						</label>
					</p>

				</div>

				<!-- TheGem Mega Menu End -->


		<?php
		// inject custom field HTML
		$output .= preg_replace(
		// NOTE: Check this regex from time to time!
			'/(?=<(fieldset|p)[^>]+class="[^"]*field-move)/',
			ob_get_clean(),
			$item_output
		);
	}

}
