<?php
use GuzzleHttp\Client;

class FacebookFeed
{
    /**
     * @param int $limit
     * @return array
     */
    public function fetch($limit = 5)
    {
        $client = new Client([
            'base_uri' => 'https://graph.facebook.com/v2.9'
        ]);

        $page_id      = FACEBOOK_PAGE_ID;
        $access_token = FACEBOOK_ACCESS_TOKEN;
        $fields       = 'id,message,link,name,caption,description,created_time,updated_time,picture,object_id,type';
        $response     = $client->request('GET', '/' . $page_id . '/posts/?fields=' . $fields . '&limit=' . $limit . '&access_token=' . $access_token);
        $feed         = json_decode($response->getBody());

        return $feed;
    }

    public function photo($fbpost)
    {
        $client = new Client([
            'base_uri' => 'https://graph.facebook.com/v2.9'
        ]);

        $access_token = FACEBOOK_ACCESS_TOKEN;
        if ($fbpost->type == 'link' || $fbpost->type == 'video') {
            $response  = $client->request('GET', '/?id=' . $fbpost->link . '&access_token=' . $access_token);
            $returned  = json_decode($response->getBody());
            if(! isset($returned->og_object->id)){
                $fbpost->type = 'foo'; //no og_object, so change type to skip this conditional
                return $this->photo($fbpost);
            }
            $og_id     = $returned->og_object->id;
            $response  = $client->request('GET', '/' . $og_id . '/?fields=image&access_token=' . $access_token);
            $returned  = json_decode($response->getBody());
            $photo_url = $returned->image[0]->url;
        } else {
            $response  = $client->request('GET', '/' . $fbpost->id . '/?fields=object_id&access_token=' . $access_token);
            $returned  = json_decode($response->getBody());
            $object_id = $returned->object_id;
            $photo_url = 'https://graph.facebook.com/v2.9/' . $object_id . '/picture?access_token=' . $access_token;

        }

        return $photo_url;

    }
}


