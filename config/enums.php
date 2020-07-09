<?php

return [
    'emailActPurpose' => [
        'ACTIVATION' => 1,
        'FORGOT_PASS' => 2,
    ],

    'applicantStatus' => [
        'PENDING_ACTIVATION' => -1,
        'ACTIVE' => 1,
        'INACTIVE' => 0,
    ],

    'applicationStatus' => [
        'DITERIMA' => 1,
        'PREQUALIFICATION_FAILED' => -2,
        'REJECTED' => -1,
        'APPROVED' => 8,
    ],

    'applicantSidebarLinks' => [
        'APP_STATUS' => 0,
        'FORM' => 1,
        'FAQ' => 2,
        'CONTACT_US' => 3,
        'ACTION_HIST' => 4,
    ],

    'employeeType' => [
        'LOCAL' => 1,
        'FOREIGNER' => 2,
        'MGMT' => 3,
        'TECH' => 4,
        'SUPER' => 5,
        'OTHERS' => 6,
    ],

    'contactType' => [
        'WEB' => [1, 'NCIA Website'],
        'SOCIAL' => [2, 'NCIA Social Media'],
        'STAFF' => [3, 'NCIA Staff'],
        'GOVERN' => [4, 'Government/State Agencies'],
        'INDUSTRY' => [5, 'Industry Contact'],
        'OTHERS' => [6, 'OTHERS'],
    ],

    'qualification'=> [
        'DEG' => 1,
        'DPLO' => 2,
        'CERT' => 3,
        'SCHLV' => 4,
    ],

    'fileContentType'=> [
        'LOGO' => 1,
        'ORG' => 2,
        'CERT' => 3,
        'FIN' => 4,
        'MEMO' => 5,
        'INCO' => 6,
        'F24' => 7,
        'F49' => 8,
        'F13' => 9,
        'F32A' => 10,
        'F20' => 11,
        'RELV' => 12,
        'TDM' => 13,
        'CDR' => 14,
    ],

    'role' => [
        'SECL1' => 1,
        'SECL2' => 2,
        'SECL3' => 3,
        'COMM'  => 4,
        'LEGAL' => 5,
        'SO'    => 6,
        'APPR'  => 7,
    ]
];