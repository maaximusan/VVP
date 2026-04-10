<?php

/**
 * Template Post Type: thegem_templates
 *
 * @package TheGem
 */

get_template_part( 'template-post', thegem_get_template_type(get_the_ID()) );
