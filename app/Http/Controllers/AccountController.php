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
        $accounts = auth()->user()->getAccounts();
        return view('account', compact('accounts'));
    }

    // redirect user to platform to approve ...
    public function connect(TokenizerContract $Tokenizer)
    {
        return $Tokenizer->redirect();
    }

    // handling redirection form plaform and add account to database
    public function callback($platform, TokenizerContract $Tokenizer)
    {
        try {
            $Tokenizer->getAndSaveData();
            return redirect(route('accounts.index'))->with('status', "{$platform} account added successfuly");
        } catch (Exception $e) {
            return redirect(route('accounts.index'))->with('error', "Faild to add {$platform} account");
        }

    }

    // revoke access token and delete account form database ....
    public function destroy($platform, Account $account)
    {
        $this->authorize('delete', $account);
        //revoke access token
        // $Tokenizer->revoke($account->token);

        $account->delete();

        return redirect(route('accounts.index'))->with('status', "{$platform} account deleted successfully");
    }

}
