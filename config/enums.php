<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Units
    |--------------------------------------------------------------------------
    */

    'units' => ([
        'pg',   // picograms
        'ng',   // nanograms
        'ug',   // micrograms
        'mg',   // milligrams
        'g',    // grams
        'kg',   // kilograms
        'pL',   // picoliters
        'nL',   // nanoliters
        'uL',   // microliters
        'mL',   // milliliters
        'L',    // liters
        'kL',   // kiloliters
    ]),

    'default_mass_unit'     => 0, // default_units index

    'default_volume_unit'   => 1, // default_units index

    'default_units'         => [
        0 => 'pg',
        1 => 'pL'
    ],

    /*
    |--------------------------------------------------------------------------
    | unit type index
    |--------------------------------------------------------------------------
    */

    'unit_type_index' => ([
        0,      // picograms
        0,      // nanograms
        0,      // micrograms
        0,      // milligrams
        0,      // grams
        0,      // kilograms
        1,      // picoliters
        1,      // nanoliters
        1,      // microliters
        1,      // milliliters
        1,      // liters
        1,      // kiloliters
    ]),

    /*
    |--------------------------------------------------------------------------
    | unit base conversion rate
    |--------------------------------------------------------------------------
    */

    'unit_base_conversion_rate' => ([
        1,      // picograms
        1000,       // nanograms
        1000000,        // micrograms
        1000000000,         // milligrams
        1000000000000,      // grams
        1000000000000000,       // kilograms
        1,          // picoliters
        1000,           // nanoliters
        1000000,        // microliters
        1000000000,         // milliliters
        1000000000000,          // liters
        1000000000000000,           // kiloliters
    ]),

    /*
    |--------------------------------------------------------------------------
    | display unit types
    |--------------------------------------------------------------------------
    */

    'display_unit_types' => ([
        'mass',
        'volume',
    ]),

    /*
    |--------------------------------------------------------------------------
    | list of permissions
    |--------------------------------------------------------------------------
    */
    'customer_permissions' => ([
        'Manage Connected Customers Users',
        'Manage Connected Customers Pricing',
        'Manage Connected Customers Configuration',
        'Manage Connected Customers Formula'
    ]),

    /*
    |--------------------------------------------------------------------------
    | list of order statuses
    |--------------------------------------------------------------------------
    */
    'order_statuses' => ([
        'Order Submitted',
        'Order Received',
        'Specification In Review',
        'Awaiting Customer Specification Approval',
        'Spec Approved Pre Production',
        'In Production',
        'Qc Testing',
        'QA Release / Preparing for Shipment',
        'Shipped',
        'Delivered'
    ]),

    /*
    |--------------------------------------------------------------------------
    | list of payment statuses
    |--------------------------------------------------------------------------
    */
    'payment_statuses' => ([
        'Paid',
        'Unpaid',
        'Partial Payment'
    ]),

    /*
    |--------------------------------------------------------------------------
    | list of payment types
    |--------------------------------------------------------------------------
    */
    'payment_types' => ([
        'Purchase Order',
        'Cash',
        'Bank Deposit',
        'Credit Card'
    ])
];
