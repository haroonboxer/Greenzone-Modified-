<?php
return [
    'list' => "لیست همه افراد مفرور و لادرک (مطلوب)",
    'id' => "آی دی",
    'name' => "اسم متهم",
    'create' => 'ثبت افراد مفرور و لادرک (مطلوب)',
    'edit' => 'تجدید افراد مفرور و لادرک (مطلوب)',
    'view' => 'نمایش معلومات افراد مفرور و لادرک (مطلوب)',
    'place_of_event' => 'محل واقعه',
    'event_type' => 'نوع واقعه',
    'event_date' => 'تاریخ واقعه',
    'file_number' => 'نمبر دوسیه جستجوی جنایّی',
    'criminal_character_and_style' => 'کرکتر و اسلوب جرمی',
    'age' => 'عمر',
    'height' => 'قد',
    'body' => 'اندام',
    'face' => 'روی',
    'hair' => 'موی',
    'beard' => 'ریش',
    'eye' => 'چشم',
    'nose' => 'بینی',
    'ear' => 'گوش',
    'tooth' => 'دندان',
    'speech_accent' => 'لهچه گفتار',
    'clothes' => 'لباس',
    'type_of_criminal_execution' => 'نوع اجراء عملی جزایّی',
    'search_reason' => 'موظف و علت جستجو',
    'criminal_complete_address' => 'آدرس مکمل که امکان دریافت مجرم باشد',
    'fugitive_information' => 'ثبت شهرت متهم',
    'job_location' => 'محل وظیفه',
    'main_location' => 'محل سکونت',
    'sub_location' => 'محل بودوباش',
    'viewPartnerData' => 'نمایش معلومات شرکای جرمی',
    'createPartnerData' => 'ثبت شرکای جرمی',
    'editPartnerData' => 'تجدید معلومات شرکای جرمی',
    'case-partner' => '',
    'case-partner-create' => 'ثبت شریک جرمی',
    'case-partner-view' => 'نمایش معلومات شریک جرمی',
    'case-partner-edit' => 'تجدید معلومات شریک جرمی',
    'case-partner-list' => '',
    'case-witnesses-create' => 'ثبت شهرت اقارب',
    'case-witnesses-view' => 'نمایش معلومات شهرت اقارب',
    'case-witnesses-edit' => 'تجدید معلومات شهرت اقارب',
    'case-witnesses-type' => 'نوعیت قرابت',
    '' => '',
    '' => '',
    '' => '',
    '' => '',
    '' => '',
    '' => '',
    '' => '',

    'fugitiveTabs' => [
        [
            'id' => '1',
            'name' => 'شهرت مفرور (مطلوب)',
            'link' => 'view-fugitive',
            'tab_code' => 1,
            'icon' => 'fas fa-handshake-slash'
        ],
        [
            'id' => '2',
            'name' => 'شهرت شرکای جرمی',
            'link' => 'case-partner-view',
            'tab_code' => 2,
            'icon' => 'fas fa-users'
        ],
        [
            'id' => '3',
            'name' => 'شهرت اقارب',
            'link' => 'case-witnesses-view',
            'tab_code' => 3,
            'icon' => 'fas fa-truck-monster'
        ]
    ],



];