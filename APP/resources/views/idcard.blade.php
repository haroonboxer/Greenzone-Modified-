<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="preload" as="image" href="{{ asset('idc-print/Card02.jpg') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Print Card</title>
    <style>
        body {
            width: 3.367in !important;
            min-width: 3.367in !important;
            margin: auto !important;
            padding: 0 !important;
            height: 2.11in !important;
        }

        .frontCover {
            background-image: url("{{ asset('idc-print/Card02.jpg') }}");
            min-width: 3.367in;
            max-width: 3.367in;
            height: 2.11in;
            direction: rtl;
            text-align: right;
            background-size: cover !important;
            background-position: center;
            background-repeat: no-repeat;
        }

        .rightTextGeneralDiv {
            padding: 1px !important;
            position: absolute;
            text-align: right !important;
            margin-left: 90px;
            width: 159px;
            height: 111px;
            margin-top: 76px;
            line-height: 14px !important;
            letter-spacing: -0.4px !important;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            font-size: 10px
        }

        .positionDynamicText {
            width: 206px;
            height: 111px;
            position: absolute;
            margin-top: 140px;
            margin-right: 22px;
            line-height: 1.26;
            overflow: hidden !important;
        }

        .rightTextGeneralDiv>p {

            padding: 2 !important;
            margin: 0 !important;
        }

        .cardPerimeterEn {
            position: absolute;
            margin-top: 176px;
            margin-right: 130px;
            width: 160px;
            height: 18px;
            font-size: 9px !important;
            font-family: "English Font", sans-serif;
            text-align: center !important;
            color: red;
        }


        .frontCoverDynamicTitle {
            font-family: "Bahij Titr",
                sans-serif;
            font-size: 11px !important;
        }

        .frontCoverMainTitle {
            font-family: "Bahij Titr",
                sans-serif;
            font-size: 10px !important;
            color: #000;
        }

        .rightText2GeneralDiv {
            width: 110px;
            height: 111px;
            position: absolute;
            margin-top: 56px;
            margin-right: 121px;
            line-height: 1.26;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .rightText2GeneralDiv>p {
            padding: 0 !important;
            margin: 0 !important;
        }

        .issueDatePersian {
            font-size: 14px !important;
        }

        .frontPhotoDiv {
            position: absolute;
            height: 20px;
            width: 20px;
            margin-right: 18px;
            margin-top: 82px;
            margin-bottom: 53px;
            border-radius: 40px;
        }

        .frontQRDiv {
            position: absolute;
            height: 43px;
            width: 43px;
            margin-right: 18px;
            margin-top: 138px;
            margin-bottom: 53px;
        }

        .frontPhoto {
            max-width: 100%;
            max-height: 100%;
            border-radius: 50%;
        }

        .idCardNo {
            text-align: left !important;
            margin-top: 155px;
            position: absolute;
            width: 95px;
            height: 14px;
            margin-right: 214px;
            font-size: 9px;
            font-weight: bolder;
        }

        .idCardNoText {
            font-family: "English Font", sans-serif;
            letter-spacing: -0.6px !important;
        }

        .cardColor {
            margin-top: 176px;
            position: absolute;
            width: 84px;
            height: 13px;
            margin-right: 226px;
            font-size: 9px;
        }

        .backCover {
            background-image: url("{{ asset('idc-print/Card01.jpg') }}");
            min-width: 3.367in;
            max-width: 3.367in;
            height: 2.11in;
            direction: ltr;
            text-align: left;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover !important;
        }

        .leftTextGeneralDiv {
            font-family: "English Font", sans-serif;
            position: absolute;
            margin-left: 10px;
            margin-right: 72px;
            text-align: left !important;
            width: 159px;
            height: 111px;
            margin-top: 76px;
            line-height: 14px !important;
            letter-spacing: -0.4px !important;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .leftTextGeneralDiv>p {
            padding: 0 !important;
            margin: 0 !important;
        }

        .cardPerimeterDr {
            position: absolute;
            margin-top: 176px;
            margin-left: 90px;
            width: 160px;
            height: 18px;
            font-size: 9px !important;
            font-family: "English Font", sans-serif;
            text-align: center !important;
            color: red;
        }

        .leftText2GeneralDiv {
            font-family: "English Font", sans-serif;
            position: absolute;
            margin-left: 182px;
            text-align: left !important;
            width: 100px;
            height: 111px;
            margin-top: 30px;
            line-height: 14px !important;
            letter-spacing: -0.4px !important;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .leftText2GeneralDiv>p {
            padding: 0 !important;
            margin: 0 !important;
        }


        .backPhotoDiv {
            position: absolute;
            margin-top: 82px;
            height: 38px;
            margin-left: 18px;
            width: 30px;
            border-radius: 40px;
        }

        .backQRDiv {
            position: absolute;
            margin-top: 138px;
            height: 43px;
            width: 43px;
            margin-left: 18px;

        }

        .backPhoto {
            max-width: 100%;
            max-height: 100%;
            border-radius: 50%;
        }

        .cardVersion {
            position: absolute;
            margin-left: 295px;
            height: 14px;
            width: 12px;
            margin-top: 56px;
            font-size: 8px;
        }

        .cardVersion>p {
            font-family: "English Font";
            padding: 0 !important;
            margin: 0 !important;
        }

        .frontBarcode {
            background-color: #fff !important;
            position: absolute;
            height: 18px;
            width: 160px;
            margin-right: 55px;
            margin-top: 170px;
        }

        .backBarcode {
            background-color: #fff !important;
            position: absolute;
            height: 32px;
            width: 260px;
            margin-left: 32px;
            margin-top: 151px;
        }

        .backBarcodeContent {
            width: 100% !important;
            height: 100% !important;
        }

        .backCoverMainTitle {
            font-size: 9px !important;
            margin-left: 0;
            padding-left: 0;
        }

        .textStyle {
            font-size: 9px !important;
            color: #000;
            margin-left: 0;
            padding-left: 0;
        }

        p {
            margin: 0;
            padding: 0;
        }

        .DynamicEnglishTexts {
            font-size: 11px !important;
            text-transform: capitalize;
        }

        .frontBarcodeContent {
            width: 100% !important;
            height: 100% !important;
        }

        .missingNote {
            font-family: "Bahij Titr",
                sans-serif;
            direction: rtl;
            width: 5in;
            text-align: center;
            position: absolute;
            margin-top: 172px;
            font-size: 11px;
            word-spacing: -1px;
        }

        .dariFrontCheck {
            position: absolute;
            margin-top: 55px;
            margin-left: 167px;
        }

        .dariBackCheck {
            position: absolute;
            margin-top: 55px;
            margin-left: 123px;
        }

        .engFrontCheck {
            position: absolute;
            margin-top: -148px;
            margin-left: 139px;
        }

        .engBackCheck {
            position: absolute;
            margin-top: -148px;
            margin-left: 194px;
        }

        .idCardNo {
            text-align: left !important;
            margin-top: 76px;
            position: absolute;
            width: 95px;
            height: 14px;
            margin-left: 14px;
            font-size: 9px;
            color: #235aa5;
        }

        .idCardNoText {
            font-family: "English Font", sans-serif;
            letter-spacing: -0.6px !important;
            line-height: 1.55em;
        }

        .idCardNoDr {
            text-align: right !important;
            margin-top: 76px;
            position: absolute;
            width: 95px;
            height: 14px;
            margin-left: 217px;
            font-size: 9px;
            color: #235aa5;
            direction: rtl;

        }

        .idCardNoTextDr {
            font-family: "Bahij Titr",
                sans-serif;
            letter-spacing: normal !important;
            line-height: 1.57em;
            font-weight: bold;

        }
    </style>
</head>

<body>
    <div class="frontCover">
        <div class="frontPhotoDiv">
            <img src="{{ asset($data->icon) }}" alt="Company Icon" style="height: 45px; width: 45px;">
        </div>
        <div class="idCardNo">
            <p class="idCardNoText">:Card Number</p>
            <p class="idCardNoText">:Company Name</p>
            <p class="idCardNoText">:Project Name</p>
            <p class="idCardNoText">:Weapon Type</p>
            <p class="idCardNoText">:Weapon Number</p>
            <p class="idCardNoText">:Issued Date</p>
            <p class="idCardNoText">:Expiry Date</p>
        </div>
        <div class='leftTextGeneralDiv'>
            <p class="backCoverMainTitle">{{ 'RMS-2025-000' . $data->id }}</p>
            <p class="backCoverMainTitle">{{ $data->companyNameEn }}</p>
            <p class="backCoverMainTitle">{{ $data->project_name_en }}</p>
            <p class="backCoverMainTitle">{{ $data->weapons }}</p>
            <p class="backCoverMainTitle">343425436</p>
            <p class="backCoverMainTitle">{{ $data->issued_date }}</p>
            <p class="backCoverMainTitle">{{ $data->expire_date }}</p>
        </div>
        <div class="cardPerimeterEn"><span>Card Limit: </span><span>{{ $data->card_perimeter_en }}</span></div>
        <img class="frontQRDiv" src="{{ $barcodeDataUri }}" />
    </div>

    <div>
        @if ($data->card_type == 'new')
            <img class="dariFrontCheck" style="height:13px; width:13px" src="{{ asset('img/checkmark.png') }}">
            <img class="engFrontCheck" style="height:13px; width:13px" src="{{ asset('img/checkmark.png') }}">
        @else
            <img class="dariBackCheck" style="height:13px; width:13px" src="{{ asset('img/checkmark.png') }}">
            <img class="engBackCheck" style="height:13px; width:13px" src="{{ asset('img/checkmark.png') }}">
        @endif
    </div>
    <div class="backCover">
        <div class="backPhotoDiv">
            <img src="{{ asset($data->icon) }}" alt="Company Icon" style="height: 45px; width: 45px;">
        </div>
        <div class="idCardNoDr">
            <p class="idCardNoTextDr">کارت شمیره:</p>
            <p class="idCardNoTextDr">د شرکت نوم:</p>
            <p class="idCardNoTextDr">د پروژه نوم:</p>
            <p class="idCardNoTextDr">د وسلی ډول:</p>
            <p class="idCardNoTextDr">د وسلی شمیره:</p>
            <p class="idCardNoTextDr">د صدور نیټه:</p>
            <p class="idCardNoTextDr">د پای نیټه:</p>
        </div>
        <div class='rightTextGeneralDiv'>
            <p class="frontCoverMainTitle">{{ 'RMS-2025-000' . $data->id }}</p>
            <p class="frontCoverMainTitle">{{ $data->companyNameDr }}</p>
            <p class="frontCoverMainTitle">{{ $data->project_name_dr }}</p>
            <p class="frontCoverMainTitle">{{ $data->weapons }}</p>
            <p class="frontCoverMainTitle">343425436</p>
            <p class="frontCoverMainTitle">{{ $data->issued_date }}</p>
            <p class="frontCoverMainTitle">{{ $data->expire_date }}</p>
        </div>
        <div class="cardPerimeterDr"><span>حدود کارت: </span><span>{{ $data->card_perimeter_dr }}</span></div>
        <img class="backQRDiv" src="{{ $barcodeDataUri }}" width="76" height="76" />
    </div>
</body>

</html>
