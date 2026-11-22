<?php
/**
 * Single post template.
 *
 * @package ISKO
 */
get_header();
?>
<main id="conteudo">
	<?php while ( have_posts() ) : the_post(); ?>
		<section class="news-intro">
			<div class="container">
				<nav aria-label="<?php esc_attr_e( 'Navegação estrutural', 'isko' ); ?>">
					<ol class="breadcrumb isko-breadcrumb">
						<li class="breadcrumb-item"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Início', 'isko' ); ?></a></li>
						<li class="breadcrumb-item"><a href="<?php echo esc_url( get_post_type_archive_link( 'post' ) ?: home_url( '/' ) ); ?>"><?php esc_html_e( 'Publicações', 'isko' ); ?></a></li>
						<li class="breadcrumb-item active" aria-current="page"><?php the_title(); ?></li>
					</ol>
				</nav>
			</div>
		</section>
		<div class="container article-container">
			<div class="row">
			<article <?php post_class( 'article-content col-12' ); ?>>
			<header class="article-header">
				<div class="post-categories"><?php the_category( ' · ' ); ?></div>
				<h1><?php the_title(); ?></h1>
				<p class="post-meta"><?php echo esc_html( get_the_date() ); ?> <span aria-hidden="true">·</span> <?php the_author(); ?></p>
			</header>
			<?php if ( has_post_thumbnail() && ! isko_post_content_has_image() ) : ?>
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
			</div>
		</div>
	<?php endwhile; ?>
</main>
<?php get_footer(); ?>
