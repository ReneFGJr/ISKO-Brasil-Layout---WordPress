<?php
/**
 * Single post template.
 *
 * @package ISKO
 */
get_header();
?>
<main id="conteudo" class="container article-container">
	<?php while ( have_posts() ) : the_post(); ?>
		<article <?php post_class( 'article-content' ); ?>>
			<a class="back-link" href="<?php echo esc_url( home_url( '/' ) ); ?>">← <?php esc_html_e( 'Voltar às notícias', 'isko' ); ?></a>
			<header class="article-header">
				<div class="post-categories"><?php the_category( ' · ' ); ?></div>
				<h1><?php the_title(); ?></h1>
				<p class="post-meta"><?php echo esc_html( get_the_date() ); ?> <span aria-hidden="true">·</span> <?php the_author(); ?></p>
			</header>
			<?php if ( has_post_thumbnail() ) : ?>
				<figure class="article-featured-image"><?php the_post_thumbnail( 'full', array( 'class' => 'img-fluid' ) ); ?></figure>
			<?php endif; ?>
			<div class="entry-content"><?php the_content(); ?></div>
			<?php
			wp_link_pages(
				array(
					'before' => '<div class="page-links">' . esc_html__( 'Páginas:', 'isko' ),
					'after'  => '</div>',
				)
			);
			?>
			<?php the_post_navigation(); ?>
			<?php if ( comments_open() || get_comments_number() ) : ?>
				<?php comments_template(); ?>
			<?php endif; ?>
		</article>
	<?php endwhile; ?>
</main>
<?php get_footer(); ?>
