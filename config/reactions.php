<?php

return [
    'types' => [
        ['type' => 'like', 'name' => 'Like', 'icon' => '👍'],
        ['type' => 'love', 'name' => 'Love', 'icon' => '❤️'],
        ['type' => 'haha', 'name' => 'Haha', 'icon' => '😂'],
        ['type' => 'wow', 'name' => 'Wow', 'icon' => '😮'],
        ['type' => 'sad', 'name' => 'Sad', 'icon' => '😢'],
        ['type' => 'angry', 'name' => 'Angry', 'icon' => '😡'],
    ],
    'allowed_users' => ['user', 'guest'], // Можливі значення: 'user', 'guest', 'both'
    'max_reactions_per_user' => 1, // Скільки реакцій один користувач може лишити для 1 запису
    'table_name' => 'reactions', // Ім'я таблиці з реакціями, на випадок якщо використовується іншим доповненням
    'user_model' => null, // Модель користувача за замовчуванням - null
    'removal_window_hours' => null, // Кількість годин протягом яких можна зняти реакцію, null означає без обмежень
];
