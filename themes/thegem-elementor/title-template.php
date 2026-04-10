<?php
	global $thegem_page_title_template_data;
	$thegem_use_custom = get_post($thegem_page_title_template_data['title_template']);
	$thegem_q = new WP_Query(array('p' => $thegem_page_title_template_data['title_template'], 'post_type' => array('thegem_title', 'thegem_templates'), 'post_status' => array('publish', 'private')));
	$thegem_has_breadcrumbs = false;
	$thegem_breadcrumbs_widgets = thegem_get_content_widgets($thegem_page_title_template_data['title_template'], array('thegem-template-blog-archive-breadcrumbs', 'thegem-template-post-breadcrumbs'));
	$thegem_has_breadcrumbs = !empty($thegem_breadcrumbs_widgets);
?>
<div id="page-title" class="page-title-block custom-page-title">
	<?php if($thegem_page_title_template_data['title_template'] && $thegem_use_custom && $thegem_q->have_posts()) : $thegem_q->the_post(); ?>
		<div class="<?php echo (get_page_template_slug() !== 'single-thegem_title-fullwidth.php' && get_post_type() != 'thegem_templates' ? 'container' : 'fullwidth-content' ); ?>">
			<?php echo \Elementor\Plugin::$instance->frontend->get_builder_content( get_the_ID(), false ); ?>
		</div>
	<?php wp_reset_postdata(); endif; ?>
	<?php if(!$thegem_has_breadcrumbs) : ?>
		<div class="page-title-alignment-<?php echo $thegem_page_title_template_data['title_alignment']; ?>"><?php echo $thegem_page_title_template_data['breadcrumbs_output']; ?></div>
	<?php endif; ?>
	<?php
	if ( defined('ELEMENTOR_VERSION') && \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
		?>
		<div class="edit-template-overlay">
			<div class="buttons">
				<?php
				$link = add_query_arg(
					array(
						'elementor' => '',
					),
					get_permalink( $thegem_page_title_template_data['title_template'] )
				);
				echo sprintf( '<a class="gem-tta-template-edit" data-tta-template-edit-link="%s">%s</a>', $link, esc_html__( 'Edit Title Area Template', 'thegem' ) );
				?>
				<a class="doc gem-tta-template-edit" data-tta-template-edit-link="https://docs.codex-themes.com/category/184-title-area-builder">?</a>
			</div>
		</div>
	<?php }
	?>
</div>
<?php
	if(!empty($GLOBALS['thagem_page_404'])) {
		$thegem_q = new WP_Query(array('page_id' => $GLOBALS['thagem_page_404']));
		$thegem_q->the_post();
	}
