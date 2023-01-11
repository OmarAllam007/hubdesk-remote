<?php

return [
    'ZHCM_PAYROLL_TECH_URL' => 'http://alkfeccprd.alkifah.com:8000/sap/bc/srt/wsdl/flv_10002A101AD1/bndg_url/sap/bc/srt/rfc/sap/zhr_hubdesk_payroll/900/zhr_payroll_v2/zhr_payroll_v2?sap-client=900',
    'ZHCM_LETTERS_TECH_URL' => 'http://ALKFECCPRD.ALKIFAH.COM:8000/sap/bc/srt/wsdl/flv_10002A101AD1/bndg_url/sap/bc/srt/rfc/sap/zhr_letter_v3/900/zhr_letter_v2/zhr_letter_v2?sap-client=900',
    'SAP_USER' => 'HUBDESK_API',
    'SAP_PASS' => 'Kifah@2021',
    'SAP_USER_Q' => 'HUBDESK_API2',
    'SAP_PASS_Q' => 'Aa1234567',

    'ecc' => [
        'qas400' => [
            'url' => 'http://ALKFECCQAT.ALKIFAH.COM:8000/sap/bc/srt/wsdl/flv_10002A101AD1/bndg_url/sap/bc/srt/rfc/sap/zhubdesk_change_password/400/zhubdesk_change_password/zsap_user?sap-client=400',
            'user' => 'HUBDESK_API',
            'password' => 'J{FCV2V@',
        ],
        'qas500' => [
            'url' => 'http://ALKFECCQAT.ALKIFAH.COM:8000/sap/bc/srt/wsdl/flv_10002A101AD1/bndg_url/sap/bc/srt/rfc/sap/zhubdesk_change_password/500/zhubdesk_change_password/zsap_user?sap-client=500',
            'user' => 'HUBDESK_API',
            'password' => 'Z&W4CAR}',
        ],
        'qas920' => [//not correct
            'url' => 'http://alkfeccqat.alkifah.com:8000/sap/bc/srt/wsdl/flv_10002A101AD1/bndg_url/sap/bc/srt/rfc/sap/zhubdesk_change_password/920/zhubdesk_change_password/zsap_user?sap-client=920',
            'user' => 'HUBDESK_API2',
            'password' => 'Aa1234567',
        ],
        'prd' => [
            'url' => 'http://ALKFECCPRD.ALKIFAH.COM:8000/sap/bc/srt/wsdl/flv_10002A101AD1/bndg_url/sap/bc/srt/rfc/sap/zhubdesk_change_password/900/zhubdesk_change_password/zsap_user_prd?sap-client=900',
            'user' => 'HUBDESK_API',
            'password' => 'Kifah@2021',
        ],
        'dev' => [
            'url' => 'http://ALKFECCDEV.ALKIFAH.COM:8000/sap/bc/srt/wsdl/flv_10002A101AD1/bndg_url/sap/bc/srt/rfc/sap/zhubdesk_change_password/100/zhubdesk_change_password/zsap_user_ecc_dev?sap-client=100',
            'user' => 'HUBDESK_API',
            'password' => '{GGVHYH\\',
        ],
    ],
    's4hana' => [
        'dev' => [
            'url' => 'http://ALKFS4DEV.ALKIFAH.COM:8000/sap/bc/srt/wsdl/flv_10002A101AD1/bndg_url/sap/bc/srt/rfc/sap/zhubdesk_change_password/100/zhubdesk_change_password/zsap_s4_dev?sap-client=100',
            'user' => 'HUBDESK_API',
            'password' => ']DDNF$G~'
        ],
        'qas' => [
            'url' => 'http://ALKFS4QAS.ALKIFAH.COM:8000/sap/bc/srt/wsdl/flv_10002A101AD1/bndg_url/sap/bc/srt/rfc/sap/zhubdesk_change_password/220/zhubdesk_change_password/zsap_user_s4_qas?sap-client=220',
            'user' => 'HUBDESK_API',
            'password' => 'V8#QXG$A'
        ],
        'prd' => [
            'url' => 'http://alkfs4prd.alkifah.com:8022/sap/bc/srt/wsdl/flv_10002A101AD1/bndg_url/sap/bc/srt/rfc/sap/zhubdesk_change_password/300/zhubdesk_change_password/zsap_user_s4_prd?sap-client=300',
            'user' => 'HUBDESK_API',
            'password' => 'VP/8KX@2'
        ]
    ],
    'solman' => [
        'url' => 'http://alkfsolprd.alkifah.com:8000/sap/bc/srt/wsdl/flv_10002A101AD1/bndg_url/sap/bc/srt/rfc/sap/zhubdesk_change_password/100/zhubdesk_change_password/zsap_user_solman?sap-client=100',
        'user' => 'HUBDESK_API',
        'password' => '3==H{%~2'
    ]
];