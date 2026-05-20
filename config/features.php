<?php

// app.phpはアプリ全体の基本設定 features.phpはアプリの機能のON/OFFの役割として分けた
return [
    // ユーザー登録できるかの設定
    'allow_user_registration' => env('ALLOW_USER_REGISTRATION', false),
];