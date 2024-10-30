<?php
global $fluffyReviewData;

// var_dump($fluffyReviewData);

// check if the api call returned data
if (array_key_exists('GetAggregateRatingFilterResult',$fluffyReviewData))
{

// check if the count of items in the dataset is bigger than 0
if ($fluffyReviewData['GetAggregateRatingFilterResult']->ReviewCount>0) {
	$ratingValue = round($fluffyReviewData['GetAggregateRatingFilterResult']->RatingValue,1);
	$ratingMin = $fluffyReviewData['GetAggregateRatingFilterResult']->MinimumValue;
	$ratingMax = $fluffyReviewData['GetAggregateRatingFilterResult']->MaximumValue;
	$ratingCount = $fluffyReviewData['GetAggregateRatingFilterResult']->ReviewCount;
	
	$percent = 100;
	if($ratingMax < 100) {$percent = 10;}
	?>
	<div class='BusinessMonitorRating BusinessMonitorRatingShortcode'>
		<div class='rating'><span style='width:<?=($ratingValue*$percent)?>%;'></span></div><span class='ratingValue' itemprop='ratingValue'><?=$ratingValue?></span>
	</div>
<?
}

}
?>