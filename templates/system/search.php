<?php
/**
 * Search results template.
 *
 * @package ISKO
 */
get_header();
?>
<main id="conteudo" class="container news-section archive-section">
	<header class="archive-header">
		<p class="eyebrow"><?php esc_html_e( 'ISKO · Pesquisa', 'isko' ); ?></p>
		<h1><?php
			printf(
				/* translators: %s: search query. */
				esc_html__( 'Resultados para: %s', 'isko' ),
				'<span>' . esc_html( get_search_query() ) . '</span>'
			);
		?></h1>
		<?php get_search_form(); ?>
	</header>
	<?php if ( have_posts() ) : ?>
		<div class="row g-4">
			<?php while ( have_posts() ) : the_post(); ?>
				<div class="col-md-6 col-lg-4"><?php get_template_part( 'templates/parts/content', 'card' ); ?></div>
			<?php endwhile; ?>
		</div>
		<div class="posts-pagination"><?php the_posts_pagination(); ?></div>
	<?php else : ?>
		<div class="empty-state"><p><?php esc_html_e( 'Não encontramos publicações com esses termos. Tente outra busca.', 'isko' ); ?></p></div>
	<?php endif; ?>
</main>
<?php get_footer(); ?>
