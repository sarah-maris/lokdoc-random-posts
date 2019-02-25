<?php
/*
Plugin Name: LokDoc Random post
Description: Display a random post
Author: Sarah Maris for LokDoc.com
Version: 1.0
*/


class LokDoc_Widget extends WP_Widget {

	/* set widget name and description */
	public function __construct() {
		$widget_ops = array(
			'classname' =>   'lokdoc_widget',
			'description' => 'This widget displays the text and title of a random post',
		);
		parent::__construct( 'lokdoc_widget', 'LokDoc Random Post Widget', $widget_ops );
	}

	public function form( $instance ) {
		// Set widget defaults
			$defaults = array(
				'title'    		=> '',
				'select_cat'  => ''
			);

			// Parse current settings with defaults
			extract( wp_parse_args( ( array ) $instance, $defaults ) ); ?>

			<?php // Widget Title ?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title', 'text_domain' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</p>

			<?php // Categories dropdown ?>
			<p>
				<?php $categories = get_categories(); ?>
				<label for="<?php echo $this->get_field_id( 'select_cat' ); ?>"><?php _e( 'Select Category', 'text_domain' ); ?></label>
				<select name="<?php echo $this->get_field_name( 'select_cat' ); ?>" id="<?php echo $this->get_field_id( 'select_cat' ); ?>" class="widefat">

					<?php
					echo '<option>' . __('none', 'text-domain') . '</option>';
					foreach ( $categories as $cat ) {
						$selected = ( $instance['select_cat'] ==  $cat->term_id  ) ? 'selected' : '';
						echo '<option value="' . esc_attr( $cat->term_id) . '" id="' . esc_attr( $cat->term_id ) . '" '. $selected . '>'. $cat->name . '</option>';
					} ?>
					</select>
			</p>
	 	<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title']    = isset( $new_instance['title'] ) ? wp_strip_all_tags( $new_instance['title'] ) : '';
		$instance['select_cat']   = isset( $new_instance['select_cat'] ) ? wp_strip_all_tags( $new_instance['select_cat'] ) : '';
		return $instance;
	}

	/* display the widget content */
	public function widget( $args, $instance ) {
		echo $args['before_widget']; ?>

		<div class="lokdoc-post-widget">

	   	<?php // start widget code

				if  ( ! empty( $instance['select_cat'] ) ) {
					$cat =  $instance['select_cat'];

					$args = array(
						'orderby'        => 'rand',
						'posts_per_page' => '1',
						'category'       => $cat
					);

					$cat_posts = get_posts( $args );
					$post = $cat_posts[0];

					?>
					<div>
							<h3 class="lokdoc-post-title"><?php echo $post -> post_title; ?> </h3>
							<div class="lokdoc-post-content">
								<?php echo get_the_post_thumbnail( $post->ID, 'thumbnail', array( 'class' => 'lokdoc-post-img' ) ); ?>
								<p class="lokdoc-post-text">	<?php echo $post->post_content?> </p>
							</div>
		     </div>
					<?php

				}else{
					echo esc_html__( 'No posts selected!', 'text_domain' );
				} ?>
		</div>  <!-- .lokdoc-post-widget -->
	</div> <!-- end of widget -->

	<?php	echo $args['after_widget'];
	}
}

// Register the widget
function add_lokdoc_random_post_widget() {
	register_widget( 'LokDoc_Widget' );
}

add_action( 'widgets_init', 'add_lokdoc_random_post_widget' );
