<?php

return [
    'custom' => [
        'name' => [
            'required' => 'Please provide a name.',
            'max'      => 'Name must not exceed :max characters.',
        ],
        'email' => [
            'required' => 'An email address is required.',
            'email'    => 'Please enter a valid email address.',
            'unique'   => 'This email is already registered.',
            'max'      => 'Email must not exceed :max characters.',
        ],
        'password' => [
            'required' => 'Please enter a password.',
            'min'      => 'Password must be at least :min characters.',
            'confirmed' => 'Password confirmation does not match.',
        ],
        'role' => [
            'required' => 'Please select a role.',
        ],
        'title' => [
            'required' => 'An event title is required.',
            'max'      => 'Title must not exceed :max characters.',
        ],
        'description' => [
            'required' => 'Please provide an event description.',
        ],
        'location' => [
            'required' => 'Please provide an event location.',
            'max'      => 'Location must not exceed :max characters.',
        ],
        'start_date' => [
            'required' => 'Please select a start date.',
            'date'     => 'Start date must be a valid date.',
            'after'    => 'Start date must be in the future.',
        ],
        'end_date' => [
            'required' => 'Please select an end date.',
            'date'     => 'End date must be a valid date.',
            'after'    => 'End date must be after the start date.',
        ],
        'capacity' => [
            'integer' => 'Capacity must be a whole number.',
            'min'     => 'Capacity must be at least :min.',
        ],
        'status' => [
            'required' => 'Please select an event status.',
        ],
        'current_password' => [
            'required' => 'Please enter your current password.',
            'current_password' => 'The current password is incorrect.',
        ],
    ],

    'attributes' => [
        'name'              => 'name',
        'email'             => 'email address',
        'password'          => 'password',
        'role'              => 'role',
        'title'             => 'title',
        'description'       => 'description',
        'location'          => 'location',
        'start_date'        => 'start date',
        'end_date'          => 'end date',
        'capacity'          => 'capacity',
        'status'            => 'status',
        'current_password'  => 'current password',
        'token'             => 'reset token',
    ],
];
