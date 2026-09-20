<?php
/**
 * Content and blog Customizer controls.
 *
 * Registers blog intro, archives, cards and single-post settings.
 *
 * @package CreceWebLumen
 */

namespace CreceWeb\Lumen;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers Contenido y blog controls.
 *
 * @param \WP_Customize_Manager $wp_customize Manager.
 * @return void
 */
function register_content_blog_controls( \WP_Customize_Manager $wp_customize ): void {
	// Diseño > Contenido y blog — recorrido guiado.
	add_customizer_note( $wp_customize, 'creceweb_blog_intro_note', 'creceweb_content_blog', __( 'Cabecera del blog', 'creceweb-lumen' ), __( 'Personalizá el bloque editorial que aparece antes de las últimas entradas. No modifica la cabecera global del sitio.', 'creceweb-lumen' ), 5, 'overview' );
	add_customizer_checkbox( $wp_customize, 'blog_intro_enabled', 'creceweb_content_blog', __( 'Mostrar cabecera del blog', 'creceweb-lumen' ), 10, __( 'Se muestra en la página de entradas y en la portada cuando muestra las últimas entradas.', 'creceweb-lumen' ), null, 'refresh' );
	add_customizer_text( $wp_customize, 'blog_intro_eyebrow', 'creceweb_content_blog', __( 'Etiqueta superior', 'creceweb-lumen' ), 20, __( 'Ejemplo: Blog · Ideas y novedades.', 'creceweb-lumen' ), 'text', array( 'maxlength' => 60 ), __NAMESPACE__ . '\is_blog_intro_active', 'refresh' );
	add_customizer_text( $wp_customize, 'blog_intro_title', 'creceweb_content_blog', __( 'Título', 'creceweb-lumen' ), 30, __( 'Dejalo vacío para usar el título de la página de entradas o “Ideas y novedades”.', 'creceweb-lumen' ), 'text', array( 'maxlength' => 90 ), __NAMESPACE__ . '\is_blog_intro_active', 'refresh' );
	add_customizer_text( $wp_customize, 'blog_intro_description', 'creceweb_content_blog', __( 'Descripción', 'creceweb-lumen' ), 40, __( 'Texto inicial: “Ideas, recursos y novedades para impulsar tu presencia digital.” Podés editarlo o dejarlo vacío para recuperar ese texto.', 'creceweb-lumen' ), 'textarea', array( 'rows' => 4, 'maxlength' => 280 ), __NAMESPACE__ . '\is_blog_intro_active', 'refresh' );
	add_customizer_checkbox( $wp_customize, 'blog_intro_show_button', 'creceweb_content_blog', __( 'Mostrar botón', 'creceweb-lumen' ), 50, '', __NAMESPACE__ . '\is_blog_intro_active', 'refresh' );
	add_customizer_text( $wp_customize, 'blog_intro_button_label', 'creceweb_content_blog', __( 'Texto del botón', 'creceweb-lumen' ), 60, '', 'text', array( 'maxlength' => 40 ), __NAMESPACE__ . '\is_blog_intro_button_active', 'refresh' );
	add_customizer_text( $wp_customize, 'blog_intro_button_url', 'creceweb_content_blog', __( 'Enlace del botón', 'creceweb-lumen' ), 70, __( 'Podés usar una URL completa o un ancla como #cw-latest-posts.', 'creceweb-lumen' ), 'url', array( 'placeholder' => '#cw-latest-posts' ), __NAMESPACE__ . '\is_blog_intro_button_active', 'refresh' );
	add_customizer_select( $wp_customize, 'blog_intro_visual_type', 'creceweb_content_blog', __( 'Visual lateral', 'creceweb-lumen' ), array( 'illustration' => __( 'Ilustración del theme', 'creceweb-lumen' ), 'image' => __( 'Imagen de la biblioteca', 'creceweb-lumen' ), 'none' => __( 'Sin visual', 'creceweb-lumen' ) ), 80, '', 'refresh', __NAMESPACE__ . '\is_blog_intro_active' );
	add_customizer_image( $wp_customize, 'blog_intro_image', 'creceweb_content_blog', __( 'Imagen de la cabecera', 'creceweb-lumen' ), 90, __( 'Se muestra como una imagen decorativa a la derecha.', 'creceweb-lumen' ), __NAMESPACE__ . '\is_blog_intro_image_active' );
	add_customizer_select( $wp_customize, 'blog_intro_alignment', 'creceweb_content_blog', __( 'Alineación del contenido', 'creceweb-lumen' ), array( 'left' => __( 'Izquierda', 'creceweb-lumen' ), 'center' => __( 'Centrada', 'creceweb-lumen' ) ), 100, '', 'refresh', __NAMESPACE__ . '\is_blog_intro_active' );

	add_customizer_note( $wp_customize, 'creceweb_blog_overview_note', 'creceweb_content_blog', __( 'Configuración en dos partes', 'creceweb-lumen' ), __( 'Primero definí cómo se ven el blog, las categorías y las búsquedas. Después configurá la lectura de cada artículo.', 'creceweb-lumen' ), 110, 'overview' );

	// Parte 1: archivos y tarjetas.
	add_customizer_note( $wp_customize, 'creceweb_blog_archive_note', 'creceweb_content_blog', __( '1. Listados del blog', 'creceweb-lumen' ), __( 'Se aplica al blog, categorías, etiquetas y resultados de búsqueda. No modifica páginas normales.', 'creceweb-lumen' ), 10, 'step' );
	add_customizer_note( $wp_customize, 'creceweb_blog_feed_header_note', 'creceweb_content_blog', __( 'Encabezado de últimas publicaciones', 'creceweb-lumen' ), __( 'Personalizá el título y la descripción que aparecen justo antes del listado en la página de entradas. La descripción puede quedar vacía.', 'creceweb-lumen' ), 12 );
	add_customizer_text( $wp_customize, 'blog_feed_title', 'creceweb_content_blog', __( 'Título del listado', 'creceweb-lumen' ), 13, __( 'Dejalo vacío para usar “Últimas publicaciones”.', 'creceweb-lumen' ), 'text', array( 'maxlength' => 90 ), null, 'refresh' );
	add_customizer_text( $wp_customize, 'blog_feed_description', 'creceweb_content_blog', __( 'Descripción del listado', 'creceweb-lumen' ), 14, __( 'Dejala vacía si no querés mostrar una descripción.', 'creceweb-lumen' ), 'textarea', array( 'rows' => 3, 'maxlength' => 240 ), null, 'refresh' );

	add_customizer_note( $wp_customize, 'creceweb_blog_structure_note', 'creceweb_content_blog', __( 'Diseño del listado', 'creceweb-lumen' ), __( 'Elegí Lista o Grilla. Debajo aparecen solo las opciones compatibles con esa elección.', 'creceweb-lumen' ), 20 );
	add_customizer_choice_cards( $wp_customize, 'blog_layout', 'creceweb_content_blog', __( 'Lista o grilla', 'creceweb-lumen' ), array( 'list' => __( 'Lista', 'creceweb-lumen' ), 'grid' => __( 'Grilla', 'creceweb-lumen' ) ), 30, 'blog' );
	add_customizer_select( $wp_customize, 'blog_columns', 'creceweb_content_blog', __( 'Cantidad de columnas', 'creceweb-lumen' ), array( '1' => __( '1 columna', 'creceweb-lumen' ), '2' => __( '2 columnas', 'creceweb-lumen' ), '3' => __( '3 columnas', 'creceweb-lumen' ) ), 40, __( 'En celular se adapta a una columna y en tablet utiliza hasta dos.', 'creceweb-lumen' ), 'postMessage', __NAMESPACE__ . '\is_blog_grid_active' );
	add_customizer_select( $wp_customize, 'blog_card_layout', 'creceweb_content_blog', __( 'Composición de las entradas', 'creceweb-lumen' ), array( 'vertical' => __( 'Imagen arriba', 'creceweb-lumen' ), 'media_left' => __( 'Imagen lateral', 'creceweb-lumen' ), 'compact' => __( 'Compacta', 'creceweb-lumen' ) ), 50, __( 'Solo se muestra con Lista. En Grilla las tarjetas usan siempre imagen arriba para mantener columnas consistentes.', 'creceweb-lumen' ), 'refresh', __NAMESPACE__ . '\is_blog_list_active' );

	add_customizer_note( $wp_customize, 'creceweb_blog_content_note', 'creceweb_content_blog', __( 'Contenido de cada tarjeta', 'creceweb-lumen' ), __( 'Activá únicamente la información que querés repetir en cada entrada del listado.', 'creceweb-lumen' ), 70 );
	add_customizer_checkbox( $wp_customize, 'blog_show_featured_image', 'creceweb_content_blog', __( 'Imagen destacada', 'creceweb-lumen' ), 80, '', null, 'refresh' );
	add_customizer_select( $wp_customize, 'blog_image_ratio', 'creceweb_content_blog', __( 'Proporción de imagen', 'creceweb-lumen' ), array( 'natural' => __( 'Original', 'creceweb-lumen' ), 'landscape' => __( 'Panorámica 16:9', 'creceweb-lumen' ), 'square' => __( 'Cuadrada 1:1', 'creceweb-lumen' ) ), 90, __( 'Funciona también con Imagen lateral y Compacta. Solo recorta la visualización de la tarjeta; no modifica el archivo original.', 'creceweb-lumen' ), 'refresh', __NAMESPACE__ . '\is_blog_featured_image_active' );
	add_customizer_checkbox( $wp_customize, 'blog_show_category', 'creceweb_content_blog', __( 'Categoría', 'creceweb-lumen' ), 100, '', null, 'refresh' );
	add_customizer_checkbox( $wp_customize, 'blog_show_meta', 'creceweb_content_blog', __( 'Fecha de publicación', 'creceweb-lumen' ), 110, '', null, 'refresh' );
	add_customizer_checkbox( $wp_customize, 'blog_show_excerpt', 'creceweb_content_blog', __( 'Extracto o resumen', 'creceweb-lumen' ), 120, '', null, 'refresh' );
	add_customizer_checkbox( $wp_customize, 'blog_show_read_more', 'creceweb_content_blog', __( 'Enlace “Seguir leyendo”', 'creceweb-lumen' ), 130, __( 'Desactivá esta opción para quitar la acción de la tarjeta.', 'creceweb-lumen' ), null, 'refresh' );
	add_customizer_text( $wp_customize, 'blog_read_more_label', 'creceweb_content_blog', __( 'Texto del enlace', 'creceweb-lumen' ), 135, __( 'Dejalo vacío para recuperar “Seguir leyendo”.', 'creceweb-lumen' ), 'text', array( 'maxlength' => 40 ), __NAMESPACE__ . '\is_blog_read_more_active', 'refresh' );

	add_customizer_note( $wp_customize, 'creceweb_blog_read_more_style_note', 'creceweb_content_blog', __( 'Apariencia de “Seguir leyendo”', 'creceweb-lumen' ), __( 'Elegí un estilo y una forma. Los colores son opcionales: si quedan vacíos, Lumen usa los colores propios del estilo seleccionado.', 'creceweb-lumen' ), 140 );
	add_customizer_select( $wp_customize, 'blog_read_more_style', 'creceweb_content_blog', __( 'Estilo', 'creceweb-lumen' ), array( 'lumen' => __( 'Predeterminado de Lumen', 'creceweb-lumen' ), 'inherit' => __( 'Heredar botones globales', 'creceweb-lumen' ), 'solid' => __( 'Sólido', 'creceweb-lumen' ), 'outline' => __( 'Contorno', 'creceweb-lumen' ), 'text' => __( 'Texto', 'creceweb-lumen' ) ), 145, '', 'refresh', __NAMESPACE__ . '\is_blog_read_more_active' );
	add_customizer_select( $wp_customize, 'blog_read_more_shape', 'creceweb_content_blog', __( 'Forma', 'creceweb-lumen' ), array( 'inherit' => __( 'Heredar forma global', 'creceweb-lumen' ), 'square' => __( 'Recta', 'creceweb-lumen' ), 'soft' => __( 'Suave', 'creceweb-lumen' ), 'pill' => __( 'Píldora', 'creceweb-lumen' ) ), 150, '', 'refresh', __NAMESPACE__ . '\is_blog_read_more_active' );
	add_customizer_color( $wp_customize, 'blog_read_more_background_color', 'creceweb_content_blog', __( 'Fondo', 'creceweb-lumen' ), 155, __NAMESPACE__ . '\is_blog_read_more_active', __( 'Opcional. Vacío conserva el fondo del estilo elegido.', 'creceweb-lumen' ), 'refresh' );
	add_customizer_color( $wp_customize, 'blog_read_more_hover_background_color', 'creceweb_content_blog', __( 'Fondo hover', 'creceweb-lumen' ), 160, __NAMESPACE__ . '\is_blog_read_more_active', __( 'Opcional. Color del fondo al pasar o enfocar.', 'creceweb-lumen' ), 'refresh' );
	add_customizer_color( $wp_customize, 'blog_read_more_text_color', 'creceweb_content_blog', __( 'Texto', 'creceweb-lumen' ), 165, __NAMESPACE__ . '\is_blog_read_more_active', __( 'Opcional. Vacío conserva el color del estilo elegido.', 'creceweb-lumen' ), 'refresh' );
	add_customizer_color( $wp_customize, 'blog_read_more_hover_text_color', 'creceweb_content_blog', __( 'Texto hover', 'creceweb-lumen' ), 170, __NAMESPACE__ . '\is_blog_read_more_active', __( 'Opcional. Color del texto al pasar o enfocar.', 'creceweb-lumen' ), 'refresh' );
	add_customizer_color( $wp_customize, 'blog_read_more_border_color', 'creceweb_content_blog', __( 'Borde', 'creceweb-lumen' ), 175, __NAMESPACE__ . '\is_blog_read_more_active', __( 'Opcional. Se usa cuando el estilo muestra borde.', 'creceweb-lumen' ), 'refresh' );

	add_customizer_note( $wp_customize, 'creceweb_blog_style_note', 'creceweb_content_blog', __( 'Apariencia de las tarjetas', 'creceweb-lumen' ), __( 'Definí cuánta separación visual tienen las entradas respecto al fondo general.', 'creceweb-lumen' ), 185 );
	add_customizer_select( $wp_customize, 'blog_surface', 'creceweb_content_blog', __( 'Borde o sombra', 'creceweb-lumen' ), array( 'minimal' => __( 'Sin borde ni sombra', 'creceweb-lumen' ), 'bordered' => __( 'Con borde', 'creceweb-lumen' ), 'elevated' => __( 'Con sombra', 'creceweb-lumen' ) ), 190 );

	// Parte 2: entrada individual.
	add_customizer_note( $wp_customize, 'creceweb_single_post_note', 'creceweb_content_blog', __( '2. Entrada individual', 'creceweb-lumen' ), __( 'Se aplica dentro de cada artículo. No cambia páginas, archivos ni contenido creado con constructores visuales.', 'creceweb-lumen' ), 200, 'step' );
	add_customizer_select( $wp_customize, 'single_layout', 'creceweb_content_blog', __( 'Ancho de lectura', 'creceweb-lumen' ), array( 'standard' => __( 'Estándar', 'creceweb-lumen' ), 'narrow' => __( 'Angosto', 'creceweb-lumen' ), 'wide' => __( 'Amplio', 'creceweb-lumen' ), 'full' => __( 'Ancho completo', 'creceweb-lumen' ) ), 210, __( 'Angosto favorece artículos largos; Amplio aprovecha el ancho amplio del sitio; Ancho completo permite que bloques y constructores visuales usen todo el ancho disponible.', 'creceweb-lumen' ), 'refresh' );
	add_customizer_select( $wp_customize, 'single_header_alignment', 'creceweb_content_blog', __( 'Alineación del encabezado', 'creceweb-lumen' ), array( 'left' => __( 'Izquierda', 'creceweb-lumen' ), 'center' => __( 'Centrada', 'creceweb-lumen' ) ), 220, __( 'Incluye título y datos de la entrada.', 'creceweb-lumen' ), 'refresh' );
	add_customizer_select( $wp_customize, 'single_featured_position', 'creceweb_content_blog', __( 'Imagen destacada', 'creceweb-lumen' ), array( 'above_header' => __( 'Antes del título', 'creceweb-lumen' ), 'below_header' => __( 'Después del título', 'creceweb-lumen' ), 'hidden' => __( 'Ocultar', 'creceweb-lumen' ) ), 230, '', 'refresh' );
	add_customizer_select( $wp_customize, 'single_meta_visibility', 'creceweb_content_blog', __( 'Información visible', 'creceweb-lumen' ), array( 'date_author' => __( 'Fecha y autor', 'creceweb-lumen' ), 'date' => __( 'Solo fecha', 'creceweb-lumen' ), 'hidden' => __( 'Ocultar', 'creceweb-lumen' ) ), 240, '', 'refresh' );
}
