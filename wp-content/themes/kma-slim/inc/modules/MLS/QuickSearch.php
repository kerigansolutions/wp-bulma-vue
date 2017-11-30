<?php
namespace Includes\Modules\MLS;

use GuzzleHttp\Client;

/**
* MLS Search - Made by Daron Adkins
*/
class QuickSearch
{
    private $searchCriteria;

    /**
     * Search Constructor
     * @param array $searchCriteria - Basically just the $_GET variables
     */
    public function __construct($searchCriteria)
    {
        $this->searchCriteria   = $searchCriteria;
    }


    public function create()
    {
        $omni         = $this->searchCriteria['omniField'] ?? '';
        $propertyType = isset($this->searchCriteria['propertyType']) && $this->searchCriteria['propertyType'] != '' ?
            implode('|', self::getPropertyTypes($this->searchCriteria['propertyType'])) : '';
        $minPrice     = $this->searchCriteria['minPrice'] ?? '';
        $maxPrice     = $this->searchCriteria['maxPrice'] ?? '';
        $bedrooms     = $this->searchCriteria['bedrooms'] ?? '';
        $bathrooms    = $this->searchCriteria['bathrooms'] ?? '';
        $sq_ft        = $this->searchCriteria['sq_ft'] ?? '';
        $acreage      = $this->searchCriteria['acreage'] ?? '';
        $waterfront   = $this->searchCriteria['waterfront'] ?? '';
        $pool         = $this->searchCriteria['pool'] ?? '';
        $page         = $this->searchCriteria['pg'] ?? 1;
        $sortBy       = $this->searchCriteria['sortBy'] ?? 'date_modified';
        $orderBy      = $this->searchCriteria['orderBy'] ?? 'DESC';
        $status       = '';

        /*
         * If multiple statuses are selected, create a string from the indexes.
         * Otherwise, just use the specified status or just default to "Active".
         */
        if (isset($this->searchCriteria['status'])) {
            if (is_array($this->searchCriteria['status'])) {
                $status = implode('|', $this->searchCriteria['status']);
            } else {
                $status = $this->searchCriteria['status'];
            }
        }

        $client       = new Client(['base_uri' => 'https://mothership.kerigan.com/api/v1/']);

        // make the API call
        $apiCall = $client->request(
            'GET',
            'search?'
            .'city='.          $omni
            .'&propertyType='. $propertyType
            .'&status='.       $status
            .'&minPrice='.     $minPrice
            .'&maxPrice='.     $maxPrice
            .'&bedrooms='.     $bedrooms
            .'&bathrooms='.    $bathrooms
            .'&sq_ft='.        $sq_ft
            .'&acreage='.      $acreage
            .'&waterfront='.   $waterfront
            .'&pool='.         $pool
            .'&page='.         $page
            .'&sortBy='.       $sortBy
            .'&orderBy='.      $orderBy
        );

        $results = json_decode($apiCall->getBody());

        return $results;
    }

    public static function getPropertyTypes($class = null)
    {
        $typeArray = [
            'Single Family Home'   => ['Detached Single Family'],
            'Condo / Townhome'     => ['Condominium', 'Townhouse', 'Townhomes'],
            'Commercial'           => ['Office', 'Retail', 'Industrial', 'Income Producing', 'Unimproved Commercial', 'Business Only', 'Auto Repair', 'Improved Commercial', 'Hotel/Motel'],
            'Lots / Land'          => ['Vacant Land', 'Residential Lots', 'Land', 'Land/Acres', 'Lots/Land'],
            'Multi-Family Home'    => ['Duplex Multi-Units', 'Triplex Multi-Units'],
            'Rental'               => ['Apartment', 'House', 'Duplex', 'Triplex', 'Quadruplex', 'Apartments/Multi-family'],
            'Manufactured'         => ['Mobile Home', 'Mobile/Manufactured'],
            'Farms / Agricultural' => ['Farm', 'Agricultural', 'Farm/Ranch', 'Farm/Timberland'],
            'Other'                => ['Attached Single Unit', 'Attached Single Family', 'Dock/Wet Slip', 'Dry Storage', 'Mobile/Trailer Park', 'Mobile Home Park', 'Residential Income', 'Parking Space', 'RV/Mobile Park']
        ];

        if ($class != null) {
            return $typeArray[$class];
        }

        return $typeArray;
    }
}
