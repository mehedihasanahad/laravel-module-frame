<?php
// Any file path must be in public path
return [
    'adminPanel' => [
        'sidebar' => [
            'title' => 'Laravel Module Admin Panel',
            'title_img_path' => 'assets/images/adminLTE/AdminLTELogo.png',
            'title_img_alt' => 'image',
            'profile_default_img_path' => 'assets/profile.png',
            'profile_default_img_path_alt' => 'profile image',
            'background_color' => 'white',
            'text_color' => 'black',
            'selected_menu_color' => '#B2B2B2',
            'menu_icon_color' => 'rgba(0, 0, 0, 0.6)',
            'selected_menu_dropdown_color' => '#E5E5E5'
        ],
        'layout' => [
            'background_color' => '#F1F5F9',
            'text_color' => 'black'
        ]
    ],
    'profile' => [
        'title' => 'Profile',
        'profile_default_img_path' => 'assets/profile.png',
        'profile_default_img_path_alt' => 'profile image',
    ],
    'UserApprovalCheckerExceptRoutes' => [
        '/login',
        '/logout',
        '/registration',
        '/register',
        '/forget-password',
        '/profile',
        'signup/identity-verify'
    ],
    'wizard' => [
        'current' => [
            'background' => '#027DB4',
            'color' => '#fff',
            'cursor' => 'default',
        ],
        'done' => [
            'background' => '#027DB4',
            'color' => '#fff',
        ],
        'disabled' => [
            'background' => '#F2F2F2',
            'color' => '#028FCA',
            'cursor' => 'default'
        ],
    ],
    'batchProcess' => [
        'headerColor' => '#f1f1f1',
        'bodyColor' => '#ffff',
        'textColor' => '#000000'
    ],
    'viewApplicationInfos' => [
        'headerColor' => '#f1f1f1',
        'bodyColor' => '#ffff',
        'textColor' => '#000000'
    ],
    'applicationListFilterBy' => 'organization' # : Valid values: "user" or "organization". [  ]
];
