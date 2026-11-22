<?php
/**
 * Page template.
 *
 * @package ISKO
 */
get_header();
?>
<main id="conteudo" class="container article-container">
	<?php while ( have_posts() ) : the_post(); ?>
		<article <?php post_class( 'article-content page-content' ); ?>>
			<header class="article-header"><h1><?php the_title(); ?></h1></header>
			<div class="entry-content"><?php the_content(); ?></div>
		</article>
	<?php endwhile; ?>
</main>
<?php get_footer(); ?>
