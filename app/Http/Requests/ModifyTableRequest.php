<?php

namespace App\Http\Requests;

//use Illuminate\Foundation\Http\FormRequest;


class ModifyTableRequest extends PublicRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'connection_name' => 'bail|required|string',
            'table_name' => 'bail|required|string',
            'table_comment' => ['bail','required',
                function ($attribute, $value, $fail) {
                   if ($old= $this->input('old')) {
                       if ($old == $value) {
                           $fail($attribute . ' 您没有改动，无需提交修改');
                       }
                   }
                },
                ],
            'old' => ['bail',],

        ];
    }

    public function messages()
    {
        return [
            'connection_name.required'            => '连接名称必须',
            'table_comment.required'         => '表注释必须填写',
            'name.regex'            => '姓名2-15汉字',
            'phone.required'        => '请填写手机号',

        ];
    }


}
