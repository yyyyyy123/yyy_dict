<?php

namespace App\Http\Requests;


class ConnectionRemoveRequest extends PublicRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'connection' => ['bail', 'required', 'regex:/^[0-9a-zA-Z-_]{2,30}$/',
                'exists:App\Models\ConnectionModel,connection'],
        ];
    }

    public function messages()
    {
        return [
            'connection.required' => '连接名称必须',
            'connection.exists' => '连接名称必须已经存在',
            'connection.regex' => '连接名称2至30个字符，必须0-9 or a-z or A-Z or - or _',
        ];
    }


}
