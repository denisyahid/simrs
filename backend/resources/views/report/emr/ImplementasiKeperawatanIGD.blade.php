@extends('template.layout-emr')
@section('title', 'Implementasi Keperawatan IGD')
@section('kode', '')
@section('page-style')
    <style>
        body,
        table,
        td {
            font-family: 'Open Sans', sans-serif !important;
        }

        th {
            text-align: center !important;
            border: 1px solid black !important;
            font-family: Arial, sans-serif;
            font-size: 14px;
            font-weight: normal;
            overflow: hidden;
            border-top: 0px;
            vertical-align: middle;
            padding: 10px 5px;
        }

        .list>td {
            text-align: center;
            vertical-align: middle;
            border: 1px solid black;
            padding: 7px;
        }

        .nbt {
            border-top: 0px;
        }

        .center {
            vertical-align: middle;
            text-align: center
        }

        .border {
            border: 1px solid black;
        }

        .list>td {
            padding: 7px;
            border: 1px solid black;
            border-top: none;
            font-size: 10pt
        }

        .font {
            font-size: 8pt
        }
    </style>
@endsection

@php
    function convertToRegularDateTime($isoDateString)
    {
        $date = new DateTime($isoDateString, new DateTimeZone('UTC'));
        $date->setTimezone(new DateTimeZone('Asia/Jakarta'));
        return $date->format('d-m-Y H:i');
    }

    function convertToRegularDate($isoDateString)
    {
        $date = new DateTime($isoDateString);
        return $date->format('d-m-Y');
    }

    function convertToRegularTime($isoDateString)
    {
        $date = new DateTime($isoDateString);
        return $date->format('H:i');
    }

    // dd($data['details']);

@endphp

@section('content')
    <tr>
        <th style="width: 15%">JAM/TGL</th>
        <th style="width: 10%%">NO DX</th>
        <th style="width: 25%">TINDAKAN KEPERAWATAN</th>
        <th style="width: 25%">EVALUASI</th>
        <th style="width: 25%">PARAF & NAMA TERANG</th>
    </tr>
    @foreach ($data['details'] as $daata)
        <tr class="list">
            <td>
                @if (array_key_exists('tgltindakan', $daata))
                    {{ convertToRegularDateTime($daata['tgltindakan']) }}
                @else
                    -
                @endif
            </td>
            <td>{{ $daata['nodx'] ?? '-' }}</td>
            <td>{{ $daata['tindakankeperawatan'] ?? '-' }}</td>
            <td>{{ $daata['evaluasi'] ?? '-' }}</td>
            <td>{{ $daata['paraf']['label'] ?? '-' }}</td>
        </tr>
    @endforeach
@endsection
