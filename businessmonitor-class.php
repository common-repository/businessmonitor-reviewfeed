<?php
/*
Description: BusinessMonitor Widgets
Version: 1.0.16
Author: Salesforce up to data b.v.
Author URI: https://businessmonitor.nl

Changelog
1.0.1: removed default color for text in the FeedBody
1.0.2: Changed BusinessMonitor_AggregateRating_Widget_CSS method to take the first/second color as a parameter
1.0.5: Fixed rendering of the review feed when just one review is returned
1.0.6: Fixed broken fix from 1.0.5
1.0.8: Added support for AggregateRating Widget with Filters, updated all BusinessMonitor links to  https://
1.0.11: add a check for no reviews and display the noReviews text when this is the case.
*/

class BusinessMonitor_Widgets {
    function BusinessMonitor_AggregateRating_Widget_CSS($firstColor,$secondColor) {
        return "<style type='text/css'>
               .ratingValue {font-size: 25px; border-radius: 50%; height:50px;width:50px;text-align:center;line-height:50px; display: inline-block; color: rgb(255, 255, 255); background-color: #".$firstColor.";}
               .rating { display: block; }
               .rating {
                 background: url(" . plugins_url( 'star.php?color=' . $secondColor , __FILE__ ) . ") repeat-x scroll left center;background-size:40px 50px;
                   display: inline-block;
                   height: 40px;
                   width: 200px;
                   vertical-align:middle;
               }
               .rating span {
                  background: rgba(0, 0, 0, 0) repeat-x scroll left top;
                  background: url(" . plugins_url( 'star.php?color=' . $firstColor , __FILE__ ) . ") repeat-x scroll left center;background-size:40px 50px;
                   display: block;
                   height: 40px;
               }
               #ratingCount{font-size:.7em;font-style:italic;}
               </style>";
    }

    function BusinessMonitor_AggregateRating_Widget_Widget($apiKey,$iItemId,$ip) {
      # Setup the soap connection
      $client = NEW SoapClient('https://s.businessmonitor.nl/bmservice.asmx?WSDL');

      # Generate a temporary key (this key is valid for 24 hours)
      try {
         $result = $client->GetAuthApiKey(Array('apiKey' => $apiKey, 'ip' => $ip));
      } catch (Exception $e) {
       return "incorrect api key?"; # Something went wrong when authenticating
      }

      $keyResultset = get_object_vars($result);
      $key = $keyResultset['GetAuthApiKeyResult'];

    try {
      # Get the aggregateRating object
      $ratingResult = $client->GetAggregateRating(Array('itemId'=>$iItemId,'key'=>$key));
    } catch (Exception $e) {
      return ""; # no ratings yet, or the item is not found
    }

      $ratingResultset = get_object_vars($ratingResult);

      $ratingValue = round($ratingResultset['GetAggregateRatingResult']->RatingValue,1);

      $ratingMin = $ratingResultset['GetAggregateRatingResult']->MinimumValue;
      $ratingMax = $ratingResultset['GetAggregateRatingResult']->MaximumValue;
      $ratingCount = $ratingResultset['GetAggregateRatingResult']->ReviewCount;

    $percent = 100;
    if($ratingMax < 100) {$percent = 10;}

    return "<div itemprop='aggregateRating' itemscope itemtype='http://schema.org/AggregateRating' class='BusinessMonitorRating'>
    <div data-rating='".$ratingValue."' class='rating'><span style='width: ".($ratingValue*$percent)."%;'></span></div><span class='ratingValue' itemprop='ratingValue'>".$ratingValue."</span>
      <span id='ratingCount' content='".$ratingCount."' itemprop='ratingCount'><br>Gebaseerd op ".$ratingCount." evaluaties</span>
      <meta content='".$ratingMin."' itemprop='worstRating'>
      <meta content='".$ratingMax."' itemprop='bestRating'>
    </div>
    <p>Evaluaties door <a target='_blank' href='https://businessmonitor.nl/'>BusinessMonitor</a></p>";
  }

    function BusinessMonitor_AggregateRating_Widget_Widget_Filtered($apiKey,$iItemId,$ip,$filterField,$filterValue) {
      # Setup the soap connection
      $client = NEW SoapClient('https://s.businessmonitor.nl/bmservice.asmx?WSDL');

      # Generate a temporary key (this key is valid for 24 hours)
      try {
         $result = $client->GetAuthApiKey(Array('apiKey' => $apiKey, 'ip' => $ip));
      } catch (Exception $e) {
       return "incorrect api key?"; # Something went wrong when authenticating
      }

      $keyResultset = get_object_vars($result);
      $key = $keyResultset['GetAuthApiKeyResult'];

    try {
      # Get the aggregateRating object
      $ratingResult = $client->GetAggregateRatingFilter(Array('itemId'=>$iItemId,'filterField'=>$filterField,'filterValue'=>$filterValue,'key'=>$key));
    } catch (Exception $e) {
      return ""; # no ratings yet, or the item is not found
    }

      $ratingResultset = get_object_vars($ratingResult);

      $ratingValue = round($ratingResultset['GetAggregateRatingFilterResult']->RatingValue,1);

      $ratingMin = $ratingResultset['GetAggregateRatingFilterResult']->MinimumValue;
      $ratingMax = $ratingResultset['GetAggregateRatingFilterResult']->MaximumValue;
      $ratingCount = $ratingResultset['GetAggregateRatingFilterResult']->ReviewCount;

    $percent = 100;
    if($ratingMax < 100) {$percent = 10;}

    return "<div itemprop='aggregateRating' itemscope itemtype='http://schema.org/AggregateRating' class='BusinessMonitorRating'>
    <div data-rating='".$ratingValue."' class='rating'><span style='width: ".($ratingValue*$percent)."%;'></span></div><span class='ratingValue' itemprop='ratingValue'>".$ratingValue."</span>
      <span id='ratingCount' content='".$ratingCount."' itemprop='ratingCount'><br>Gebaseerd op ".$ratingCount." evaluaties</span>
      <meta content='".$ratingMin."' itemprop='worstRating'>
      <meta content='".$ratingMax."' itemprop='bestRating'>
    </div>
    <p>Evaluaties door <a target='_blank' href='https://businessmonitor.nl/'>BusinessMonitor</a></p>";
  }

  function BusinessMonitor_ReviewFeed_CSS($firstColor) {
     return "<style type='text/css'>
     .FeedProduct{color:#".$firstColor.";display:block;border-top:1px solid #ccc;}
     .FeedWho{font-style:italic;}
     .FeedRating{padding:3px;}
     </style>";
  }

function BusinessMonitor_ReviewFeed_Widget_Multiple_Fields($apiKey,$itemGrade,$itemText,$itemAgree,$answerAgree,$arrayRenderFields,$noReviewText,$answerAnonymous,$anonymousPlaceholder,$ip)
{
	# Setup the soap connection
    $client = NEW SoapClient('https://s.businessmonitor.nl/bmservice.asmx?WSDL');

    # Generate a temporary key (this key is valid for 24 hours)
    try {
        $result = $client->GetAuthApiKey(Array('apiKey' => $apiKey, 'ip' => $ip));
    } catch (Exception $e) {
      #echo $e;
      return ""; # Something went wrong when authenticating
    }

    $keyResultset = get_object_vars($result);
    $key = $keyResultset['GetAuthApiKeyResult'];

    try {
      # Get the reviewFeed object
      $ratingResult = $client->GetReviewsFiltered(Array('itemGrade'=>$itemGrade,'itemText'=>$itemText,'itemAgree'=>$itemAgree,'answerAgree'=>$answerAgree,'renderFields'=>$arrayRenderFields,'anonymousPlaceholder'=>$anonymousPlaceholder,'answerAnonymous'=>$answerAnonymous,'key'=>$key));
    } catch (Exception $e) {
      //echo $e;
      return ""; # no ratings yet, or the item is not found
    }

    #echo "<span style='color:#0f0'>";
    #var_dump($ratingResult);
    #echo "</span>";

    $ratingResultset = get_object_vars($ratingResult);

    if (!property_exists($ratingResultset["GetReviewsFilteredResult"], "Review")) {
      # case of no reviews
      echo "<div class='reviewFeed'>";
      echo "<span class='noReviews'>";
      echo $noReviewText;
      echo "</span>";
      echo "</div>";
    } else {
      $a = ((array)$ratingResultset['GetReviewsFilteredResult']->Review);
      
      # if the feed returns just one record, the cast will put it into a single array instead of an array of arrays.
      # case of multiple review, here every field is the index with each an review array filled with the properties of a review.
      if (empty($a['reviewProduct'])) {
        foreach ($a as $value) {
          echo "<div class='reviewFeed'>";
          echo "<span class='FeedProduct Feedfield1'>";
          echo $value->field1;
          echo "</span>";
          echo "<span class='FeedWho Feedfield2'>";
          echo $value->field2;
          echo "</span>";
          echo "<span class='Feedfield3'>";
          echo $value->field3;
          echo "</span>";
          echo "<span class='Feedfield4'>";
          echo $value->field4;
          echo "</span>";
          echo "<span class='Feedfield5'>";
          echo $value->field5;
          echo "</span>";
          echo "<span class='FeedRating'>";
          echo $value->reviewRating;
          echo "</span>";
          echo "<div class='FeedBody'>";
          echo $value->reviewBody;
          echo "</div>";
          echo "</div>";
        }
      } else {
        # In case of only one review, here every field in the array of a is a property of the review.
        echo "<div class='reviewFeed'>";
        echo "<span class='FeedProduct Feedfield1'>";
        echo $a['field1'];
        echo "</span>";
        echo "<span class='FeedWho Feedfield2'>";
        echo $a['field2'];
        echo "</span>";
        echo "<span class='Feedfield3'>";
        echo $a['field3'];
        echo "</span>";
        echo "<span class='Feedfield4'>";
        echo $a['field4'];
        echo "</span>";
        echo "<span class='Feedfield5'>";
        echo $a['field5'];
        echo "</span>";
        echo "<span class='FeedRating'>";
        echo $a['reviewRating'];
        echo "</span>";
        echo "<div class='FeedBody'>";
        echo $a['reviewBody'];
        echo "</div>";
        echo "</div>";
      }
    }
    return "";
}

  function BusinessMonitor_ReviewFeed_Widget($apiKey,$itemGrade,$itemText,$itemAgree,$answerAgree,$fieldWho,$fieldProduct,$noReviewText,$ip) {
		BusinessMonitor_ReviewFeed_Widget_Multiple_Fields($apiKey,$itemGrade,$itemText,$itemAgree,array_map('intval', explode(",", $answerAgree)),explode(",",strval($fieldProduct).",".strval($fieldWho)),$noReviewText,$ip);
  }
}
?>