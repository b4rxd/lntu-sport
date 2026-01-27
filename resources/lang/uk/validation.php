<?php

return [

    'accepted' => 'Поле :attribute має бути прийняте.',
    'accepted_if' => 'Поле :attribute має бути прийняте, коли :other дорівнює :value.',
    'active_url' => 'Поле :attribute має бути коректним URL.',
    'after' => 'Поле :attribute має бути датою пізніше ніж :date.',
    'after_or_equal' => 'Поле :attribute має бути датою не раніше ніж :date.',
    'alpha' => 'Поле :attribute може містити лише літери.',
    'alpha_dash' => 'Поле :attribute може містити лише літери, цифри, дефіси та підкреслення.',
    'alpha_num' => 'Поле :attribute може містити лише літери та цифри.',
    'any_of' => 'Поле :attribute має некоректне значення.',
    'array' => 'Поле :attribute має бути масивом.',
    'ascii' => 'Поле :attribute може містити лише ASCII-символи.',
    'before' => 'Поле :attribute має бути датою раніше ніж :date.',
    'before_or_equal' => 'Поле :attribute має бути датою не пізніше ніж :date.',

    'between' => [
        'array' => 'Поле :attribute має містити від :min до :max елементів.',
        'file' => 'Поле :attribute має бути від :min до :max кілобайт.',
        'numeric' => 'Поле :attribute має бути між :min та :max.',
        'string' => 'Поле :attribute має містити від :min до :max символів.',
    ],

    'boolean' => 'Поле :attribute має бути істинним або хибним.',
    'can' => 'Поле :attribute містить заборонене значення.',
    'confirmed' => 'Підтвердження поля :attribute не співпадає.',
    'contains' => 'Поле :attribute не містить обовʼязкове значення.',
    'current_password' => 'Невірний пароль.',
    'date' => 'Поле :attribute має бути коректною датою.',
    'date_equals' => 'Поле :attribute має бути датою :date.',
    'date_format' => 'Поле :attribute має відповідати формату :format.',
    'decimal' => 'Поле :attribute має містити :decimal знаків після коми.',
    'declined' => 'Поле :attribute має бути відхилене.',
    'declined_if' => 'Поле :attribute має бути відхилене, коли :other дорівнює :value.',
    'different' => 'Поля :attribute та :other мають бути різними.',
    'digits' => 'Поле :attribute має містити :digits цифр.',
    'digits_between' => 'Поле :attribute має містити від :min до :max цифр.',
    'dimensions' => 'Поле :attribute має некоректні розміри зображення.',
    'distinct' => 'Поле :attribute містить повторюване значення.',
    'doesnt_contain' => 'Поле :attribute не повинно містити значення: :values.',
    'doesnt_end_with' => 'Поле :attribute не повинно закінчуватися на: :values.',
    'doesnt_start_with' => 'Поле :attribute не повинно починатися з: :values.',
    'email' => 'Поле :attribute має бути коректною email-адресою.',
    'ends_with' => 'Поле :attribute має закінчуватися одним з: :values.',
    'enum' => 'Обране значення поля :attribute некоректне.',
    'exists' => 'Обране значення поля :attribute некоректне.',
    'extensions' => 'Поле :attribute має мати одне з розширень: :values.',
    'file' => 'Поле :attribute має бути файлом.',
    'filled' => 'Поле :attribute обовʼязкове для заповнення.',

    'gt' => [
        'array' => 'Поле :attribute має містити більше ніж :value елементів.',
        'file' => 'Поле :attribute має бути більше ніж :value кілобайт.',
        'numeric' => 'Поле :attribute має бути більше ніж :value.',
        'string' => 'Поле :attribute має містити більше ніж :value символів.',
    ],

    'gte' => [
        'array' => 'Поле :attribute має містити :value або більше елементів.',
        'file' => 'Поле :attribute має бути не менше ніж :value кілобайт.',
        'numeric' => 'Поле :attribute має бути не менше ніж :value.',
        'string' => 'Поле :attribute має містити не менше ніж :value символів.',
    ],

    'hex_color' => 'Поле :attribute має бути коректним HEX-кольором.',
    'image' => 'Поле :attribute має бути зображенням.',
    'in' => 'Обране значення поля :attribute некоректне.',
    'in_array' => 'Поле :attribute має існувати в :other.',
    'in_array_keys' => 'Поле :attribute має містити один з ключів: :values.',
    'integer' => 'Поле :attribute має бути цілим числом.',
    'ip' => 'Поле :attribute має бути коректною IP-адресою.',
    'ipv4' => 'Поле :attribute має бути коректною IPv4-адресою.',
    'ipv6' => 'Поле :attribute має бути коректною IPv6-адресою.',
    'json' => 'Поле :attribute має бути коректним JSON.',
    'list' => 'Поле :attribute має бути списком.',
    'lowercase' => 'Поле :attribute має бути в нижньому регістрі.',

    'lt' => [
        'array' => 'Поле :attribute має містити менше ніж :value елементів.',
        'file' => 'Поле :attribute має бути менше ніж :value кілобайт.',
        'numeric' => 'Поле :attribute має бути менше ніж :value.',
        'string' => 'Поле :attribute має містити менше ніж :value символів.',
    ],

    'lte' => [
        'array' => 'Поле :attribute не повинно містити більше ніж :value елементів.',
        'file' => 'Поле :attribute має бути не більше ніж :value кілобайт.',
        'numeric' => 'Поле :attribute має бути не більше ніж :value.',
        'string' => 'Поле :attribute має містити не більше ніж :value символів.',
    ],

    'mac_address' => 'Поле :attribute має бути коректною MAC-адресою.',

    'max' => [
        'array' => 'Поле :attribute не повинно містити більше ніж :max елементів.',
        'file' => 'Поле :attribute не повинно бути більше ніж :max кілобайт.',
        'numeric' => 'Поле :attribute не повинно бути більше ніж :max.',
        'string' => 'Поле :attribute не повинно містити більше ніж :max символів.',
    ],

    'min' => [
        'array' => 'Поле :attribute має містити щонайменше :min елементів.',
        'file' => 'Поле :attribute має бути щонайменше :min кілобайт.',
        'numeric' => 'Поле :attribute має бути не менше ніж :min.',
        'string' => 'Поле :attribute має містити щонайменше :min символів.',
    ],

    'numeric' => 'Поле :attribute має бути числом.',
    'regex' => 'Формат поля :attribute некоректний.',
    'required' => 'Поле :attribute є обовʼязковим.',
    'string' => 'Поле :attribute має бути рядком.',
    'timezone' => 'Поле :attribute має бути коректним часовим поясом.',
    'unique' => 'Значення поля :attribute вже використовується.',
    'url' => 'Поле :attribute має бути коректним URL.',

    'attributes' => [
        'email' => 'email',
        'first_name' => 'імʼя',
        'last_name' => 'прізвище',
        'password' => 'пароль',
    ],
];
