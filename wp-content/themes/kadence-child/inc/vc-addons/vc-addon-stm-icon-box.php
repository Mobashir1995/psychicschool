<?php
if ( ! defined( 'ABSPATH' ) ) {
	header( 'HTTP/1.0 403 Forbidden' );
	exit;
}

if ( ! function_exists( 'vc_map' ) ) {
	return;
}

// 1) VC map – register the Icon Box element
vc_map(
	array(
		'name'     => __( 'Icon Box', 'kadence-child' ),
		'base'     => 'stm_icon_box',
		'icon'     => 'stm_icon_box',
		'category' => __( 'STM', 'kadence-child' ),
		'params'   => array(
			array(
				'type'       => 'textfield',
				'holder'     => 'div',
				'heading'    => __( 'Title', 'kadence-child' ),
				'param_name' => 'title',
			),
			array(
				'type'       => 'vc_link',
				'heading'    => __( 'Link', 'kadence-child' ),
				'param_name' => 'link',
			),
			array(
				'type'   => 'dropdown',
				'heading' => __( 'Title Holder', 'kadence-child' ),
				'param_name' => 'title_holder',
				'value'  => array( 'H1' => 'h1', 'H2' => 'h2', 'H3' => 'h3', 'H4' => 'h4', 'H5' => 'h5' ),
				'std'    => 'h3',
			),
			array(
				'type'   => 'dropdown',
				'heading' => __( 'Hover position', 'kadence-child' ),
				'param_name' => 'hover_pos',
				'value'  => array(
					__( 'None', 'kadence-child' )   => 'none',
					__( 'Top', 'kadence-child' )    => 'top',
					__( 'Right', 'kadence-child' )   => 'right',
					__( 'Left', 'kadence-child' )    => 'left',
					__( 'Bottom', 'kadence-child' )  => 'bottom',
				),
				'std'    => 'none',
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => __( 'Box background color', 'kadence-child' ),
				'param_name'  => 'box_bg_color',
				'description' => __( 'default - green', 'kadence-child' ),
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => __( 'Box text color', 'kadence-child' ),
				'param_name'  => 'box_text_color',
				'description' => __( 'Default - white', 'kadence-child' ),
			),
			array(
				'type'       => 'colorpicker',
				'heading'    => __( 'Box icon color', 'kadence-child' ),
				'param_name' => 'box_icon_bg_color',
			),
			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Link color style', 'kadence-child' ),
				'param_name' => 'link_color_style',
				'value'      => array( 'Standart' => 'standart', 'Dark' => 'dark' ),
			),
			array(
				'type'       => 'iconpicker',
				'heading'    => __( 'Icon', 'kadence-child' ),
				'param_name' => 'icon',
				'value'      => '',
			),
			array(
				'type'        => 'number_field',
				'heading'     => __( 'Icon Size', 'kadence-child' ),
				'param_name'  => 'icon_size',
				'value'       => '60',
			),
			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Icon Align', 'kadence-child' ),
				'param_name' => 'icon_align',
				'value'      => array( 'Center' => 'center', 'Left' => 'left', 'Right' => 'right' ),
			),
			array(
				'type'   => 'dropdown',
				'heading' => __( 'Box Align', 'kadence-child' ),
				'param_name' => 'box_align',
				'value'  => array( 'Center' => 'center', 'Left' => 'left', 'Right' => 'right' ),
				'std'    => 'left',
			),
			array(
				'type'       => 'number_field',
				'heading'    => __( 'Icon Height', 'kadence-child' ),
				'param_name' => 'icon_height',
				'value'      => '65',
				'dependency' => array( 'element' => 'icon_align', 'value' => array( 'center' ) ),
			),
			array(
				'type'       => 'number_field',
				'heading'    => __( 'Icon Width', 'kadence-child' ),
				'param_name' => 'icon_width',
				'value'      => '65',
				'dependency' => array( 'element' => 'icon_align', 'value' => array( 'left', 'right' ) ),
			),
			array(
				'type'        => 'colorpicker',
				'heading'    => __( 'Icon Color', 'kadence-child' ),
				'param_name' => 'icon_color',
				'value'      => '#fff',
			),
			array(
				'type'       => 'textarea_html',
				'heading'    => __( 'Text', 'kadence-child' ),
				'param_name' => 'content',
			),
			array(
				'type'     => 'css_editor',
				'heading'  => __( 'Icon Css', 'kadence-child' ),
				'param_name' => 'css_icon',
				'group'    => __( 'Icon Design options', 'kadence-child' ),
			),
			array(
				'type'     => 'css_editor',
				'heading'  => __( 'Css', 'kadence-child' ),
				'param_name' => 'css',
				'group'    => __( 'Design options', 'kadence-child' ),
			),
		),
	)
);

// 2) Shortcode output – use MasterStudy template when available, else self‑contained output
add_shortcode( 'stm_icon_box', 'kadence_child_stm_icon_box_shortcode' );

function kadence_child_stm_icon_box_shortcode( $atts, $content = null ) {
	$atts = vc_map_get_attributes( 'stm_icon_box', $atts );

	$icon_align  = isset( $atts['icon_align'] ) ? $atts['icon_align'] : 'center';
	$icon_height = isset( $atts['icon_height'] ) ? $atts['icon_height'] : 65;
	$icon_width  = isset( $atts['icon_width'] ) ? $atts['icon_width'] : 65;

	$icon_inline_css = ( 'center' === $icon_align ) ? 'height:' . esc_attr( $icon_height ) . 'px;' : 'width:' . esc_attr( $icon_width ) . 'px;';

	$atts['css_class'] = isset( $atts['css'] ) ? apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $atts['css'], ' ' ) ) : '';
	$atts['css_icon_class'] = isset( $atts['css_icon'] ) ? apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $atts['css_icon'], ' ' ) ) : '';
	$atts['icon_inline_css'] = $icon_inline_css;
	$atts['link'] = vc_build_link( isset( $atts['link'] ) ? $atts['link'] : '' );
	$atts['unique'] = function_exists( 'kadence_child_create_unique_id' ) ? kadence_child_create_unique_id( $atts ) : 'stm_icon_box_' . uniqid();
	$atts['content'] = $content;

	// Reuse MasterStudy partial when available
	if ( function_exists( 'masterstudy_load_template' ) ) {
		return masterstudy_load_template( 'icon_box', $atts );
	}

	// Self-contained output when MasterStudy is not the parent
	return kadence_child_stm_icon_box_render( $atts );
}

/**
 * Self-contained Icon Box markup and styles (from MasterStudy partials/vc_templates/icon_box.php).
 * Used when MasterStudy is not the parent theme.
 */
function kadence_child_stm_icon_box_render( $atts ) {
	$unique       = isset( $atts['unique'] ) ? $atts['unique'] : uniqid( 'stm_icon_box_' );
	$css_class    = isset( $atts['css_class'] ) ? $atts['css_class'] : '';
	$link         = isset( $atts['link'] ) && is_array( $atts['link'] ) ? $atts['link'] : array();
	$link_color_style = isset( $atts['link_color_style'] ) ? $atts['link_color_style'] : 'standart';
	$box_align    = isset( $atts['box_align'] ) ? $atts['box_align'] : 'left';
	$hover_pos    = isset( $atts['hover_pos'] ) ? $atts['hover_pos'] : 'none';
	$icon_align   = isset( $atts['icon_align'] ) ? $atts['icon_align'] : 'center';
	$icon_height  = isset( $atts['icon_height'] ) ? $atts['icon_height'] : 65;
	$icon_width   = isset( $atts['icon_width'] ) ? $atts['icon_width'] : 65;
	$icon_size    = isset( $atts['icon_size'] ) ? $atts['icon_size'] : 60;
	$icon_color   = isset( $atts['icon_color'] ) ? $atts['icon_color'] : '#fff';
	$box_bg_color   = isset( $atts['box_bg_color'] ) ? $atts['box_bg_color'] : '';
	$box_text_color = isset( $atts['box_text_color'] ) ? $atts['box_text_color'] : '';
	$box_icon_bg_color = isset( $atts['box_icon_bg_color'] ) ? $atts['box_icon_bg_color'] : '';
	$icon         = isset( $atts['icon'] ) ? $atts['icon'] : '';
	$css_icon_class = isset( $atts['css_icon_class'] ) ? $atts['css_icon_class'] : '';
	$title        = isset( $atts['title'] ) ? $atts['title'] : '';
	$title_holder = isset( $atts['title_holder'] ) ? $atts['title_holder'] : 'h3';
	$content      = isset( $atts['content'] ) ? $atts['content'] : '';

	// Per-element inline CSS (from MasterStudy partial)
	$icon_inline_css = ( 'center' === $icon_align ) ? 'height:' . esc_attr( $icon_height ) . 'px;' : 'width:' . esc_attr( $icon_width ) . 'px;';
	if ( ! empty( $box_icon_bg_color ) ) {
		$icon_inline_css .= ' background-color: ' . esc_attr( $box_icon_bg_color ) . ';';
	}

	$inline_css = ".{$unique} { background:{$box_bg_color} !important; color:{$box_text_color}; }
.{$unique} .icon { {$icon_inline_css} }
.{$unique} .icon svg { width: {$icon_size}px; height: {$icon_size}px; }
.{$unique} .icon svg, .{$unique} .icon i { font-size: {$icon_size}px; color: {$icon_color} !important; }
.icon_alignment_center .{$unique} { text-align: center; margin-right: auto; margin-left: auto; }
.icon_alignment_left .{$unique} { text-align: left; }
.icon_alignment_right .{$unique} { text-align: right; margin-right: 0; margin-left: auto; }";

	$css_class .= ' stm_icon_box_hover_' . $hover_pos;
	$icon_b_classes = array( $css_class, $link_color_style, $unique, 'box_align_' . $box_align, 'clearfix' );

	// Enqueue base iconbox styles when MasterStudy stm_module_styles is not available
	if ( ! function_exists( 'stm_module_styles' ) ) {
		static $iconbox_base_loaded = false;
		if ( ! $iconbox_base_loaded ) {
			$iconbox_base = '.icon_box p,.stm_icon_box_hover_bottom,.stm_icon_box_hover_left,.stm_icon_box_hover_right,.stm_icon_box_hover_top{transition:.3s ease}.icon_box{position:relative;top:0;padding:30px 32px 50px}.icon_box p{margin-bottom:10px;opacity:.6}.icon_box.dark a{color:#555;border:0;text-decoration:none}.icon_box.dark a:hover{color:#48a7d4;border-color:#48a7d4}.icon_box:hover p{opacity:1}.stm_icon_box_hover_top{-webkit-transform:translateY(0);transform:translateY(0)}.stm_icon_box_hover_top:hover{-webkit-transform:translateY(-7px);transform:translateY(-7px)}.stm_icon_box_hover_bottom{-webkit-transform:translateY(0);transform:translateY(0)}.stm_icon_box_hover_bottom:hover{-webkit-transform:translateY(7px);transform:translateY(7px)}.stm_icon_box_hover_right{-webkit-transform:translateX(0);transform:translateX(0)}.stm_icon_box_hover_right:hover{-webkit-transform:translateX(7px);transform:translateX(7px)}.stm_icon_box_hover_left{-webkit-transform:translateX(0);transform:translateX(0)}.stm_icon_box_hover_left:hover{-webkit-transform:translateX(-7px);transform:translateX(-7px)}';
			$ms_css = get_theme_root() . '/masterstudy/assets/css/vc_modules/iconbox/style_1.css';
			if ( file_exists( $ms_css ) ) {
				wp_enqueue_style( 'kadence-child-stm-iconbox', get_theme_root_uri() . '/masterstudy/assets/css/vc_modules/iconbox/style_1.css', array(), '1.0' );
			} else {
				wp_register_style( 'kadence-child-stm-iconbox', false );
				wp_enqueue_style( 'kadence-child-stm-iconbox' );
				wp_add_inline_style( 'kadence-child-stm-iconbox', $iconbox_base );
			}
			$iconbox_base_loaded = true;
		}
		wp_add_inline_style( 'kadence-child-stm-iconbox', $inline_css );
	} else {
		stm_module_styles( 'iconbox', 'style_1', array(), $inline_css );
	}

	ob_start();
	?>
    <div class="kadence-child-stm-icon-box">
	<?php if ( ! empty( $link['url'] ) ) : ?>
		<a href="<?php echo esc_url( $link['url'] ); ?>" title="<?php echo esc_attr( ! empty( $link['title'] ) ? $link['title'] : '' ); ?>"
			<?php echo ! empty( $link['target'] ) ? ' target="_blank"' : ''; ?>>
	<?php endif; ?>
	<div class="icon_box <?php echo esc_attr( implode( ' ', $icon_b_classes ) ); ?>">
		<div class="icon_alignment_<?php echo esc_attr( $icon_align ); ?>">
			<?php if ( $icon ) : ?>
				<div class="icon <?php echo esc_attr( $css_icon_class ); ?>">
					<i class="<?php echo esc_attr( $icon ); ?>"></i>
				</div>
			<?php endif; ?>
			<div class="icon_text">
				<?php if ( $title ) : ?>
					<<?php echo esc_attr( $title_holder ); ?> style="color:<?php echo esc_attr( $box_text_color ); ?>"><?php echo esc_html( $title ); ?></<?php echo esc_attr( $title_holder ); ?>>
				<?php endif; ?>
				<?php echo function_exists( 'wpb_js_remove_wpautop' ) ? wpb_js_remove_wpautop( $content, true ) : $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
		</div>
	</div>
	<?php if ( ! empty( $link['url'] ) ) : ?>
		</a>
	<?php endif; ?>
	</div>
	<?php
	return ob_get_clean();
}
