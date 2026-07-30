<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Pagination Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used by the paginator library to build
    | the simple pagination links. You are free to change them to anything
    | you want to customize your views to better match your application.
    |
    */

    'home_number' => 'نمبر خانه',
    'main_lane_number' => 'کوچه عمومی',
    'sub_lane_number' => 'کوچه فرعی',
    'home_type' => 'نوعیت خانه',
    'gps_longitude' => 'عرض البلد',
    'gps_latitude' => 'طول البلد',
    'track_point' => 'نقطه نیرنگی',
    'add' => 'علاوه کردن فورم جدید',
    'createForm' => 'ثبت فورم جدید',
    'addHome' => 'ثبت فورم خانه',
    'houseInformation' => 'درج مشخصات خانه',
    '' => '',
    '' => '',
    '' => '',
    '' => '',
    '' => '',
    '' => '',
    '' => '',

    'relationFamily' => [
        [
            'id' => '1',
            'name' => 'پدر'
        ],
        [
            'id' => '2',
            'name' => 'پدر کلان'
        ],
        [
            'id' => '3',
            'name' => 'مادر کلان'
        ],
        [
            'id' => '4',
            'name' => 'همسر/خانم'
        ],
        [
            'id' => '5',
            'name' => 'دختر'
        ],
        [
            'id' => '6',
            'name' => 'پسر'
        ]
    ],

    'maritalStatus' => [
        [
            'id' => '1',
            'name' => 'مجرد'
        ],
        [
            'id' => '2',
            'name' => 'متاهل'
        ],
        [
            'id' => '3',
            'name' => 'بیوه'
        ],
        [
            'id' => '4',
            'name' => 'نامزد'
        ]
    ],

    'typeOfGuarantor' => [
        [
            'id' => '1',
            'name' => 'دکوڅی نماینده / نماینده کوچه'
        ],
        [
            'id' => '2',
            'name' => 'د کور مشر / ریّس خانواده'
        ],
        [
            'id' => '3',
            'name' => 'وکیل گذر'
        ]
    ],

    'homeFormStep' => [
        [
            'id' => '1',
            'name' => 'شهرت ریّس فامیل',
            'link' => 'view-home-head-details',
            'tab_code' => 1,
            'icon' => 'fas fa-user-secret'
        ],
        [
            'id' => '2',
            'name' => 'شهرت اعضاء خانواده',
            'link' => 'view-home-member',
            'tab_code' => 2,
            'icon' => 'fas fa-users'
        ],
        [
            'id' => '3',
            'name' => 'معلومات وسایط',
            'link' => 'view-vehicles',
            'tab_code' => 3,
            'icon' => 'fas fa-truck-monster'
        ],
        [
            'id' => '4',
            'name' => 'ضمانت کننده ها',
            'link' => 'view-guarantor',
            'tab_code' => 4,
            'icon' => 'fas fa-receipt'
        ],
        [
            'id' => '5',
            'name' => 'معلومات مالک خانه در صورتیکه خانه کرایی یا گروی باشد',
            'link' => 'view-home-owner',
            'tab_code' => 5,
            'icon' => 'fas fa-user-tie'
        ],
        [
            'id' => '6',
            'name' => 'درخواست نقل مکان',
            'link' => 'view-home-transfer',
            'tab_code' => 6,
            'icon' => 'fas fa-arrows-alt'
        ]
    ],
    'name' => 'اسم',
    'nick_name' => 'نام مستعار',
    'father_name' => 'نام پدر',
    'grand_father_name' => 'نام پدر کلان',
    'identity_card_number' => 'نمبر تذکره',
    'mail_location' => 'سکونت اصلی',
    'sub_location' => 'سکونت فعلی',
    'birth_date' => 'تاریخ تولد',
    'birth_place' => 'محل تولد',
    'ethnicity' => 'قومیت',
    'religion' => 'مذهب',
    'language' => 'زبان مادری',
    'electricity_meter_number' => 'نمبر میتر برق',
    'number_of_members_male' => 'تعداد اعضای فامیل پسر',
    'number_of_members_female' => 'تعداد اعضای فامیل دختر',
    'existince_in_political_party' => 'عضویت در احزاب',
    'phone_number1' => 'شماره تماس',
    'phone_number2' => 'شماره واتس آپ',
    'current_job' => 'وظیفه فعلی',
    'current_job_place' => 'موقعیت وظیفه فعلی',
    'current_job_date' => 'تاریخ شروع وظیفه فعلی',
    'previous_job_place' => 'وظیفه قبلی',
    'education_degree' => 'درجه تحصیلی',
    'education_refrence' => 'مرجع تحصیلی',
    'graduation_date' => 'سال فراغت',
    'type_of_house' => 'نوعیت خانه',
    'start_date_of_contraction' => 'تاریخ شروع قرار داد',
    'end_date_of_contraction' => 'تاریخ ختم قرار داد',
    'photo' => 'عکس ریّس فامیل',
    'sherid_employee' => 'کارمند شرید',
    'matter_of_residantial_control' => 'آمر کنترول منطقوی',
    'criminal_manager' => 'مدیر جنایّی',
    'headOfHomeInformation' => 'شهرت ریّس فامیل',
    'viewHome' => 'نمایش معلومات خانه',
    'viewHeadOfHomes' => 'نمایش معلومات ریّس خانه موجود و انتقال شده',
    'status' => 'حالت',
    'viewHomeMemeber' => 'شهرت اعضاء خانواده',
    'gender' => 'جنسیت',
    'relation_with_family_head' => 'رابطه با ریّس خانواده',
    'job' => 'وظیفه',
    'job_place' => 'موقعیت وظیفه',
    'phone_number' => 'شماره تماس',
    'finger' => 'نشان انگشت',
    'familyMemeberImage' => 'عکس',
    'addFamilyMemeber' => 'ثبت شهرت اعضاء خانواده',
    'party_or_arrangement' => 'حزب یا تنظیم',
    'doubt' => 'مشکوکیت',
    'addMoreFamilyMember' => 'اظافه نمودن عضو خانواده',
    'editFamilyMember' => 'تجدید معلومات عضو خانواده',
    'viewFamilyMember' => 'نمایش معلومات عضو خانواده',

    'car_type' => 'نوعیت موتر',
    'plate_number' => 'نمبر پلیت',
    'engine_number' => 'نمبر انجن',
    'shasi_number' => 'شاسی نمبر',
    'car_color' => 'رنگ موتر',
    'car_streening' => 'نوعیت اشترنگ',
    'addMoreVehicles' => 'اظافه نمودن وسایط بیشتر',
    'viewVehicles' => 'نمایش معلومات وسایط',
    'editVehicles' => 'تجدید معلومات وسایط',
    'gaurantorView' => 'نمایش معلومات ضمانت کننده',
    'gaurantorAdd' => 'ثبت ضمانت کننده',
    'gaurantorEdit' => 'تجدید معلومات ضمانت کننده',
    'gaurantorAddMore' => 'اظافه نمودن ضمانت کننده بیشتر',
    'type_of_guarantor' => 'نوعیت ضمانت کننده',
    'homeOwnerAdd' => 'ثبت معلومات مالک خانه',
    'homeOwnerEdit' => 'تجدید معلومات مالک خانه',
    'homeOwnerView' => 'نمایش معلومات مالک خانه',
    'previous_job' => 'وظیفه قبلی',
    'doubtOwner' => 'مشکوکیت/عدم مشکوکیت گرویی/کرایه دار',
    'current_location' => 'سکونت فعلی',
    'signature' => 'امضاء الکترونیکی',
    'system' => 'سیستم',
    'searchForms' => 'جستجوی فورم های کنترول منطقوی',
    'homeStoriesInformation' => 'نمایش معلومات نقل مکان در خانه',
    'homeTransferAdd' => 'ثبت نقل مکان',
    'vehicles' => 'وسایط نقلیه',
    'family_member' => 'تعداد اعضاء فامیل',
    'transfer' => 'نقل مکان از',
    'transfer_to' => 'به ولایت',
    'vehicle_type' => 'نوع',
    'vehicle_plate_number' => 'پلیت نمبر',
    'transfer_reason' => 'علت نقل مکان نمودن',
    'wakil_gozar' => 'شهرت وکیل گذر',
    'gozar_number' => 'نمبر گذر',
    'wakil_gozar_confirm' => 'تصدیق وکیل گذر در باره شخص',
    'electricity_bill' => 'باقی داری بل برق',
    'shared_employee_info' => 'معلومات کارمند شرید',
    'shared_number' => 'نمبر شرید',
    'signature' => 'امضاء',
    'howza_title' => 'به آمریت محترم حوزه/ولسوالی',
    'title_one' => 'اسلام علیکم و رحمته الله و برکاته',
    'title_two' => 'به اساس کتاب ثبت واقعات جرمی',
    'title_three' => 'اسامی',
    'title_four' => 'فرزند',
    'title_five' => 'تا به حال نزد این اداره کدام مسّولیت جرمی ندارد از طرف ما تصدیق میباشد!',
    'title_six' => 'حوزه مربوطه در رابطه به شخص متذکره همکاری لازیم نماید!',
    'acu_amer' => 'امضاء و مهر آمر کنترول منطقوی و حوزه مربوطه',
    'form_confirm' => 'ترتیب فورم هذا درست و صحت است',
    'zone' => 'زون',
    'gozar' => 'گذر',
    'shared' => 'شرید',
    '' => '',
    '' => '',
    '' => '',
    '' => '',
    '' => '',
    '' => '',
    '' => '',


];