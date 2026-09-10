<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>EMR - CHECK LIST PRA OPERASI DAN PASCA OPERASI</title>
    <style>
        @media print {
            td.merah {
                background-color: #d54242 !important;
                -webkit-print-color-adjust: exact;
            }

            td.kuning {
                background-color: #c5d542 !important;
                -webkit-print-color-adjust: exact;
            }

            td.hijau {
                background-color: #42d55b !important;
                -webkit-print-color-adjust: exact;
            }

            td.hitam {
                background-color: #000000 !important;
                -webkit-print-color-adjust: exact;
            }
        }

        @page {
            size: A4;
        }

        /*@media print {*/
        /*    body {margin:0}*/
        /*}*/

        input[type=checkbox]:before {
            font-family: DejaVu Sans !important;
        }

        input[type=checkbox] {
            margin-bottom: -5px;
        }
        .double-border {

            border: 4px solid #000;

        }

        .double-border:before {

            border: 4px solid #fff;

        }

        .box {
            border: 2px solid black;
            /*border-radius: 6px;*/
        }

        .mt-5 {
            margin-top: 5px;
        }

        .garis6 td {
            padding: 3px;
        }

        .bold {
            font-weight: bold;
        }

        table {
        border-collapse: collapse !important;
        width: 100%;
        }

        .pd td {
        padding: 3px;
        font-size: 10pt;
        }

        .pd2 td {
            padding: 3px;
            font-size: 7pt;
        }

        .pl {
            text-align: left;
        }

        .pc {
            text-align: center;
        }

        .f-s-15 {
            font-size: 12px;
        }
        
        .half {
            width: 50%;
        }

        .top-height {
            height: 50px;
            vertical-align: text-top;
            width: 15%;
        }

        .text-top {
            vertical-align: text-top;
        }

        .kotak {
            width: 50px;
            height: 20px;
        }

        .merah {
            background-color: #d54242 !important;
        }

        .kuning {
            background-color: #c5d542 !important;
        }

        .hijau {
            background-color: #42d55b !important;
        }

        .hitam {
            background-color: #000000 !important;
        }

        .bmerah {
            border: thin solid #d54242;
        }

        .bkuning {
            border: thin solid #c5d542;
        }

        .bhijau {
            border: thin solid #42d55b;
        }

        .bhitam {
            border: thin solid #000000;
        }

        .border-lr {
            border-collapse: collapse;
        }

        .border-lr td {
            border: thin solid #000;
        }

        .border-doang {
            border-collapse: collapse;
            border: thin solid #000;
            border-top: none;
        }

        .border-doang td {
            padding: 5px;
        }

        .bg-gray {
            background-color: #DCDCDC;
        }

        .bg-blue {
            background-color: #91CEDE;
        }

        .tc {
            text-align: center;
        }

        .font {
            font-size: 9pt;
        }

    </style>
</head>

@php
$rows = [
    [
        'no' => 1,
        'data' => [
            ['style' => '', 'type' => 'no', 'colspan' => 1, 'rowspan' => 4, 'no' => '1.'],
            ['style' => 'text-align: left; vertical-align: top;', 'type' => 'label', 'colspan' => 1, 'rowspan' => 4, 'value' => 'Identitas pasien'],
            ['style' => 'text-align: left; vertical-align: top;', 'type' => 'label', 'colspan' => 1, 'rowspan' => 1, 'value' => 'Benar gelang pasien'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'gelA', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'gelA', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'gelB', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'gelB', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'text', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'gelE'],
        ]
    ],
    [
        'no' => 2,
        'data' => [
            ['style' => 'text-align: left; vertical-align: top;', 'type' => 'label', 'colspan' => 1, 'rowspan' => 1, 'value' => 'Benar nama'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'namaA', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'namaA', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'namaB', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'namaB', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'text', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'namaE'],
        ]
    ],
    [
        'no' => 3,
        'data' => [
            ['style' => 'text-align: left; vertical-align: top;', 'type' => 'label', 'colspan' => 1, 'rowspan' => 1, 'value' => 'Benar Nomor RM'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'RMA', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'RMA', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'RMB', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'RMB', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'text', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'RME'],
        ]
    ],
    [
        'no' => 4,
        'data' => [
            ['style' => 'text-align: left; vertical-align: top;', 'type' => 'label', 'colspan' => 1, 'rowspan' => 1, 'value' => 'Benar Tanggal Lahir'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'TLA', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'TLA', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'TLB', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'TLB', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'text', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'TLE'],
        ]
    ],
    [
        'no' => 5,
        'data' => [
            ['style' => '', 'type' => 'no', 'colspan' => 1, 'rowspan' => 2, 'no' => '2.'],
            ['style' => 'text-align: left; vertical-align: top;', 'type' => 'label', 'colspan' => 1, 'rowspan' => 2, 'value' => 'Informed Consent'],
            ['style' => 'text-align: left; vertical-align: top;', 'type' => 'label', 'colspan' => 1, 'rowspan' => 1, 'value' => 'Bedah'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'bedahA', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'bedahA', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'bedahB', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'bedahB', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'text', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'bedahE'],
        ]
    ],
    [
        'no' => 6,
        'data' => [
            ['style' => 'text-align: left; vertical-align: top;', 'type' => 'label', 'colspan' => 1, 'rowspan' => 1, 'value' => 'Anestesi'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'anesA', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'anesA', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'anesB', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'anesB', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'text', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'anesE'],
        ]
    ],
    [
        'no' => 7,
        'data' => [
            ['style' => '', 'type' => 'no', 'colspan' => 1, 'rowspan' => 1, 'no' => '3.'],
            ['style' => 'text-align: left; vertical-align: top;', 'type' => 'label', 'colspan' => 2, 'rowspan' => 1, 'value' => 'Site Marking'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'SMA', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'SMA', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'SMB', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'SMB', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'text', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'SME'],
        ]
    ],
    [
        'no' => 8,
        'data' => [
            ['style' => '', 'type' => 'no', 'colspan' => 1, 'rowspan' => 2, 'no' => '4.'],
            ['style' => 'text-align: left; vertical-align: top;', 'type' => 'label', 'colspan' => 1, 'rowspan' => 2, 'value' => 'Evaluasi pre operasi'],
            ['style' => 'text-align: left; vertical-align: top;', 'type' => 'label', 'colspan' => 1, 'rowspan' => 1, 'value' => 'Pra anestesi'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'pranesA', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'pranesA', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'pranesB', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'pranesB', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'text', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'pranesE'],
        ]
    ],
    [
        'no' => 9,
        'data' => [
            ['style' => 'text-align: left; vertical-align: top;', 'type' => 'label', 'colspan' => 1, 'rowspan' => 1, 'value' => 'Pra bedah'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'prabedA', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'prabedA', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'prabedB', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'prabedB', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'text', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'prabedE'],
        ]
    ],
    [
        'no' => 10,
        'data' => [
            ['style' => '', 'type' => 'no', 'colspan' => 1, 'rowspan' => 2, 'no' => '5.'],
            ['style' => 'text-align: left; vertical-align: top;', 'type' => 'label', 'colspan' => 1, 'rowspan' => 2, 'value' => 'Hasil pemeriksaan'],
            ['style' => 'text-align: left; vertical-align: top;', 'type' => 'label', 'colspan' => 1, 'rowspan' => 1, 'value' => 'Laboratorium'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'labA', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'labA', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'labB', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'labB', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'text', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'labE'],
        ]
    ],
    [
        'no' => 11,
        'data' => [
            ['style' => 'text-align: left; vertical-align: top;', 'type' => 'label', 'colspan' => 1, 'rowspan' => 1, 'value' => 'PA'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'PAA', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'PAA', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'PAB', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'PAB', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'text', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'PAE'],
        ]
    ],
    [
        'no' => 12,
        'data' => [
            ['style' => '', 'type' => 'no', 'colspan' => 1, 'rowspan' => 2, 'no' => '6.'],
            ['style' => 'text-align: left; vertical-align: top;', 'type' => 'label', 'colspan' => 1, 'rowspan' => 2, 'value' => 'Rontgen'],
            ['style' => 'text-align: left; vertical-align: top;', 'type' => 'label', 'colspan' => 1, 'rowspan' => 1, 'value' => 'Thorak'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'thoA', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'thoA', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'thoB', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'thoB', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'text', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'thoE'],
        ]
    ],
    [
        'no' => 13,
        'data' => [
            ['style' => 'text-align: left; vertical-align: top;', 'type' => 'label', 'colspan' => 1, 'rowspan' => 1, 'value' => 'Foto lain'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'fotA', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'fotA', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'fotB', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'fotB', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'text', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'fotE'],
        ]
    ],
    [
        'no' => 14,
        'data' => [
            ['style' => '', 'type' => 'no', 'colspan' => 1, 'rowspan' => 3, 'no' => '7.'],
            ['style' => 'text-align: left; vertical-align: top;', 'type' => 'label', 'colspan' => 1, 'rowspan' => 3, 'value' => 'Operasi khusus jantung'],
            ['style' => 'text-align: left; vertical-align: top;', 'type' => 'label', 'colspan' => 1, 'rowspan' => 1, 'value' => 'CT scan angio'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'ctsA', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'ctsA', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'ctsB', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'ctsB', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'text', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'ctsE'],
        ]
    ],
    [
        'no' => 15,
        'data' => [
            ['style' => 'text-align: left; vertical-align: top;', 'type' => 'label', 'colspan' => 1, 'rowspan' => 1, 'value' => 'Echo'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'echoA', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'echoA', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'echoB', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'echoB', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'text', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'echoE'],
        ]
    ],
    [
        'no' => 16,
        'data' => [
            ['style' => 'text-align: left; vertical-align: top;', 'type' => 'label', 'colspan' => 1, 'rowspan' => 1, 'value' => 'Kateterisasi jantung'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'kateA', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'kateA', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'kateB', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'kateB', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'text', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'kateE'],
        ]
    ],
    [
        'no' => 17,
        'data' => [
            ['style' => '', 'type' => 'no', 'colspan' => 1, 'rowspan' => 5, 'no' => '8.'],
            ['style' => 'text-align: left; vertical-align: top;', 'type' => 'label', 'colspan' => 1, 'rowspan' => 5, 'value' => 'Hasil konsul'],
            ['style' => 'text-align: left; vertical-align: top;', 'type' => 'label', 'colspan' => 1, 'rowspan' => 1, 'value' => 'Penyakit dalam'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'pdA', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'pdA', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'pdB', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'pdB', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'text', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'pdE'],
        ]
    ],
    [
        'no' => 18,
        'data' => [
            ['style' => 'text-align: left; vertical-align: top;', 'type' => 'label', 'colspan' => 1, 'rowspan' => 1, 'value' => 'Anestesi'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'aneA', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'aneA', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'aneB', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'aneB', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'text', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'aneE'],
        ]
    ],
    [
        'no' => 19,
        'data' => [
            ['style' => 'text-align: left; vertical-align: top;', 'type' => 'label', 'colspan' => 6, 'rowspan' => 1, 'value' => 'Bagian lain :'],
        ]
    ],
    [
        'no' => 20,
        'data' => [
            ['style' => 'text-align: left; vertical-align: top;', 'type' => 'text2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'bag1', 'nama2' => '1.'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'bagA', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'bagA', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'bagB', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'bagB', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'text', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'bagE'],
        ]
    ],
    [
        'no' => 21,
        'data' => [
            ['style' => 'text-align: left; vertical-align: top;', 'type' => 'text2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'bag2', 'nama2' => '2.'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'balA', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'balA', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'balB', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'balB', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'text', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'balE'],
        ]
    ],
    [
        'no' => 22,
        'data' => [
            ['style' => '', 'type' => 'no', 'colspan' => 1, 'rowspan' => 2, 'no' => '9.'],
            ['style' => 'text-align: left; vertical-align: top;', 'type' => 'label', 'colspan' => 1, 'rowspan' => 2, 'value' => 'Transfusi'],
            ['style' => 'text-align: left; vertical-align: top;', 'type' => 'label', 'colspan' => 1, 'rowspan' => 1, 'value' => 'Persiapan transfusi'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'PTA', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'PTA', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'PTB', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'PTB', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'text', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'PTE'],
        ]
    ],
    [
        'no' => 23,
        'data' => [
            ['style' => 'text-align: left; vertical-align: top;', 'type' => 'label', 'colspan' => 1, 'rowspan' => 1, 'value' => 'Persetujuan transfusi'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'setuA', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'setuA', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'setuB', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'setuB', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'text', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'setuE'],
        ]
    ],
    [
    'no' => 24,
    'data' => [
        ['style' => '', 'type' => 'no', 'colspan' => 1, 'rowspan' => 4, 'no' => '10.'],
        ['style' => 'text-align: left; vertical-align: top;', 'type' => 'label', 'colspan' => 1, 'rowspan' => 4, 'value' => 'Prothesa'],
        ['style' => 'text-align: left; vertical-align: top;', 'type' => 'label', 'colspan' => 1, 'rowspan' => 1, 'value' => 'Implant'],
        ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'impA', 'label' => 'Ya', 'value' => 'Ya'],
        ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'impA', 'label' => 'Tidak', 'value' => 'Tidak'],
        ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'impB', 'label' => 'Ya', 'value' => 'Ya'],
        ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'impB', 'label' => 'Tidak', 'value' => 'Tidak'],
        ['style' => '', 'type' => 'text', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'impE'],
    ]
],
[
    'no' => 25,
    'data' => [
        ['style' => 'text-align: left; vertical-align: top;', 'type' => 'label', 'colspan' => 1, 'rowspan' => 1, 'value' => 'Pace maker'],
        ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'PMA', 'label' => 'Ya', 'value' => 'Ya'],
        ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'PMA', 'label' => 'Tidak', 'value' => 'Tidak'],
        ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'PMB', 'label' => 'Ya', 'value' => 'Ya'],
        ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'PMB', 'label' => 'Tidak', 'value' => 'Tidak'],
        ['style' => '', 'type' => 'text', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'PME'],
    ]
],
[
    'no' => 26,
    'data' => [
        ['style' => 'text-align: left; vertical-align: top;', 'type' => 'label', 'colspan' => 1, 'rowspan' => 1, 'value' => 'Alat bantu dengar'],
        ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'alatA', 'label' => 'Ya', 'value' => 'Ya'],
        ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'alatA', 'label' => 'Tidak', 'value' => 'Tidak'],
        ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'alatB', 'label' => 'Ya', 'value' => 'Ya'],
        ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'alatB', 'label' => 'Tidak', 'value' => 'Tidak'],
        ['style' => '', 'type' => 'text', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'alatE'],
    ]
],
[
    'no' => 27,
    'data' => [
        ['style' => 'text-align: left; vertical-align: top;', 'type' => 'label', 'colspan' => 1, 'rowspan' => 1, 'value' => 'Lensa kontak/kaca mata'],
        ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'lensA', 'label' => 'Ya', 'value' => 'Ya'],
        ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'lensA', 'label' => 'Tidak', 'value' => 'Tidak'],
        ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'lensB', 'label' => 'Ya', 'value' => 'Ya'],
        ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'lensB', 'label' => 'Tidak', 'value' => 'Tidak'],
        ['style' => '', 'type' => 'text', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'lensE'],
    ]
],
];
@endphp

@php
$rows2 = [
    [
        'no' => 28,
        'data' => [
            ['style' => '', 'type' => 'no', 'colspan' => 1, 'rowspan' => 2, 'no' => '11.'],
            ['style' => 'text-align: left; vertical-align: top;', 'type' => 'label', 'colspan' => 1, 'rowspan' => 2, 'value' => 'Gigi'],
            ['style' => 'text-align: left; vertical-align: top;', 'type' => 'label', 'colspan' => 1, 'rowspan' => 1, 'value' => 'Gigi palsu'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'GPA', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'GPA', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'GPB', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'GPB', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'text', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'GPE'],
        ]
    ],
    [
        'no' => 29,
        'data' => [
            ['style' => 'text-align: left; vertical-align: top;', 'type' => 'label', 'colspan' => 1, 'rowspan' => 1, 'value' => 'Gigi goyang/lepas'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'GGLA', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'GGLA', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'GGLB', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'GGLB', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'text', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'GGLE'],
        ]
    ],
    [
        'no' => 30,
        'data' => [
            ['style' => '', 'type' => 'no', 'colspan' => 1, 'rowspan' => 11, 'no' => '12.'],
            ['style' => 'text-align: left; vertical-align: top;', 'type' => 'label', 'colspan' => 1, 'rowspan' => 9, 'value' => 'Persiapan khusus'],
            ['style' => 'text-align: left; vertical-align: top;', 'type' => 'label', 'colspan' => 1, 'rowspan' => 1, 'value' => 'Puasa'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'pusA', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'pusA', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'pusB', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'pusB', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'text', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'pusE'],
        ]
    ],
    [
        'no' => 31,
        'data' => [
            ['style' => 'text-align: left; vertical-align: top;', 'type' => 'label', 'colspan' => 1, 'rowspan' => 1, 'value' => 'Pasang infus'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'pasinA', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'pasinA', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'pasinB', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'pasinB', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'text', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'pasinE'],
        ]
    ],
    [
        'no' => 32,
        'data' => [
            ['style' => 'text-align: left; vertical-align: top;', 'type' => 'label', 'colspan' => 1, 'rowspan' => 1, 'value' => 'Pasang kateter'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'paskatA', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'paskatA', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'paskatB', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'paskatB', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'text', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'paskatE'],
        ]
    ],
    [
        'no' => 33,
        'data' => [
            ['style' => 'text-align: left; vertical-align: top;', 'type' => 'label', 'colspan' => 1, 'rowspan' => 1, 'value' => 'Mandi besar'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'manbesA', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'manbesA', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'manbesB', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'manbesB', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'text', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'manbesE'],
        ]
    ],
    [
        'no' => 34,
        'data' => [
            ['style' => 'text-align: left; vertical-align: top;', 'type' => 'label', 'colspan' => 1, 'rowspan' => 1, 'value' => 'Cuci rambut'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'curamA', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'curamA', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'curamB', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'curamB', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'text', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'curamE'],
        ]
    ],
    [
        'no' => 35,
        'data' => [
            ['style' => 'text-align: left; vertical-align: top;', 'type' => 'label', 'colspan' => 1, 'rowspan' => 1, 'value' => 'Cukur daerah operasi'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'cudaA', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'cudaA', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'cudaB', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'cudaB', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'text', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'cudaE'],
        ]
    ],
    [
        'no' => 36,
        'data' => [
            ['style' => 'text-align: left; vertical-align: top;', 'type' => 'label', 'colspan' => 1, 'rowspan' => 1, 'value' => 'Potong kuku'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'pokuA', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'pokuA', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'pokuB', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'pokuB', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'text', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'pokuE'],
        ]
    ],
    [
        'no' => 37,
        'data' => [
            ['style' => 'text-align: left; vertical-align: top;', 'type' => 'label', 'colspan' => 1, 'rowspan' => 1, 'value' => 'Hapus make up, cat kuku'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'makeA', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'makeA', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'makeB', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'makeB', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'text', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'makeE'],
        ]
    ],
    [
        'no' => 38,
        'data' => [
            ['style' => 'text-align: left; vertical-align: top;', 'type' => 'label', 'colspan' => 1, 'rowspan' => 1, 'value' => 'Pakaian operasi'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'pakoA', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'pakoA', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'pakoB', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'pakoB', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'text', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'pakoE'],
        ]
    ],
    [
        'no' => 39,
        'data' => [
            ['style' => 'text-align: left; vertical-align: top;', 'type' => 'label', 'colspan' => 1, 'rowspan' => 2, 'value' => 'Huknah'],
            ['style' => 'text-align: left; vertical-align: top;', 'type' => 'label', 'colspan' => 1, 'rowspan' => 1, 'value' => 'Tinggi, jam'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'tinggiA', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'tinggiA', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'tinggiB', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'tinggiB', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'text', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'tinggiE'],
        ]
    ],
    [
        'no' => 40,
        'data' => [
            ['style' => 'text-align: left; vertical-align: top;', 'type' => 'label', 'colspan' => 1, 'rowspan' => 1, 'value' => 'Rendah, jam'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'rendahA', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'rendahA', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'rendahB', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'rendahB', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'text', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'rendahE'],
        ]
    ],
    [
        'no' => 41,
        'data' => [
            ['style' => '', 'type' => 'no', 'colspan' => 1, 'rowspan' => 1, 'no' => '13.'],
            ['style' => 'text-align: left; vertical-align: top;', 'type' => 'label', 'colspan' => 2, 'rowspan' => 1, 'value' => 'Barang-barang milik paisen/perhiasan'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'barmilA', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'barmilA', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'barmilB', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'barmilB', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'text', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'barmilE'],
        ]
    ],
    [
        'no' => 42,
        'data' => [
            ['style' => '', 'type' => 'no', 'colspan' => 1, 'rowspan' => 1, 'no' => '14.'],
            ['style' => 'text-align: left;', 'type' => 'checkbox3', 'colspan' => 7, 'rowspan' => 1, 'text' => 'Pendamping selama transfer :', 'nama' => 'dokter1', 'label' => 'Dokter', 'nama2' => 'perawat1', 'label2' => 'Perawat/Bidan', 'nama3' => 'pos1', 'label3' => 'POS',],
        ]
    ],
    [
        'no' => 43,
        'data' => [
            ['style' => '', 'type' => 'no', 'colspan' => 1, 'rowspan' => 3, 'no' => '15.'],
            ['style' => 'text-align: left; vertical-align: top;', 'type' => 'label', 'colspan' => 1, 'rowspan' => 3, 'value' => 'Profilaksis antibiotik'],
            ['style' => 'text-align: left;', 'type' => 'text2', 'colspan' => 6, 'rowspan' => 1, 'nama2' => 'Jenis :', 'nama' => 'jenis1'],
        ]
    ],
    [
        'no' => 44,
        'data' => [
            ['style' => 'text-align: left;', 'type' => 'text2', 'colspan' => 6, 'rowspan' => 1, 'nama2' => 'Dosis :', 'nama' => 'donis1'],
        ]
    ],
    [
        'no' => 45,
        'data' => [
            ['style' => 'text-align: left;', 'type' => 'text2', 'colspan' => 6, 'rowspan' => 1, 'nama2' => 'Waktu Pemberian :', 'nama' => 'pemberian1'],
        ]
    ],
];
@endphp
@php
$rows3 = [
    [
        'no' => 46,
        'data' => [
            ['style' => '', 'type' => 'no', 'colspan' => 1, 'rowspan' => 9, 'no' => '1.'],
            ['style' => 'text-align: left; vertical-align: top;', 'type' => 'label', 'colspan' => 1, 'rowspan' => 9, 'value' => 'Blangko'],
            ['style' => 'text-align: left; vertical-align: top;', 'type' => 'label', 'colspan' => 1, 'rowspan' => 1, 'value' => 'Catatan keperawatan peri operatif'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'periA', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'periA', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'periB', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'periB', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'text', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'periE'],
        ]
    ],
    [
        'no' => 47,
        'data' => [
            ['style' => 'text-align: left; vertical-align: top;', 'type' => 'label', 'colspan' => 1, 'rowspan' => 1, 'value' => 'Cek list kesiapan anestesi'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'sipanA', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'sipanA', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'sipanB', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'sipanB', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'text', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'sipanE'],
        ]
    ],
    [
        'no' => 48,
        'data' => [
            ['style' => 'text-align: left; vertical-align: top;', 'type' => 'label', 'colspan' => 1, 'rowspan' => 1, 'value' => 'Cek list keselamatan pasien'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'sepA', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'sepA', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'sepB', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'sepB', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'text', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'sepE'],
        ]
    ],
    [
        'no' => 49,
        'data' => [
            ['style' => 'text-align: left; vertical-align: top;', 'type' => 'label', 'colspan' => 1, 'rowspan' => 1, 'value' => 'Catatan anestesi'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'catanA', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'catanA', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'catanB', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'catanB', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'text', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'catanE'],
        ]
    ],
    [
        'no' => 50,
        'data' => [
            ['style' => 'text-align: left; vertical-align: top;', 'type' => 'label', 'colspan' => 1, 'rowspan' => 1, 'value' => 'Laporan operasi'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'LapopA', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'LapopA', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'LapopB', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'LapopB', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'text', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'LapopE'],
        ]
    ],
    [
        'no' => 51,
        'data' => [
            ['style' => 'text-align: left; vertical-align: top;', 'type' => 'label', 'colspan' => 1, 'rowspan' => 1, 'value' => 'Pemakaian alat'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'pemalA', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'pemalA', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'pemalB', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'pemalB', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'text', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'pemalE'],
        ]
    ],
    [
        'no' => 52,
        'data' => [
            ['style' => 'text-align: left; vertical-align: top;', 'type' => 'label', 'colspan' => 1, 'rowspan' => 1, 'value' => 'Kitir tindakan'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'kitirA', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'kitirA', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'kitirB', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'kitirB', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'text', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'kitirE'],
        ]
    ],
    [
        'no' => 53,
        'data' => [
            ['style' => 'text-align: left; vertical-align: top;', 'type' => 'label', 'colspan' => 1, 'rowspan' => 1, 'value' => 'Blangko ILO'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'iloA', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'iloA', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'iloB', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'iloB', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'text', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'iloE'],
        ]
    ],
    [
        'no' => 54,
        'data' => [
            ['style' => 'text-align: left; vertical-align: top;', 'type' => 'label', 'colspan' => 1, 'rowspan' => 1, 'value' => 'Catatan terintegrasi'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'terinA', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'terinA', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'terinB', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'terinB', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'text', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'terinE'],
        ]
    ],
    [
        'no' => 55,
        'data' => [
            ['style' => '', 'type' => 'no', 'colspan' => 1, 'rowspan' => 2, 'no' => '2.'],
            ['style' => 'text-align: left; vertical-align: top;', 'type' => 'label', 'colspan' => 1, 'rowspan' => 2, 'value' => 'Bahan pemeriksaan'],
            ['style' => 'text-align: left; vertical-align: top;', 'type' => 'label', 'colspan' => 1, 'rowspan' => 1, 'value' => 'PA'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'labPAA', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'labPAA', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'labPAB', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'labPAB', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'text', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'labPAE'],
        ]
    ],
    [
        'no' => 56,
        'data' => [
            ['style' => 'text-align: left; vertical-align: top;', 'type' => 'label', 'colspan' => 1, 'rowspan' => 1, 'value' => 'Kultur'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'kulturA', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'kulturA', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'kulturB', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'kulturB', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'text', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'kulturE'],
        ]
    ],
    [
        'no' => 57,
        'data' => [
            ['style' => '', 'type' => 'no', 'colspan' => 1, 'rowspan' => 2, 'no' => '3.'],
            ['style' => 'text-align: left; vertical-align: top;', 'type' => 'label', 'colspan' => 1, 'rowspan' => 2, 'value' => 'Rontgen'],
            ['style' => 'text-align: left; vertical-align: top;', 'type' => 'label', 'colspan' => 1, 'rowspan' => 1, 'value' => 'Thorak'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'thorA', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'thorA', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'thorB', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'thorB', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'text', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'thorE'],
        ]
    ],
    [
        'no' => 58,
        'data' => [
            ['style' => 'text-align: left; vertical-align: top;', 'type' => 'label', 'colspan' => 1, 'rowspan' => 1, 'value' => 'Foto lain'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'folaA', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'folaA', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'folaB', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => '', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'folaB', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => '', 'type' => 'text', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'folaE'],
            ]
    ],
    [
        'no' => 59,
        'data' => [
            ['style' => 'height: 300px;', 'type' => 'no', 'colspan' => 1, 'rowspan' => 1, 'no' => '4.'],
            ['style' => 'text-align: left; vertical-align: top;', 'type' => 'label2', 'colspan' => 2, 'rowspan' => 1, 'value' => 'Barang-barang milik pasien', 'nama' => 'barangpasienA'],
            ['style' => 'vertical-align: top;', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'folaA', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => 'vertical-align: top;', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'folaA', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => 'vertical-align: top;', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'folaB', 'label' => 'Ya', 'value' => 'Ya'],
            ['style' => 'vertical-align: top;', 'type' => 'checkbox2', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'folaB', 'label' => 'Tidak', 'value' => 'Tidak'],
            ['style' => 'vertical-align: top;', 'type' => 'text', 'colspan' => 1, 'rowspan' => 1, 'nama' => 'folaE'],
        ]
    ],
    [
        'no' => 60,
        'data' => [
            ['style' => '', 'type' => 'no', 'colspan' => 1, 'rowspan' => 1, 'no' => '5.'],
            ['style' => 'text-align: left;', 'type' => 'checkbox3', 'colspan' => 7, 'rowspan' => 1, 'text' => 'Pendamping selama transfer :', 'nama' => 'dokter2', 'label' => 'Dokter', 'nama2' => 'perawat2', 'label2' => 'Perawat/Bidan', 'nama3' => 'pos2', 'label3' => 'POS'],
        ]
    ],
];
@endphp

<body class="A4" style="font-family:DejaVu Sans, sans-serif;;height: auto" onload="window.print()">
    <section class="sheet padding-10mm" style="font-family:DejaVu Sans, sans-serif;;height: auto;overflow: hidden;">
        @php
            $tglv_pembuatan = isset($data['tglOperasi']) ? date('d-m-Y H:i', strtotime($data['tglOperasi'])) : "";
        @endphp
        
        <table width="100%" cellspacing="0" cellpadding="0" border="1">
            <thead>
                <tr>
                    <td colspan="2">
                        <table width="100%" cellspacing="0" cellpadding="0" border="1">
                            <tr>
                                <td width="60%" style="text-align:right" colspan=2>
                                    <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                        <tr class="bg-blue">
                                            <td>
                                                <td width="50%" style="text-align:left; font-size: 14px">RSUD BALI MANDARA</td>
                                                <td width="50%" style="font-size: 14px; text-align: right;">RM.10/IBSA/00</td>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </thead>
            <tr>
                <td colspan="2">
                    <table width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td width="10%" style="padding: 5px; border-right: 1px solid black;">
                                <img src="{{ 'img/logo-rs.png' }}" style="width: 90px;">
                            </td>
                            <td style="text-align: center; border-right: 1px solid black;">
                                <b>
                                    <span style="font-size: 16px">CHECK LIST PRA OPERASI DAN PASCA OPERASI
                                </b>
                            </td>
                            <td width="50%" style="padding: 10px">
                                <div class="box" style="text-align: left">
                                    <table style="padding: 3px;" border="0">
                                        <tr>
                                            <td class="f-s-15 bold  text-top" style="width: 100px">No. RM</td>
                                            <td class="f-s-15 bold  text-top">:</td>
                                            <td class="f-s-15 bold text-top"><b>{{ $pasien['nocm'] }}</b></td>
                                        </tr>
                                        <tr>
                                            <td class="f-s-15 bold  text-top">Nama</td>
                                            <td class="f-s-15 bold  text-top">:</td>
                                            <td class="f-s-15 bold  text-top"><b>{{ $pasien['namapasien'] }}</b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="f-s-15 bold  text-top">Jenis Kelamin</td>
                                            <td class="f-s-15 bold  text-top">:</td>
                                            <td class="f-s-15 bold  text-top">
                                                <b>{{ $pasien['jeniskelamin'] }}</b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="f-s-15 bold  text-top">Tgl Lahir</td>
                                            <td class="f-s-15 bold  text-top">:</td>
                                            <td class="f-s-15 bold  text-top">
                                                <b>{{ $pasien['tgllahir'] }}</b>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" style=" border-bottom: none;">
                    <table class="pd" border="0">
                        <td class="pl" style="width: 100%;">
                            NOTE : <br />
                            &nbsp;&nbsp;&nbsp;Barang, obat dan dokumen yang disertakan :
                        </td>
                    </table>
                </td>
            </tr>
            <tr style="page-break-after: always;">
                <td colspan="2" style="border-top: 1px solid black; padding: 5px; border-top: none;">
                    <table cellspacing="0" cellpadding="0" class="pd2" border="1">
                        <tr>
                            <td colspan="8" class="pc">
                                PRE OPERASI
                            </td>
                        </tr>
                        <tr>
                            <td rowspan="2" class="pc">
                                No.
                            </td>
                            <td colspan="2" rowspan="2" class="pc">
                                Hal-hal Yang Dioperkan <br />
                                Oleh Petugas Ruangan
                            </td>
                            <td colspan="2" class="pc">
                                Perawat <br>
                                Ruangan
                            </td>
                            <td colspan="2" class="pc">
                                Perawat <br>
                                Kamar Operasi
                            </td>
                            <td rowspan="2" class="pc">
                                Keterangan
                            </td>
                        </tr>
                        <tr>
                            <td class="pc">
                                Ya
                            </td>
                            <td class="pc">
                                Tidak
                            </td>
                            <td class="pc">
                                Ya
                            </td>
                            <td class="pc">
                                Tidak
                            </td>
                        </tr>
                        @foreach ($rows as $row)
                            <tr>
                            @foreach ($row['data'] as $cell)
                                <td colspan="{{ $cell['colspan'] }}" rowspan="{{ $cell['rowspan'] }}" class="pc" style="{{ $cell['style'] }}">
                                    @if ($cell['type'] === 'checkbox2')
                                        <input type="checkbox" {{ isset($data[$cell['nama']]) && $data[$cell['nama']] == $cell['value'] ? 'checked' : '' }} /><span></span>
                                        @elseif ($cell['type'] === 'label')
                                        <label>{{ $cell['value'] }}</label>
                                        @elseif ($cell['type'] === 'checkbox')
                                        <input type="checkbox" {{ isset($data[$cell['nama']]) ? 'checked' : '' }}  {{ $cell['label'] }}/><span></span>
                                        @elseif ($cell['type'] === 'text')
                                        <label>{{ isset($data[$cell['nama']]) ? $data[$cell['nama']] : '' }}</label>
                                        @elseif ($cell['type'] === 'text2')
                                        <label>{{ isset($cell['nama2']) ? $cell['nama2'] : '' }}  {{ isset($data[$cell['nama']]) ? $data[$cell['nama']] : '' }}</label>
                                        @elseif ($cell['type'] === 'no')
                                        <label>{{ $cell['no'] }}</label>
                                    @endif
                                </td>
                            @endforeach
                            </tr>
                        @endforeach
                    </table>
                </td>
            </tr>
            <tr style="page-break-after: always;">
                <td colspan="2" style="border-top: 1px solid black; padding: 5px;">
                    <table cellspacing="0" cellpadding="0" class="pd2" border="1">
                        @foreach ($rows2 as $row)
                            <tr>
                            @foreach ($row['data'] as $cell)
                                <td colspan="{{ $cell['colspan'] }}" rowspan="{{ $cell['rowspan'] }}" class="pc" style="{{ $cell['style'] }}">
                                    @if ($cell['type'] === 'checkbox2')
                                        <input type="checkbox" {{ isset($data[$cell['nama']]) && $data[$cell['nama']] == $cell['value'] ? 'checked' : '' }}/><span></span>
                                        @elseif ($cell['type'] === 'label')
                                        <label>{{ $cell['value'] }}</label>
                                        @elseif ($cell['type'] === 'checkbox')
                                        <input type="checkbox" {{ isset($data[$cell['nama']]) ? 'checked' : '' }}  {{ $cell['label'] }}/><span></span>
                                        @elseif ($cell['type'] === 'text')
                                        <label>{{ isset($data[$cell['nama']]) ? $data[$cell['nama']] : '' }}</label>
                                        @elseif ($cell['type'] === 'text2')
                                        <label>{{ isset($cell['nama2']) ? $cell['nama2'] : '' }}  {{ isset($data[$cell['nama']]) ? $data[$cell['nama']] : '' }}</label>
                                        @elseif ($cell['type'] === 'no')
                                        <label>{{ $cell['no'] }}</label>
                                        @elseif ($cell['type'] === 'checkbox3')
                                        {{ $cell['text'] }}<input type="checkbox" {{ isset($data[$cell['nama']]) ? 'checked' : '' }}/><span>{{ $cell['label'] }}</span>&nbsp;&nbsp;<input type="checkbox" {{ isset($data[$cell['nama2']]) ? 'checked' : '' }}/><span>{{ $cell['label2'] }}</span>&nbsp;&nbsp;<input type="checkbox" {{ isset($data[$cell['nama3']]) ? 'checked' : '' }}/><span>{{ $cell['label3'] }}</span>
                                    @endif
                                </td>
                            @endforeach
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="8">
                                <table cellspacing="0" cellpadding="0" class="pd2" border="0">
                                    <tr>
                                        <td style="width: 50%; border-right: none;" class="pc">
                                            Diserahkan, <br>
                                            @isset($data['TTDDiserahkan'])
                                            <img src="{{ $data['TTDDiserahkan'] }}" alt="TTD" width="100px" height="100px"> <br>
                                            @endisset
                                            {{ isset($data['Diserahkan']['label']) ? $data['Diserahkan']['label'] : '' }}
                                        </td>
                                        <td style="width: 50%; border-left: none;" class="pc">
                                            Diterima, <br>
                                            @isset($data['TTDDiterima'])
                                            <img src="{{ $data['TTDDiterima'] }}" alt="TTD" width="100px" height="100px"> <br>
                                            @endisset
                                            {{ isset($data['Diterima']['label']) ? $data['Diterima']['label'] : '' }}
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" style=" border-bottom: none;">
                    <table class="pd" border="0">
                        <td class="pl" style="width: 100%;">
                            NOTE : <br />
                            &nbsp;&nbsp;&nbsp;Barang, obat dan dokumen yang disertakan :
                        </td>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="border-top: 1px solid black; padding: 5px; border-top: none; border-bottom: none;">
                    <table cellspacing="0" cellpadding="0" class="pd2" border="1">
                        <tr>
                            <td colspan="8" class="pc">
                                PASCA OPERASI
                            </td>
                        </tr>
                        <tr>
                            <td rowspan="2" class="pc">
                                No.
                            </td>
                            <td colspan="2" rowspan="2" class="pc">
                                Hal-hal Yang Dioperkan <br />
                                Oleh Petugas Ruangan
                            </td>
                            <td colspan="2" class="pc">
                                Perawat <br>
                                Kamar Operasi
                            </td>
                            <td colspan="2" class="pc">
                                Perawat <br>
                                Ruangan
                            </td>
                            <td rowspan="2" class="pc">
                                Keterangan
                            </td>
                        </tr>
                        <tr>
                            <td class="pc">
                                Ya
                            </td>
                            <td class="pc">
                                Tidak
                            </td>
                            <td class="pc">
                                Ya
                            </td>
                            <td class="pc">
                                Tidak
                            </td>
                        </tr>
                        @foreach ($rows3 as $row)
                            <tr>
                            @foreach ($row['data'] as $cell)
                                <td colspan="{{ $cell['colspan'] }}" rowspan="{{ $cell['rowspan'] }}" class="pc" style="{{ $cell['style'] }}">
                                    @if ($cell['type'] === 'checkbox2')
                                        <input type="checkbox" {{ isset($data[$cell['nama']]) && $data[$cell['nama']] == $cell['value'] ? 'checked' : '' }} /><span></span>
                                        @elseif ($cell['type'] === 'label')
                                        <label>{{ $cell['value'] }}</label>
                                        @elseif ($cell['type'] === 'label2')
                                        <label>{{ $cell['value'] }}</label> <br>
                                        <label>{{ isset($data[$cell['nama']]) ? $data[$cell['nama']] : '' }}</label>
                                        @elseif ($cell['type'] === 'checkbox')
                                        <input type="checkbox" {{ isset($data[$cell['nama']]) ? 'checked' : '' }}  {{ $cell['label'] }}/><span></span>
                                        @elseif ($cell['type'] === 'text')
                                        <label>{{ isset($data[$cell['nama']]) ? $data[$cell['nama']] : '' }}</label>
                                        @elseif ($cell['type'] === 'text2')
                                        <label>{{ isset($cell['nama2']) ? $cell['nama2'] : '' }}  {{ isset($data[$cell['nama']]) ? $data[$cell['nama']] : '' }}</label>
                                        @elseif ($cell['type'] === 'no')
                                        <label>{{ $cell['no'] }}</label>
                                        @elseif ($cell['type'] === 'checkbox3')
                                        {{ $cell['text'] }}<input type="checkbox" {{ isset($data[$cell['nama']]) ? 'checked' : '' }}/><span>{{ $cell['label'] }}</span>&nbsp;&nbsp;<input type="checkbox" {{ isset($data[$cell['nama2']]) ? 'checked' : '' }}/><span>{{ $cell['label2'] }}</span>&nbsp;&nbsp;<input type="checkbox" {{ isset($data[$cell['nama3']]) ? 'checked' : '' }}/><span>{{ $cell['label3'] }}</span>
                                    @endif
                                </td>
                            @endforeach
                            </tr>
                        @endforeach
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="border-top: none; padding: 5px;">
                    <table cellspacing="0" cellpadding="0" class="pd2" border="1">
                        <tr>
                            <td style="width: 33%;" class="pc">
                                Disetujui, <br>
                                @isset($data['TTDDisetujuidua'])
                                <img src="{{ $data['TTDDisetujuidua'] }}" alt="TTD" width="100px" height="100px"> <br>
                                @endisset
                                {{ isset($data['PDisetujuidua']['label']) ? $data['Disetujuidua']['label'] : '' }}
                            </td>
                            <td style="width: 33%;" class="pc">
                                Diserahkan, <br>
                                @isset($data['TTDDiserahkandua'])
                                <img src="{{ $data['TTDDiserahkandua'] }}" alt="TTD" width="100px" height="100px"> <br>
                                @endisset
                                {{ isset($data['Diserahkandua']['label']) ? $data['Diserahkandua']['label'] : '' }}
                            </td>
                            <td style="width: 33%;" class="pc">
                                Diterima, <br>
                                @isset($data['TTDDiterimadua'])
                                <img src="{{ $data['TTDDiterimadua'] }}" alt="TTD" width="100px" height="100px"> <br>
                                @endisset
                                {{ isset($data['Diterimadua']['label']) ? $data['Diterimadua']['label'] : '' }}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 33%;" class="pc">
                                Dokter anestesi
                            </td>
                            <td style="width: 33%;" class="pc">
                                Perawat kamar operasi
                            </td>
                            <td style="width: 33%;" class="pc">
                                Perawat ruangan
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            </table>
    </section>
</body>

</html>
