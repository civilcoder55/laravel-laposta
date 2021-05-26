<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\PostaBot\Contracts\Tokenizable;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;

class AccountController extends Controller
{
    public function index()
    {
        $accounts = UserRepository::getAccounts();
        return view('main.accounts', compact('accounts'));
    }

    // redirect user to platform to add social media account ...
    public function connect(Tokenizable $Tokenizer)
    {
        return $Tokenizer->redirect();
    }

    // handling redirection callback form platform and add account to database
    public function callback($platform, Tokenizable $Tokenizer): \Illuminate\Http\RedirectResponse
    {
        $Tokenizer->getAndSaveData();
        return redirect()->route('accounts.index')->with('status', "{$platform} account added successfully");
    }

    public function destroy(Account $account): \Illuminate\Http\RedirectResponse
    {
        $this->authorize('delete', $account);
        $account->delete();
        return redirect()->route('accounts.index')->with('status', "{$account->platform} account deleted successfully");
    }

}
