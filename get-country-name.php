<?php
function admin_block_country_get_country_from_ip($ip) {
  // Test Code.
  // $gi = geoip_open(ABSPATH."/wp-content/uploads/GeoLiteCity.dat", GEOIP_STANDARD);
  // $rsGeoData = geoip_record_by_addr($gi, "203.32.34.34");
  // $countrycode = $rsGeoData->country_code;
  // geoip_close($gi);
  // echo $countrycode;
  // exit;

  $activate_nonce = wp_create_nonce( "admin-block-country-code" );
  if (isset($_SESSION[$activate_nonce.str_replace(" ", "_", strtolower(get_option("siteurl")))."admin_block_country_code"])) {
    return $_SESSION[$activate_nonce.str_replace(" ", "_", strtolower(get_option("siteurl")))."admin_block_country_code"];
  } else {

    $countrycode = "";

    if (file_exists(ABSPATH."/wp-content/uploads/GeoLiteCity.dat")) {
      $gi = geoip_open(ABSPATH."/wp-content/uploads/GeoLiteCity.dat", GEOIP_STANDARD);
      $rsGeoData = geoip_record_by_addr($gi, $_SERVER['REMOTE_ADDR']);
      $countrycode = $rsGeoData->country_code;
      geoip_close($gi);
    }

    // See if your local server can find the country of the visitor.
    if ($countrycode != "") {
      // The local database found the country.
      return $countrycode;
    } else {
      // It can't so use an external service.
      if (get_option("admin_block_country_method") == "" || get_option("admin_block_country_method") == "2") {
        $temp = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip='.$ip));
        $_SESSION[$activate_nonce.str_replace(" ", "_", strtolower(get_option("siteurl")))."admin_block_country_code"] = $temp["geoplugin_countryCode"];
        return $temp["geoplugin_countryCode"];
      } else if (get_option("admin_block_country_method") == "3") {
        $code = file_get_contents("http://ipcountry.marketingmix.com.au/?ip=".$ip);
        $_SESSION[$activate_nonce.str_replace(" ", "_", strtolower(get_option("siteurl")))."admin_block_country_code"] = $code;
        return  $code;
      } else if (get_option("admin_block_country_method") == "4") {
        $content = file_get_contents("http://xml.utrace.de/?query=".$ip);
        list($a, $b) = explode('<countrycode>', $content);
        $_SESSION[$activate_nonce.str_replace(" ", "_", strtolower(get_option("siteurl")))."admin_block_country_code"] = substr($b,0,2);;
        return substr($b,0,2);
      }
    }
    
  }
}

$GLOBALS['geoipcountry'] = array('AD' => 'Andorra', 'AE' => 'United Arab Emirates', 'AF' => 'Afghanistan', 'AG' => 'Antigua and Barbuda', 'AI' => 'Anguilla'
  , 'AL' => 'Albania', 'AM' => 'Armenia', 'AO' => 'Angola', 'AP' => 'Non-spec Asia Pas Location', 'AR' => 'Argentina'
  , 'AS' => 'American Samoa', 'AT' => 'Austria', 'AU' => 'Australia', 'AW' => 'Aruba', 'AZ' => 'Azerbaijan'
  , 'BA' => 'Bosnia and Herzegowina', 'BB' => 'Barbados', 'BD' => 'Bangladesh', 'BE' => 'Belgium', 'BF' => 'Burkina Faso'
  , 'BG' => 'Bulgaria', 'BH' => 'Bahrain', 'BI' => 'Burundi', 'BJ' => 'Benin', 'BM' => 'Bermuda'
  , 'BN' => 'Brunei Darussalam', 'BO' => 'Bolivia', 'BQ' => 'Bonaire; Sint Eustatius; Saba', 'BR' => 'Brazil', 'BS' => 'Bahamas'
  , 'BT' => 'Bhutan', 'BW' => 'Botswana', 'BY' => 'Belarus', 'BZ' => 'Belize', 'CA' => 'Canada'
  , 'CD' => 'Congo The Democratic Republic of The', 'CF' => 'Central African Republic', 'CG' => 'Congo', 'CH' => 'Switzerland', 'CI' => 'Cote D\'ivoire'
  , 'CK' => 'Cook Islands', 'CL' => 'Chile', 'CM' => 'Cameroon', 'CN' => 'China', 'CO' => 'Colombia'
  , 'CR' => 'Costa Rica', 'CU' => 'Cuba', 'CV' => 'Cape Verde', 'CW' => 'Curacao', 'CY' => 'Cyprus'
  , 'CZ' => 'Czech Republic', 'DE' => 'Germany', 'DJ' => 'Djibouti', 'DK' => 'Denmark', 'DM' => 'Dominica'
  , 'DO' => 'Dominican Republic', 'DZ' => 'Algeria', 'EC' => 'Ecuador', 'EE' => 'Estonia', 'EG' => 'Egypt'
  , 'ER' => 'Eritrea', 'ES' => 'Spain', 'ET' => 'Ethiopia', 'EU' => 'European Union', 'FI' => 'Finland'
  , 'FJ' => 'Fiji', 'FM' => 'Micronesia Federated States of', 'FO' => 'Faroe Islands', 'FR' => 'France', 'GA' => 'Gabon'
  , 'GB' => 'United Kingdom', 'GD' => 'Grenada', 'GE' => 'Georgia', 'GF' => 'French Guiana', 'GG' => 'Guernsey'
  , 'GH' => 'Ghana', 'GI' => 'Gibraltar', 'GL' => 'Greenland', 'GM' => 'Gambia', 'GN' => 'Guinea'
  , 'GP' => 'Guadeloupe', 'GQ' => 'Equatorial Guinea', 'GR' => 'Greece', 'GT' => 'Guatemala', 'GU' => 'Guam'
  , 'GW' => 'Guinea-bissau', 'GY' => 'Guyana', 'HK' => 'Hong Kong', 'HN' => 'Honduras', 'HR' => 'Croatia (LOCAL Name: Hrvatska)'
  , 'HT' => 'Haiti', 'HU' => 'Hungary', 'ID' => 'Indonesia', 'IE' => 'Ireland', 'IL' => 'Israel'
  , 'IM' => 'Isle of Man', 'IN' => 'India', 'IO' => 'British Indian Ocean Territory', 'IQ' => 'Iraq', 'IR' => 'Iran (ISLAMIC Republic Of)'
  , 'IS' => 'Iceland', 'IT' => 'Italy', 'JE' => 'Jersey', 'JM' => 'Jamaica', 'JO' => 'Jordan'
  , 'JP' => 'Japan', 'KE' => 'Kenya', 'KG' => 'Kyrgyzstan', 'KH' => 'Cambodia', 'KI' => 'Kiribati'
  , 'KM' => 'Comoros', 'KN' => 'Saint Kitts and Nevis', 'KP' => 'Korea Democratic People\'s Republic of', 'KR' => 'Korea Republic of', 'KW' => 'Kuwait'
  , 'KY' => 'Cayman Islands', 'KZ' => 'Kazakhstan', 'LA' => 'Lao People\'s Democratic Republic', 'LB' => 'Lebanon', 'LC' => 'Saint Lucia'
  , 'LI' => 'Liechtenstein', 'LK' => 'Sri Lanka', 'LR' => 'Liberia', 'LS' => 'Lesotho', 'LT' => 'Lithuania'
  , 'LU' => 'Luxembourg', 'LV' => 'Latvia', 'LY' => 'Libyan Arab Jamahiriya', 'MA' => 'Morocco', 'MC' => 'Monaco'
  , 'MD' => 'Moldova Republic of', 'ME' => 'Montenegro', 'MF' => 'Saint Martin', 'MG' => 'Madagascar', 'MH' => 'Marshall Islands'
  , 'MK' => 'Macedonia', 'ML' => 'Mali', 'MM' => 'Myanmar', 'MN' => 'Mongolia', 'MO' => 'Macau'
  , 'MP' => 'Northern Mariana Islands', 'MQ' => 'Martinique', 'MR' => 'Mauritania', 'MS' => 'Montserrat', 'MT' => 'Malta'
  , 'MU' => 'Mauritius', 'MV' => 'Maldives', 'MW' => 'Malawi', 'MX' => 'Mexico', 'MY' => 'Malaysia'
  , 'MZ' => 'Mozambique', 'NA' => 'Namibia', 'NC' => 'New Caledonia', 'NE' => 'Niger', 'NF' => 'Norfolk Island'
  , 'NG' => 'Nigeria', 'NI' => 'Nicaragua', 'NL' => 'Netherlands', 'NO' => 'Norway', 'NP' => 'Nepal'
  , 'NR' => 'Nauru', 'NU' => 'Niue', 'NZ' => 'New Zealand', 'OM' => 'Oman', 'PA' => 'Panama'
  , 'PE' => 'Peru', 'PF' => 'French Polynesia', 'PG' => 'Papua New Guinea', 'PH' => 'Philippines', 'PK' => 'Pakistan'
  , 'PL' => 'Poland', 'PM' => 'St. Pierre and Miquelon', 'PR' => 'Puerto Rico', 'PS' => 'Palestinian Territory Occupied', 'PT' => 'Portugal'
  , 'PW' => 'Palau', 'PY' => 'Paraguay', 'QA' => 'Qatar', 'RE' => 'Reunion', 'RO' => 'Romania'
  , 'RS' => 'Serbia', 'RU' => 'Russian Federation', 'RW' => 'Rwanda', 'SA' => 'Saudi Arabia', 'SB' => 'Solomon Islands'
  , 'SC' => 'Seychelles', 'SD' => 'Sudan', 'SE' => 'Sweden', 'SG' => 'Singapore', 'SI' => 'Slovenia'
  , 'SK' => 'Slovakia (SLOVAK Republic)', 'SL' => 'Sierra Leone', 'SM' => 'San Marino', 'SN' => 'Senegal', 'SO' => 'Somalia'
  , 'SR' => 'Suriname', 'SS' => 'South Sudan', 'ST' => 'Sao Tome and Principe', 'SV' => 'El Salvador', 'SX' => 'Sint Maarten'
  , 'SY' => 'Syrian Arab Republic', 'SZ' => 'Swaziland', 'TC' => 'Turks and Caicos Islands', 'TD' => 'Chad', 'TG' => 'Togo'
  , 'TH' => 'Thailand', 'TJ' => 'Tajikistan', 'TK' => 'Tokelau', 'TL' => 'Timor-leste', 'TM' => 'Turkmenistan'
  , 'TN' => 'Tunisia', 'TO' => 'Tonga', 'TR' => 'Turkey', 'TT' => 'Trinidad and Tobago', 'TV' => 'Tuvalu'
  , 'TW' => 'Taiwan; Republic of China (ROC)', 'TZ' => 'Tanzania United Republic of', 'UA' => 'Ukraine', 'UG' => 'Uganda', 'UM' => 'United States Minor Outlying Islands'
  , 'US' => 'United States', 'UY' => 'Uruguay', 'UZ' => 'Uzbekistan', 'VA' => 'Holy See (VATICAN City State)', 'VC' => 'Saint Vincent and The Grenadines'
  , 'VE' => 'Venezuela', 'VG' => 'Virgin Islands (BRITISH)', 'VI' => 'Virgin Islands (U.S.)', 'VN' => 'Viet Nam', 'VU' => 'Vanuatu'
  , 'WF' => 'Wallis and Futuna Islands', 'WS' => 'Samoa', 'YE' => 'Yemen', 'ZA' => 'South Africa', 'ZM' => 'Zambia'
  , 'ZW' => 'Zimbabwe', 'ZZ' => 'Reserved');

?>