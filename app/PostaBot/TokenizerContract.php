<?php

namespace App\PostaBot;

interface TokenizerContract
{
    /**
     * redrect user to social platform login page to auth the app
     */
    public function redirect();

    /**
     * get array of account info and add it to database
     */
    public function getAndSaveData();

    /**
     * revoke the account access token (logout)
     */
    public function revoke($accessToken);

}
