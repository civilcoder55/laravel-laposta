<?php

namespace App\Http\Requests;

use App\Rules\PostAccountsCheck;
use App\Rules\PostMediaCheck;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'draft' => ['required', 'boolean'],
            'accounts' => ['required_if:draft,==,0', 'array', 'min:1', new PostAccountsCheck],
            'accounts.*' => ['distinct', 'integer'],
            'message' => ['required_if:draft,==,0', 'string', 'nullable'],
            'media' => ['array', 'min:1', new PostMediaCheck],
            'media.*' => ['distinct', 'integer'],
            'schedule_date' => ['required_if:draft,==,0', 'date_format:m/d/Y g:i A', 'after:now', 'nullable'],
        ];
    }

    public function withValidator($validator)
    {
        if (!$validator->fails()) {
            $validator->after(function ($validator) {
                if ($this->schedule_date) {
                    $this->merge(['schedule_date' => Carbon::createFromFormat('m/d/Y g:i A', $this->schedule_date)->timestamp]);
                }

                if (!$this->media) {
                    $this->merge(['media' => []]);
                }

                if (!$this->accounts) {
                    $this->merge(['accounts' => []]);
                }

            });
        }

    }

    public function messages()
    {
        return [
            'accounts.required_if' => 'Please select some accounts before schedule',
            'message.required_if' => 'Please add text message before schedule',
            'schedule_date.required_if' => 'Please select schedule date and time',

        ];
    }
}
