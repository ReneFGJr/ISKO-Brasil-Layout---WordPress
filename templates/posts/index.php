<?php
/**
 * Main posts template.
 *
 * @package ISKO
 */
get_header();
?>
<main id="conteudo">
	<section class="news-intro">
		<div class="container">
			<p class="eyebrow"><?php esc_html_e( 'ISKO · Publicações', 'isko' ); ?></p>
			<h1><?php esc_html_e( 'Notícias e ideias', 'isko' ); ?></h1>
			<p class="intro-copy"><?php esc_html_e( 'Acompanhe as novidades, eventos e publicações da associação.', 'isko' ); ?></p>
		</div>
	</section>

	<section class="container news-section" aria-label="<?php esc_attr_e( 'Publicações recentes', 'isko' ); ?>">
		<?php if ( have_posts() ) : ?>
			<div class="row g-4">
				<?php
				$post_number = 0;
				while ( have_posts() ) :
					the_post();
					$post_number++;
					?>
					<?php if ( 1 === $post_number ) : ?>
						<div class="col-12">
							<article <?php post_class( 'featured-card row g-0 align-items-stretch' ); ?>>
								<?php if ( has_post_thumbnail() ) : ?>
									<a class="featured-image col-lg-7" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
										<?php the_post_thumbnail( 'large', array( 'class' => 'img-fluid' ) ); ?>
									</a>
								<?php else : ?>
									<div class="featured-image featured-placeholder col-lg-7" aria-hidden="true"><span>ISKO</span></div>
								<?php endif; ?>
								<div class="featured-content col-lg-5">
									<p class="eyebrow"><?php esc_html_e( 'Em destaque', 'isko' ); ?></p>
									<p class="post-meta"><?php echo esc_html( get_the_date() ); ?></p>
									<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
									<div class="post-excerpt"><?php the_excerpt(); ?></div>
									<a class="text-link" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Leia a notícia', 'isko' ); ?><span aria-hidden="true"> →</span></a>
								</div>
							</article>
						</div>
					<?php else : ?>
						<div class="col-md-6 col-lg-4">
							<?php get_template_part( 'templates/parts/content', 'card' ); ?>
						</div>
					<?php endif; ?>
				<?php endwhile; ?>
			</div>
			<div class="posts-pagination">
				<?php
				the_posts_pagination(
					array(
						'mid_size'  => 1,
						'prev_text' => __( '← Anterior', 'isko' ),
						'next_text' => __( 'Próximas →', 'isko' ),
					)
				);
				?>
			</div>
		<?php else : ?>
			<div class="empty-state">
				<h2><?php esc_html_e( 'Ainda não há publicações', 'isko' ); ?></h2>
				<p><?php esc_html_e( 'As notícias da ISKO aparecerão aqui em breve.', 'isko' ); ?></p>
			</div>
		<?php endif; ?>
	</section>
</main>
<?php get_footer(); ?>
