<?php
namespace Includes\Modules\MLS;

use GuzzleHttp\Client;

class FavoriteProperty
{
    public function handleFavorite($user_id, $mls_account)
    {
        global $wpdb;

        $returned_object = $wpdb->get_results(
            "SELECT * FROM favorite_properties
             WHERE user_id = {$user_id}
             AND mls_account LIKE '{$mls_account}'"
        );

        if (empty($returned_object)) {
            $this->addListingToFavorites($user_id, $mls_account);
            $count    = $wpdb->get_results("SELECT COUNT(id) as items FROM favorite_properties WHERE user_id = {$user_id}");
            $listings = $count[0]->items;

            $response = [
                'status'  => 'Success',
                'message' => 'Listing ' . $mls_account . ' has been added to your saved properties!',
                'count'   => $listings
            ];

            return json_encode($response);
        }

        $this->removeListingFromFavorites($user_id, $mls_account);
        $count    = $wpdb->get_results("SELECT COUNT(id) as items FROM favorite_properties WHERE user_id = {$user_id}");
        $listings = $count[0]->items;

        $response = [
            'status'  => 'Success',
            'message' => 'Listing ' . $mls_account . ' has been removed from your saved properties!',
            'count'   => $listings
        ];

        return json_encode($response);
    }

    private function addListingToFavorites($user_id, $mls_account)
    {
        global $wpdb;

        $wpdb->insert(
            'favorite_properties',
            array(
                'user_id'     => $user_id,
                'mls_account' => $mls_account
            ),
            array(
                '%d',
                '%s'
            )
        );
    }

    private function removeListingFromFavorites($user_id, $mls_account)
    {
        global $wpdb;

        $wpdb->query(
            "DELETE FROM favorite_properties
             WHERE user_id={$user_id} AND mls_account LIKE '{$mls_account}'"
        );
    }

    public function findFavorite($user_id, $mls_account)
    {
        global $wpdb;

        $query = "SELECT * FROM favorite_properties
                  WHERE user_id={$user_id}
                  AND mls_account LIKE '{$mls_account}'";

        $results = $wpdb->get_results($query);

        return $results;
    }

    public static function getNumberOfFavorites($user_id)
    {
        global $wpdb;
        $count    = $wpdb->get_results("SELECT COUNT(id) as items FROM favorite_properties WHERE user_id = {$user_id}");
        $listings = $count[0]->items;

        return $listings;
    }

    /**
     * Returns array of mls numbers that were saved by the given user
     * @param  integer $user_id
     * @return array
     */
    public function getSavedListings($user_id)
    {
        global $wpdb;

        $mlsNumbers = [];
        $query      = "SELECT mls_account FROM favorite_properties WHERE user_id = {$user_id}";
        $results    = $wpdb->get_results($query);

        foreach ($results as $result) {
            array_push($mlsNumbers, $result->mls_account);
        }

        $mlsString = implode('|', $mlsNumbers);

        $client = new Client(['base_uri' => 'https://mothership.kerigan.com/api/v1/listings']);
        $raw = $client->request(
            'GET',
            '?mlsNumbers='.$mlsString
        );

        $listings = json_decode($raw->getBody());

        return $listings;
    }

    /**
     * Returns user information for the items in the beachy bucket so that agents can see who likes their stuff
     * @param  string $agentName
     * @return mixed|array
     */
    public function clientFavorites($agentName)
    {
        global $wpdb;
        $userIDs    = [];
        $userData   = [];
        $mlsNumbers = [];
        $query      = '';

        $results    = $wpdb->get_results("SELECT user_id from wp_usermeta WHERE meta_value LIKE '{$agentName}'");
        if (!empty($results)) {
            foreach ($results as $result) {
                array_push($userIDs, $result->user_id);
            }

            // We need to use 2 functions to get all the data we need because...Wordpress...yeah...
            for ($i = 0; $i < sizeOf($userIDs); $i++) {
                $userData[$i]              = get_user_meta($userIDs[$i]);
                $userData[$i]['id']        = $userIDs[$i];
                $userData[$i]['email']     = get_userdata($userIDs[$i])->user_email;
                $userData[$i]['favorites'] = $this->savedProperties($userIDs[$i]);
            }
        }

        return $userData;
    }

    public function allFavorites()
    {
        global $wpdb;

        $userIDs  = [];
        $userData = [];

        $results = $wpdb->get_results("SELECT DISTINCT user_id from favorite_properties WHERE 1=1");

        if (! empty($results)) {
            foreach ($results as $result) {
                array_push($userIDs, $result->user_id);
            }

            // We need to use 2 functions to get all the data we need because...Wordpress...yeah...
            for ($i = 0; $i < sizeOf($userIDs); $i++) {
                $userData[$i]              = get_user_meta($userIDs[$i]);
                $userData[$i]['id']        = $userIDs[$i];
                $userData[$i]['email']     = isset(get_userdata($userIDs[$i])->user_email) ? get_userdata($userIDs[$i])->user_email : '';
                $userData[$i]['favorites'] = $this->savedProperties($userIDs[$i]);
            }
        }

        return $userData;
    }


    protected function savedProperties($userId)
    {
        global $wpdb;
        $mlsNumbers = [];

        $query = "SELECT mls_account FROM favorite_properties WHERE user_id = {$userId}";

        $results = $wpdb->get_results($query);

        if (count($results) > 0) {
            foreach ($results as $result) {
                array_push($mlsNumbers, $result->mls_account);
            }
        }

        return $mlsNumbers;
    }


    public function favoriteResults($mlsNumberArray)
    {
        $results = $this->getListingsFromMothership($mlsNumberArray);

        return $results;
    }

    /**
     * @param $mlsNumberArray
     * @return string
     */
    public function getListingsFromMothership($mlsNumberArray)
    {
        $mlsNumberString = implode('|', $mlsNumberArray);
        $client          = new Client([
            'base_uri'   => 'https://mothership.kerigan.com/api/v1'
        ]);

        $raw = $client->request([
            'GET',
            '/listings?mlsNumbers='. $mlsNumberString
        ]);

        $results = json_decode($raw);

        return $results;
    }
}
