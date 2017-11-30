<?php
namespace Includes\Modules\MLS;

use GuzzleHttp\Client;
use Includes\Modules\Agents\Agents;

/**
* MLS Listing - Made by Daron Adkins
*/
class FullListing
{
    private $mlsNumber;
    protected $listingInfo;

    /**
     * Search Constructor
     * @param string $mlsNumber - Basically just the $_GET variables
     */
    public function __construct($mlsNumber)
    {
        $this->mlsNumber   = $mlsNumber;
    }

    public function create()
    {
        $client = new Client(['base_uri' => 'https://mothership.kerigan.com/api/v1/listing/','http_errors' => false]);

        // make the API call
        $raw = $client->request(
            'GET',
            $this->mlsNumber
        );

        $results = json_decode($raw->getBody());

        return $results;
    }

    public function isOurs($listingInfo)
    {
        $agents = new Agents();
        $agentArray = $agents->getTeam();

        $mlsArray = array();
        foreach ($agentArray as $agent) {
            $agentIds = explode(',', $agent['short_ids']);
            foreach ($agentIds as $agentId) {
                if ($agentId != '') {
                    $mlsArray[] = $agentId;
                }
            }
        }

        if (in_array($listingInfo->listing_member_shortid, $mlsArray)) {
            return 'listing_member_shortid';
        }
        if (in_array($listingInfo->colisting_member_shortid, $mlsArray)) {
            return 'colisting_member_shortid';
        }

        return false;
    }

    public function isInFavorites($user_id, $mls_number)
    {
        $favorite = new FavoriteProperty();

        $results = $favorite->findFavorite($user_id, $mls_number);

        return (! empty($results));
    }

    public function setListingSeo($listingInfo)
    {
        $this->listingInfo = $listingInfo;

        add_filter('wpseo_title', function () {
            $title = $this->listingInfo->street_number . ' ' . $this->listingInfo->street_name;
            $title = ($this->listingInfo->unit_number != '' ? $title . ' ' . $this->listingInfo->unit_number : $title);
            $metaTitle = $title . ' | $' . number_format($this->listingInfo->price) . ' | ' . $this->listingInfo->city . ' | ' . get_bloginfo('name');
            return $metaTitle;
        });

        add_filter('wpseo_metadesc', function () {
            return strip_tags($this->listingInfo->description);
        });

        add_filter('wpseo_opengraph_image', function () {
            return ($this->listingInfo->preferred_image != '' ? $this->listingInfo->preferred_image : get_template_directory_uri() . '/img/beachybeach-placeholder.jpg');
        });

        add_filter('wpseo_canonical',  function () {
            return get_the_permalink() . '?mls=' . $this->listingInfo->mls_account;
        });

        add_filter('wpseo_opengraph_url', function () {
            return get_the_permalink() . '?mls=' . $this->listingInfo->mls_account;
        }, 100, 1);
    }
}
