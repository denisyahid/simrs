<!DOCTYPE html>
<html>

@php
    use Carbon\Carbon;
    Carbon::setLocale('id');
@endphp

<head>
    <title>Surat Pengantar Pasien Rencana Operasi Di Ruang Operasi IBSA</title>
    <style>
        body,
        table,
        td {
            font-family: 'Open Sans', sans-serif !important;
            border-collapse: collapse;
        }

        .tdHeader {
            padding: 5px;
            font-weight: bold;
            /* background-color: lightblue */
        }

        .checkbox-wrapper {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 1px solid black;
            text-align: center;
            line-height: 20px;
        }

        .checked {
            font-weight: bold;
        }

        @media print {
            .page-break {
                page-break-before: always;
            }
        }
    </style>
</head>

<body>
    {{-- {{dd($data)}} --}}
    <table width="100%">
            <tr>
                <td>
                    <table style="font-size: 10pt" cellspacing="0" cellpadding="0" width="100%">
                        <tr>
                            <td class="tdHeader">
                            </td>
                            <td class="tdHeader" style="text-align: right;border-right: none">
                                RM.8F/ADM/00
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <table width="100%" style="border-collapse: collapse">
                                    <tr style="border: ">
                                        <td width="15%" style="text-align: center;padding: 10px">
                                            <img src="{{ 'img/logo-rs.png' }}" width="80px" height="80px"
                                                style="display: block;">
                                        </td>
                                        <td width="70%" style="text-align: center;">
                                            <span>
                                                <b>PEMERINTAH PROVINSI BALI</b><br>
                                                <b>RUMAH SAKIT UMUM DAERAH BALI MANDARA</b><br>
                                                Jalan ByPass Ngurah Rai No.548 Sanur,Garut-Bali
                                            </span>
                                        </td>
                                        <td width="15%" style="text-align: center;padding: 10px">
                                            
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">
                                            <hr style="border: none; border-top: 1px solid #000; margin: 10px 0;">
                                            <hr style="border: none; border-top: 4px solid #000; margin: 10px 0;margin-top:-7px">
                                            </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2"
                                style="font-size: 10pt; font-weight: bold; color: #000000;text-align: center;padding:5px">
                                SURAT PENGANTAR PASIEN RENCANA OPERASI DI RUANG OPERASI IBSA
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div style="padding:7px">
                                    <span>Mohon dipersiapkan pasien dibawah ini :</span>
                                        <div style="margin-left: 20px;margin-top: 10px;margin-bottom: 10px">
                                            <table width="100%" style="border-spacing: 0px;">
                                                <tr>
                                                    <td width="20%">Tanggal</td>
                                                    <td width="70%">:  {{ $dataRegis->tglorder ? Carbon::parse($dataRegis->tglorder)->translatedFormat('d F Y') : '-'  }}</td>
                                                    <td width="20%">Nama</td>
                                                    <td width="70%">:  {{ $dataRegis->namapasien ? $dataRegis->namapasien : '-'  }}</td>
                                                </tr>
                                                <tr>
                                                    <td width="20%">Ruangan Asal</td>
                                                    <td width="70%">: {{ $dataRegis->ruanganasal ? $dataRegis->ruanganasal : '-'  }} 
                                                    </td>
                                                    <td width="20%">No. RM</td>
                                                    <td width="70%">:  {{ $dataRegis->nocm ? $dataRegis->nocm : '-'  }}</td>
                                                </tr>
                                                <tr>
                                                    <td width="20%">Ruangan Tujuan</td>
                                                    <td width="70%">: {{ $dataRegis->ruangantujuan ? $dataRegis->ruangantujuan : '-'  }} 
                                                    </td>
                                                    <td width="20%">Tgl lahir</td>
                                                    <td width="70%">:  {{ $dataRegis->tgllahir ? $dataRegis->tgllahir : '-'  }}</td>
                                                </tr>
                                                <tr>
                                                    <td width="20%">Pengorder</td>
                                                    <td width="70%">: {{ $dataRegis->pengorder ? $dataRegis->pengorder : '-'  }}
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                </div>       
                            </td> 
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div style="padding:3px">
                                    <div style="margin-left: 20px;margin-top: 10px;">
                                        <table width="100%" style="border-spacing: 0px;">
                                            <tr>
                                                <td width="20%">Diagnosis</td>
                                                <td width="70%">:  {{ $dataRegis->diagnosis ? $dataRegis->diagnosis : '-'  }}</td>
                                            </tr>
                                            <tr>
                                                <td width="20%">Perkiraan Durasi</td>
                                                <td width="70%">: {{ $dataRegis->durasi ? $dataRegis->durasi : '-'  }} 
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="20%">Tanggal & Jam Operasi</td>
                                                <td width="70%">: {{ $dataRegis->tgloperasi ? Carbon::parse($dataRegis->tgloperasi)->translatedFormat('d F Y H:i') : '-'  }} 
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="20%">Persiapan khusus</td>
                                                <td width="70%">: {{ $dataRegis->persiapan ? $dataRegis->persiapan : '-'  }} 
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="20%">Rencana Tindakan</td>
                                                <td width="70%">: {{ $dataRegis->keteranganlainnya ? $dataRegis->keteranganlainnya : '-'  }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="20%">Pasien Anestesi</td>
                                                <td width="70%">: @if ($dataRegis->isanastesi == 't')
                                                    Anastesi
                                                @else ($dataRegis->isanastesi == 'f')
                                                    Non Anastesi (Anastesi Lokal)
                                                @endif
                                                
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="20%">Dokter Operator</td>
                                                <td width="70%">: {{ $dataRegis->dokteroperator2 ? $dataRegis->dokteroperator2 : '-'  }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="20%">Dokter Operator Tambahan</td>
                                                <td width="70%">: {{ $dataRegis->dokteroperator3 ? $dataRegis->dokteroperator3 : '-'  }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="20%">TB</td>
                                                <td width="70%">: {{ $dataRegis->tb ? $dataRegis->tb : '-'  }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="20%">TB</td>
                                                <td width="70%">: {{ $dataRegis->bb ? $dataRegis->bb : '-'  }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="20%">Jaminan</td>
                                                <td width="70%">: {{ $dataRegis->jaminan ? $dataRegis->jaminan : '-'  }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="20%">No. Hp</td>
                                                <td width="70%">: {{ $dataRegis->nohp ? $dataRegis->nohp : '-'  }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="20%">No. Hp Keluarga</td>
                                                <td width="70%">: {{ $dataRegis->nohpkel ? $dataRegis->nohpkel : '-'  }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="20%">Amprahan Alat</td>
                                                <td width="70%">: {{ $dataRegis->alat ? $dataRegis->alat : '-'  }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="20%">Riwayat Swab</td>
                                                <td width="70%">: {{ $dataRegis->riwayatswab ? $dataRegis->riwayatswab : '-'  }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="20%">Riwayat Vaksin</td>
                                                <td width="70%">: {{ $dataRegis->riwayatvaksin ? $dataRegis->riwayatvaksin : '-'  }}
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div style="padding:3px">
                                    <div style="margin-left: 20px;margin-bottom: 10px">
                                        <table width="100%" style="border-spacing: 0px;">
                                            <tr>
                                                <td width="17%" style="vertical-align: middle">Tingkat Urgensi</td>
                                                <td width="20%" style="vertical-align: middle">: 
                                                    <input type="checkbox" style="vertical-align: bottom"
                                                    {{ $dataRegis->isurgent == 't' ? 'checked' : '' }} />
                                                    <span style="font-size: 9pt;vertical-align:bottom;" color="#000000">Urgent</span>
                                                </td>
                                                <td width="20%">
                                                    <input type="checkbox" style="vertical-align: bottom"
                                                    {{ $dataRegis->cito == 't' ? 'checked' : '' }} />
                                                    <span style="font-size: 9pt;vertical-align:bottom;" color="#000000">Cito</span>
                                                </td>
                                                <td width="20%">
                                                    <input type="checkbox" style="vertical-align: bottom"
                                                    {{ $dataRegis->iselektif == 't' ? 'checked' : '' }} />
                                                    <span style="font-size: 9pt;vertical-align:bottom;" color="#000000">Elektif</span>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>       
                            </td> 
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div style="padding:3px">
                                    <div style="margin-left: 20px;margin-bottom: 10px">
                                        <table width="100%" style="border-spacing: 0px;">
                                            <tr>
                                                <td style="text-align:right">Hormat kami</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div style="padding:3px">
                                    <div style="margin-left: 20px;margin-bottom: 10px">
                                        <table width="100%" style="border-spacing: 0px;">
                                            <tr>
                                                <td style="text-align:right">{{ $dataRegis->pengorder ? $dataRegis->pengorder : '-'  }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
    </table>
</body>

</html>
