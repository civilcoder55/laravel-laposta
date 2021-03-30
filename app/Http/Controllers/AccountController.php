<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\PostaBot\TokenizerContract;
use Exception;


class AccountController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        $accounts = auth()->user()->accounts()->where(['parent_id' => null])->orderBy('created_at', 'desc')->paginate(15);
        return view('account', compact('accounts'));
    }


    // redirect user to platform to approve ...
    public function redirectToPlatform(TokenizerContract $Tokenizer)
    {
        return $Tokenizer->redirect();
    }

    // handling redirection form plaform and add account to database
    public function redirectionHandler($platform, TokenizerContract $Tokenizer)
    {
        try {
            $data = $Tokenizer->getAndSaveData();
            return redirect(route('accounts.index'))->with('status', "{$platform} account added successfuly");
        } catch (Exception $e) {
            return redirect(route('accounts.index'))->with('error', "Faild to add {$platform} account");
        }

    }


    // revoke access token and delete account form database ....
    public function destroy($platform, TokenizerContract $Tokenizer, Account $account)
    {
        $this->authorize('delete', $account);
        //revoke access token
        $revoke = $Tokenizer->revoke($account->token);

        //delete parent account infos
        $account->delete();

        //delete child accounts
        $accounts = auth()->user()->accounts()->where(['platform' => $platform, 'parent_id' => $account->id])->get();
        foreach ($accounts as $account) {
            $account->delete();
        }
        return redirect(route('accounts.index'))->with('status', "{$platform} account deleted successfuly");
    }

}
