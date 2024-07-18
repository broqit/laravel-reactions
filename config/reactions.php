<?php

return [
    'types' => ['like', 'love', 'haha', 'wow', 'sad', 'angry'],
    'allowed_users' => ['user', 'guest'], // Можливі значення: 'user', 'guest', 'both'
    'max_reactions_per_user' => 1,
    'table_name' => 'reactions', // Ім'я таблиці з реакціями, на випадок якщо використовується іншим доповненням
    'user_model' => null, // Модель користувача за замовчуванням
];
