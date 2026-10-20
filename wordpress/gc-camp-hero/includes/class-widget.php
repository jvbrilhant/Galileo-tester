<?php
/**
 * "Camp Hero" Elementor widget.
 *
 * @package GC_Camp_Hero
 */

defined( 'ABSPATH' ) || exit;

/**
 * Elementor widget wrapping gc_camp_hero_render().
 */
class GC_Camp_Hero_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'gc_camp_hero';
	}

	public function get_title() {
		return 'Camp Hero';
	}

	public function get_icon() {
		return 'eicon-image-hotspot';
	}

	public function get_categories() {
		return array( 'general' );
	}

	public function get_keywords() {
		return array( 'hero', 'camp', 'galileo', 'tent' );
	}

	public function get_style_depends() {
		return array( 'gc-camp-hero' );
	}

	public function get_script_depends() {
		return array( 'gc-camp-hero' );
	}

	protected function register_controls() {
		$d = gc_camp_hero_defaults();

		$this->start_controls_section( 'section_intro', array( 'label' => 'Texto inicial' ) );
		$this->add_control(
			'eyebrow',
			array(
				'label'       => 'Linha de destaque',
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => $d['eyebrow'],
				'label_block' => true,
			)
		);
		$this->add_control(
			'title',
			array(
				'label'   => 'Título',
				'type'    => \Elementor\Controls_Manager::TEXTAREA,
				'default' => $d['title'],
				'rows'    => 2,
			)
		);
		$this->add_control(
			'heading_tag',
			array(
				'label'       => 'Tag do título',
				'type'        => \Elementor\Controls_Manager::SELECT,
				'default'     => 'h1',
				'options'     => array(
					'h1'  => 'H1',
					'h2'  => 'H2',
					'div' => 'div',
				),
				'description' => 'Use H1 se este for o título principal da página.',
			)
		);
		$this->add_control(
			'header_height',
			array(
				'label'       => 'Altura do cabeçalho do site (px)',
				'type'        => \Elementor\Controls_Manager::NUMBER,
				'default'     => 0,
				'min'         => 0,
				'max'         => 300,
				'description' => 'Ex.: 80. Com 0, o hero ocupa a altura inteira da tela.',
			)
		);
		$this->add_control(
			'header_overlay',
			array(
				'label'        => 'Cabeçalho transparente por cima do hero',
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => 'Sim',
				'label_off'    => 'Não',
				'return_value' => 'yes',
				'default'      => '',
				'description'  => 'Sim: o texto desce para não ficar atrás do menu. Não: o hero encolhe para caber na tela abaixo do cabeçalho.',
			)
		);
		$this->end_controls_section();

		$labels = array(
			'kitchen' => 'Fogueira — Kitchen Ideas',
			'library' => 'Barraca azul — Library',
			'study'   => 'Barraca bege — Study Groups',
			'about'   => 'Barraca laranja — About Us',
		);
		foreach ( $labels as $key => $label ) {
			$p = $d['places'][ $key ];
			$this->start_controls_section( "section_$key", array( 'label' => $label ) );
			$this->add_control(
				"{$key}_name",
				array(
					'label'   => 'Nome',
					'type'    => \Elementor\Controls_Manager::TEXT,
					'default' => $p['name'],
				)
			);
			$this->add_control(
				"{$key}_desc",
				array(
					'label'       => 'Descrição (aparece ao passar o mouse)',
					'type'        => \Elementor\Controls_Manager::TEXT,
					'default'     => $p['desc'],
					'label_block' => true,
				)
			);
			$this->add_control(
				"{$key}_link",
				array(
					'label'   => 'Link',
					'type'    => \Elementor\Controls_Manager::URL,
					'default' => array( 'url' => $p['url'] ),
					'dynamic' => array( 'active' => true ),
				)
			);
			$this->end_controls_section();
		}

		// Style tab: typography + colour for each text. Empty = the built-in Prata/Poppins look.
		$texts = array(
			'eyebrow' => array( 'Linha de destaque', '.gc-hero__eyebrow' ),
			'title'   => array( 'Título', '.gc-hero__title' ),
			'name'    => array( 'Nomes (Library, Study Groups…)', '.gc-place__name' ),
			'desc'    => array( 'Descrições (hover)', '.gc-place__desc' ),
		);
		foreach ( $texts as $key => $t ) {
			$this->start_controls_section(
				"style_$key",
				array(
					'label' => $t[0],
					'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
				)
			);
			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				array(
					'name'     => "{$key}_typography",
					'selector' => '{{WRAPPER}} .gc-hero ' . $t[1],
				)
			);
			$this->add_control(
				"{$key}_color",
				array(
					'label'     => 'Cor',
					'type'      => \Elementor\Controls_Manager::COLOR,
					// Names: keep the hover colour working when only the rest colour is set.
					'selectors' => array( '{{WRAPPER}} .gc-hero ' . ( 'name' === $key ? '.gc-place:not(:hover):not(:focus-visible) ' : '' ) . $t[1] => 'color: {{VALUE}};' ),
				)
			);
			if ( 'name' === $key ) {
				$this->add_control(
					'name_hover_color',
					array(
						'label'     => 'Cor no hover',
						'type'      => \Elementor\Controls_Manager::COLOR,
						'selectors' => array( '{{WRAPPER}} .gc-hero .gc-place:is(:hover, :focus-visible) .gc-place__name' => 'color: {{VALUE}};' ),
					)
				);
			}
			$this->end_controls_section();
		}
	}

	protected function render() {
		$s      = $this->get_settings_for_display();
		$places = array();
		foreach ( array( 'kitchen', 'library', 'study', 'about' ) as $key ) {
			$link           = isset( $s[ "{$key}_link" ] ) ? $s[ "{$key}_link" ] : array();
			$places[ $key ] = array(
				'name'     => isset( $s[ "{$key}_name" ] ) ? (string) $s[ "{$key}_name" ] : '',
				'desc'     => isset( $s[ "{$key}_desc" ] ) ? (string) $s[ "{$key}_desc" ] : '',
				'url'      => isset( $link['url'] ) ? (string) $link['url'] : '',
				'external' => ! empty( $link['is_external'] ) ? '1' : '',
				'nofollow' => ! empty( $link['nofollow'] ) ? '1' : '',
			);
		}
		echo gc_camp_hero_render( // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside.
			array(
				'eyebrow'       => isset( $s['eyebrow'] ) ? (string) $s['eyebrow'] : '',
				'title'         => isset( $s['title'] ) ? (string) $s['title'] : '',
				'heading_tag'   => isset( $s['heading_tag'] ) ? $s['heading_tag'] : 'h1',
				'header_height'  => isset( $s['header_height'] ) ? (int) $s['header_height'] : 0,
				'header_overlay' => isset( $s['header_overlay'] ) && 'yes' === $s['header_overlay'],
				'places'        => $places,
			)
		);
	}
}
