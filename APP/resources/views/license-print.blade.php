<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="preload" as="image" href="{{ asset('idc-print/gzLicense.jpg') }}">
    <meta charset="UTF-8">
    <title>GreenZone License</title>
    <style>
        @font-face {
            font-family: 'Bahij Titr';
            src: url('/fonts/BahijTitr-Bold.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        @page {
            size: A4 landscape;
            margin: 0;
        }

        /* html,
        body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
        }

        .card {
            width: 100%;
            height: 100%;
            background-image: url("{{ asset('idc-print/gzLicense.jpg') }}");
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            position: relative;
            color: #000;
        } */


        html,
        body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #eee;
        }

        .card {
            width: 1123px;
            height: 794px;
            background-image: url("{{ asset('idc-print/gzLicense.jpg') }}");
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            position: relative;
            color: #000;
        }

        .barcode {
            position: absolute;
            bottom: 235px;
            right: 73px;
            border-radius: 5px;
        }

        .driver_photo {
            position: absolute;
            top: 236.4px;
            right: 56px;
            border-radius: 2px;
            height: 173px;
            width: 162px;
        }

        .front_photo {
            position: absolute;
            top: 228.5px;
            right: -51px;
            border-radius: 2px;
            height: 150px;
            width: 801px;
            object-fit: contain;
        }

        .back_photo {
            position: absolute;
            top: 446px;
            left: 353px;
            border-radius: 2px;
            height: 122px;
            width: 850px;
            object-fit: contain;
        }

        .plate_photo {
            position: absolute;
            top: 387px;
            left: 657.8px;
            border-radius: 2px;
            height: 50px;
            width: 231px;
            object-fit: inherit;
        }

        .info {
            position: absolute;
            top: 278px;
            left: 390px;
            font-family: 'Bahij Zar';
            font-size: 17px;
            line-height: 1.5;
            color: #000;
            direction: rtl;
            line-height: 25px;
            /* ↓ tighter lines */
        }

        .info-bullet {
            display: inline-block;
            width: 10px;
            height: 10px;
            background-color: #000000a3;
            border-radius: 50%;
            margin-left: 3px;
            margin-top: 8px;
            position: absolute;
            left: 250px;
        }

        .sn {
            position: absolute;
            top: 170px;
            left: 838px;
            font-size: 18px;
            color: #000;
        }

        .issue_date {
            position: absolute;
            top: 200px;
            right: 55px;
            font-size: 18px;
            color: #000;
        }

        .expire_date {
            position: absolute;
            top: 200px;
            right: 220px;
            font-size: 18px;
            color: #000;
        }
    </style>
</head>

<body>
    <img src="{{ asset('idc-print/gzLicense.jpg') }}" style="display:none;" alt="preload">
    <div class="card">

        {{-- license_type type  --}}
        @if ($data->license_type == 'new')
            <div
                style="background-color: #000; position: absolute;  left: 529.7px; top: 231.9px; border-radius: 15px; width: 21.3px; height: 21.5px; padding: 4px">
            </div>
        @elseif($data->license_type == 'extend')
            <div
                style="background-color: #000; position: absolute; left: 303.9px; top: 232px; border-radius: 15px; width: 21px; height: 21.4px; padding: 4px;">
            </div>
        @else
            <div
                style="background-color: #000; position: absolute;  left: 94.4px; top: 232px; border-radius: 15px; width: 21px; height: 21.4px; padding: 4px">
            </div>
        @endif

        {{-- Bar Code --}}
        <img src="{{ $barcodeDataUri }}" alt="QR Code" class="barcode" height="120px" width="128px">

        {{-- Boss Photo --}}
        <div class="column">
            @if ($data->driver_photo)
                <img src="{{ asset($data->driver_photo) }}" class="driver_photo" alt="Boss Photo">
            @endif
        </div>
        {{-- Front Photo --}}
        <div class="column">
            @if ($data->front_photo)
                <img src="{{ asset($data->front_photo) }}" class="front_photo" alt="Front Photo">
            @endif
        </div>
        {{-- Back Photo --}}
        <div class="column">
            @if ($data->back_photo)
                <img src="{{ asset($data->back_photo) }}" class="back_photo" alt="Front Photo">
            @endif
        </div>
        {{-- Plate Photo --}}
        <div class="column">
            @if ($data->plate_photo)
                <img src="{{ asset($data->plate_photo) }}" class="plate_photo" alt="Front Photo">
            @endif
        </div>

        {{-- Infos --}}
        <div class="info">
            <div><span
                    style="display:inline-block; transform:scale(1.8); vertical-align:middle; margin-left:6px; color:black;">•</span>
                <strong>نوم:</strong> {{ $data->driver_name }}
            </div>
            <div><span
                    style="display:inline-block; transform:scale(1.8); vertical-align:middle; margin-left:6px; color:black;">•</span>
                <strong>د پلار نوم:</strong> {{ $data->f_name }}
            </div>
            <div><span
                    style="display:inline-block; transform:scale(1.8); vertical-align:middle; margin-left:6px; color:black;">•</span>
                <strong>د نیکه نوم:</strong> {{ $data->g_f_name }}
            </div>
            <div><span
                    style="display:inline-block; transform:scale(1.8); vertical-align:middle; margin-left:6px; color:black;">•</span>
                <strong>ولایت:</strong> {{ $data->province_name }}
            </div>
            <div><span
                    style="display:inline-block; transform:scale(1.8); vertical-align:middle; margin-left:6px; color:black;">•</span>
                <strong>د تذکرې شمیره:</strong> {{ $data->nic }}
            </div>
            <div><span
                    style="display:inline-block; transform:scale(1.8); vertical-align:middle; margin-left:6px; color:black;">•</span>
                <strong>د اړیکي شمیره:</strong> {{ $data->phone }}
            </div>
            <div><span
                    style="display:inline-block; transform:scale(1.8); vertical-align:middle; margin-left:6px; color:black;">•</span>
                <strong>د واسطې نوعیت:</strong> {{ $data->vehicle_type }}
            </div>
            <div><span
                    style="display:inline-block; transform:scale(1.8); vertical-align:middle; margin-left:6px; color:black;">•</span>
                <strong>د واسطې رنګ:</strong> {{ $data->vehicle_color }}
            </div>
            <div><span
                    style="display:inline-block; transform:scale(1.8); vertical-align:middle; margin-left:6px; color:black;">•</span>
                <strong>نمبر پلیټ:</strong>
                @php
                    $plate = $data->vehicle_platte_no;
                    $hasMinus = Str::contains($plate, '-');
                    $cleaned = str_replace('-', '', $plate);
                    $parts = preg_split('/\s+/', trim($cleaned));

                    if (count($parts) >= 2) {
                        $plateFormatted = $hasMinus ? "{$parts[1]} {$parts[0]}-" : "{$parts[1]} {$parts[0]}";
                    } else {
                        $plateFormatted = $plate;
                    }
                @endphp
                {{ $plateFormatted }}
            </div>
            <div><span
                    style="display:inline-block; transform:scale(1.8); vertical-align:middle; margin-left:6px; color:black;">•</span>
                <strong>د شاسی شمیره:</strong> {{ $data->vehicle_engine_no }}
            </div>
            <div><span
                    style="display:inline-block; transform:scale(1.8); vertical-align:middle; margin-left:6px; color:black;">•</span>
                <strong>غوښتونکې مرجع:</strong> {{ $data->vehicle_source }}
            </div>
        </div>

        <div class="sn">
            {{ $data->sn }} <strong>:د کارټ شمیره</strong>
        </div>
        <div class="issue_date">
            <span style="font-weight:normal">{{ $data->issue_date }}</span> <strong>:د صدور نیټه</strong>
        </div>
        <div class="expire_date">
            <span style="font-weight:normal">{{ $data->expire_date }}</span> <strong>:د ختم نیټه</strong>
        </div>
    </div>
</body>

</html>
