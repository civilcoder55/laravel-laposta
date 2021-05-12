<?php

namespace App\PostaBot\TokenizerProviders;

use App\PostaBot\Contracts\Tokenizable;
use App\PostaBot\Exceptions\TokenizerException;
use Illuminate\Support\Facades\Http;

class Facebook implements Tokenizable
{
    /**
     * get facebook app required configurations from laravel configs
     */
    public function __construct()
    {
        $this->client_id = config('services.facebook.client_id');
        $this->client_secret = config('services.facebook.client_secret');
        $this->redirect_uri = route('accounts.connect.callback', 'facebook');
    }

    /**
     * redrect user to social platform login page to auth the app
     */
    public function redirect()
    {
        return redirect()->to("https://www.facebook.com/v8.0/dialog/oauth?client_id={$this->client_id}&redirect_uri={$this->redirect_uri}&scope=pages_manage_posts,pages_read_engagement,publish_to_groups&response_type=code");
    }

    public function getAndSaveData()
    {
        $this->access_token = $this->handleCallback();
        //$account_info = $this->getAccountInfo();
        // $account = auth()->user()->accounts()->updateOrCreate(
        //     ['platform' => 'facebook', 'uid' => $account_info['uid']],
        //     ['name' => $account_info['name'], 'type' => $account_info['type'], 'token' => $account_info['token'], 'secret' => $account_info['secret']]
        // );

        $account_pages = $this->getAccountPages();
        foreach ($account_pages as $page) {
            auth()->user()->accounts()->updateOrCreate(
                ['platform' => 'facebook', 'uid' => $page['uid']],
                ['name' => $page['name'], 'type' => 'Page', 'token' => $page['token'], 'secret' => $page['secret']]
            );
        }

        $account_groups = $this->getAccountGroups();
        foreach ($account_groups as $group) {
            auth()->user()->accounts()->updateOrCreate(
                ['platform' => 'facebook', 'uid' => $group['uid']],
                ['name' => $group['name'], 'type' => 'Group', 'token' => $group['token'], 'secret' => $group['secret']]
            );
        }
    }

    /**
     *
     */
    private function handleCallback()
    {
        $code = request()->code;

        if (empty($code)) {
            throw new TokenizerException("Malformed Request , Please try again .. ");
        }

        // Get access token
        $response = Http::get('https://graph.facebook.com/v4.0/oauth/access_token', [
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'redirect_uri' => $this->redirect_uri,
            'grant_type' => 'authorization_code',
            'code' => $code,
        ]);

        if (!$response->successful()) {
            throw new TokenizerException(($e = $response->json()['error']['message']) ? $e : "Error , Please try again .. ");
        }

        return $response->json()['access_token'];
    }

    /**
     *
     */
    private function getAccountInfo()
    {
        $access_token = $this->access_token;
        $response = Http::get("https://graph.facebook.com/me?fields=id,first_name,last_name&access_token={$access_token}");
        if (!$response->successful()) {
            throw new TokenizerException("Malformed Request , Please try again .. ");
        }

        $account = $response->json();

        return ['platform' => 'facebook', 'uid' => $account['id'], 'name' => $account['first_name'] . " " . $account['last_name'], 'type' => 'Account', 'token' => $access_token, 'secret' => null];
    }
    /**
     *
     */

    /**
     *
     */
    private function getAccountPages()
    {
        $pages_data = [];
        $access_token = $this->access_token;

        //get facebook pages info
        $response = Http::get("https://graph.facebook.com/me/accounts?access_token={$access_token}");
        if (!$response->successful()) {
            throw new TokenizerException($response->json()['error']['message'] || "Error , Please try again .. ");
        }
        $pages = $response->json()['data'];
        foreach ($pages as $page) {
            $token = $this->getPageAccessToken($page['id']);
            array_push($pages_data, ['platform' => 'facebook', 'uid' => $page['id'], 'name' => $page['name'], 'token' => $token, 'secret' => null]);
        }
        return $pages_data;
    }

    private function getPageAccessToken($uid)
    {
        $access_token = $this->access_token;
        $response = Http::get("https://graph.facebook.com/$uid?fields=access_token&access_token=$access_token");
        if (!$response->successful()) {
            throw new TokenizerException($response->json()['error']['message'] || "Error , Please try again .. ");
        }
        return $response->json()['access_token'];
    }

    /**
     *
     */
    private function getAccountGroups()
    {
        $groups_data = [];
        $access_token = $this->access_token;

        //get facebook groups info
        $response = Http::get("https://graph.facebook.com/me/groups?access_token={$access_token}");
        if (!$response->successful()) {
            throw new TokenizerException($response->json()['error']['message'] || "Error , Please try again .. ");
        }

        $groups = $response->json()['data'];
        foreach ($groups as $group) {
            array_push($groups_data, ['platform' => 'facebook', 'uid' => $group['id'], 'name' => $group['name'], 'token' => $access_token, 'secret' => null]);
        }
        return $groups_data;
    }

    public function revoke($access_token)
    {
        $response = Http::delete("https://graph.facebook.com/v2.4/me/permissions?access_token={$access_token}");
        if ($response->ok()) {
            return true;
        }

        return false;
    }

}
