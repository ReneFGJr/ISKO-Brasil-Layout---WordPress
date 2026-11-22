<?php
/**
 * Reusable publication card.
 *
 * @package ISKO
 */
?>
<article <?php post_class( 'news-card h-100' ); ?>>
	<a class="news-card-image" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
		<?php $card_image = isko_get_post_card_image(); ?>
		<?php if ( $card_image ) : ?>
			<?php echo wp_kses_post( $card_image ); ?>
		<?php else : ?>
			<span class="card-placeholder">ISKO</span>
		<?php endif; ?>
	</a>
	<div class="news-card-body">
		<p class="post-meta"><?php echo esc_html( get_the_date() ); ?></p>
		<h2 class="news-card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		<div class="post-excerpt"><?php the_excerpt(); ?></div>
		<a class="text-link" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Continuar lendo', 'isko' ); ?><span aria-hidden="true"> →</span></a>
	</div>
</article>
