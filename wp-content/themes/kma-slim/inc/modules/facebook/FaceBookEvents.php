<?php
use GuzzleHttp\Client;

class FaceBookEvents
{
    public function fetch($limit = 5)
    {
        $client = new Client([
            'base_uri' => 'https://graph.facebook.com/v2.9'
        ]);

        $page_id      = FACEBOOK_PAGE_ID;
        $access_token = FACEBOOK_ACCESS_TOKEN;
        $response     = $client->request('GET', '/' . $page_id . '/events/?limit=' . $limit . '&access_token=' . $access_token);

        $feed = json_decode($response->getBody());

        return $feed;
    }
}


