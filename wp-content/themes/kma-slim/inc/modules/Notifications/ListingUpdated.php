<?php
namespace Includes\Modules\Notifications;

use GuzzleHttp\Client;

class ListingUpdated
{
    public function notify()
    {
        $users = $this->getUsersWithSavedProperties();

        foreach ($users as $user) {
            if ($this->userHasUpdatedFavorites($user->user_id)) {
                $this->notifyUserOfChanges($user->user_id);
            }
        }
    }

    private function userHasUpdatedFavorites($userId)
    {
        $favorites       = $this->flattenListings($this->favoritedListings($userId));
        $updatedListings = $this->flattenListings($this->fetchUpdatedListings());

        foreach ($favorites as $favorite) {
            if (in_array($favorite, $updatedListings)) {
                return true;
            }
        }
        return false;
    }

    private function getUsersWithSavedProperties()
    {
        global $wpdb;

        $query   = "SELECT DISTINCT user_id from favorite_properties";
        $results = $wpdb->get_results($query);

        return $results;
    }

    private function fetchUpdatedListings()
    {
        $client = new Client(['base_uri' => 'https://mothership.kerigan.com/api/v1/']);
        $raw    = $client->request(
            'GET',
            'updatedListings'
        );

        $updatedListings = json_decode($raw->getBody());

        return $updatedListings;
    }

    private function favoritedListings($userId)
    {
        global $wpdb;

        $query   = "SELECT DISTINCT mls_account FROM favorite_properties WHERE user_id = {$userId}";
        $results = $wpdb->get_results($query);

        return $results;
    }

    private function flattenListings($listings)
    {
        $mlsNumberArray = [];

        foreach ($listings as $listing) {
            array_push($mlsNumberArray, $listing->mls_account);
        }

        return $mlsNumberArray;
    }

    private function notifyUserOfChanges($userId)
    {
        $eol     = "\r\n";
        $to      = get_userdata($userId)->user_email;

        $subject = 'Properties that you saved have been recently updated';
        $message = 'There have been changes to one or more properties that you have saved at beachtimerealty.com.
                     Check them out <a href = "https://beachtimerealty.com/my-account">here </a>';

        $headers = 'From: noreply@beachtime.com' . $eol;
        $headers .= 'MIME-Version: 1.0' . $eol;
        $headers .= 'Content-type: text/html; charset=utf-8' . $eol;

        wp_mail($to, $subject, $message, $headers);
    }
}
