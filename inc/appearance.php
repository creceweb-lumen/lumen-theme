<?php
/**
 * Appearance > CreceWeb dashboard.
 *
 * @package CreceWebLumen
 */

namespace CreceWeb\Lumen;

/**
 * @return void
 */
function register_lumen_appearance_page(): void {
	add_theme_page(
		esc_html__( 'CreceWeb Lumen', 'creceweb-lumen' ),
		esc_html__( 'CreceWeb Lumen', 'creceweb-lumen' ),
		'edit_theme_options',
		'creceweb-lumen',
		__NAMESPACE__ . '\render_appearance_page'
	);
}
add_action( 'admin_menu', __NAMESPACE__ . '\register_lumen_appearance_page' );

/**
 * Returns whether the current WordPress user uses an English locale.
 *
 * Documentation links follow the administration language of the
 * current user, not the public site language.
 *
 * @return bool
 */
function is_english_user_locale(): bool {
	$locale = strtolower( str_replace( '_', '-', get_user_locale() ) );
	return 'en' === $locale || str_starts_with( $locale, 'en-' );
}

/**
 * Returns the localized public documentation URL for CreceWeb Lumen.
 *
 * @return string
 */
function get_documentation_url(): string {
	$url = is_english_user_locale()
		? 'https://creceweb.com.ar/en/help/lumen-theme'
		: 'https://creceweb.com.ar/ayuda/lumen-theme';

	/**
	 * Filters the public documentation URL shown by the theme.
	 *
	 * @param string $url Localized documentation URL.
	 */
	return (string) apply_filters( 'creceweb_lumen_documentation_url', $url );
}

/**
 * Returns the localized voluntary support URL for Lumen Theme and Lite.
 *
 * @return string
 */
function get_support_url(): string {
	$url = is_english_user_locale()
		? 'https://creceweb.com.ar/en/lumen/support-theme-lite'
		: 'https://creceweb.com.ar/lumen/apoyar-theme-lite';

	/**
	 * Filters the public support URL shown by the theme.
	 *
	 * @param string $url Localized support URL.
	 */
	return (string) apply_filters( 'creceweb_lumen_support_url', $url );
}


/**
 * Returns the Customizer URL focused on the CreceWeb design panel.
 *
 * @return string
 */
function get_customizer_url(): string {
	return add_query_arg(
		array(
			'return' => admin_url( 'themes.php' ),
			'autofocus[panel]' => 'creceweb_design',
		),
		admin_url( 'customize.php' )
	);
}


/**
 * Creates a Customizer URL focused on a specific section.
 *
 * @param string $section_id Customizer section ID.
 * @return string
 */
function get_customizer_section_url( string $section_id ): string {
	return add_query_arg(
		array(
			'return'                     => get_hub_url(),
			'autofocus[panel]'           => 'creceweb_design',
			'autofocus[section]'         => $section_id,
		),
		admin_url( 'customize.php' )
	);
}

/**
 * Creates a Customizer URL focused on a native widget area.
 *
 * @param string $sidebar_id Registered sidebar ID.
 * @return string
 */
function get_customizer_widget_area_url( string $sidebar_id ): string {
	return add_query_arg(
		array(
			'return'                     => get_hub_url(),
			'autofocus[panel]'           => 'widgets',
			'autofocus[section]'         => 'sidebar-widgets-' . $sidebar_id,
		),
		admin_url( 'customize.php' )
	);
}

/**
 * @return string
 */
function get_hub_url(): string {
	return add_query_arg( array( 'page' => 'creceweb-lumen' ), admin_url( 'themes.php' ) );
}

/**
 * Keeps the Customizer as the direct action from the active theme card.
 *
 * @param string[] $actions Theme action links.
 * @return string[]
 */
function add_theme_action_link( array $actions ): array {
	$actions['customize'] = sprintf( '<a href="%1$s">%2$s</a>', esc_url( get_customizer_url() ), esc_html__( 'Personalizar', 'creceweb-lumen' ) );
	$actions['creceweb-lumen'] = sprintf( '<a href="%1$s">%2$s</a>', esc_url( get_hub_url() ), esc_html__( 'CreceWeb Lumen', 'creceweb-lumen' ) );
	return $actions;
}
add_filter( 'theme_action_links_' . get_template(), __NAMESPACE__ . '\add_theme_action_link' );

/**
 * @return void
 */
function handle_customizer_reset(): void {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		wp_die( esc_html__( 'No tenés permisos para restablecer estos ajustes.', 'creceweb-lumen' ) );
	}
	check_admin_referer( 'creceweb_lumen_reset_customizer' );
	delete_option( CUSTOMIZATION_OPTION );
	delete_option( LEGACY_CUSTOMIZATION_OPTION );
	wp_safe_redirect( add_query_arg( 'creceweb_lumen_reset', '1', get_hub_url() ) );
	exit;
}
add_action( 'admin_post_creceweb_lumen_reset_customizer', __NAMESPACE__ . '\handle_customizer_reset' );

/**
 * Displays one-time feedback after the theme settings are reset.
 *
 * @return void
 */
function render_appearance_admin_notices(): void {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}

	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
	if ( ! $screen || 'appearance_page_creceweb-lumen' !== $screen->id ) {
		return;
	}

	$did_reset = isset( $_GET['creceweb_lumen_reset'] )
		&& '1' === sanitize_text_field( wp_unslash( $_GET['creceweb_lumen_reset'] ) );

	if ( ! $did_reset ) {
		return;
	}
	?>
	<div id="creceweb-reset-notice" class="notice notice-success is-dismissible cw-lumen-hub__notice" role="status" aria-live="polite">
		<p><strong><?php esc_html_e( 'Los ajustes de CreceWeb Lumen se restablecieron correctamente.', 'creceweb-lumen' ); ?></strong></p>
	</div>
	<?php
}
add_action( 'admin_notices', __NAMESPACE__ . '\render_appearance_admin_notices' );

/**
 * @return void
 */
function render_appearance_page(): void {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}

	$active_section = get_requested_admin_section();
	if ( 'theme' !== $active_section ) {
		?>
		<div class="wrap cw-lumen-hub cw-lumen-hub--extension">
			<?php render_admin_section_navigation( $active_section ); ?>
			<section class="cw-lumen-hub__extension-content">
				<?php render_admin_extension_section( $active_section ); ?>
			</section>
		</div>
		<?php
		return;
	}
	$customizer_url          = get_customizer_url();
	$brand_customizer_url    = get_customizer_section_url( 'title_tagline' );
	$sidebar_customizer_url  = get_customizer_section_url( 'creceweb_screen_layout' );
	$blog_customizer_url     = get_customizer_section_url( 'creceweb_content_blog' );
	$footer_customizer_url   = get_customizer_section_url( 'creceweb_footer' );
	$sidebar_widgets_url     = get_customizer_widget_area_url( 'sidebar-1' );
	$footer_widgets_url      = get_customizer_widget_area_url( 'footer-bar' );
	$pages_url               = admin_url( 'edit.php?post_type=page' );
	$new_page_url            = admin_url( 'post-new.php?post_type=page' );
	$posts_url               = admin_url( 'edit.php' );
	$new_post_url            = admin_url( 'post-new.php' );
	$reading_url             = admin_url( 'options-reading.php' );
	$menus_url               = admin_url( 'nav-menus.php' );
	$widgets_url             = admin_url( 'widgets.php' );
	$plugins_url             = admin_url( 'plugins.php' );
	$profile_url             = admin_url( 'profile.php#locale' );
	$profile_locale          = str_replace( '_', '-', get_user_locale() );
	$theme_metadata          = wp_get_theme( get_template() );
	$theme_author_url        = (string) $theme_metadata->get( 'AuthorURI' );
	$lite_active             = function_exists( 'cw_lumen_lite_is_theme_compatible' ) && cw_lumen_lite_is_theme_compatible();
	$lite_manage_url         = function_exists( 'cw_lumen_lite_library_url' ) ? cw_lumen_lite_library_url() : get_admin_section_url( 'lite-menu' );
	$lite_status_url         = admin_url( 'plugins.php' );
	$pro_status              = get_pro_status();
	$is_pro                  = 'active' === $pro_status;
	$is_pro_incompatible     = 'incompatible' === $pro_status;
	$floating_action_config  = get_floating_action_config();
	$back_to_top_active      = 'back_to_top' === (string) ( $floating_action_config['action'] ?? '' );
	$has_floating_extension  = defined( 'CRECEWEB_LUMEN_LITE_VERSION' ) || defined( 'CRECEWEB_LUMEN_PRO_VERSION' );
	$back_to_top_url         = get_customizer_section_url( 'creceweb_floating_action' );
	$documentation_url       = get_documentation_url();
	?>
	<div class="wrap cw-lumen-hub">
		<?php render_admin_section_navigation( 'theme' ); ?>
		<?php if ( $back_to_top_active && $has_floating_extension ) : ?>
			<div class="cw-lumen-hub__compatibility-note" role="note">
				<p><strong><?php esc_html_e( 'Revisá las acciones flotantes.', 'creceweb-lumen' ); ?></strong> <?php esc_html_e( 'Volver arriba funciona de forma independiente. Si una extensión instalada agrega otra acción flotante en la misma esquina, configurá su posición para evitar superposiciones. El Theme no controla la posición de las acciones proporcionadas por extensiones.', 'creceweb-lumen' ); ?> <a href="<?php echo esc_url( $back_to_top_url ); ?>"><?php esc_html_e( 'Abrir Botón Volver arriba', 'creceweb-lumen' ); ?></a></p>
			</div>
		<?php endif; ?>
		<div class="cw-lumen-hub__hero">
			<div>
				<?php /* translators: %s: Current CreceWeb Lumen version. */ ?>
				<p class="cw-lumen-hub__eyebrow"><?php echo esc_html( sprintf( __( 'Versión %s', 'creceweb-lumen' ), CRECEWEB_LUMEN_VERSION ) ); ?></p>
				<h1 class="cw-lumen-hub__brand-title">Lumen <span><?php esc_html_e( 'por', 'creceweb-lumen' ); ?> <a href="<?php echo esc_url( $theme_author_url ); ?>">CreceWeb</a></span></h1>
				<p><?php esc_html_e( 'Una base estable para editar tu sitio sin complicaciones. Personalizá la identidad y el diseño desde un flujo guiado, sin entregar la estructura global del sitio.', 'creceweb-lumen' ); ?></p>
			</div>
			<div class="cw-lumen-hub__actions">
				<a class="button button-primary button-hero" href="<?php echo esc_url( $customizer_url ); ?>"><?php esc_html_e( 'Abrir Personalizar', 'creceweb-lumen' ); ?></a>
				<a class="button button-secondary button-hero cw-lumen-hub__help-link" href="<?php echo esc_url( $documentation_url ); ?>" target="_blank" rel="noopener noreferrer">
					<span class="dashicons dashicons-editor-help" aria-hidden="true"></span>
					<span><?php esc_html_e( 'Ayuda y documentación', 'creceweb-lumen' ); ?></span>
					<span class="screen-reader-text"><?php esc_html_e( ' (abre en una nueva pestaña)', 'creceweb-lumen' ); ?></span>
				</a>
			</div>
		</div>


		<section class="cw-lumen-hub__onboarding" aria-labelledby="cw-lumen-getting-started">
			<div class="cw-lumen-hub__section-heading">
				<div>
					<p class="cw-lumen-hub__kicker"><?php esc_html_e( 'Configuración inicial', 'creceweb-lumen' ); ?></p>
					<h2 id="cw-lumen-getting-started"><?php esc_html_e( 'Publicá una primera versión en cuatro pasos', 'creceweb-lumen' ); ?></h2>
					<p><?php esc_html_e( 'Seguí este recorrido en una instalación nueva. Podés volver a cualquier paso cuando lo necesites.', 'creceweb-lumen' ); ?></p>
				</div>
			</div>
			<ol class="cw-lumen-hub__steps">
				<li class="cw-lumen-hub__step">
					<span class="cw-lumen-hub__step-number" aria-hidden="true">1</span>
					<div>
						<h3><?php esc_html_e( 'Definí la marca y el estilo', 'creceweb-lumen' ); ?></h3>
						<p><?php esc_html_e( 'Cargá logo, nombre, colores y tipografías. Empezá por Marca del sitio y Estilo global para darle una base propia al sitio.', 'creceweb-lumen' ); ?></p>
						<p><a class="button button-primary" href="<?php echo esc_url( $brand_customizer_url ); ?>"><?php esc_html_e( 'Configurar marca', 'creceweb-lumen' ); ?></a></p>
					</div>
				</li>
				<li class="cw-lumen-hub__step">
					<span class="cw-lumen-hub__step-number" aria-hidden="true">2</span>
					<div>
						<h3><?php esc_html_e( 'Elegí el diseño de pantalla', 'creceweb-lumen' ); ?></h3>
						<p><?php esc_html_e( 'Definí si blog, entradas y páginas usan barra lateral a la izquierda, derecha o sin barra. La barra solo se ve cuando tiene widgets.', 'creceweb-lumen' ); ?></p>
						<p><a class="button button-secondary" href="<?php echo esc_url( $sidebar_customizer_url ); ?>"><?php esc_html_e( 'Configurar barra lateral', 'creceweb-lumen' ); ?></a> <a class="button button-secondary" href="<?php echo esc_url( $sidebar_widgets_url ); ?>"><?php esc_html_e( 'Editar widgets', 'creceweb-lumen' ); ?></a></p>
					</div>
				</li>
				<li class="cw-lumen-hub__step">
					<span class="cw-lumen-hub__step-number" aria-hidden="true">3</span>
					<div>
						<h3><?php esc_html_e( 'Armá el pie de página', 'creceweb-lumen' ); ?></h3>
						<p><?php esc_html_e( 'Usá Fila completa del pie para un bloque ancho, o Widgets del pie de página 1 a 5 para columnas. Las áreas vacías no se muestran.', 'creceweb-lumen' ); ?></p>
						<p><a class="button button-secondary" href="<?php echo esc_url( $footer_widgets_url ); ?>"><?php esc_html_e( 'Editar widgets del pie', 'creceweb-lumen' ); ?></a> <a class="button button-secondary" href="<?php echo esc_url( $footer_customizer_url ); ?>"><?php esc_html_e( 'Configurar el pie', 'creceweb-lumen' ); ?></a></p>
					</div>
				</li>
				<li class="cw-lumen-hub__step">
					<span class="cw-lumen-hub__step-number" aria-hidden="true">4</span>
					<div>
						<h3><?php esc_html_e( 'Prepará páginas y blog', 'creceweb-lumen' ); ?></h3>
						<p><?php esc_html_e( 'Creá tus páginas y entradas con Gutenberg. Después elegí cómo se muestran el blog, las tarjetas, el extracto y la cabecera editorial.', 'creceweb-lumen' ); ?></p>
						<p><a class="button button-secondary" href="<?php echo esc_url( $new_page_url ); ?>"><?php esc_html_e( 'Crear página', 'creceweb-lumen' ); ?></a> <a class="button button-secondary" href="<?php echo esc_url( $blog_customizer_url ); ?>"><?php esc_html_e( 'Configurar blog', 'creceweb-lumen' ); ?></a></p>
					</div>
				</li>
			</ol>
			<div class="cw-lumen-hub__guide-notes">
				<article>
					<h3><?php esc_html_e( 'Barra lateral', 'creceweb-lumen' ); ?></h3>
					<p><?php esc_html_e( 'Agregá los widgets que necesites desde Widgets. Las áreas vacías no reservan espacio en el sitio.', 'creceweb-lumen' ); ?></p>
				</article>
				<article>
					<h3><?php esc_html_e( 'Pie y widgets', 'creceweb-lumen' ); ?></h3>
					<p><?php esc_html_e( 'El pie se construye desde Widgets. No hay logo ni menú duplicados: agregá solo las áreas que necesites.', 'creceweb-lumen' ); ?></p>
				</article>
				<article>
					<h3><?php esc_html_e( 'Personalizador', 'creceweb-lumen' ); ?></h3>
					<p><?php esc_html_e( 'Usalo para estilos globales. El texto, las imágenes y las secciones de cada página se editan desde Páginas o Entradas.', 'creceweb-lumen' ); ?></p>
				</article>
			</div>
		</section>

		<section class="cw-lumen-hub__language" aria-labelledby="cw-lumen-language-title">
			<div>
				<p class="cw-lumen-hub__kicker"><?php esc_html_e( 'Idiomas disponibles', 'creceweb-lumen' ); ?></p>
				<h2 id="cw-lumen-language-title"><?php esc_html_e( 'Interfaz disponible en español e inglés', 'creceweb-lumen' ); ?></h2>
				<p><?php esc_html_e( 'CreceWeb Lumen y las extensiones compatibles instaladas incluyen interfaz en español e inglés. El idioma se adapta automáticamente al configurado en tu perfil de WordPress. Este cambio afecta solamente la administración; no traduce páginas, entradas, menús ni contenido publicado.', 'creceweb-lumen' ); ?></p>
				<?php /* translators: %s: Current WordPress profile locale. */ ?>
			<p class="cw-lumen-hub__language-current"><?php echo esc_html( sprintf( __( 'Idioma actual del perfil: %s.', 'creceweb-lumen' ), $profile_locale ) ); ?></p>
			</div>
			<p class="cw-lumen-hub__language-action"><a class="button button-secondary" href="<?php echo esc_url( $profile_url ); ?>"><?php esc_html_e( 'Cambiar idioma del perfil', 'creceweb-lumen' ); ?></a></p>
		</section>

		<section class="cw-lumen-hub__tools" aria-labelledby="cw-lumen-tools-title">
			<div class="cw-lumen-hub__section-heading cw-lumen-hub__section-heading--compact">
				<div>
					<p class="cw-lumen-hub__kicker"><?php esc_html_e( 'Accesos directos', 'creceweb-lumen' ); ?></p>
					<h2 id="cw-lumen-tools-title"><?php esc_html_e( 'Herramientas del sitio', 'creceweb-lumen' ); ?></h2>
				</div>
			</div>
			<div class="cw-lumen-hub__grid">
				<article class="cw-lumen-hub__card cw-lumen-hub__card--primary">
					<h2><?php esc_html_e( 'Personalizar diseño', 'creceweb-lumen' ); ?></h2>
					<p><?php esc_html_e( 'Ajustá identidad, cabecera, navegación, blog, pie, responsive y accesibilidad con vista previa.', 'creceweb-lumen' ); ?></p>
					<p><a class="button button-primary" href="<?php echo esc_url( $customizer_url ); ?>"><?php esc_html_e( 'Abrir Personalizar', 'creceweb-lumen' ); ?></a></p>
				</article>
				<article class="cw-lumen-hub__card cw-lumen-hub__card--primary">
					<h2><?php esc_html_e( 'Páginas y entradas', 'creceweb-lumen' ); ?></h2>
					<p><?php esc_html_e( 'Editá contenidos con Gutenberg y definí si la portada muestra una página estática o las últimas entradas.', 'creceweb-lumen' ); ?></p>
					<p><a class="button button-secondary" href="<?php echo esc_url( $pages_url ); ?>"><?php esc_html_e( 'Ver páginas', 'creceweb-lumen' ); ?></a> <a class="button button-secondary" href="<?php echo esc_url( $new_post_url ); ?>"><?php esc_html_e( 'Crear entrada', 'creceweb-lumen' ); ?></a> <a class="button button-secondary" href="<?php echo esc_url( $reading_url ); ?>"><?php esc_html_e( 'Ajustes de lectura', 'creceweb-lumen' ); ?></a></p>
				</article>
				<article class="cw-lumen-hub__card">
					<h2><?php esc_html_e( 'Menús y widgets', 'creceweb-lumen' ); ?></h2>
					<p><?php esc_html_e( 'Administrá el menú principal, los widgets de barra lateral, la barra superior y las áreas del pie de página.', 'creceweb-lumen' ); ?></p>
					<p><a class="button button-secondary" href="<?php echo esc_url( $menus_url ); ?>"><?php esc_html_e( 'Gestionar menús', 'creceweb-lumen' ); ?></a> <a class="button button-secondary" href="<?php echo esc_url( $widgets_url ); ?>"><?php esc_html_e( 'Gestionar widgets', 'creceweb-lumen' ); ?></a></p>
				</article>
				<article class="cw-lumen-hub__card">
					<h2><?php esc_html_e( 'Gutenberg y builders', 'creceweb-lumen' ); ?></h2>
					<p><?php esc_html_e( 'Usá Gutenberg como opción recomendada u otros editores compatibles en páginas específicas. La estructura global sigue bajo control de Lumen.', 'creceweb-lumen' ); ?></p>
					<p><a class="button button-secondary" href="<?php echo esc_url( $plugins_url ); ?>"><?php esc_html_e( 'Ver plugins instalados', 'creceweb-lumen' ); ?></a></p>
				</article>
				<article class="cw-lumen-hub__card cw-lumen-hub__card--reset">
					<h2><?php esc_html_e( 'Restablecer Personalizador', 'creceweb-lumen' ); ?></h2>
					<p><?php esc_html_e( 'Restaura solamente los ajustes propios de CreceWeb Lumen.', 'creceweb-lumen' ); ?></p>
					<p class="description"><?php esc_html_e( 'No elimina páginas, entradas, menús, widgets, logo, contenido de Gutenberg ni estilos de constructores visuales.', 'creceweb-lumen' ); ?></p>
					<form class="cw-lumen-reset-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" data-confirm-message="<?php echo esc_attr__( '¿Restablecer los ajustes de CreceWeb Lumen?', 'creceweb-lumen' ); ?>">
						<input type="hidden" name="action" value="creceweb_lumen_reset_customizer" />
						<?php wp_nonce_field( 'creceweb_lumen_reset_customizer' ); ?>
						<button class="button button-secondary" type="submit"><?php esc_html_e( 'Restablecer ajustes', 'creceweb-lumen' ); ?></button>
					</form>
				</article>
			</div>
		</section>


		<?php if ( $is_pro || $is_pro_incompatible || $lite_active ) : ?>
			<div class="cw-lumen-hub__pro">
				<div>
					<?php if ( $is_pro ) : ?>
						<p class="cw-lumen-hub__eyebrow"><?php esc_html_e( 'Extensión instalada', 'creceweb-lumen' ); ?></p>
						<h2><?php esc_html_e( 'Lumen Pro está activo', 'creceweb-lumen' ); ?></h2>
						<p><?php esc_html_e( 'La extensión instalada amplía CreceWeb Lumen y se administra desde su propia pantalla.', 'creceweb-lumen' ); ?></p>
					<?php elseif ( $is_pro_incompatible ) : ?>
						<p class="cw-lumen-hub__eyebrow"><?php esc_html_e( 'Revisión requerida', 'creceweb-lumen' ); ?></p>
						<h2><?php esc_html_e( 'Revisá las extensiones instaladas', 'creceweb-lumen' ); ?></h2>
						<p><?php esc_html_e( 'Una extensión instalada requiere versiones compatibles. Revisá su estado antes de habilitar sus módulos.', 'creceweb-lumen' ); ?></p>
					<?php else : ?>
						<p class="cw-lumen-hub__eyebrow"><?php esc_html_e( 'Extensión instalada', 'creceweb-lumen' ); ?></p>
						<h2><?php esc_html_e( 'Lumen Lite está activo', 'creceweb-lumen' ); ?></h2>
						<p><?php esc_html_e( 'La extensión instalada agrega su biblioteca y herramientas desde esta misma pantalla.', 'creceweb-lumen' ); ?></p>
					<?php endif; ?>
				</div>
				<?php
				$ecosystem_url = $is_pro ? get_pro_manage_url() : ( $is_pro_incompatible ? $lite_status_url : $lite_manage_url );
				$ecosystem_label = $is_pro ? __( 'Gestionar extensión', 'creceweb-lumen' ) : ( $is_pro_incompatible ? __( 'Revisar extensiones', 'creceweb-lumen' ) : __( 'Abrir Biblioteca Lumen', 'creceweb-lumen' ) );
				?>
				<a class="button button-primary" href="<?php echo esc_url( $ecosystem_url ); ?>"><?php echo esc_html( $ecosystem_label ); ?></a>
			</div>
		<?php endif; ?>
	</div>
	<?php
}

/**
 * @param string $hook_suffix Admin screen hook.
 * @return void
 */
function enqueue_appearance_assets( string $hook_suffix ): void {
	if ( 'appearance_page_creceweb-lumen' !== $hook_suffix ) {
		return;
	}
	$relative_path = 'assets/css/appearance.css';
	$absolute_path = CRECEWEB_LUMEN_DIR . '/' . $relative_path;
	wp_enqueue_style( 'creceweb-lumen-appearance', CRECEWEB_LUMEN_URI . '/' . $relative_path, array( 'dashicons' ), file_exists( $absolute_path ) ? (string) filemtime( $absolute_path ) : get_version() );

	$script_relative_path = 'assets/js/appearance-admin.js';
	$script_absolute_path = CRECEWEB_LUMEN_DIR . '/' . $script_relative_path;
	wp_enqueue_script(
		'creceweb-lumen-appearance',
		CRECEWEB_LUMEN_URI . '/' . $script_relative_path,
		array(),
		file_exists( $script_absolute_path ) ? (string) filemtime( $script_absolute_path ) : get_version(),
		true
	);
}
add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\enqueue_appearance_assets' );
