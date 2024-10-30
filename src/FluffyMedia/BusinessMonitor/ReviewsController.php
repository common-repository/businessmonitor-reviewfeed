<?php
namespace FluffyMedia\BusinessMonitor;

class ReviewsController
{
    protected $pluginPath;

    protected $url;
    protected $apiKey;
    protected $itemGrade;
    protected $itemText;
    protected $itemAgree;
    protected $answerAgree;
    protected $renderFields;
    protected $field1;
    protected $field2;
    protected $field3;
    protected $field4;
    protected $field5;

    protected $cache;
    protected $cacheExpiration;
    protected $debug;

    protected $client;
    protected $authApiKey;

    public function __construct($pluginPath)
    {
        $this->pluginPath = $pluginPath;
        $this->init();
        $this->setupShortCodes();
    }

    public function init()
    {
		$setting = get_option('BusinessMonitor_options');
		
				if (!isset($setting['BusinessMonitor_field_field3'])) {
			// set the default value if not set
			$setting['BusinessMonitor_field_field3'] = "";
			update_option('BusinessMonitor_options', $setting);
		}

		if (!isset($setting['BusinessMonitor_field_field4'])) {
			// set the default value if not set
			$setting['BusinessMonitor_field_field4'] = "";
			update_option('BusinessMonitor_options', $setting);
		}

		if (!isset($setting['BusinessMonitor_field_field5'])) {
			// set the default value if not set
			$setting['BusinessMonitor_field_field5'] = "";
			update_option('BusinessMonitor_options', $setting);
		}

		if (!isset($setting['BusinessMonitor_field_answerAnonymous'])) {
			// set the default value if not set
			$setting['BusinessMonitor_field_answerAnonymous'] = "";
			update_option('BusinessMonitor_field_answerAnonymous', $setting);
		}

		if (!isset($setting['BusinessMonitor_field_anonymousText'])) {
			// set the default value if not set
			$setting['BusinessMonitor_field_anonymousText'] = "";
			update_option('BusinessMonitor_field_anonymousText', $setting);
		}

        $this->url = apply_filters('fluffy_businessmonitor_url', 'https://s.businessmonitor.nl/bmservice.asmx?WSDL');
        $this->apiKey = apply_filters('fluffy_businessmonitor_api_key', $setting['BusinessMonitor_field_apikey']);
        $this->itemGrade = apply_filters('fluffy_businessmonitor_item_grade', $setting['BusinessMonitor_field_itemGradeFeed']);

        $this->itemText = apply_filters('fluffy_businessmonitor_item_text', $setting['BusinessMonitor_field_itemText']);
        $this->itemAgree = apply_filters('fluffy_businessmonitor_item_agree', $setting['BusinessMonitor_field_itemAgree']);
        $this->answerAgree = apply_filters('fluffy_businessmonitor_answer_agree', $setting['BusinessMonitor_field_answerAgree']);

       
        $this->field1 = apply_filters('fluffy_businessmonitor_field_field1', $setting['BusinessMonitor_field_fieldProduct']);
        $this->field2 = apply_filters('fluffy_businessmonitor_field_field2', $setting['BusinessMonitor_field_fieldWho']);
        $this->field3 = apply_filters('fluffy_businessmonitor_field_field3', $setting['BusinessMonitor_field_field3']);
        $this->field4 = apply_filters('fluffy_businessmonitor_field_field4', $setting['BusinessMonitor_field_field4']);
        $this->field5 = apply_filters('fluffy_businessmonitor_field_field5', $setting['BusinessMonitor_field_field5']);

        $this->cache = apply_filters('fluffy_businessmonitor_setting_cache', true); //set to false to disable caching
        $this->cacheExpiration = apply_filters('fluffy_business_monitor_setting', 12 * HOUR_IN_SECONDS);
        $this->debug = apply_filters('fluffy_businessmonitor_setting_debug', false);
        $this->renderFields = apply_filters('fluffy_businessmonitor_setting_renderFields',explode (",", $setting['BusinessMonitor_field_fieldProduct'].",".$setting['BusinessMonitor_field_fieldWho'].",".$setting['BusinessMonitor_field_field3'].",".$setting['BusinessMonitor_field_field4'].",".$setting['BusinessMonitor_field_field5']));
		$this->answerAnonymous = apply_filters('fluffy_businessmonitor_answerAnonymous', $setting['BusinessMonitor_field_answerAnonymous']);
		$this->anonymousPlaceholder = apply_filters('fluffy_businessmonitor_anonymousPlaceholder', $setting['BusinessMonitor_field_anonymousText']);
}
    public function setupShortCodes()
    {
        add_shortcode('businessmonitor_filter1', [$this, 'renderReviewsByLang1']); // [businessmonitor_filter1 filter1="385961"]
        add_shortcode('businessmonitor_filter2', [$this, 'renderReviewsByLang2']); // [businessmonitor_filter2 filter1="385961" filter2="nl"]
		add_shortcode('businessmonitor_aggfilter1', [$this, 'renderAggByLang1']); // [businessmonitor_aggfilter1 filter1="385961"]
    }

    protected function getClient()
    {
        if (!isset($this->client) || !isset($this->authApiKey))
        {
            try {
                $this->client = new \SoapClient(
                    $this->url,
                    ['trace' => $this->debug ? 1 : 0, "exceptions" => false]
                );

                $result = $this->client->GetAuthApiKey([
                    'apiKey' => $this->apiKey,
                    'ip' => $_SERVER['REMOTE_ADDR']
                ]);
                $keyResultset = get_object_vars($result);
                $this->authApiKey = $keyResultset['GetAuthApiKeyResult'];
            } catch (\Exception $ex)
            {
                if ($this->debug)
                {
                    var_dump($ex->getMessage());
                    var_dump($ex->getTraceAsString());
                    die();
                } else
                {
                    wp_die(__('BusinessMonitor error: Error connecting to webservice', 'fluffymedia'));
                }
            }
        }

        return $this->client;
    }

    protected function render($templateName, $data)
    {
        $templateFile = $this->getTemplate($templateName);
        global $fluffyReviewData;
        $fluffyReviewData = $data;

        ob_start();
        include $templateFile;
        return ob_get_clean();
    }

	public function renderAggByLang1($atts)
	{
		$setting = get_option('BusinessMonitor_options');

        $atts = shortcode_atts(
            ['filter1' => 0],
            $atts
        );

        $args = [
            'filterField' => $setting['BusinessMonitor_field_fieldfilter1'],
            'filterValue' => $atts['filter1'],
        ];

        return $this->render('aggByLang', $this->fetchAgg($args));		
	}

    public function renderReviewsByLang1($atts)
    {
		$setting = get_option('BusinessMonitor_options');

        $atts = shortcode_atts(
            ['filter1' => 0],
            $atts
        );

        $args = [
            'filterField' => $setting['BusinessMonitor_field_fieldfilter1'],
            'filterValue' => explode (",", $atts['filter1']),
        ];

        return $this->render('reviewsByLang', $this->fetchFor($args));
    }

	    public function renderReviewsByLang2($atts)
    {
		$setting = get_option('BusinessMonitor_options');

        $atts = shortcode_atts(
            ['filter1' => 0,
			'filter2' => 1],
            $atts
        );

        $args = [
            'filterField' => $setting['BusinessMonitor_field_fieldfilter1'],
            'filterValue' => explode (",", $atts['filter1']),
            'filterField2' => $setting['BusinessMonitor_field_fieldfilter2'],
            'filterValue2' => explode (",", $atts['filter2']),
        ];

        return $this->render('reviewsByLang', $this->fetchFor($args));
    }

    protected function getTemplate($templateName)
    {
        $stylesheetDir = get_stylesheet_directory(); // Child theme or main theme
        $templateDir = get_template_directory(); // main theme

        $filePathChild = sprintf('%s/%s/%s.php', $stylesheetDir, 'fluffy-business-monitor', $templateName);
        $filePathMain = sprintf('%s/%s/%s.php', $templateDir, 'fluffy-business-monitor', $templateName);
        $filePathPlugin = sprintf('%s/templates/%s.php', $this->pluginPath, $templateName);

        if ($stylesheetDir !== $templateDir && file_exists($filePathChild))
        {
            return $filePathChild;
        } else if (file_exists($filePathMain))
        {
            return $filePathMain;
        } else if (file_exists($filePathPlugin))
        {
            return $filePathPlugin;
        } else
        {
            if ($this->debug)
            {
                var_dump('Fallback template file not found', $filePathPlugin);
                die();
            } else
            {
                wp_die(__('Business Monitor error: Template file not found', 'fluffymedia'));
            }
        }
    }

protected function fetchAgg($args)
{
	       $client = $this->getClient();
        $args = array_merge_recursive(
            [
                'key'=> $this->authApiKey,
                'itemId' => $this->itemGrade,
            ],
            $args
        );

		//var_dump($args);

        if (!array_key_exists('filterField', $args) || !array_key_exists('filterValue', $args))
        {
            if ($this->debug)
            {
                var_dump($args);
                die();
            } else
            {
                wp_die('Business Monitor error: Filter Field or Filter Value not set');
            }
        }

        // Ratings with single filter
        $method = 'GetAggregateRatingFilter';
        $resultProperty = sprintf('%sResult', $method);
        $cacheName = sprintf('fluffy-bm-AggregateRating-%s-%s-z', $args['filterField'], $args['filterValue']);

        // // Ratings with double filter:
        // if (array_key_exists('filterField2', $args) && array_key_exists('filterValue2', $args))
        // {
        //     $resultProperty = sprintf('%sResult', $method);
        //     $cacheName = sprintf('%s-%s-%s', $cacheName, $args['filterField2'], implode($args['filterValue2']));
        // }

        if ( !$this->cache || false === ( $AggregateRating = get_transient($cacheName) ) )
        {
            $result = $client->{$method}($args);
            $resultset = get_object_vars($result);

            $AggregateRating = $resultset;
            set_transient( $cacheName, $AggregateRating, $this->cacheExpiration );
        }

        return $AggregateRating;
}

    protected function fetchFor($args)
    {
        $client = $this->getClient();
        $args = array_merge_recursive(
            [
                'key'=> $this->authApiKey,
                'itemGrade' => $this->itemGrade,
                'itemText' => $this->itemText,
                'itemAgree'=> $this->itemAgree,
                'answerAgree'=> array_map('intval', explode(",", $this->answerAgree)),
                'renderFields' => $this->renderFields,
				'answerAnonymous' => array_map('intval', explode(",", $this->answerAnonymous)),
				'anonymousPlaceholder' =>$this->anonymousPlaceholder
            ],
            $args
        );

		//var_dump($args);

        if (!array_key_exists('filterField', $args) || !array_key_exists('filterValue', $args))
        {
            if ($this->debug)
            {
                var_dump($args);
                die();
            } else
            {
                wp_die('Business Monitor error: Filter Field or Filter Value not set');
            }
        }

        // Reviews with single filter
        $method = 'GetReviewsFiltered';
        $resultProperty = sprintf('%sResult', $method);
        $cacheName = sprintf('fluffy-bm-reviews-%s-%s', $args['filterField'], implode($args['filterValue']));

        // Reviews with double filter:
        if (array_key_exists('filterField2', $args) && array_key_exists('filterValue2', $args))
        {
            $resultProperty = sprintf('%sResult', $method);
            $cacheName = sprintf('%s-%s-%s', $cacheName, $args['filterField2'], implode($args['filterValue2']));
        }

        if ( !$this->cache || false === ( $reviews = get_transient($cacheName) ) )
        {
            $result = $client->{$method}($args);
            $resultset = get_object_vars($result);

            $reviews = (array)$resultset[$resultProperty]->Review;
            set_transient( $cacheName, $reviews, $this->cacheExpiration );
        }

        return $reviews;
    }
}