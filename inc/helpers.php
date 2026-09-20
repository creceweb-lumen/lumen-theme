<?php
/**
 * Helpers shared by CreceWeb Lumen.
 *
 * @package CreceWebLumen
 */

namespace CreceWeb\Lumen;

/**
 * Returns the current theme version.
 *
 * @return string
 */
function get_version(): string {
	return CRECEWEB_LUMEN_VERSION;
}

/**
 * Reports whether the current template can provide one continuous surface
 * beneath the complete site header.
 *
 * The default page template with an active sidebar deliberately disables the
 * transparent-header contract because the header would otherwise span both the
 * Hero surface and the independent sidebar surface. Templates that do not set
 * an explicit decision remain compatible by default.
 *
 * @return bool
 */
function supports_transparent_header(): bool {
	if ( ! array_key_exists( 'creceweb_lumen_transparent_header_supported', $GLOBALS ) ) {
		return true;
	}

	return true === $GLOBALS['creceweb_lumen_transparent_header_supported'];
}


/**
 * Returns the leading Gutenberg block when it is explicitly aligned full width.
 *
 * The Hero ancho completo template uses this small, predictable convention to
 * keep a real hero outside the later content/sidebar grid. Empty editorial
 * artifacts are ignored, but the first visually meaningful block must opt into
 * full alignment through Gutenberg's native `align` attribute or `alignfull` class.
 *
 * @param array<int, array<string, mixed>> $blocks Parsed block list.
 * @return array{index:int, block:array<string,mixed>}|null
 */
function get_leading_full_width_block( array $blocks ): ?array {
	foreach ( $blocks as $index => $block ) {
		if ( ! is_array( $block ) ) {
			continue;
		}

		/*
		 * Gutenberg puede conservar antes del pattern un párrafo vacío, un bloque
		 * clásico con solo espacios o comentarios editoriales. Esos artefactos no
		 * deben impedir que el primer bloque visual real active el contrato Hero.
		 * Los separadores, spacers con altura, fondos, medios y cualquier bloque
		 * realmente visible siguen cortando la detección de manera deliberada.
		 */
		if ( ! block_has_visible_content( $block ) ) {
			continue;
		}

		$attributes = is_array( $block['attrs'] ?? null ) ? $block['attrs'] : array();
		$align      = (string) ( $attributes['align'] ?? '' );
		$class_name = (string) ( $attributes['className'] ?? '' );

		if ( 'full' === $align || false !== strpos( ' ' . $class_name . ' ', ' alignfull ' ) ) {
			return array(
				'index' => (int) $index,
				'block' => $block,
			);
		}

		return null;
	}

	return null;
}


/**
 * Checks a whitespace-delimited class string for one of the supplied tokens.
 *
 * @param string              $class_name Class attribute value.
 * @param array<int, string>  $tokens Accepted class tokens.
 * @return bool
 */
function class_string_has_token( string $class_name, array $tokens ): bool {
	$classes = preg_split( '/\s+/', trim( $class_name ) );
	if ( ! is_array( $classes ) ) {
		return false;
	}

	foreach ( $tokens as $token ) {
		if ( in_array( $token, $classes, true ) ) {
			return true;
		}
	}

	return false;
}

/**
 * Checks rendered HTML for one of the supplied class tokens.
 *
 * @param string             $html Rendered or stored block HTML.
 * @param array<int,string>  $tokens Accepted class tokens.
 * @return bool
 */
function html_has_class_token( string $html, array $tokens ): bool {
	if ( '' === trim( $html ) ) {
		return false;
	}

	if ( ! preg_match_all( "/\\bclass\\s*=\\s*(['\"])(.*?)\\1/i", $html, $matches ) ) {
		return false;
	}

	foreach ( $matches[2] as $class_name ) {
		if ( class_string_has_token( (string) $class_name, $tokens ) ) {
			return true;
		}
	}

	return false;
}

/**
 * Determines whether a Gutenberg block is a Lumen Hero Base or a Pro variant.
 *
 * The public base class is `cw-lumen-hero`. The legacy/pro class
 * `cw-lumen-pro-hero` remains accepted so existing pages and Pro patterns keep
 * the same template behavior. The two legacy Lite Hero pattern classes are
 * accepted only as a migration bridge for content inserted before alpha.38.
 *
 * @param array<string,mixed>|null $block Parsed block.
 * @return bool
 */
function block_has_lumen_hero_class( $block ): bool {
	if ( ! is_array( $block ) ) {
		return false;
	}

	$tokens     = array( 'cw-lumen-hero', 'cw-lumen-pro-hero', 'cw-lumen-lite-pattern--hero-split', 'cw-lumen-lite-pattern--cta' );
	$attributes = is_array( $block['attrs'] ?? null ) ? $block['attrs'] : array();
	$class_name = (string) ( $attributes['className'] ?? '' );

	if ( class_string_has_token( $class_name, $tokens ) ) {
		return true;
	}

	$inner_html = (string) ( $block['innerHTML'] ?? '' );
	if ( html_has_class_token( $inner_html, $tokens ) ) {
		return true;
	}

	$inner_blocks = $block['innerBlocks'] ?? array();
	if ( is_array( $inner_blocks ) ) {
		foreach ( $inner_blocks as $inner_block ) {
			if ( block_has_lumen_hero_class( $inner_block ) ) {
				return true;
			}
		}
	}

	return false;
}

/**
 * Returns the leading full-width block only when it is a Lumen Hero.
 *
 * @param array<int, array<string,mixed>> $blocks Parsed block list.
 * @return array{index:int, block:array<string,mixed>}|null
 */
function get_leading_lumen_hero_block( array $blocks ): ?array {
	$leading = get_leading_full_width_block( $blocks );
	if ( null === $leading || ! block_has_lumen_hero_class( $leading['block'] ) ) {
		return null;
	}

	return $leading;
}


/**
 * Determines whether a parsed Gutenberg block produces a visible region.
 *
 * Gutenberg can keep trailing empty paragraphs, whitespace-only freeform
 * blocks, or editorial comments in post_content after a Cover. Those parser
 * artifacts must not turn a true hero-only page into a hero-plus-empty-content
 * layout. Real visual blocks (media, separators, styled spacers and nested
 * block content) continue to count as posterior content.
 *
 * @param array<string, mixed> $block Parsed Gutenberg block.
 * @return bool
 */
function block_has_visible_content( array $block ): bool {
	$block_name   = (string) ( $block['blockName'] ?? '' );
	$inner_blocks = $block['innerBlocks'] ?? array();

	if ( is_array( $inner_blocks ) ) {
		foreach ( $inner_blocks as $inner_block ) {
			if ( is_array( $inner_block ) && block_has_visible_content( $inner_block ) ) {
				return true;
			}
		}
	}

	$inner_html = (string) ( $block['innerHTML'] ?? '' );
	$markup     = preg_replace( '/<!--[\s\S]*?-->/', '', $inner_html );
	$markup     = is_string( $markup ) ? $markup : $inner_html;
	$text       = html_entity_decode( strip_tags( $markup ), ENT_QUOTES | ENT_HTML5, 'UTF-8' );
	$text       = preg_replace( '/[\s\p{Z}\x{200B}\x{FEFF}]+/u', '', $text );

	if ( is_string( $text ) && '' !== $text ) {
		return true;
	}

	/* Media and structural blocks can be visible even without textual content. */
	if ( preg_match( '/<(?:img|svg|video|audio|iframe|hr|canvas|table|form|input|select|textarea|button|object|embed)\b/i', $markup ) ) {
		return true;
	}

	$attributes = is_array( $block['attrs'] ?? null ) ? $block['attrs'] : array();
	$style      = $attributes['style'] ?? null;
	if ( ( is_array( $style ) && ! empty( $style ) ) || ( is_string( $style ) && '' !== trim( $style ) ) ) {
		return true;
	}

	if ( 'core/spacer' === $block_name ) {
		$height = $attributes['height'] ?? '';
		$height = is_scalar( $height ) ? strtolower( trim( (string) $height ) ) : '';
		return ! in_array( $height, array( '', '0', '0px', '0em', '0rem', '0vh', '0vw', '0%' ), true );
	}

	return false;
}

/**
 * Removes parser-only blocks that cannot create visible posterior content.
 *
 * @param array<int, array<string, mixed>> $blocks Parsed block list.
 * @return array<int, array<string, mixed>>
 */
function get_visible_blocks( array $blocks ): array {
	$visible_blocks = array();

	foreach ( $blocks as $block ) {
		if ( is_array( $block ) && block_has_visible_content( $block ) ) {
			$visible_blocks[] = $block;
		}
	}

	return $visible_blocks;
}


/**
 * Renders a parsed block list without changing the original block order.
 *
 * @param array<int, array<string, mixed>> $blocks Parsed block list.
 * @return string
 */
function render_blocks( array $blocks ): string {
	$output = '';

	foreach ( $blocks as $block ) {
		if ( is_array( $block ) ) {
			$output .= render_block( $block );
		}
	}

	return $output;
}


/**
 * Returns the class tokens from an HTML opening tag.
 *
 * @param string $opening_tag HTML opening tag.
 * @return array<int, string>
 */
function get_html_tag_classes( string $opening_tag ): array {
	if ( ! preg_match( '/\bclass\s*=\s*(["\'])(.*?)\1/i', $opening_tag, $matches ) ) {
		return array();
	}

	$classes = preg_split( '/\s+/', trim( (string) $matches[2] ) );
	return is_array( $classes ) ? array_filter( $classes ) : array();
}

/**
 * Finds the closing tag that belongs to a DIV opening tag.
 *
 * Elementor outputs nested DIVs, while the PHP build used for Lumen does not
 * require the DOM extension. This small balanced-tag reader only handles the
 * wrapper needed by the template and leaves Elementor markup untouched.
 *
 * @param string $html       Full HTML fragment.
 * @param int    $open_start Offset of the opening DIV.
 * @return array{close_start:int,close_end:int}|null
 */
function find_matching_div_end( string $html, int $open_start ): ?array {
	if ( ! preg_match_all( '/<\/?div\b[^>]*>/i', $html, $matches, PREG_OFFSET_CAPTURE, $open_start ) ) {
		return null;
	}

	$depth = 0;
	foreach ( $matches[0] as $token ) {
		$tag    = (string) $token[0];
		$offset = (int) $token[1];

		if ( 0 === stripos( $tag, '</div' ) ) {
			$depth--;
			if ( 0 === $depth ) {
				return array(
					'close_start' => $offset,
					'close_end'   => $offset + strlen( $tag ),
				);
			}
			continue;
		}

		$depth++;
	}

	return null;
}

/**
 * Splits an Elementor page root into its first visual section and the content
 * that follows it. The template can render the first section outside the
 * sidebar grid as the hero, then render the remaining Elementor sections in
 * the regular content column. No CSS-only grid trick is used.
 *
 * The first top-level Elementor section is the only global hero candidate.
 * The `cw-lumen-hero` class may be used on that first section as an explicit
 * label, but it never promotes a later section out of the editorial flow.
 * A later section carrying that class stays in its original place, so it
 * cannot duplicate above the header or detach from the sidebar layout.
 * The helper intentionally targets Elementor only; Divi is not an officially
 * supported builder for Lumen's advanced Hero ancho completo template.
 *
 * @param string $html Rendered page content.
 * @return array{hero_html:string,content_html:string,has_elementor_hero:bool,has_content_after_hero:bool,hero_selection:string}
 */
function prepare_elementor_hero_content( string $html ): array {
	$result = array(
		'hero_html'              => '',
		'content_html'           => trim( $html ),
		'has_elementor_hero'     => false,
		'has_content_after_hero' => '' !== trim( $html ),
		'hero_selection'         => 'none',
	);

	$html = trim( $html );
	if ( '' === $html || false === stripos( $html, 'elementor' ) ) {
		return $result;
	}

	if ( ! preg_match_all( '/<div\b[^>]*>/i', $html, $open_tags, PREG_OFFSET_CAPTURE ) ) {
		return $result;
	}

	$root = null;
	foreach ( $open_tags[0] as $token ) {
		$tag     = (string) $token[0];
		$classes = get_html_tag_classes( $tag );
		if ( in_array( 'elementor', $classes, true ) ) {
			$root = array(
				'tag'    => $tag,
				'offset' => (int) $token[1],
			);
			break;
		}
	}

	if ( ! is_array( $root ) ) {
		return $result;
	}

	$root_end = find_matching_div_end( $html, (int) $root['offset'] );
	if ( ! is_array( $root_end ) ) {
		return $result;
	}

	$root_open_start = (int) $root['offset'];
	$root_open       = (string) $root['tag'];
	$root_open_end   = $root_open_start + strlen( $root_open );
	$root_close      = substr( $html, (int) $root_end['close_start'], (int) $root_end['close_end'] - (int) $root_end['close_start'] );
	$root_inner      = substr( $html, $root_open_end, (int) $root_end['close_start'] - $root_open_end );

	if ( ! preg_match_all( '/<\/?div\b[^>]*>/i', $root_inner, $div_tokens, PREG_OFFSET_CAPTURE ) ) {
		return $result;
	}

	$children = array();
	$depth    = 0;
	$current  = null;
	foreach ( $div_tokens[0] as $token ) {
		$tag    = (string) $token[0];
		$offset = (int) $token[1];
		$is_close = 0 === stripos( $tag, '</div' );

		if ( $is_close ) {
			$depth--;
			if ( 0 === $depth && is_array( $current ) ) {
				$current['end'] = $offset + strlen( $tag );
				$children[]     = $current;
				$current        = null;
			}
			continue;
		}

		if ( 0 === $depth ) {
			$current = array(
				'start'   => $offset,
				'open'    => $tag,
				'classes' => get_html_tag_classes( $tag ),
			);
		}
		$depth++;
	}

	$sections = array_values(
		array_filter(
			$children,
			static function ( array $child ): bool {
				return in_array( 'elementor-element', $child['classes'], true );
			}
		)
	);

	if ( empty( $sections ) ) {
		return $result;
	}

	/*
	 * Keep the hero structurally stable: only the first top-level Elementor
	 * section may leave the content/sidebar flow. A `cw-lumen-hero` class on a
	 * later section is a local authoring marker, not a global-hero override.
	 */
	$selected  = $sections[0];
	$selection = in_array( 'cw-lumen-hero', $selected['classes'], true ) ? 'explicit-first' : 'auto';

	if ( ! isset( $selected['end'] ) ) {
		return $result;
	}

	$hero_section = substr( $root_inner, (int) $selected['start'], (int) $selected['end'] - (int) $selected['start'] );
	$remaining    = substr( $root_inner, 0, (int) $selected['start'] ) . substr( $root_inner, (int) $selected['end'] );
	$hero_html    = $root_open . $hero_section . $root_close;
	$content_html = $root_open . $remaining . $root_close;

	$remaining_sections = 0;
	foreach ( $sections as $section ) {
		if ( (int) $section['start'] !== (int) $selected['start'] ) {
			$remaining_sections++;
		}
	}

	$result['hero_html']              = $hero_html;
	$result['content_html']           = $content_html;
	$result['has_elementor_hero']     = true;
	$result['has_content_after_hero'] = $remaining_sections > 0 || '' !== trim( wp_strip_all_tags( $remaining ) );
	$result['hero_selection']         = $selection;

	return $result;
}

