<div class="inquiry-form">
    <?php
    // Custom comments_args here: https://codex.wordpress.org/Function_Reference/comment_form
    $commenter = wp_get_current_commenter();
    $req = get_option('require_name_email');
    $aria_req = ($req ? " aria-required=true" : '');

    $comments_args = array(
        'title_reply' => '<h3 class="comment-title">' . esc_html__('Leave A Comment:', 'linkpva') . '</h3>',
        'fields'     => apply_filters('comment_form_default_fields', array(
            'author' => '<div class="col-md-6 form-inner two name"><label>' . esc_html__('Full Name*', 'linkpva') . '</label><input id="author" name="author" type="text" value="' . esc_attr($commenter['comment_author'])
                . '" placeholder="' . esc_attr__('Enter your name', 'linkpva') . '" ' . esc_html($aria_req) . '></div>',

            'email' => '<div class="col-md-6 form-inner two email"><label>' . esc_html__('Your Email*', 'linkpva') . '</label><input  id="email" name="email" type="email"  value="' . esc_attr($commenter['comment_author_email'])
                . '" placeholder="' . esc_attr__('Enter your email', 'linkpva') . '" ' . esc_html($aria_req) . '></div>',
        )),
        'comment_field' => '<div class="col-12 form-inner two"><label>' . esc_html__('Message*', 'linkpva') . '</label><textarea class=" text__area" id="comment" name="comment" cols="45" rows="8" placeholder="' . esc_attr__('Your Message', 'linkpva') . '"></textarea></div>',

        'submit_button' => '<div class="form-inner">
        <button type="submit" class="primary-btn3">
            <span data-text=" ' . esc_html__('Post a Comment', 'linkpva') . '">' . esc_html__('Post a Comment', 'linkpva') . '</span>
        </button>
    </div>',

        'class_submit' => 'primary-btn3',
        'label_submit' => esc_html__('Post a Comment', 'linkpva'),
        'format'       => 'xhtml'
    );
    ?>
    <?php comment_form($comments_args); ?>
</div>