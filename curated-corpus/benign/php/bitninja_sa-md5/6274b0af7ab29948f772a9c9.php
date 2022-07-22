<?php

namespace ADP\BaseVersion\Includes\External\Customizer\Controls;

use WP_Customize_Control;

defined( 'ABSPATH' ) or exit;

class FontEmphasisBold extends WP_Customize_Control {
	public $type = 'wdp_font_emphasis_bold';

	public function enqueue() {
		// enqueue control script in Customizer
	}

	public function render_content() {
		$value = $this->value();

		?>
        <label class="wdp-control-icon font-style-button <?php if ( $value ) : echo 'active'; endif; ?>"
               data-tip="<?php echo $this->label; ?>">
            <input type="checkbox" class="font-style-bold" style="display: none;" <?php $this->link(); ?>
                   value="<?php echo esc_attr( $value ); ?>" <?php checked( $value ); ?> />
            <span class="dashicons dashicons-editor-bold"></span>
        </label>

		<?php
	}
}
