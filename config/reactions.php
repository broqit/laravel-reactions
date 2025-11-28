<?php

return [
    // Default reaction types (used when no model-specific types are defined)
    'types' => [
        ['type' => 'like', 'name' => 'Like', 'icon' => '👍'],
        ['type' => 'love', 'name' => 'Love', 'icon' => '❤️'],
        ['type' => 'haha', 'name' => 'Haha', 'icon' => '😂'],
        ['type' => 'wow', 'name' => 'Wow', 'icon' => '😮'],
        ['type' => 'sad', 'name' => 'Sad', 'icon' => '😢'],
        ['type' => 'angry', 'name' => 'Angry', 'icon' => '😡'],
    ],
    
    // Model-specific reaction types
    // You can define different reaction sets for different model classes
    // Example:
    // 'model_types' => [
    //     'App\Models\Post' => [
    //         ['type' => 'like', 'name' => 'Like', 'icon' => '👍'],
    //         ['type' => 'love', 'name' => 'Love', 'icon' => '❤️'],
    //     ],
    //     'App\Models\Comment' => [
    //         ['type' => 'thumbs_up', 'name' => 'Thumbs Up', 'icon' => '👍'],
    //         ['type' => 'thumbs_down', 'name' => 'Thumbs Down', 'icon' => '👎'],
    //     ],
    // ],
    'model_types' => [],
    
    'allowed_users' => ['user', 'guest'], // Possible values: 'user', 'guest', 'both'
    'max_reactions_per_user' => 1, // How many reactions can one user leave for 1 entry
    'table_name' => 'reactions', // The name of the reaction table, in case it is used by another plugin
    'user_model' => null, // The default user model. If is null will be used App/Models/User
    'removal_window_hours' => null, // The number of hours during which you can remove the reaction, null means no limit
];
