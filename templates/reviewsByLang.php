<?php
global $fluffyReviewData;
?>

<ul class="businessmonitor-all-reviews">
<?php 
if (array_key_exists('maximumValue',$fluffyReviewData)) {
    ?>
        <li>
            <strong><?=esc_html($fluffyReviewData['field2'])?> <?=esc_html($fluffyReviewData['field3'])?> <?=esc_html($fluffyReviewData['field4'])?> <?=esc_html($fluffyReviewData['field5'])?> - <?php _e('Beoordeling:', 'fluffymedia')?> <?=esc_html($fluffyReviewData['reviewRating'])?>/10</strong><br>
            <em><?=esc_html($fluffyReviewData['reviewBody'])?></em>
        </li>
    <?php
} else if(empty($fluffyReviewData)) {
    ?>
        <li>
            <strong>
                <?php
                    echo get_option('BusinessMonitor_options')['BusinessMonitor_field_noReviewText'];
                ?>
            </strong>
        </li>
    <?php
} else {
    foreach ($fluffyReviewData as $review): 
        ?>
            <li>
                <strong><?=esc_html($review->field2)?> <?= $review->field3 ?> <?= $review->field4 ?> <?= $review->field5 ?> - <?php _e('Beoordeling:', 'fluffymedia')?> <?=esc_html($review->reviewRating)?>/10</strong><br>
                <em><?=esc_html($review->reviewBody)?></em>
            </li>
        <?php 
    endforeach;
}
?>
</ul>