<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="preload" as="image" href="{{ asset('idc-print/licenseNew.jpg') }}">
    <meta charset="UTF-8">
    <title>ID Card</title>
    <style>
        @font-face {
            font-family: 'Bahij Titr';
            src: url('/fonts/BahijTitr-Bold.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        body {
            margin: 0;
            padding: 0;
        }

        .card {
            width: 1000px;
            height: 700px;
            background-image: url("{{ asset('idc-print/licenseNew.jpg') }}");
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            position: relative;
            padding: 60px;
            box-sizing: border-box;
            color: #000;
        }

        .row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .column {
            flex: 1;
            padding: 10px;
        }

        .barcode {
            position: absolute;
            bottom: 50px;
            right: 50px;
            border-radius: 5px;
        }

        .logo {
            /* margin-top: 25rem; */
            position: absolute;
            bottom: 45px;
            left: 50px;
        }

        .field {
            padding-top: 45px
        }

        .boss_row {
            text-align: right;
            margin-top: 50px;
        }

        .company_name {
            padding-top: 50px;
            margin-top: 50px;
        }

        .boss_photo {
            position: absolute;
            /* bottom: 50px; */
            right: 52px;
            top: 34%;
            border-radius: 2px;
            height: 136px;
            width: 125px;
        }

        .boss_name_dr {
            position: absolute;
            right: 55px;
            top: 56.5%;
        }

        .boss_name_en {
            position: absolute;
            right: 50px;
            top: 60%;

        }

        .assistant_name_dr {
            position: absolute;
            left: 55px;
            top: 56.5%;
        }

        .assistant_name_en {
            position: absolute;
            left: 50px;
            top: 59.8%;
        }

        .company_name_dr {
            font-family: Bahij Titr Bold;
            position: absolute;
            right: 60px;
            top: 66.5%;
        }

        .company_name_en {
            position: absolute;
            left: 60px;
            top: 66%;
        }

        .serial {
            position: absolute;
            right: 60px;
            top: 24%;
        }

        .start_date {
            position: absolute;
            left: 48%;
            top: 36.7%;
        }

        .end_date {
            position: absolute;
            left: 48%;
            top: 40.5%;
        }

        .assistant_photo {
            position: absolute;
            top: 34%;
            left: 54px;
            border-radius: 2px;
            height: 136px;
            width: 125px;
        }


        .license_text_container {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            margin: 20px 0;
            direction: ltr;
        }

        .company_name_en {
            text-align: justify;
            word-wrap: break-word;
            direction: ltr;
            width: 41%;
            font-weight: bold
        }

        .company_name_dr {
            text-align: justify;
            word-wrap: break-word;
            direction: rtl;
            width: 41%;
            font-weight: bold
        }

        .text_en {
            color: #063970;
        }

        .text_dr {
            color: #063970;
        }
    </style>
</head>

<body>
    <div class="card">
        {{-- Company Logo --}}
        @if ($data->company_icon)
            <div class="logo">
                <img src="{{ asset($data->company_icon) }}" alt="Company Icon" class="company-icon" height="84px"
                    style="border-radius: 5px;" width="84px" />
            </div>
        @endif

        {{-- license_type type  --}}
        @if ($data->license_type == 'new')
            <div
                style="background-color: #000; position: absolute;  left: 56.5%; top: 31.5%; border-radius: 10px; width: 10px; height: 10px; padding: 4px">
            </div>
        @elseif($data->license_type == 'extend')
            <div
                style="background-color: #000; position: absolute;  left: 46.2%; top: 31.5%; border-radius: 10px; width: 10px; height: 10px; padding: 4px">
            </div>
        @else
            <div
                style="background-color: #000; position: absolute;  left: 36.6%; top: 31.5%; border-radius: 10px; width: 10px; height: 10px; padding: 4px">
            </div>
        @endif

        {{-- date --}}
        <div class="start_date">{{ $data->issue_date }}</div>
        <div class="end_date">{{ $data->validity_date }}</div>


        {{-- Boss Info --}}
        <div class="row boss_row">
            <div class="boss_name_dr">ریس:
                ({{ '        ' . $data->boss_name_dr . ' ' . $data->boss_last_name_dr . '     ' }})
            </div>
            <div class="boss_name_en">President:
                ({{ '        ' . $data->boss_name_en . ' ' . $data->boss_last_name_en . '     ' }})</div>

            <div class="column">
                @if ($data->boss_photo)
                    <img src="{{ asset($data->boss_photo) }}" class="boss_photo" alt="Boss Photo">
                @endif
            </div>
        </div>

        {{-- Assistant Info --}}
        <div class="row">
            <div class="assistant_name_dr">مرستیال:
                ({{ '        ' . $data->assistant_name_dr . '        ' . $data->assistant_last_name_dr . '     ' }})
            </div>
            <div class="assistant_name_en">Vice President:
                ({{ '        ' . $data->assistant_name_en . '        ' . $data->assistant_last_name_en . '     ' }})
            </div>

            <div class="column">
                @if ($data->assistant_photo)
                    <img src="{{ asset($data->assistant_photo) }}" class="assistant_photo" alt="Assistant Photo">
                @endif
            </div>
        </div>

        {{-- Serial Number --}}
        <div class="serial">
            {{ $serialNumber }} :سریال نمبر
        </div>

        {{-- QR Code --}}
        <img src="{{ $barcodeDataUri }}" alt="QR Code" class="barcode" height="84px" width="84px">

        <div class="company_name_dr">
            (<span class="text_dr">{{ '  ' . $data->company_name_dr . '  ' }}</span>)
            د زرهی او ایمني وسایطو ترمیموونکی شرکت
            د دې جواز په لرلو سره اجازه لري چې زرهی او ایمني وسایط په قانوني بڼه ترمیم کړي.
        </div>


        <div class="company_name_en">The holder of this license (<span class="text_en">
                {{ $data->company_name_en }}</span>)
            is
            granted the
            legal right to repair armored security vehicles in a lawful and authorized manner.
        </div>
    </div>
</body>

</html>
