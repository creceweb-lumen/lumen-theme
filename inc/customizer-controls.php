<?php
/**
 * Customizer-only controls used by the guided CreceWeb experience.
 *
 * @package CreceWebLumen
 */

namespace CreceWeb\Lumen;


/**
 * Explicit 1/0 checkbox for array-backed Customizer options.
 *
 * Native checkbox controls may treat the string "0" as truthy in the
 * controls pane, so CreceWeb renders boolean settings explicitly.
 */
class Boolean_Checkbox_Control extends \WP_Customize_Control {
	/**
	 * Control type.
	 *
	 * @var string
	 */
	public $type = 'creceweb-boolean-checkbox';

	/**
	 * Renders the checkbox with an explicit checked state.
	 *
	 * @return void
	 */
	public function render_content(): void {
		?>
		<label class="cw-boolean-checkbox">
			<input class="cw-boolean-checkbox__input" type="checkbox" value="1" <?php checked( '1', (string) $this->value() ); ?> />
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php if ( $this->description ) : ?>
				<span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
			<?php endif; ?>
		</label>
		<?php
	}
}

/**
 * Native-looking textarea control for Customizer versions that do not
 * provide a dedicated WP_Customize_Textarea_Control class.
 */
class Textarea_Control extends \WP_Customize_Control {
	/**
	 * Control type.
	 *
	 * @var string
	 */
	public $type = 'creceweb-textarea';

	/**
	 * Renders a linked textarea with optional input attributes.
	 *
	 * @return void
	 */
	public function render_content(): void {
		$rows = isset( $this->input_attrs['rows'] ) ? max( 2, absint( $this->input_attrs['rows'] ) ) : 4;
		?>
		<label>
			<?php if ( $this->label ) : ?>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php endif; ?>
			<?php if ( $this->description ) : ?>
				<span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
			<?php endif; ?>
			<textarea rows="<?php echo esc_attr( (string) $rows ); ?>" <?php $this->link(); ?>><?php echo esc_textarea( (string) $this->value() ); ?></textarea>
		</label>
		<?php
	}
}

/**
 * Read-only Customizer control that links to an external support resource.
 *
 * It does not create or persist a setting. The control is intentionally
 * limited to a user-initiated link, so loading the Customizer performs no
 * external request.
 */
class External_Link_Control extends \WP_Customize_Control {
	/**
	 * Destination URL.
	 *
	 * @var string
	 */
	public $url = '';

	/**
	 * Visible button label.
	 *
	 * @var string
	 */
	public $button_label = '';

	/**
	 * Dashicon class.
	 *
	 * @var string
	 */
	public $icon = 'dashicons-editor-help';

	/**
	 * Renders the external documentation link.
	 *
	 * @return void
	 */
	public function render_content(): void {
		if ( '' === $this->url || '' === $this->button_label ) {
			return;
		}
		?>
		<div class="cw-customizer-resource">
			<?php if ( $this->label ) : ?>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php endif; ?>
			<?php if ( $this->description ) : ?>
				<span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
			<?php endif; ?>
			<a class="button button-secondary cw-customizer-resource__button" href="<?php echo esc_url( $this->url ); ?>" target="_blank" rel="noopener noreferrer">
				<span class="dashicons <?php echo esc_attr( sanitize_html_class( $this->icon ) ); ?>" aria-hidden="true"></span>
				<span><?php echo esc_html( $this->button_label ); ?></span>
				<span class="screen-reader-text"><?php esc_html_e( ' (abre en una nueva pestaña)', 'creceweb-lumen' ); ?></span>
			</a>
		</div>
		<?php
	}
}

/**
 * Reusable slider with an explicit numeric readout.
 */
class Range_Control extends \WP_Customize_Control {
	/**
	 * Control type.
	 *
	 * @var string
	 */
	public $type = 'creceweb-range';

	/**
	 * Text displayed after the numeric value.
	 *
	 * @var string
	 */
	public $unit = 'px';

	/**
	 * Renders the control.
	 *
	 * @return void
	 */
	public function render_content(): void {
		$min   = isset( $this->input_attrs['min'] ) ? (float) $this->input_attrs['min'] : 0;
		$max   = isset( $this->input_attrs['max'] ) ? (float) $this->input_attrs['max'] : 100;
		$step  = isset( $this->input_attrs['step'] ) ? (float) $this->input_attrs['step'] : 1;
		$raw_value = (string) $this->value();
		$value     = '' === $raw_value ? $min : (float) $raw_value;
		?>
		<label class="cw-range-control">
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php if ( $this->description ) : ?>
				<span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
			<?php endif; ?>
			<span class="cw-range-control__row">
				<input class="cw-range-control__range" type="range" min="<?php echo esc_attr( (string) $min ); ?>" max="<?php echo esc_attr( (string) $max ); ?>" step="<?php echo esc_attr( (string) $step ); ?>" value="<?php echo esc_attr( (string) $value ); ?>" <?php $this->link(); ?> />
				<span class="cw-range-control__field">
					<input class="cw-range-control__number" type="number" min="<?php echo esc_attr( (string) $min ); ?>" max="<?php echo esc_attr( (string) $max ); ?>" step="<?php echo esc_attr( (string) $step ); ?>" value="<?php echo esc_attr( (string) $value ); ?>" aria-label="<?php echo esc_attr( $this->label ); ?>" />
					<?php if ( '' !== $this->unit ) : ?><span class="cw-range-control__unit" aria-hidden="true"><?php echo esc_html( $this->unit ); ?></span><?php endif; ?>
				</span>
			</span>
		</label>
		<?php
	}
}

/**
 * Backward compatible name for the logo width slider.
 */
class Logo_Width_Control extends Range_Control {
	/**
	 * Control type.
	 *
	 * @var string
	 */
	public $type = 'creceweb-logo-width';
}

/**
 * Visual radio choices used for layout-oriented Customizer controls.
 */
class Choice_Cards_Control extends \WP_Customize_Control {
	/**
	 * Control type.
	 *
	 * @var string
	 */
	public $type = 'creceweb-choice-cards';

	/**
	 * Preview family used by the control CSS.
	 *
	 * @var string
	 */
	public $preview_type = 'default';

	/**
	 * Outputs a small but literal representation of the setting being changed.
	 * The diagrams use semantic pieces (brand, menu, post, column) instead of
	 * generic decorative marks, so they remain meaningful at Customizer scale.
	 *
	 * @param string $type Diagram family.
	 * @param string $value Current choice value.
	 * @return void
	 */
	private function render_diagram( string $type, string $value ): void {
		$type  = sanitize_html_class( $type );
		$value = sanitize_html_class( $value );

		switch ( $type ) {
			case 'density':
				$positions = array(
					'compact'  => array( 24, 36, 48 ),
					'normal'   => array( 24, 43, 62 ),
					'spacious' => array( 24, 50, 76 ),
				);
				$sections  = $positions[ $value ] ?? $positions['normal'];
				?>
				<span class="cw-preview cw-preview--literal cw-preview--density cw-preview--density-<?php echo esc_attr( $value ); ?>" aria-hidden="true">
					<svg class="cw-preview__svg" viewBox="0 0 180 92" preserveAspectRatio="none">
						<rect class="cw-svg-frame" x="4" y="3" width="172" height="86" rx="5"></rect>
						<rect class="cw-svg-chrome" x="10" y="9" width="160" height="8" rx="2"></rect>
						<circle class="cw-svg-accent" cx="16" cy="13" r="2"></circle>
						<rect class="cw-svg-light" x="22" y="11" width="22" height="3" rx="1.5"></rect>
						<rect class="cw-svg-light" x="132" y="11" width="8" height="3" rx="1.5"></rect>
						<rect class="cw-svg-light" x="145" y="11" width="10" height="3" rx="1.5"></rect>
						<rect class="cw-svg-light" x="160" y="11" width="5" height="3" rx="1.5"></rect>
						<rect class="cw-svg-section cw-svg-section--hero" x="12" y="<?php echo esc_attr( (string) $sections[0] ); ?>" width="156" height="8" rx="2"></rect>
						<rect class="cw-svg-section" x="12" y="<?php echo esc_attr( (string) $sections[1] ); ?>" width="112" height="7" rx="2"></rect>
						<rect class="cw-svg-section" x="12" y="<?php echo esc_attr( (string) $sections[2] ); ?>" width="156" height="8" rx="2"></rect>
					</svg>
				</span>
				<?php
				return;

			case 'layout':
				$positions = array(
					'left'          => array( 13, 50 ),
					'center'        => array( 46, 83 ),
					'space-between' => array( 13, 121 ),
				);
				$placement = $positions[ $value ] ?? $positions['space-between'];
				$brand_x   = $placement[0];
				$menu_x    = $placement[1];
				?>
				<span class="cw-preview cw-preview--literal cw-preview--layout cw-preview--layout-<?php echo esc_attr( $value ); ?>" aria-hidden="true">
					<svg class="cw-preview__svg" viewBox="0 0 180 72" preserveAspectRatio="none">
						<rect class="cw-svg-frame" x="4" y="7" width="172" height="58" rx="5"></rect>
						<text class="cw-svg-caption" x="12" y="19"><?php esc_html_e( 'CABECERA', 'creceweb-lumen' ); ?></text>
						<line class="cw-svg-divider" x1="12" y1="24" x2="168" y2="24"></line>
						<rect class="cw-svg-logo" x="<?php echo esc_attr( (string) $brand_x ); ?>" y="37" width="18" height="14" rx="2"></rect>
						<text class="cw-svg-logo-text" x="<?php echo esc_attr( (string) ( $brand_x + 5 ) ); ?>" y="47">L</text>
						<rect class="cw-svg-nav" x="<?php echo esc_attr( (string) $menu_x ); ?>" y="40" width="12" height="5" rx="2"></rect>
						<rect class="cw-svg-nav" x="<?php echo esc_attr( (string) ( $menu_x + 16 ) ); ?>" y="40" width="14" height="5" rx="2"></rect>
						<rect class="cw-svg-nav" x="<?php echo esc_attr( (string) ( $menu_x + 34 ) ); ?>" y="40" width="11" height="5" rx="2"></rect>
					</svg>
				</span>
				<?php
				return;

			case 'alignment':
				$positions = array(
					'left'   => 13,
					'center' => 67,
					'right'  => 121,
				);
				$menu_x = $positions[ $value ] ?? $positions['left'];
				?>
				<span class="cw-preview cw-preview--literal cw-preview--alignment cw-preview--alignment-<?php echo esc_attr( $value ); ?>" aria-hidden="true">
					<svg class="cw-preview__svg" viewBox="0 0 180 72" preserveAspectRatio="none">
						<rect class="cw-svg-frame" x="4" y="7" width="172" height="58" rx="5"></rect>
						<text class="cw-svg-caption" x="12" y="19"><?php esc_html_e( 'MENÚ', 'creceweb-lumen' ); ?></text>
						<line class="cw-svg-divider" x1="12" y1="24" x2="168" y2="24"></line>
						<rect class="cw-svg-nav" x="<?php echo esc_attr( (string) $menu_x ); ?>" y="40" width="12" height="5" rx="2"></rect>
						<rect class="cw-svg-nav" x="<?php echo esc_attr( (string) ( $menu_x + 16 ) ); ?>" y="40" width="14" height="5" rx="2"></rect>
						<rect class="cw-svg-nav" x="<?php echo esc_attr( (string) ( $menu_x + 34 ) ); ?>" y="40" width="11" height="5" rx="2"></rect>
					</svg>
				</span>
				<?php
				return;

			case 'blog':
				if ( 'grid' === $value ) :
					?>
					<span class="cw-preview cw-preview--literal cw-preview--blog cw-preview--blog-grid" aria-hidden="true">
						<svg class="cw-preview__svg" viewBox="0 0 180 92" preserveAspectRatio="none">
							<rect class="cw-svg-frame" x="4" y="3" width="172" height="86" rx="5"></rect>
							<text class="cw-svg-caption" x="12" y="16"><?php esc_html_e( 'BLOG', 'creceweb-lumen' ); ?></text>
							<rect class="cw-svg-card" x="12" y="24" width="70" height="54" rx="3"></rect>
							<rect class="cw-svg-image" x="17" y="29" width="60" height="22" rx="2"></rect>
							<rect class="cw-svg-line" x="17" y="57" width="48" height="4" rx="2"></rect>
							<rect class="cw-svg-line cw-svg-line--muted" x="17" y="66" width="35" height="3" rx="1.5"></rect>
							<rect class="cw-svg-card" x="98" y="24" width="70" height="54" rx="3"></rect>
							<rect class="cw-svg-image" x="103" y="29" width="60" height="22" rx="2"></rect>
							<rect class="cw-svg-line" x="103" y="57" width="48" height="4" rx="2"></rect>
							<rect class="cw-svg-line cw-svg-line--muted" x="103" y="66" width="35" height="3" rx="1.5"></rect>
						</svg>
					</span>
					<?php
				else :
					?>
					<span class="cw-preview cw-preview--literal cw-preview--blog cw-preview--blog-list" aria-hidden="true">
						<svg class="cw-preview__svg" viewBox="0 0 180 92" preserveAspectRatio="none">
							<rect class="cw-svg-frame" x="4" y="3" width="172" height="86" rx="5"></rect>
							<text class="cw-svg-caption" x="12" y="16"><?php esc_html_e( 'BLOG', 'creceweb-lumen' ); ?></text>
							<rect class="cw-svg-card" x="12" y="24" width="156" height="23" rx="3"></rect>
							<rect class="cw-svg-image" x="17" y="29" width="30" height="13" rx="2"></rect>
							<rect class="cw-svg-line" x="55" y="30" width="70" height="4" rx="2"></rect>
							<rect class="cw-svg-line cw-svg-line--muted" x="55" y="38" width="48" height="3" rx="1.5"></rect>
							<rect class="cw-svg-card" x="12" y="55" width="156" height="23" rx="3"></rect>
							<rect class="cw-svg-image" x="17" y="60" width="30" height="13" rx="2"></rect>
							<rect class="cw-svg-line" x="55" y="61" width="70" height="4" rx="2"></rect>
							<rect class="cw-svg-line cw-svg-line--muted" x="55" y="69" width="48" height="3" rx="1.5"></rect>
						</svg>
					</span>
					<?php
				endif;
				return;

			case 'columns':
				$columns      = 'auto' === $value ? 3 : max( 1, min( 5, absint( $value ) ) );
				$column_gap   = 5;
				$column_width = ( 156 - ( ( $columns - 1 ) * $column_gap ) ) / $columns;
				?>
				<span class="cw-preview cw-preview--literal cw-preview--columns cw-preview--columns-<?php echo esc_attr( $value ); ?>" aria-hidden="true">
					<svg class="cw-preview__svg" viewBox="0 0 180 92" preserveAspectRatio="none">
						<rect class="cw-svg-footer" x="4" y="3" width="172" height="86" rx="5"></rect>
						<text class="cw-svg-footer-label" x="12" y="17"><?php echo esc_html( 'auto' === $value ? __( 'PIE · AUTO', 'creceweb-lumen' ) : __( 'PIE', 'creceweb-lumen' ) ); ?></text>
						<line class="cw-svg-footer-divider" x1="12" y1="24" x2="168" y2="24"></line>
						<?php for ( $index = 0; $index < $columns; $index++ ) : ?>
							<?php $x = 12 + ( $index * ( $column_width + $column_gap ) ); ?>
							<rect class="cw-svg-footer-widget" x="<?php echo esc_attr( (string) $x ); ?>" y="34" width="<?php echo esc_attr( (string) $column_width ); ?>" height="38" rx="2"></rect>
							<rect class="cw-svg-footer-line cw-svg-footer-line--title" x="<?php echo esc_attr( (string) ( $x + 4 ) ); ?>" y="41" width="<?php echo esc_attr( (string) max( 7, $column_width - 8 ) ); ?>" height="4" rx="2"></rect>
							<rect class="cw-svg-footer-line" x="<?php echo esc_attr( (string) ( $x + 4 ) ); ?>" y="52" width="<?php echo esc_attr( (string) max( 7, $column_width - 10 ) ); ?>" height="3" rx="1.5"></rect>
							<rect class="cw-svg-footer-line" x="<?php echo esc_attr( (string) ( $x + 4 ) ); ?>" y="61" width="<?php echo esc_attr( (string) max( 7, $column_width - 14 ) ); ?>" height="3" rx="1.5"></rect>
						<?php endfor; ?>
					</svg>
				</span>
				<?php
				return;
		}
		?>
		<span class="cw-preview cw-preview--literal cw-preview--generic" aria-hidden="true">
			<svg class="cw-preview__svg" viewBox="0 0 180 72" preserveAspectRatio="none">
				<rect class="cw-svg-frame" x="4" y="7" width="172" height="58" rx="5"></rect>
				<rect class="cw-svg-card" x="12" y="19" width="156" height="34" rx="3"></rect>
			</svg>
		</span>
		<?php
	}

	/**
	 * Renders compact visual cards. Values are stored by the native Customizer.
	 *
	 * @return void
	 */
	public function render_content(): void {
		?>
		<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
		<?php if ( $this->description ) : ?>
			<span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
		<?php endif; ?>
		<span class="cw-choice-cards cw-choice-cards--<?php echo esc_attr( $this->preview_type ); ?>" role="radiogroup" aria-label="<?php echo esc_attr( $this->label ); ?>">
			<?php foreach ( $this->choices as $value => $choice ) : ?>
				<?php
				$label       = is_array( $choice ) ? (string) ( $choice['label'] ?? $value ) : (string) $choice;
				$description = is_array( $choice ) ? (string) ( $choice['description'] ?? '' ) : '';
				?>
				<label class="cw-choice-card">
					<input type="radio" name="<?php echo esc_attr( $this->id ); ?>" value="<?php echo esc_attr( (string) $value ); ?>" <?php $this->link(); checked( $this->value(), $value ); ?> />
					<span class="cw-choice-card__content">
						<?php $this->render_diagram( (string) $this->preview_type, (string) $value ); ?>
						<span class="cw-choice-card__copy"><strong><?php echo esc_html( $label ); ?></strong><?php if ( '' !== $description ) : ?><small><?php echo esc_html( $description ); ?></small><?php endif; ?></span>
					</span>
				</label>
			<?php endforeach; ?>
		</span>
		<?php
	}
}

/**
 * Visual preset selector. The JavaScript bridge applies the selected base
 * values through native Customizer settings, leaving editor content untouched.
 */
class Design_Preset_Control extends \WP_Customize_Control {
	/**
	 * Control type.
	 *
	 * @var string
	 */
	public $type = 'creceweb-design-presets';

	/**
	 * Renders preset cards.
	 *
	 * @return void
	 */
	public function render_content(): void {
		$presets = get_design_presets();
		?>
		<div class="cw-design-presets" role="region" aria-label="<?php esc_attr_e( 'Puntos de partida visuales', 'creceweb-lumen' ); ?>">
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php if ( $this->description ) : ?>
				<span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
			<?php endif; ?>
			<input class="cw-design-presets__value" type="hidden" value="<?php echo esc_attr( (string) $this->value() ); ?>" <?php $this->link(); ?> />
			<div class="cw-design-presets__grid">
				<?php foreach ( $presets as $key => $preset ) : ?>
					<button type="button" class="cw-design-presets__card cw-design-presets__card--<?php echo esc_attr( $key ); ?>" data-cw-design-preset="<?php echo esc_attr( $key ); ?>" aria-pressed="<?php echo esc_attr( $this->value() === $key ? 'true' : 'false' ); ?>">
						<span class="cw-design-presets__preview" aria-hidden="true">
							<span class="cw-preset-window">
								<span class="cw-preset-window__header"><i></i><b></b><em></em></span>
								<span class="cw-preset-window__hero"><b></b><i></i><i></i><em></em></span>
								<span class="cw-preset-window__content"><i></i><i></i><i></i></span>
								<span class="cw-preset-window__footer"></span>
							</span>
						</span>
						<span class="cw-design-presets__copy">
							<strong><?php echo esc_html( $preset['label'] ?? $key ); ?></strong>
							<small><?php echo esc_html( $preset['description'] ?? '' ); ?></small>
						</span>
					</button>
				<?php endforeach; ?>
			</div>
			<p class="cw-design-presets__status" aria-live="polite"></p>
			<p class="cw-design-presets__note"><?php esc_html_e( 'Aplicar un estilo reemplaza los valores visuales globales de CreceWeb. No modifica páginas, bloques, menús, widgets ni estilos de constructores visuales.', 'creceweb-lumen' ); ?></p>
		</div>
		<?php
	}
}

/**
 * Static helper heading inside long Customizer sections.
 */
class Section_Note_Control extends \WP_Customize_Control {
	/**
	 * Control type.
	 *
	 * @var string
	 */
	public $type = 'creceweb-section-note';

	/**
	 * Visual hierarchy used inside long guided sections.
	 *
	 * @var string
	 */
	public $note_style = 'standard';

	/**
	 * Renders the note.
	 *
	 * @return void
	 */
	public function render_content(): void {
		$note_style = sanitize_html_class( (string) $this->note_style );
		?>
		<div class="cw-section-note cw-section-note--<?php echo esc_attr( $note_style ); ?>">
			<?php if ( $this->label ) : ?><strong><?php echo esc_html( $this->label ); ?></strong><?php endif; ?>
			<?php if ( $this->description ) : ?><p><?php echo esc_html( $this->description ); ?></p><?php endif; ?>
		</div>
		<?php
	}
}
