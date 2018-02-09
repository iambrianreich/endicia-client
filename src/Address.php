<?php
/**
 * This file contains the RWC\Endicia\Address class.
 *
 * @author     Tom Egan <tom@tomegan.tech>
 * @copyright  (C) Copyright 2018 Reich Web Consulting https://www.reich-consulting.net/
 * @license    MIT
 */
namespace RWC\Endicia;

use RWC\Endicia\InvalidArgumentException;

/**
 * Class for representing addresses.
 *
 * @author     Tom Egan <tom@tomegan.tech>
 * @copyright  (C) Copyright 2018 Reich Web Consulting https://www.reich-consulting.net/
 * @license    MIT
 */
class Address
{
	/**
	 *	List of recognized country codes
	 */
	const COUNTRY_CODES = array('AD','AE','AF','AG','AI','AL','AM','AO','AR','AT','AW','AZ','BA','BB','BD','BE','BF','BG','BH','BI','BJ','BM','BN','BO','BQ','BR','BS','BT','BW','BY','BZ','CA','CD','CF','CG','CH','CI','CL','CM','CN','CO','CR','CU','CV','CW','CY','CZ','DE','DJ','DK','DM','DO','DZ','EC','EE','EG','ER','ES','ET','FI','FJ','FK','FO','FR','GA','GB','GD','GE','GF','GH','GI','GL','GM','GN','GP','GQ','GR','GT','GW','GY','HK','HN','HR','HT','HU','ID','IE','IL','IN','IQ','IR','IS','IT','JM','JO','JP','KE','KG','KH','KI','KM','KN','KP','KR','KW','KY','KZ','LA','LB','LC','LI','LK','LR','LS','LT','LU','LV','LY','MA','MD','ME','MG','MK','ML','MM','MN','MO','MQ','MR','MS','MT','MU','MV','MW','MX','MY','MZ','NA','NC','NE','NG','NI','NL','NO','NP','NR','NZ','OM','PA','PE','PF','PG','PH','PK','PL','PM','PN','PT','PY','QA','RE','RO','RS','RS','RU','RW','SA','SB','SC','SD','SE','SG','SH','SI','SK','SL','SM','SN','SO','SR','ST','SV','SX','SY','SZ','TC','TD','TG','TH','TJ','TL','TM','TN','TO','TR','TT','TV','TW','TZ','UA','UG','US','UY','UZ','VA','VC','VD','VE','VG','VN','VU','WS','YE','ZA','ZM','ZW');

	/**
	 *	Map of country names and common usage aliases to recognized country code
	 */
	const COUNTRY_NAME_TO_CODE_MAP = array('Afghanistan' => 'AF', 'Albania' => 'AL',
		'Algeria' => 'DZ', 'Andorra' => 'AD', 'Angola' => 'AO', 'Anguilla' => 'AI',
		'Antigua and Barbuda' => 'AG', 'Antigua' => 'AG', 'Barbuda' => 'AG',
		'Redonda' => 'AG', 'Argentina' => 'AR', 'Armenia' => 'AM', 'Aruba' => 'AW',
		'Austria' => 'AT', 'Azerbaijan' => 'AZ', 'Bahamas' => 'BS', 'Bahrain' => 'BH',
		'Bangladesh' => 'BD', 'Barbados' => 'BB', 'Belarus' => 'BY', 'Belgium' => 'BE',
		'Belize' => 'BZ', 'Benin' => 'BJ', 'Dahomey' => 'BJ', 'Bermuda' => 'BM',
		'Bhutan' => 'BT', 'Bolivia' => 'BO', 'Bonaire, Sint Eustatius, and Saba' => 'BQ',
		'Bonaire' => 'BQ', 'Sint Eustatius' => 'BQ', 'Saba' => 'BQ',
		'Bosnia-Herzegovina' => 'BA', 'Botswana' => 'BW', 'Brazil' => 'BR',
		'British Virgin Islands' => 'VG', 'Brunei Darussalam' => 'BN',
		'Bulgaria' => 'BG', 'Burkina Faso' => 'BF', 'Burma' => 'MM',
		'Burundi' => 'BI', 'Cambodia' => 'KH', 'Kampuchea' => 'KH',
		'Cameroon' => 'CM', 'Canada' => 'CA', 'Cape Verde' => 'CV',
		'Cayman Islands' => 'KY', 'Central African Republic' => 'CF',
		'Chad' => 'TD', 'Tchad' => 'TD', 'Chile' => 'CL', 'China' => 'CN',
		'Colombia' => 'CO', 'Comoros' => 'KM', 'Congo, Democratic Republic of the' => 'CD',
		'Democratic Republic of the Congo' => 'CD', 'Congo, Republic of the' => 'CG',
		'Republic of the Congo' => 'CG', 'Costa Rica' => 'CR',
		"Cote d'Ivoire" => 'CI', 'Croatia' => 'HR', 'Cuba' => 'CU',
		'Curacao' => 'CW', 'Cyprus' => 'CY', 'Czech Republic' => 'CZ',
		'Denmark' => 'DK', 'Djibouti' => 'DJ', 'Dominica' => 'DM',
		'Dominican Republic' => 'DO', 'Ecuador' => 'EC', 'Egypt' => 'EG',
		'El Salvador' => 'SV', 'Equatorial Guinea' => 'GQ', 'Eritrea' => 'ER',
		'Estonia' => 'EE', 'Ethiopia' => 'ET', 'Falkland Islands' => 'FK',
		'Faroe Islands' => 'FO', 'Fiji' => 'FJ', 'Finland' => 'FI',
		'France' => 'FR', 'French Guiana' => 'GF', 'French Polynesia' => 'PF',
		'Gabon' => 'GA', 'Gambia' => 'GM', 'Georgia, Republic of' => 'GE',
		'Republic of Georgia' => 'GE', 'Germany' => 'DE', 'Ghana' => 'GH',
		'Gibraltar' => 'GI', 'Great Britain and Northern Ireland' => 'GB',
		'Great Britain' => 'GB', 'Northern Ireland' => 'GB', 'Greece' => 'GR',
		'Greenland' => 'GL', 'Grenada' => 'GD', 'Guadeloupe' => 'GP',
		'Guatemala' => 'GT', 'Guinea' => 'GN', 'Guinea-Bissau' => 'GW',
		'Guyana' => 'GY', 'Haiti' => 'HT', 'Honduras' => 'HN',
		'Hong Kong' => 'HK', 'Hungary' => 'HU', 'Iceland' => 'IS',
		'India' => 'IN', 'Indonesia' => 'ID', 'Iran' => 'IR',
		'Iraq' => 'IQ', 'Ireland' => 'IE', 'Israel' => 'IL',
		'Italy' => 'IT', 'Jamaica' => 'JM', 'Japan' => 'JP',
		'Jordan' => 'JO', 'Kazakhstan' => 'KZ', 'Kenya' => 'KE',
		'Kiribati' => 'KI', 'Korea, Democratic Peoples Republic of' => 'KP',
		'Democratic Peoples Republic of Korea' => 'KP', 'North Korea' => 'KP',
		'Korea, Republic of' => 'KR', 'Republic of Korea' => 'KR',
		'South Korea' => 'KR', 'Kosovo, Republic of' => 'RS', 'Republic of Kosovo' => 'RS',
		'Kuwait' => 'KW', 'Kyrgyzstan' => 'KG', 'Laos' => 'LA',
		'Latvia' => 'LV', 'Lebanon' => 'LB', 'Lesotho' => 'LS',
		'Liberia' => 'LR', 'Libya' => 'LY', 'Liechtenstein' => 'LI',
		'Lithuania' => 'LT', 'Luxembourg' => 'LU', 'Macao' => 'MO',
		'Macedonia, Republic of' => 'MK', 'Republic of Macedonia' => 'MK',
		'Macedonia' => 'MK', 'Madagascar' => 'MG', 'Malawi' => 'MW',
		'Malaysia' => 'MY', 'Maldives' => 'MV', 'Mali' => 'ML',
		'Malta' => 'MT', 'Martinique' => 'MQ', 'Mauritania' => 'MR',
		'Mauritius' => 'MU', 'Mexico' => 'MX', 'Moldova' => 'MD',
		'Mongolia' => 'MN', 'Montenegro' => 'ME', 'Montserrat' => 'MS',
		'Morocco' => 'MA', 'Mozambique' => 'MZ', 'Myanmar' => 'MM',
		'Namibia' => 'NA', 'Nauru' => 'NR', 'Nepal' => 'NP',
		'Netherlands' => 'NL', 'New Caledonia' => 'NC', 'New Zealand' => 'NZ',
		'Nicaragua' => 'NI', 'Niger' => 'NE', 'Nigeria' => 'NG',
		'Norway' => 'NO', 'Oman' => 'OM', 'Pakistan' => 'PK',
		'Panama' => 'PA', 'Papua New Guinea' => 'PG', 'Paraguay' => 'PY',
		'Peru' => 'PE', 'Philippines' => 'PH', 'Pitcairn Island' => 'PN',
		'Poland' => 'PL', 'Portugal' => 'PT', 'Qatar' => 'QA',
		'Reunion' => 'RE', 'Romania' => 'RO', 'Russia' => 'RU',
		'Rwanda' => 'RW', 'Saint Helena' => 'SH', 'Saint Kitts and Nevis' => 'KN',
		'Saint Lucia' => 'LC', 'Saint Pierre and Miquelon' => 'PM',
		'Saint Vincent and the Grenadines' => 'VC', 'Samoa' => 'WS',
		'San Marino' => 'SM', 'Sao Tome and Principe' => 'ST',
		'Saudi Arabia' => 'SA', 'Senegal' => 'SN', 'Serbia, Republic of' => 'RS',
		'Republic of Serbia' => 'RS', 'Serbia' => 'RS', 'Seychelles' => 'SC',
		'Sierra Leone' => 'SL', 'Singapore' => 'SG', 'Sint Maarten' => 'SX',
		'Slovak Republic (Slovakia)' => 'SK', 'Slovak Republic' => 'SK',
		'Slovakia' => 'SK', 'Slovenia' => 'SI', 'Solomon Islands' => 'SB',
		'Somalia' => 'SO', 'South Africa' => 'ZA', 'Spain' => 'ES',
		'Sri Lanka' => 'LK', 'Sudan' => 'SD', 'Suriname' => 'SR',
		'Swaziland' => 'SZ', 'Sweden' => 'SE', 'Switzerland' => 'CH',
		'Syrian Arab Republic (Syria)' => 'SY', 'Syrian Arab Republic' => 'SY',
		'Syria' => 'SY', 'Taiwan' => 'TW', 'Tajikistan' => 'TJ',
		'Tanzania' => 'TZ', 'Thailand' => 'TH',
		'Timor-Leste Democratic Republic of' => 'TL',
		'Democratic Republic of Timor-Leste' => 'TL',
		'East Timor' => 'TL', 'Togo' => 'TG', 'Tonga' => 'TO',
		'Trinidad and Tobago' => 'TT', 'Tristan da Cunha' => 'SH',
		'Tunisia' => 'TN', 'Turkey' => 'TR', 'Turkmenistan' => 'TM',
		'Turks and Caicos Islands' => 'TC', 'Tuvalu' => 'TV',
		'Uganda' => 'UG', 'Ukraine' => 'UA',
		'United Arab Emirates' => 'AE', 'United States' => 'US',
		'United States of America' => 'US', 'USA' => 'US',
		'Uruguay' => 'UY', 'Uzbekistan' => 'UZ', 'Vanuatu' => 'VU',
		'Vatican City' => 'VA', 'Venezuela' => 'VE', 'Vietnam' => 'VN',
		'Wallis and Futuna Islands' => 'VD', 'Yemen' => 'YE',
		'Zambia' => 'ZM', 'Zimbabwe' => 'ZW');

	/**
	 *	List of recognized state and territory codes for United States
	 */
	const US_STATE_CODES = array('AL', 'AK', 'AZ', 'AR', 'CA', 'CO', 'CT', 'DE', 'DC', 'FL', 'GA', 'HI', 'ID', 'IL', 'IN', 'IA', 'KS', 'KY', 'LA', 'ME', 'MD', 'MA', 'MI', 'MN', 'MS', 'MO', 'MT', 'NE', 'NV', 'NH', 'NJ', 'NM', 'NY', 'NC', 'ND', 'OH', 'OK', 'OR', 'PA', 'RI', 'SC', 'SD', 'TN', 'TX', 'UT', 'VT', 'VA', 'WA', 'WV', 'WI', 'WY', 'AS', 'GU', 'MP', 'PR', 'VI', 'FM', 'MH', 'PW', 'AA', 'AE', 'AP');

	/**
	 *	List of recognized state and territory codes for Canada
	 */
	const CANADA_PROVINCE_CODES = array('AB', 'BC', 'MB', 'NB', 'NL', 'NT', 'NS', 'NU', 'ON', 'PE', 'QC', 'SK', 'YT');

	/**
	 *	List of recognized state and territory codes for Australia
	 */
	const AUSTRALIA_TERRITORY_CODES = array('ACT', 'JBT', 'NSW', 'NT', 'QLD', 'SA', 'TAS', 'VIC', 'WA');

    /**
     * The ???
     *
     * @var string
     */
    protected $name;

    /**
     * The ???
     *
     * @var string
     */
    protected $company;

    /**
     * The ???
     *
     * @var string
     */
    protected $address_line_1;

    /**
     * The ???
     *
     * @var string
     */
    protected $address_line_2;
	
    /**
     * The ???
     *
     * @var string
     */
    protected $address_line_3;

    /**
     * The ???
     *
     * @var string
     */
    protected $address_line_4;
	
    /**
     * The ???
     *
     * @var string
     */
    protected $city;
	
    /**
     *	The two character code representing the state or province component
	 *		of the address
     *
     * @var string
     */
    protected $state;
	
    /**
     *	The postal code component of the address for US addresses this may be
	 *		either the five or nine digit zip code.
     *
     * @var string
     */
    protected $postal_code;
	
    /**
     * The ???
     *
     * @var string
     */
    protected $delivery_point;
	
    /**
     *	The ISO 3166 two character country code representing the country
	 *		component of the address
     *
     * @var string
     */
    protected $country;
	
    /**
     *	A phone number to associate with the address. Typically this phone
	 *		should be the one a delivery person may use when there is a problem
	 *		delivering a parcel to an address.
     *
     * @var string
     */
    protected $phone;
	
    /**
     * An email address to associate with the address. Typically this email address
	 *		should be one a parcel delivery operator may use when there is a problem
	 *		delivering a parcel to an address.
     *
     * @var string
     */
    protected $email;
	
	/**
	* Creates a new Address.
	*
	* @param string|null $name - the Recipient's name must be 0 < len ≤ 47
	*		characters must not be null if company is null
	* @param string|null $company - the Recipient company must be 0 < len ≤ 47
	*		characters must not be null if name is null
	* @param string $address_line_1 - the first address line 0 < len ≤ 47
	 *		characters
	* @param string|null $address_line_2 - the second address line 0 < len ≤ 47
	*		characters
	* @param string|null $address_line_3 - the third address line 0 < len ≤ 47
	*		characters
	* @param string|null $address_line_4 - the fourth address line 0 < len ≤ 47
	*		characters
	* @param string $city - the city 0 < len ≤ 50 characters
	* @param string $state - the two character state or province code for US
	*		Addresses, 0 < len ≤ 25 for international
	* @param string $postal_code - the five or nine character zip code for
	*		US Addresses may be 0 < len ≤ 15 characters for international
	*		addresses
	* @param string|null $delivery_point - the two delivery point US Addresses
	*		only
	* @param string $country - two character country code
	* @param string|null $phone - the phone number for the address must be 10
	*		digits with no punctuation for US or up to 30 digits still with no
	*		punctuation for International
	* @param string|null $phone - the email address for the address 0 < len ≤ 64
	*		characters
	*
	* @throws  InvalidArgumentException If the requester id is invalid.
	*/
	public function __construct(
		?string $name,
		?string $company,
		string $address_line_1,
		?string $address_line_2,
		?string $address_line_3,
		?string $address_line_4,
		string $city,
		string $state,
		string $postal_code,
		?string $delivery_point,
		string $country,
		?string $phone,
		?string $email
	) {
		// do first as some validation rules depend upon the country
		$this->setCountry($country);
		
		// setting name to NULL will trigger a validation error as company starts as NULL
		// but setters enforce that company and name may not both be null
		if($name) {
			$this->setName($name);
		}
		$this->setCompany($company);
		$this->setAddressLine1($address_line_1);
		$this->setAddressLine2($address_line_2);
		$this->setAddressLine3($address_line_3);
		$this->setAddressLine4($address_line_4);
		$this->setCity($city);
		$this->setState($state);
		$this->setPostalCode($postal_code);
		$this->setDeliveryPoint($delivery_point);
		$this->setPhone($phone);
		$this->setEmail($email);
	}
	
	/**
	* ???
	*
	* @param string|null $name - 
	*
	* @throws  InvalidArgumentException ???
	*/
	public function setName(?string $name)
	{
		if(!$name && !$this->company)
		{
			throw new InvalidArgumentException(
				"The name must be provided unless the company is provided"
			);
		}
		
		if($name)
		{
			if(strlen($name) > 47)
			{
				throw new InvalidArgumentException(
					"The name must not be longer than 47 characters"
				);
			}			
		}
		
		$this->name = $name;
	}
	
	/**
	 *	Get the name line of the Address
	 *
	 *	@return string|null the name line of the Address
	 */
	public function getName() : ?string
	{
		return $this->name;
	}
	
	/**
	* ???
	*
	* @param string|null $name - 
	*
	* @throws  InvalidArgumentException ???
	*/
	public function setCompany(?string $company)
	{
		if(!$company && !$this->name)
		{
			throw new InvalidArgumentException(
				"The company must be provided unless name is provided"
			);
		}
		
		if($company)
		{
			if(strlen($company) > 47)
			{
				throw new InvalidArgumentException(
					"The company name must not be longer than 47 characters"
				);
			}
		}
		
		$this->company = $company;
	}
	
	/**
	 *	???
	 *
	 *	@return string|null ???
	 */
	public function getCompany() : ?string
	{
		return $this->company;
	}
	
	/**
	* ???
	*
	* @param string|null $name - 
	*
	* @throws  InvalidArgumentException ???
	*/
	public function setAddressLine1(?string $address_line)
	{
		if(!$address_line && !$this->company)
		{
			throw new InvalidArgumentException(
				"The address line 1 must be provided unless the company is provided"
			);
		}
		
		if(strlen($address_line) > 47)
		{
			throw new InvalidArgumentException(
				"The address line 1 must not be longer than 47 characters"
			);
		}
		
		$this->address_line_1 = $address_line;
	}
	
	/**
	 *	???
	 *
	 *	@return string|null ???
	 */
	public function getAddressLine1() : ?string
	{
		return $this->address_line_1;
	}
	
	/**
	* ???
	*
	* @param string|null $name - 
	*
	* @throws  InvalidArgumentException ???
	*/
	public function setAddressLine2(?string $address_line)
	{
		if($address_line && strlen($address_line) > 47)
		{
			throw new InvalidArgumentException(
				"The address line 2 must not be longer than 47 characters"
			);
		}
		
		$this->address_line_2 = $address_line;
	}
	
	/**
	 *	???
	 *
	 *	@return string|null ???
	 */
	public function getAddressLine2() : ?string
	{
		return $this->address_line_2;
	}
	
	/**
	* ???
	*
	* @param string|null $name - 
	*
	* @throws  InvalidArgumentException ???
	*/
	public function setAddressLine3(?string $address_line)
	{
		if($address_line && strlen($address_line) > 47)
		{
			throw new InvalidArgumentException(
				"The address line 3 must not be longer than 47 characters"
			);
		}
		
		$this->address_line_3 = $address_line;
	}
	
	/**
	 *	???
	 *
	 *	@return string|null ???
	 */
	public function getAddressLine3() : ?string
	{
		return $this->address_line_3;
	}
	
	/**
	* ???
	*
	* @param string|null $name - 
	*
	* @throws  InvalidArgumentException ???
	*/
	public function setAddressLine4(?string $address_line)
	{
		if($address_line && strlen($address_line) > 47)
		{
			throw new InvalidArgumentException(
				"The address line 4 must not be longer than 47 characters"
			);
		}
		
		$this->address_line_4 = $address_line;
	}
	
	/**
	 *	???
	 *
	 *	@return string|null ???
	 */
	public function getAddressLine4() : ?string
	{
		return $this->address_line_4;
	}
	
	/**
	* ???
	*
	* @param string|null $name - 
	*
	* @throws  InvalidArgumentException ???
	*/
	public function setCity(?string $city)
	{
		if(!$city || strlen($city) > 50 || preg_match('/\A[a-zA-Z\-\. ]+\z/', $city) != 1)
		{
			throw new InvalidArgumentException(
				"The city must not be longer than 50 characters, and contain only a-z, A-Z, space, period and hyphen characters."
			);
		}
		
		$this->city = $city;
	}
	
	/**
	 *	???
	 *
	 *	@return string
	 */
	public function getCity() : string
	{
		return $this->city;
	}
	
	/**
	* ???
	*
	* @param string|null $name - 
	*
	* @throws  InvalidArgumentException ???
	*/
	public function setState(string $state)
	{
		if(!$state)
		{
			throw new InvalidArgumentException(
				"The state of province is required."
			);
		}
		
		if('US' == $this->country)
		{
			if(in_array($state, self::US_STATE_CODES))
			{
				$this->state = $state;
			}
			else
			{
				throw new InvalidArgumentException(
					"Please provide the state as the accepted two letter code for US addresses."
				);
			}
		}
		else if('AU' == $this->country)
		{
			if(in_array($state, self::AUSTRALIA_TERRITORY_CODES))
			{
				$this->state = $state;
			}
			else
			{
				throw new InvalidArgumentException(
					"Please provide the province as the accepted two letter code for Canadian addresses."
				);
			}
		}
		else if('CA' == $this->country)
		{
			if(in_array($state, self::CANADA_PROVINCE_CODES))
			{
				$this->state = $state;
			}
			else
			{
				throw new InvalidArgumentException(
					"Please provide the territory as the accepted two/three letter code for Australian addresses."
				);
			}
		}
		else
		{
			$this->state = $state;
		}
	}

	/**
	 *	???
	 *
	 *	@return string
	 */
	public function getState() : string
	{
		return $this->state;
	}
	
	/**
	* ???
	*
	* @param string|null $name - 
	*
	* @throws  InvalidArgumentException ???
	*/
	public function setPostalCode(string $postal_code)
	{
		if(!$postal_code)
		{
			throw new InvalidArgumentException(
				"The postal code must be provided."
			);
		}
		
		if('US' == $this->country)
		{
			if(preg_match('/\A\d{5}\z/', $postal_code) != 1 && preg_match('/\A\d{5}-\d{4}\z/', $postal_code) != 1)
			{
				throw new InvalidArgumentException(
					"The postal code must be either a 5 digit zip code or a 10 digit (including hyphen) zip+4 for US addresses."
				);
			}
		}
		else
		{
			if(strlen($postal_code) > 10)
			{
				throw new InvalidArgumentException(
					"The postal code must not be longer than 10 characters."
				);
			}
		}

		$this->postal_code = $postal_code;
	}
	
	/**
	 *	???
	 *
	 *	@return string
	 */
	public function getPostalCode() : string
	{
		return $this->postal_code;
	}
	
	/**
	* ???
	*
	* @param string|null $name - 
	*
	* @throws  InvalidArgumentException ???
	*/
	public function setDeliveryPoint(?string $delivery_point)
	{
		if($delivery_point && strlen($delivery_point) != 2)
		{
			throw new InvalidArgumentException(
				"The delivery point must be exactly 2 characters"
			);
		}
		
		$this->delivery_point = $delivery_point;
	}
	
	/**
	 *	???
	 *
	 *	@return string|null ???
	 */
	public function getDeliveryPoint() : ?string
	{
		return $this->delivery_point;
	}
	
	/**
	* ???
	*
	* @param string|null $name - 
	*
	* @throws  InvalidArgumentException ???
	*/
	public function setCountry(string $country)
	{
		if(!$country)
		{
			throw new InvalidArgumentException(
				"The country is required"
			);
		}
		
		if(in_array($country, self::COUNTRY_CODES))
		{
			$this->country = $country;
		}
		else if(array_key_exists($country, self::COUNTRY_NAME_TO_CODE_MAP))
		{
			$this->country = self::COUNTRY_NAME_TO_CODE_MAP[$country];
		}
		else
		{
			throw new InvalidArgumentException(
				"The country must be an ISO 3166 two letter country code, a country short name, or a common vulgar country name e.g. USA for United States"
			);
		}
	}
	
	/**
	 *	???
	 *
	 *	@return string|null ???
	 */
	public function getCountry() : string
	{
		return $this->country;
	}
	
	/**
	* ???
	*
	* @param string|null $name - 
	*
	* @throws  InvalidArgumentException ???
	*/
	public function setPhone(?string $phone)
	{
		if($phone)
		{
			if('US' == $this->country)
			{
				if(strlen($phone) != 10)
				{
					throw new InvalidArgumentException(
						'The phone number associated with a domestic (US) address must be exactly 10 characters long.'
					);
				}
				if(preg_match('/\A\d+\z/', $phone) != 1)
				{
					throw new InvalidArgumentException(
						'The phone number associated with a domestic (US) address must contain only digits.'
					);
				}
			}
			else
			{
				if(strlen($phone) > 30 || preg_match('/\A\d+\z/', $phone) != 1)
				{
					throw new InvalidArgumentException(
						"The phone number associated with an international address must not contain characters other than digits and must be exactly 10 characters long."
					);
				}
			}
		}
		
		$this->phone = $phone;
	}
	
	/**
	 *	???
	 *
	 *	@return string|null ???
	 */
	public function getPhone() : string
	{
		return $this->phone;
	}
	
	/**
	* Sets the email address to associate with this address
	*
	* @param string|null $email - the new email address
	*
	* @throws  InvalidArgumentException if the email address is longer than 64 characters
	*/
	public function setEmail(?string $email) : void
	{
		if($email && strlen($email) > 64)
		{
			throw new InvalidArgumentException(
				"The email address must not be longer than 64 characters"
			);
		}
		
		$this->email = $email;
	}
	
	/**
	 *	Gets the email address associated with this address
	 *
	 *	@return a string which should be a valid email address
	 */
	public function getEmail() : string
	{
		return $this->email;
	}
}