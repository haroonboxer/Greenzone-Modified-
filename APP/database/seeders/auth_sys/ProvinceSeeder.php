<?php

namespace Database\Seeders\auth_sys;

use App\models\Provinces;
use Illuminate\Database\Seeder;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        //
        $provinces = [
            [
                "id" => 1,
                "code" => 1,
                "name_en" => "KABUL",
                "name_dr" => "کابل",
                "name_pa" => "کابل",
                "zone" => 101,
                "lat" => 34.5184042259092,
                "lang" => 69.201296853017,
                "ab" => "KBL",
                "color" => "85C0F8",
                "mgrs" => "42SWD1847619656"
            ],
            [
                "id" => 2,
                "code" => 2,
                "name_en" => "KAPISA",
                "name_dr" => "کاپيسا",
                "name_pa" => "کاپيسا",
                "zone" => 202,
                "lat" => 35.0451066148951,
                "lang" => 69.3314056615201,
                "ab" => "KPA",
                "color" => "FE6264",
                "mgrs" => "42SWD3022578095"
            ],
            [
                "id" => 3,
                "code" => 3,
                "name_en" => "PARWAN",
                "name_dr" => "پروان",
                "name_pa" => "پروان",
                "zone" => 202,
                "lat" => 35.004041552873,
                "lang" => 69.1689032925095,
                "ab" => "PAR",
                "color" => "8080C0",
                "mgrs" => "42SWD1541273504"
            ],
            [
                "id" => 4,
                "code" => 4,
                "name_en" => "WARDAK",
                "name_dr" => "وردک",
                "name_pa" => "وردک",
                "zone" => 303,
                "lat" => 34.3963153321221,
                "lang" => 68.8655982034757,
                "ab" => "WAR",
                "color" => "E7D3E3",
                "mgrs" => "42SVD8764606108"
            ],
            [
                "id" => 5,
                "code" => 5,
                "name_en" => "LOGAR",
                "name_dr" => "لوگر",
                "name_pa" => "لوگر",
                "zone" => 303,
                "lat" => 33.9921477742856,
                "lang" => 69.0276052508786,
                "ab" => "LOG",
                "color" => "7FB500",
                "mgrs" => "42SWC0255061286"
            ],
            [
                "id" => 6,
                "code" => 6,
                "name_en" => "NANGARHAR",
                "name_dr" => "ننگرهار",
                "name_pa" => "ننگرهار",
                "zone" => 202,
                "lat" => 34.4220126530481,
                "lang" => 70.4500198890865,
                "ab" => "NAN",
                "color" => "511A6A",
                "mgrs" => "42SXD3324609903"
            ],
            [
                "id" => 7,
                "code" => 7,
                "name_en" => "LAGHMAN",
                "name_dr" => "لغمان",
                "name_pa" => "لغمان",
                "zone" => 202,
                "lat" => 34.6631994421109,
                "lang" => 70.2090416827914,
                "ab" => "LAG",
                "color" => "5E5E62",
                "mgrs" => "42SXD1078036359"
            ],
            [
                "id" => 8,
                "code" => 8,
                "name_en" => "PANJSHER",
                "name_dr" => "پنجشير",
                "name_pa" => "پنجشير",
                "zone" => 202,
                "lat" => 35.2709403744565,
                "lang" => 69.4785537165976,
                "ab" => "PAN",
                "color" => "CFE715",
                "mgrs" => "42SWE4352503195"
            ],
            [
                "id" => 9,
                "code" => 9,
                "name_en" => "BAGHLAN",
                "name_dr" => "بغلان",
                "name_pa" => "بغلان",
                "zone" => 808,
                "lat" => 35.9447739257189,
                "lang" => 68.7056462436838,
                "ab" => "BAG",
                "color" => "FFBB2C",
                "mgrs" => "42SVE7345277863"
            ],
            [
                "id" => 10,
                "code" => 10,
                "name_en" => "BAMYAN",
                "name_dr" => "باميان",
                "name_pa" => "باميان",
                "zone" => 303,
                "lat" => 34.8183782561839,
                "lang" => 67.8250519845061,
                "ab" => "BAM",
                "color" => "FFFF81",
                "mgrs" => "42SUD9254553531"
            ],
            [
                "id" => 11,
                "code" => 11,
                "name_en" => "GHAZNI",
                "name_dr" => "غزني",
                "name_pa" => "غزني",
                "zone" => 303,
                "lat" => 33.5506601269994,
                "lang" => 68.4211631131052,
                "ab" => "GHZ",
                "color" => 366300,
                "mgrs" => "42SVC4626512486"
            ],
            [
                "id" => 12,
                "code" => 12,
                "name_en" => "PAKTIKA",
                "name_dr" => "پکتيکا",
                "name_pa" => "پکتيکا",
                "zone" => 303,
                "lat" => 33.1584621556685,
                "lang" => 68.7931307334239,
                "ab" => "PKK",
                "color" => "FFE7EE",
                "mgrs" => "42SVB8071068873"
            ],
            [
                "id" => 13,
                "code" => 13,
                "name_en" => "PAKTYA",
                "name_dr" => "پکتيا",
                "name_pa" => "پکتيا",
                "zone" => 303,
                "lat" => 33.5944336513976,
                "lang" => 69.2315456946516,
                "ab" => "PKT",
                "color" => "FFE800",
                "mgrs" => "42SWC2148417213"
            ],
            [
                "id" => 14,
                "code" => 14,
                "name_en" => "KHOST",
                "name_dr" => "خوست",
                "name_pa" => "خوست",
                "zone" => 303,
                "lat" => 33.3397972358171,
                "lang" => 69.9248894825411,
                "ab" => "KHO",
                "color" => "FFE8FF",
                "mgrs" => "42SWB8606889340"
            ],
            [
                "id" => 15,
                "code" => 15,
                "name_en" => "KUNARHA",
                "name_dr" => "کنرها",
                "name_pa" => "کنرها",
                "zone" => 202,
                "lat" => 34.8667314155401,
                "lang" => 71.1498493834973,
                "ab" => "KUR",
                "color" => "DE0062",
                "mgrs" => "42SXD9651160372"
            ],
            [
                "id" => 16,
                "code" => 16,
                "name_en" => "NOORISTAN",
                "name_dr" => "نورستان",
                "name_pa" => "نورستان",
                "zone" => 202,
                "lat" => 35.6722436064465,
                "lang" => 71.3411278739911,
                "ab" => "NOR",
                "color" => "FF7F00",
                "mgrs" => "42SYE1188950122"
            ],
            [
                "id" => 17,
                "code" => 17,
                "name_en" => "BADAKHSHAN",
                "name_dr" => "بدخشان",
                "name_pa" => "بدخشان",
                "zone" => 808,
                "lat" => 37.113583947131,
                "lang" => 70.5812135264683,
                "ab" => "BDS",
                "color" => "11B1FF",
                "mgrs" => "42SXG4048608643"
            ],
            [
                "id" => 18,
                "code" => 18,
                "name_en" => "TAKHAR",
                "name_dr" => "تخار",
                "name_pa" => "تخار",
                "zone" => 808,
                "lat" => 36.7359007089061,
                "lang" => 69.5409441054468,
                "ab" => "TAK",
                "color" => 808080,
                "mgrs" => "42SWF4829765712"
            ],
            [
                "id" => 19,
                "code" => 19,
                "name_en" => "KUNDUZ",
                "name_dr" => "کندوز",
                "name_pa" => "کندوز",
                "zone" => 808,
                "lat" => 36.7280001724505,
                "lang" => 68.862779603877,
                "ab" => "KND",
                "color" => "FF8041",
                "mgrs" => "42SVF8774764708"
            ],
            [
                "id" => 20,
                "code" => 20,
                "name_en" => "SAMANGAN",
                "name_dr" => "سمنگان",
                "name_pa" => "سمنگان",
                "zone" => 707,
                "lat" => 36.2692660325555,
                "lang" => 68.0229872306625,
                "ab" => "SAM",
                "color" => "FBACA3",
                "mgrs" => "42SVF1224314257"
            ],
            [
                "id" => 21,
                "code" => 21,
                "name_en" => "BALKH",
                "name_dr" => "بلخ",
                "name_pa" => "بلخ",
                "zone" => 707,
                "lat" => 36.7100839259961,
                "lang" => 67.1147882843912,
                "ab" => "BAL",
                "color" => "437F80",
                "mgrs" => "42SUF3161864368"
            ],
            [
                "id" => 22,
                "code" => 22,
                "name_en" => "SAR-E-PUL",
                "name_dr" => "سرپل",
                "name_pa" => "سرپل",
                "zone" => 707,
                "lat" => 36.2190779443106,
                "lang" => 65.9340652736018,
                "ab" => "SER",
                "color" => "BDD0D8",
                "mgrs" => "41SQA6374212240"
            ],
            [
                "id" => 23,
                "code" => 23,
                "name_en" => "GHOR",
                "name_dr" => "غور",
                "name_pa" => "غور",
                "zone" => 606,
                "lat" => 34.521539103989,
                "lang" => 65.2565447236648,
                "ab" => "GHR",
                "color" => "7F8000",
                "mgrs" => "41SQU0712422298"
            ],
            [
                "id" => 24,
                "code" => 24,
                "name_en" => "DAYKUNDI",
                "name_dr" => "دايکندي",
                "name_pa" => "دايکوندي",
                "zone" => 404,
                "lat" => 33.7237552160148,
                "lang" => 66.1473663870644,
                "ab" => "DAI",
                "color" => "453C7E",
                "mgrs" => "42STC3567435182"
            ],
            [
                "id" => 25,
                "code" => 25,
                "name_en" => "UROZGAN",
                "name_dr" => "اورزگان",
                "name_pa" => "اورزگان",
                "zone" => 404,
                "lat" => 32.6209650244781,
                "lang" => 65.8759136127114,
                "ab" => "URO",
                "color" => "9A6200",
                "mgrs" => "41SQS6984712921"
            ],
            [
                "id" => 26,
                "code" => 26,
                "name_en" => "ZABUL",
                "name_dr" => "زابل",
                "name_pa" => "زابل",
                "zone" => 404,
                "lat" => 32.1128796666514,
                "lang" => 66.9110591172556,
                "ab" => "ZAB",
                "color" => "708F8F",
                "mgrs" => "42SUA0291154858"
            ],
            [
                "id" => 27,
                "code" => 27,
                "name_en" => "KANDAHAR",
                "name_dr" => "کندهار",
                "name_pa" => "کندهار",
                "zone" => 404,
                "lat" => 31.6236899648026,
                "lang" => 65.7079154863354,
                "ab" => "KAN",
                "color" => "0080C1",
                "mgrs" => "41RQR5686001910"
            ],
            [
                "id" => 28,
                "code" => 28,
                "name_en" => "JAWZJAN",
                "name_dr" => "جوزجان",
                "name_pa" => "جوزجان",
                "zone" => 707,
                "lat" => 36.6651959657002,
                "lang" => 65.757454079303,
                "ab" => "JAW",
                "color" => "40B503",
                "mgrs" => "41SQA4644661275"
            ],
            [
                "id" => 29,
                "code" => 29,
                "name_en" => "FARYAB",
                "name_dr" => "فارياب",
                "name_pa" => "فارياب",
                "zone" => 707,
                "lat" => 35.9180544610172,
                "lang" => 64.7778984945136,
                "ab" => "FAY",
                "color" => "A8FB74",
                "mgrs" => "41SPV6041176320"
            ],
            [
                "id" => 30,
                "code" => 30,
                "name_en" => "HELMAND",
                "name_dr" => "هــلـمــند",
                "name_pa" => "هــلـمــند",
                "zone" => 505,
                "lat" => 31.5850103172586,
                "lang" => 64.3696670624767,
                "ab" => "HEL",
                "color" => "FFFFD3",
                "mgrs" => "41RPQ2995795252"
            ],
            [
                "id" => 31,
                "code" => 31,
                "name_en" => "BADGHIS",
                "name_dr" => "بادغيس",
                "name_pa" => "بادغيس",
                "zone" => 606,
                "lat" => 34.9886870571679,
                "lang" => 63.1254793011613,
                "ab" => "BDG",
                "color" => "A991BD",
                "mgrs" => "41SNU1145271796"
            ],
            [
                "id" => 32,
                "code" => 32,
                "name_en" => "HERAT",
                "name_dr" => "هرات",
                "name_pa" => "هرات",
                "zone" => 606,
                "lat" => 34.2874285442171,
                "lang" => 62.2066859831488,
                "ab" => "HER",
                "color" => "FF807F",
                "mgrs" => "41SMT2698694311"
            ],
            [
                "id" => 33,
                "code" => 33,
                "name_en" => "FARAH",
                "name_dr" => "فراه",
                "name_pa" => "فراه",
                "zone" => 606,
                "lat" => 32.3714744883451,
                "lang" => 62.1161684405442,
                "ab" => "FAR",
                "color" => "7E413E",
                "mgrs" => "41SMR1685481955"
            ],
            [
                "id" => 34,
                "code" => 34,
                "name_en" => "NIMROZ",
                "name_dr" => "نيمروز",
                "name_pa" => "نيمروز",
                "zone" => 505,
                "lat" => 30.9584522554952,
                "lang" => 61.8589825138862,
                "ab" => "NIM",
                "color" => "C0C0C0",
                "mgrs" => "41RLQ9102125556"
            ],
            [
                "id" => 35,
                "code" => 35,
                "name_en" => "UNKNOWN",
                "name_dr" => "نا معلوم",
                "name_pa" => "نا معلوم",
                "zone" => 0,
                "lat" => "",
                "lang" => "",
                "ab" => "UNK",
                "color" => "9A6200",
                "mgrs" => ""
            ]

        ];

        foreach ($provinces as $province) {
            Provinces::create($province);
        }
    }
}