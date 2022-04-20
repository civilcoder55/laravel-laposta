<?php

namespace App\Http\Requests;

use App\Rules\PostAccountsCheck;
use App\Rules\PostMediaCheck;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'draft' => ['required', 'boolean'],
            'accounts' => ['required_if:draft,==,0', 'array', 'min:1', new PostAccountsCheck],
            'accounts.*' => ['distinct', 'integer'],
            'message' => ['required_if:draft,==,0', 'string', 'nullable'],
            'media' => ['array', 'min:1', new PostMediaCheck],
            'media.*' => ['distinct', 'integer'],
            'schedule_date' => ['required_if:draft,==,0', 'date_format:d/m/Y h:i A', 'after:now', 'nullable'],
        ];
    }

    public function withValidator($validator)
    {
        if (!$validator->fails()) {
            $validator->after(function ($validator) {
                // convert date to timestamp
                if ($this->schedule_date) {
                    $this->merge(['schedule_date' => Carbon::createFromFormat('d/m/Y h:i A', $this->schedule_date)->timestamp]);
                }
                // convert non media to empty array
                if (!$this->media) {
                    $this->merge(['media' => []]);
                }
                // convert non accounts to empty array
                if (!$this->accounts) {
                    $this->merge(['accounts' => []]);
                }

            });
        }

    }

    public function messages()
    {
        return [
            'accounts.required_if' => 'Please select at least one account',
            'message.required_if' => 'Please add text message',
            'schedule_date.required_if' => 'Please select publish date and time',
        ];
    }
}
