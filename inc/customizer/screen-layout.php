<?php
/**
 * Screen-layout Customizer controls.
 *
 * Registers native sidebar layout controls.
 *
 * @package CreceWebLumen
 */

namespace CreceWeb\Lumen;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers Diseño de pantalla controls.
 *
 * @param \WP_Customize_Manager $wp_customize Manager.
 * @return void
 */
function register_screen_layout_controls( \WP_Customize_Manager $wp_customize ): void {
	// Diseño > Diseño de pantalla — barras laterales.
	add_customizer_note( $wp_customize, 'creceweb_sidebar_note', 'creceweb_screen_layout', __( 'Barras laterales', 'creceweb-lumen' ), __( 'Elegí dónde usar la barra lateral. Agregá su contenido desde Apariencia > Widgets > Barra lateral principal. Sin widgets activos, el contenido ocupa todo el ancho.', 'creceweb-lumen' ), 5 );
	add_customizer_select( $wp_customize, 'sidebar_layout', 'creceweb_screen_layout', __( 'Diseño global', 'creceweb-lumen' ), array( 'none' => __( 'Sin barra lateral', 'creceweb-lumen' ), 'right' => __( 'Barra lateral derecha', 'creceweb-lumen' ), 'left' => __( 'Barra lateral izquierda', 'creceweb-lumen' ) ), 10, __( 'Valor base para archivos, entradas y páginas que usan “Heredar global”.', 'creceweb-lumen' ), 'refresh' );
	add_customizer_select( $wp_customize, 'archive_sidebar_layout', 'creceweb_screen_layout', __( 'Blog, categorías y búsquedas', 'creceweb-lumen' ), array( 'inherit' => __( 'Heredar global', 'creceweb-lumen' ), 'none' => __( 'Sin barra lateral', 'creceweb-lumen' ), 'right' => __( 'Barra lateral derecha', 'creceweb-lumen' ), 'left' => __( 'Barra lateral izquierda', 'creceweb-lumen' ) ), 20, '', 'refresh' );
	add_customizer_select( $wp_customize, 'single_sidebar_layout', 'creceweb_screen_layout', __( 'Entradas individuales', 'creceweb-lumen' ), array( 'inherit' => __( 'Heredar global', 'creceweb-lumen' ), 'none' => __( 'Sin barra lateral', 'creceweb-lumen' ), 'right' => __( 'Barra lateral derecha', 'creceweb-lumen' ), 'left' => __( 'Barra lateral izquierda', 'creceweb-lumen' ) ), 30, '', 'refresh' );
	add_customizer_select( $wp_customize, 'page_sidebar_layout', 'creceweb_screen_layout', __( 'Páginas', 'creceweb-lumen' ), array( 'inherit' => __( 'Heredar global', 'creceweb-lumen' ), 'none' => __( 'Sin barra lateral', 'creceweb-lumen' ), 'right' => __( 'Barra lateral derecha', 'creceweb-lumen' ), 'left' => __( 'Barra lateral izquierda', 'creceweb-lumen' ) ), 40, __( 'Las plantillas “Ancho completo” y “Landing sin cabecera” no incluyen barra lateral.', 'creceweb-lumen' ), 'refresh' );
	add_customizer_select( $wp_customize, 'sidebar_width', 'creceweb_screen_layout', __( 'Ancho de barra lateral', 'creceweb-lumen' ), array( 'narrow' => __( 'Angosta', 'creceweb-lumen' ), 'standard' => __( 'Estándar', 'creceweb-lumen' ), 'wide' => __( 'Amplia', 'creceweb-lumen' ) ), 50, __( 'En celular y tablet angosta la barra se apila automáticamente debajo del contenido.', 'creceweb-lumen' ), 'refresh' );
}
