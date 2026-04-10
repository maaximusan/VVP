<?php

function thegem_wp_image_editor( $editorArray ) {
	return array(
		'WP_Image_Editor_GD',
		'WP_Image_Editor_Imagick',
	);
}
add_filter( 'wp_image_editors', 'thegem_wp_image_editor' );

function thegem_get_attachment_relative_path( $file ) {
	$dirname = dirname( $file );
	$uploads = wp_upload_dir();

	if ( '.' === $dirname ) {
		return '';
	}

	return str_replace($uploads['basedir'], '', $dirname);
}

if (!function_exists('thegem_get_generated_image_sizes')) {
	function thegem_get_generated_image_sizes() {
		global $_wp_additional_image_sizes;

		$default_image_sizes = array('thumbnail', 'medium', 'medium_large', 'large');
		$available_sizes = array();

		foreach ($default_image_sizes as $size) {
			$available_sizes[$size] = array(
				'width' => (int)get_option($size . '_size_w'),
				'height' => (int)get_option($size . '_size_h'),
				'crop' => (bool)get_option($size . '_crop'),
			);
		}

		if ($_wp_additional_image_sizes) {
			$available_sizes = array_merge($available_sizes, $_wp_additional_image_sizes);
		}

		return $available_sizes;
	}
}

if(!function_exists('thegem_generate_thumbnail_src')) {

	function thegem_generate_thumbnail_src($attachment_id, $size) {
		$thegem_image_sizes = thegem_image_sizes();
		if(!isset($thegem_image_sizes[$size])) {
			return wp_get_attachment_image_src($attachment_id, $size);
		}
		$data = thegem_image_cache_get($attachment_id, $size);
		if ($data) {
			return $data;
		}

		$data = thegem_get_thumbnail_src($attachment_id, $size);
		if ($data) {
			thegem_image_cache_set($attachment_id, $size, $data);
		}
		return $data;
	}

	function thegem_get_thumbnail_src($attachment_id, $size) {
		$thegem_image_sizes = thegem_image_sizes();
		$is_svg = strpos(get_post_mime_type($attachment_id), 'svg') > 0;
		if ($attachment_id == 'THEGEM_TRANSPARENT_IMAGE' && in_array($size, ['woocommerce_thumbnail', 'woocommerce_single', 'thumbnail', 'medium', 'medium_large', 'large', '1536x1536', '2048x2048'])) {
			$size_arr = thegem_get_generated_image_sizes()[$size];
			$thegem_image_sizes[$size] = array($size_arr['width'], $size_arr['height'], $size_arr['crop']);
		}

		if(isset($thegem_image_sizes[$size]) && !$is_svg) {
			$attachment_path = get_attached_file($attachment_id);
			if (!$attachment_path || !file_exists($attachment_path)) {
				$default_img = wp_get_attachment_image_src($attachment_id, $thegem_image_sizes[$size]);
				if(is_array($default_img)) {
					$default_img['not_generated'] = true;
				}
				return $default_img;
			}

			$dummy_image_editor = new TheGem_Dummy_WP_Image_Editor($attachment_path);
			$attachment_thumb_path = $dummy_image_editor->generate_filename($size);
			$attachment_thumb_path = apply_filters('thegem_attachment_thumbnail_path', $attachment_thumb_path, $attachment_id);

			if (file_exists($attachment_thumb_path)) {
				$file_data = thegem_build_image_data($attachment_thumb_path);
			}

			if (!file_exists($attachment_thumb_path) || $file_data[1] !== $thegem_image_sizes[$size][0] || $file_data[2] !== $thegem_image_sizes[$size][1]) {
				$image_editor = wp_get_image_editor($attachment_path);
				if (!is_wp_error($image_editor) && !is_wp_error($image_editor->resize($thegem_image_sizes[$size][0], $thegem_image_sizes[$size][1], $thegem_image_sizes[$size][2]))) {
					$attachment_resized = $image_editor->save($attachment_thumb_path);
					if (!is_wp_error($attachment_resized) && $attachment_resized) {
						do_action('thegem_thumbnail_generated', array('/'._wp_relative_upload_path($attachment_thumb_path)));
						return thegem_build_image_result($attachment_resized['path'], $attachment_resized['width'], $attachment_resized['height']);
					} else {
						return thegem_build_image_data($attachment_path);
					}
				} else {
					return thegem_build_image_data($attachment_path);
				}
			}
			return $file_data;
		}
		return wp_get_attachment_image_src($attachment_id, $size);
	}

	function thegem_build_image_data($path) {
		$editor = new TheGem_Dummy_WP_Image_Editor($path);
		$size = $editor->get_size();
		if (!$size) {
			return null;
		}
		return thegem_build_image_result($path, $size['width'], $size['height']);
	}

	function thegem_image_cache_get($attachment_id, $size) {

		$thegem_image_src_cache = get_post_meta($attachment_id, 'thegem_image_src_cache', true);
		$thegem_image_regenerated = get_post_meta($attachment_id, 'thegem_image_regenerated', true);

		if (empty($thegem_image_src_cache) || !is_array($thegem_image_src_cache)) {
			$thegem_image_src_cache = array();
		}

		if (!empty($thegem_image_regenerated) &&
				isset($thegem_image_src_cache[$size]['time']) &&
				$thegem_image_regenerated >= $thegem_image_src_cache[$size]['time']) {
			return false;
		}

		if (!empty($thegem_image_src_cache[$size])) {
			$data = $thegem_image_src_cache[$size];
			unset($data['time']);
			$uploads = wp_upload_dir();
			if ( is_ssl() && ! is_admin() && 'wp-login.php' !== $GLOBALS['pagenow'] ) {
				$uploads['baseurl'] = set_url_scheme( $uploads['baseurl'] );
			}
			$data[0] = (empty($data['not_generated']) ? $uploads['baseurl'] : '') . $data[0];
			return $data;
		}
		return false;
	}

	function thegem_image_cache_set($attachment_id, $size, $data) {
		global $thegem_image_src_cache_changed;

		$thegem_image_src_cache = get_post_meta($attachment_id, 'thegem_image_src_cache', true);

		if (empty($thegem_image_src_cache) || !is_array($thegem_image_src_cache)) {
			$thegem_image_src_cache = array();
		}

		$data['time'] = time();
		$uploads = wp_upload_dir();
		if ( is_ssl() && ! is_admin() && 'wp-login.php' !== $GLOBALS['pagenow'] ) {
			$uploads['baseurl'] = set_url_scheme( $uploads['baseurl'] );
		}
		$data[0] = str_replace($uploads['baseurl'], '', $data[0]);
		$thegem_image_src_cache[$size] = $data;
		update_post_meta($attachment_id, 'thegem_image_src_cache', $thegem_image_src_cache);
		$thegem_image_src_cache_changed = true;
	}

	function thegem_build_image_result($file, $width, $height) {
		$uploads = wp_upload_dir();
		$url = trailingslashit( $uploads['baseurl'] . thegem_get_attachment_relative_path( $file ) ) . basename( $file );
		if ( is_ssl() && ! is_admin() && 'wp-login.php' !== $GLOBALS['pagenow'] ) {
			$url = set_url_scheme( $url );
		}
		return array($url, $width, $height);
	}

}


/* DEPRECATED */
function thegem_get_image_cache_option_key_prefix() {
	return 'thegem_image_cache_';
}