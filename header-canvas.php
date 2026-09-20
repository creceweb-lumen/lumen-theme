<?php
/**
 * Canvas header for builder landing pages.
 *
 * @package CreceWebLumen
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head><meta charset="<?php bloginfo( 'charset' ); ?>" /><meta name="viewport" content="width=device-width, initial-scale=1" /><?php wp_head(); ?></head>
<body <?php body_class( 'cw-builder-canvas' ); ?>>
<?php wp_body_open(); ?>
