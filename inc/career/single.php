<?php

use Egns\Helper\Egns_Helper;

$apply_heading               = Egns_Helper::egns_career_option_value('apply_heading') ?? '';
$apply_now_lbl               = Egns_Helper::egns_career_option_value('apply_now_lbl') ?? '';
$apply_desc                  = Egns_Helper::egns_career_option_value('apply_desc') ?? '';
$apply_note                  = Egns_Helper::egns_career_option_value('apply_note') ?? '';
$career_apply_form_shortcode = Egns_Helper::egns_career_option_value('career_apply_form_shortcode') ?? '';

?>

<div class="career-details-page mt-140 mb-140">
    <div class="container one">
        <div class="row gy-5 justify-content-between">
            <div class="col-xl-7 col-lg-8">
                <div class="career-details-page-content-wrap">
                    <?php the_content(); ?>
                </div>
            </div>
            <div class="col-lg-4 col-md-7 col-sm-9">
                <div class="career-details-sidebar">
                    <h3><?php echo esc_html($apply_heading); ?></h3>
                    <button class="primary-btn3" data-bs-toggle="modal" data-bs-target="#applyModal">
                        <span data-text="<?php echo esc_attr($apply_now_lbl); ?>"><?php echo esc_html($apply_now_lbl); ?></span>
                    </button>
                    <p><?php echo esc_html($apply_desc); ?></p>
                    <div class="note">
                        <?php echo wp_kses_post($apply_note); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>