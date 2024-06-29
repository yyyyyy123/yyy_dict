<?php

namespace App\Http\Requests;

use App\Repository\PdoHelp;
use App\Repository\SqliteRepository;
use Illuminate\Validation\Validator;

class ConnectionAddRequest extends PublicRequest
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
                'unique:App\Models\ConnectionModel,connection'],
            'host' => 'bail|required|string',
            'port' => ['bail', 'required', 'integer'],
            'db_name' => ['bail', 'required', 'regex:/^.{1,64}$/',
                function ($attribute, $value, $fail) {
                    if (preg_match('#^(information_schema|mysql|performance_schema|sys)$#i', $value)) {
                        $fail( '库名称不可以使用这几个关键词');
                    }
                },
            ],
            'username' => ['bail', 'required',],
            'password' => ['bail', 'required',],

        ];
    }

    /**
     * 配置验证实例
     *
     * @param Validator $validator
     * @return void
     */
    public function withValidator(Validator $validator)
    {

        $validator->after(function ($validator) {
            $sqliteRepository = new SqliteRepository();
            // 先检查唯一。
            $connectionList = $sqliteRepository->getAllConnections();
            foreach ($connectionList as $connection) {
                if ($connection->host == $this->input('host') &&
                    $connection->port == $this->input('port') &&
                    $connection->db_name == $this->input('db_name')) {
                    $validator->errors()->add('field', '主机，端口，库名必须联合唯一');
                    return;
                }

            }

            // 再检查是否能连上数据库。
            $pdoHelp = new PdoHelp(
                $this->input('host'),
                $this->input('port'),
                $this->input('db_name'),
                $this->input('username'),
                $this->input('password'));
            if (!$pdoHelp->isValid()) {
                $validator->errors()->add('field', $pdoHelp->getErr());
                return;
            }

        });
    }

    public function messages()
    {
        return [
            'connection.required' => '连接名称必须',
            'connection.unique' => '连接名称必须唯一',
            'connection.regex' => '连接名称2至30个字符，必须0-9 or a-z or A-Z or - or _',

            'host.required' => '主机必须',
            'port.required' => '端口号必须',
            'port.integer' => '端口号必须整数',
            'db_name.required' => '数据库名必须',
            'db_name.regex' => '数据库名最长64个字符',

            'username.required' => '账号必须',
            'password.required' => '密码必须',

        ];
    }


}
