@extends('template.head-emr')
@section('title', 'EMR-Resume medis rawat jalan')
@section('about', 'RESUME MEDIS LEMBAR KHUSUS')

@push('style')
    <style>
        span{
            font-size: 11pt;
        }
    </style>
@endpush

@section('content')
    <small style="color: rgb(242, 43, 43)">*</small>
    <small style="font-size: 11px">Lembar untuk diisi dokter</small>
    <table style="padding-top: 10px">
        <tr>
            <td width="25%">
                <span>Anamnesa</span>
            </td>
            <td width="1%">
                <span>:</span>
            </td>
            <td>
                <span>{{ isset($data['anamnesa']) ? $data['anamnesa'] : '-'}}</span>
            </td>
        </tr>
        <tr>
            <td width="25%">
                <span>Pemeriksaan Fisik</span>
            </td>
            <td width="1%">
                <span>:</span>
            </td>
            <td>
                <span>{{ isset($data['pemeriksaanFisik']) ? $data['pemeriksaanFisik'] : '-'}}</span>
            </td>
        </tr>
        <tr>
            <td width="25%">
                <span>Pemeriksaan Penunjang</span>
            </td>
            <td width="1%">
                <span>:</span>
            </td>
            <td>
               <span> {{ isset($data['pemeriksaanPenunjang']) ? $data['pemeriksaanPenunjang'] : '-'}}</span>
            </td>
        </tr>
        <tr>
            <td width="25%">
                <span>Diagnosa</span>
            </td>
            <td width="1%">
                <span>:</span>
            </td>
            <td>
                <span>{{ isset($data['diagonsa']) ? $data['diagonsa'] : '-'}}</span>
            </td>
        </tr>
        <tr>
            <td width="25%">
                <span>Terapi</span>
            </td>
            <td width="1%">
                <span>:</span>
            </td>
            <td>
                <span>{{ isset($data['terapi']) ? $data['terapi'] : '-'}}</span>
            </td>
        </tr>
        <tr>
            <td width="25%">
                <span>Rencana</span>
            </td>
            <td width="1%">
                <span>:</span>
            </td>
            <td>
                <span>{{isset($data['rencana']) ? $data['rencana'] : '-'}}</span>
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <td width="60%"></td>
            <td style="text-align: center">
                <span style="font-size: 11pt;" color="#000000"><b>Bandung, </b>
                    <span>{{ isset($data['created_at']) ? date('d-m-Y', strtotime($data['created_at'])) : '-' }}</span>
                </span>
            </td>
        </tr>
        <tr>
            <td width="60%"></td>
            <td style="text-align: center">
                <span style="font-size: 11pt;" color="#000000"><b>Dokter Pemeriksa</b>
            </td>
        </tr>
        <tr>
            <td width="100%" colspan="4" height="10"></td>
        </tr>
        <tr>
            <td width="60%"></td>
            <td width="40%" style="text-align:center">
                <span style="font-size: 11pt;font-weight: bold;" color="#000000">
                    ( {{ $data['komiteMedis']['label'] }} )</span>
            </td>
        </tr>
    </table>
@endsection
