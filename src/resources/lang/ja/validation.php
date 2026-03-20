<?php
return [
    'required' => ':attributeを入力してください',
    'email'    => 'メールアドレスの形式で入力してください',
    'unique'   => 'その:attributeは既に登録されています',
    'min'      => [
        'string' => ':attributeは:min文字以上で入力してください',
    ],
    'attributes' => [
        'name'     => 'ユーザー名',
        'email'    => 'メールアドレス',
        'password' => 'パスワード',
    ],
    'custom' => [
        'name' => [
            'required' => 'お名前を入力してください',
        ],
        'email' => [
            'required' => 'メールアドレスを入力してください',
            'email'    => 'メールアドレスは「ユーザー名@ドメイン」の形式で入力してください',
            'unique'   => 'このメールアドレスは既に登録されています',
        ],
        'password' => [
            'required' => 'パスワードを入力してください',
            'min'      => 'パスワードは8文字以上で入力してください',
        ],
    ],
];