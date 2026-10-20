<?php
/**
 * Camp Hero markup, shared by the Elementor widget and the [gc_camp_hero] shortcode.
 *
 * @package GC_Camp_Hero
 */

defined( 'ABSPATH' ) || exit;

/**
 * Default content. Positions are fractions of the backdrop photo (see readme.txt).
 */
function gc_camp_hero_defaults() {
	return array(
		'eyebrow'       => 'A scientific community, gathered around the fire',
		'title'         => 'Where scientific ideas gather, grow and get published.',
		'heading_tag'   => 'h1',
		'header_height'  => 0,
		'header_overlay' => false,
		'height'          => '',
		'height_mobile'   => '',
		'reveal'          => '',    // '', fade, up, down, blur, zoom, words
		'reveal_buttons'  => false,
		'fireflies'       => true,
		'fireflies_count' => 6,
		'fireflies_speed' => 1,
		'places'        => array(
			'kitchen' => array(
				'name' => 'Kitchen Ideas',
				'desc' => 'Raw hypotheses, open for discussion',
				'url'  => '/kitchen-ideas/',
			),
			'library' => array(
				'name' => 'Library',
				'desc' => 'Peer-reviewed papers and reports',
				'url'  => '/library/',
			),
			'study'   => array(
				'name' => 'Study Groups',
				'desc' => 'Private groups building papers together',
				'url'  => '/study-groups/',
			),
			'about'   => array(
				'name' => 'About Us',
				'desc' => 'Who we are and our affiliation',
				'url'  => '/about/',
			),
		),
	);
}

/**
 * Builds a srcset string for one image family.
 *
 * @param string $base   File name without the width suffix, e.g. "tent-library".
 * @param int[]  $widths Available widths.
 */
function gc_camp_hero_srcset( $base, $widths ) {
	$out = array();
	foreach ( $widths as $w ) {
		$out[] = esc_url( GC_CAMP_HERO_URL . "assets/img/{$base}-{$w}.webp" ) . " {$w}w";
	}
	return implode( ', ', $out );
}

/**
 * Link attributes (href, target, rel) for one place.
 *
 * @param array $place Place settings.
 */
function gc_camp_hero_link_attrs( $place ) {
	$attrs = ' href="' . esc_url( $place['url'] ) . '"';
	$rel   = array();
	if ( ! empty( $place['external'] ) ) {
		$attrs .= ' target="_blank"';
		$rel[]  = 'noopener';
	}
	if ( ! empty( $place['nofollow'] ) ) {
		$rel[] = 'nofollow';
	}
	if ( $rel ) {
		$attrs .= ' rel="' . esc_attr( implode( ' ', $rel ) ) . '"';
	}
	return $attrs;
}

/**
 * Firefly markup: deterministic positions (same page = same layout), spread over the
 * ground and treeline band, each with its own drift, duration and delay.
 *
 * @param array $args Render args (fireflies, fireflies_count).
 */
function gc_camp_hero_fireflies( $args ) {
	if ( ! filter_var( $args['fireflies'], FILTER_VALIDATE_BOOLEAN ) ) {
		return '';
	}
	$n = min( 60, max( 0, (int) $args['fireflies_count'] ) );
	if ( ! $n ) {
		return '';
	}
	$out  = '<span class="gc-fireflies">';
	$seed = 7;
	$rand = function () use ( &$seed ) {
		$seed = ( $seed * 1103515245 + 12345 ) & 0x7fffffff;
		return $seed / 0x7fffffff;
	};
	for ( $i = 0; $i < $n; $i++ ) {
		$x    = round( ( $i + $rand() ) / $n * 96 + 2, 2 ); // even spread across the width
		$y    = round( 55 + $rand() * 30, 2 );              // 55–85% of the height
		$mx   = round( ( $rand() - .5 ) * 50 );
		$my   = round( -8 - $rand() * 22 );
		$d    = round( 6 + $rand() * 6, 2 );
		$dl   = round( -$rand() * 10, 2 );
		$out .= sprintf( '<i style="--fx:%s%%;--fy:%s%%;--mx:%spx;--my:%spx;--d:%ss;--dl:%ss"></i>', $x, $y, $mx, $my, $d, $dl );
	}
	return $out . '</span>';
}

/**
 * Renders the hero.
 *
 * @param array $args Overrides for gc_camp_hero_defaults().
 * @return string HTML.
 */
function gc_camp_hero_render( $args = array() ) {
	$d      = gc_camp_hero_defaults();
	$args   = wp_parse_args( $args, $d );
	$places = array();
	foreach ( $d['places'] as $key => $defaults ) {
		$given          = isset( $args['places'][ $key ] ) ? (array) $args['places'][ $key ] : array();
		$places[ $key ] = wp_parse_args( array_filter( $given, 'strlen' ), $defaults );
		// An empty name or link falls back to the default (a plaque needs both); an emptied
		// description is respected and simply not shown.
		if ( array_key_exists( 'desc', $given ) ) {
			$places[ $key ]['desc'] = (string) $given['desc'];
		}
	}
	$tag    = in_array( $args['heading_tag'], array( 'h1', 'h2', 'div' ), true ) ? $args['heading_tag'] : 'h1';
	$header = max( 0, (int) $args['header_height'] );
	$style  = '';
	if ( $header ) {
		$style = filter_var( $args['header_overlay'], FILTER_VALIDATE_BOOLEAN ) ? "--gc-overlay-offset:{$header}px" : "--gc-header-space:{$header}px";
	}
	// Shortcode heights, e.g. "100vh", "80svh", "720px". The widget sets --gc-hero-h through its own selector.
	foreach ( array( 'height' => '--gc-hero-h', 'height_mobile' => '--gc-hero-h-m' ) as $key => $var ) {
		if ( preg_match( '/^\d{1,4}(\.\d+)?(vh|svh|dvh|lvh|px|rem|em)$/', trim( (string) $args[ $key ] ), $m ) ) {
			$style .= ( $style ? ';' : '' ) . $var . ':' . $m[0];
		}
	}
	$speed = (float) $args['fireflies_speed'];
	if ( $speed > 0 && 1.0 !== $speed ) {
		$style .= ( $style ? ';' : '' ) . '--gc-ff-speed:' . min( 4, max( .1, $speed ) );
	}
	$reveal = in_array( $args['reveal'], array( 'fade', 'up', 'down', 'blur', 'zoom', 'words' ), true ) ? $args['reveal'] : '';
	$attrs  = $reveal ? ' data-gc-reveal="' . $reveal . '"' . ( filter_var( $args['reveal_buttons'], FILTER_VALIDATE_BOOLEAN ) ? ' data-gc-reveal-buttons' : '' ) : '';
	$title  = esc_html( $args['title'] );
	if ( 'words' === $reveal ) {
		// One span per word, each with its index for the stagger; spaces stay real text.
		$words = preg_split( '/\s+/u', trim( $args['title'] ), -1, PREG_SPLIT_NO_EMPTY );
		foreach ( $words as $i => $word ) {
			$words[ $i ] = '<span class="gc-hero__word" style="--i:' . (int) $i . '">' . esc_html( $word ) . '</span>';
		}
		$title = implode( ' ', $words );
	}
	$uid    = wp_unique_id( 'gc-hero-' );
	$img    = GC_CAMP_HERO_URL . 'assets/img/';

	// Tents: x, y (bottom of art), width, cast-shadow offset — fractions of the photo.
	$tents = array(
		'library' => array( '.235', '.815', '.25', '-.1', '(min-width: 900px) 27vw, 6.5rem' ),
		'study'   => array( '.445', '.735', '.19', '-.08', '(min-width: 900px) 21vw, 6.5rem' ),
		'about'   => array( '.80', '.80', '.235', '.1', '(min-width: 900px) 26vw, 6.5rem' ),
	);

	ob_start();
	?>
<section class="gc-hero" aria-labelledby="<?php echo esc_attr( $uid ); ?>-title"<?php echo $style ? ' style="' . esc_attr( $style ) . '"' : ''; ?><?php echo $attrs; // phpcs:ignore WordPress.Security.EscapeOutput -- whitelisted values ?>>
	<div class="gc-hero__media" aria-hidden="true">
		<picture>
			<source media="(max-width: 899px)" type="image/webp" srcset="<?php echo gc_camp_hero_srcset( 'camp-bg-portrait', array( 540, 1060 ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>" sizes="100vw" width="1060" height="1882">
			<img class="gc-hero__image" src="<?php echo esc_url( $img . 'camp-bg-1920.webp' ); ?>" srcset="<?php echo gc_camp_hero_srcset( 'camp-bg', array( 1280, 1920, 2560, 3342 ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>" sizes="100vw" width="3342" height="1882" alt="" fetchpriority="high">
		</picture>
		<?php echo gc_camp_hero_fireflies( $args ); // phpcs:ignore WordPress.Security.EscapeOutput -- numbers only ?>
	</div>
	<div class="gc-hero__fx" aria-hidden="true"><span class="gc-hero__shade"></span></div>

	<div class="gc-hero__intro">
		<?php if ( '' !== trim( $args['eyebrow'] ) ) : ?>
			<p class="gc-hero__eyebrow"><?php echo esc_html( $args['eyebrow'] ); ?></p>
		<?php endif; ?>
		<<?php echo $tag; // phpcs:ignore WordPress.Security.EscapeOutput -- whitelisted above ?> class="gc-hero__title" id="<?php echo esc_attr( $uid ); ?>-title"><?php echo $title; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped above ?></<?php echo $tag; // phpcs:ignore WordPress.Security.EscapeOutput ?>>
	</div>

	<div class="gc-camp">
		<ul class="gc-camp__places" role="list">
			<li class="gc-camp__item">
				<a class="gc-place gc-campfire"<?php echo gc_camp_hero_link_attrs( $places['kitchen'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?> style="--x:.6;--y:.85;--w:.104;--ar:.7122" aria-labelledby="<?php echo esc_attr( $uid ); ?>-kitchen"<?php echo '' !== $places['kitchen']['desc'] ? ' aria-describedby="' . esc_attr( "$uid-kitchen-desc" ) . '"' : ''; ?>>
					<span class="gc-place__art gc-campfire__art" aria-hidden="true">
						<span class="gc-campfire__glow"></span>
						<img class="gc-campfire__img" src="<?php echo esc_url( $img . 'campfire-800.webp' ); ?>" srcset="<?php echo gc_camp_hero_srcset( 'campfire', array( 420, 800 ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>" sizes="(min-width: 900px) 12vw, 13.5rem" width="800" height="570" alt="">
						<span class="gc-campfire__flame gc-campfire__flame--1"></span>
						<span class="gc-campfire__flame gc-campfire__flame--2"></span>
						<span class="gc-campfire__flame gc-campfire__flame--3"></span>
						<span class="gc-campfire__embers"><i></i><i></i><i></i><i></i><i></i><i></i></span>
					</span>
					<span class="gc-place__label">
						<span class="gc-place__name" id="<?php echo esc_attr( $uid ); ?>-kitchen"><?php echo esc_html( $places['kitchen']['name'] ); ?></span>
						<?php if ( '' !== $places['kitchen']['desc'] ) : ?>
						<span class="gc-place__desc" id="<?php echo esc_attr( "$uid-kitchen-desc" ); ?>"><span><?php echo esc_html( $places['kitchen']['desc'] ); ?></span></span>
						<?php endif; ?>
					</span>
				</a>
			</li>
			<?php foreach ( $tents as $key => $t ) : ?>
			<li class="gc-camp__item">
				<a class="gc-place gc-cabin gc-cabin--<?php echo esc_attr( $key ); ?>"<?php echo gc_camp_hero_link_attrs( $places[ $key ] ); // phpcs:ignore WordPress.Security.EscapeOutput ?> style="--x:<?php echo esc_attr( $t[0] ); ?>;--y:<?php echo esc_attr( $t[1] ); ?>;--w:<?php echo esc_attr( $t[2] ); ?>;--ar:.558;--sx:<?php echo esc_attr( $t[3] ); ?>" aria-labelledby="<?php echo esc_attr( "$uid-$key" ); ?>"<?php echo '' !== $places[ $key ]['desc'] ? ' aria-describedby="' . esc_attr( "$uid-$key-desc" ) . '"' : ''; ?>>
					<span class="gc-place__art" aria-hidden="true">
						<span class="gc-place__shadow"></span><span class="gc-place__light"></span>
						<img class="gc-place__img" src="<?php echo esc_url( $img . "tent-$key-900.webp" ); ?>" srcset="<?php echo gc_camp_hero_srcset( "tent-$key", array( 260, 520, 900, 1400 ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>" sizes="<?php echo esc_attr( $t[4] ); ?>" width="900" height="502" alt="" decoding="async">
						<img class="gc-place__glow" src="<?php echo esc_url( $img . "tent-$key-glow.webp" ); ?>" width="520" height="290" alt="" loading="lazy">
					</span>
					<span class="gc-place__label">
						<span class="gc-place__name" id="<?php echo esc_attr( "$uid-$key" ); ?>"><?php echo esc_html( $places[ $key ]['name'] ); ?></span>
						<?php if ( '' !== $places[ $key ]['desc'] ) : ?>
						<span class="gc-place__desc" id="<?php echo esc_attr( "$uid-$key-desc" ); ?>"><span><?php echo esc_html( $places[ $key ]['desc'] ); ?></span></span>
						<?php endif; ?>
					</span>
				</a>
			</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
	<?php
	return ob_get_clean();
}
