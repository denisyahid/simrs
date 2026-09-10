<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Lembar Bantu Pengamatan Menyusui</title>

    @if (stripos(\Request::url(), 'localhost') !== false || stripos(\Request::url(), '127.0.0.1') !== false)
    <link rel="stylesheet" href="{{ asset('css/paper.css') }} ">
    <link rel="stylesheet" href="{{ asset('css/table-v2.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tabel.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @else
    <link rel="stylesheet" href="{{ asset('service/css/paper.css') }} ">
    <link rel="stylesheet" href="{{ asset('service/css/table-v2.css') }}">
    <link rel="stylesheet" href="{{ asset('service/css/tabel.css') }}">
    <link rel="stylesheet" href="{{ asset('service/css/style.css') }}">
    @endif

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
            size: F4;
        }

        .double-border {
            border: 4px solid #000;
        }

        .double-border:before {
            border: 4px solid #fff;
        }

        .box {
            border: 2px solid black;
        }

        .garis6 td {
            padding: 3px;
        }

        .bold {
            font-weight: bold;
        }

        .f-s-15 {
            font-size: 12px;
        }

        .top-height {
            height: 50px;
            vertical-align: text-top;
            width: 15%;
        }

        .text-top {
            vertical-align: text-top;
        }

        table {
            width: 100%;
            height: 100%;
        }

        .hitam {
            background-color: #000000 !important;
        }

        .table-ex {
            /* border: 1px solid black; */
            border-collapse: collapse !important;
            width: 100%;
        }

        .table-eex {
            /* border: 1px solid black; */
            border-collapse: collapse !important;
            width: 100%;
        }

        .table-sub {
            padding: 5px;
            border: 1px black;
            border-collapse: collapse;
            width: 100%;
        }

        .th-sub {
            padding: 5px;
        }

        .dot {
            border-bottom: 1px dotted rgba(0, 0, 0, .6)
        }

        .th-ex,
        .td-ex {
            border: 1px solid black;
            padding: 3px;
            text-align: left;
        }

        .label {
            font-size: 11px;
            color: #000000;
        }

        .label-sub {
            font-size: 13px;
            font-weight: 600;
            color: #000000;
        }

        .normal-text {
            font-weight: normal;
            text-transform: uppercase;
        }

        .sm {
            font-size: xx-small;
        }

        .italic {
            font-style: italic;
            font-weight: normal;
        }
    </style>

    @stack('style')

</head>

<body class="F4" style="font-family:Tahoma;height: auto">
    <section class="sheet padding-10mm" style="font-family:Tahoma;height: auto;overflow: hidden;">
        <table class="table-ex">
            <tr>
                <th class="th-ex" width="50%" style="padding: 0px;">
                    <table width="100%" style="border-collapse: collapse;border-bottom:1px solid black">
                        <tr>
                            <td width="25%" style="text-align: left;padding:7px">
                                @if (stripos(\Request::url(), 'localhost') !== false || stripos(\Request::url(),
                                '127.0.0.1') !== false)
                                <img src="{{ asset('img/logo-rs.png') }}" width="70px" border="0">
                                @else
                                <img src="{{ asset('service/img/logo-rs.png') }}" width="70px" border="0">
                                @endif
                            </td>
                            <td width="75%">
                                <table style="border-collapse: collapse">
                                    <tr>
                                        <td style="text-align: center;padding:0px">
                                            <span class="normal-text sm">{{$profile->namapemerintahan}}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center; padding:0px">
                                            <span class="normal-text sm">{{$profile->reportdisplay}}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center; padding:0px">
                                            <span style="font-size: x-small">{{$profile->namalengkap}}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center; padding:0px">
                                            <span class="normal-text sm">{{$profile->alamatlengkap}} .
                                                Tlp.{{$profile->fixedphone}}, Fax.${{$profile->faksimile}}</span>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center; padding:0px">
                                            <span class="normal-text sm">{{$profile->alamatemail}}</span>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                    <table width="100%" style="margin:18px 0px">
                        <tr>
                            {{-- <th style="text-align: center;text-transform:uppercase">{{ $profile->website}}
                            </th> --}}
                            <th style="text-align: center;text-transform:uppercase">Lembar Bantu Pengamatan Menyusui
                            </th>
                        </tr>
                    </table>
                </th>
                <th class="th-ex" width="50%">
                    <table class="table-ex">
                        <tr>
                            <td width="40%" class="th-sub">
                                <span>Nomor RM</span>
                            </td>
                            <td width="3%" class="th-sub">
                                <span>:</span>
                            </td>
                            <td width="50%" class="th-sub dot">
                                <span>{{ $pasien['nocm'] }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td width="40%" class="th-sub">
                                <span>Nama Pasien</span>
                            </td>
                            <td width="3%" class="th-sub">
                                <span>:</span>
                            </td>
                            <td width="50%" class="th-sub dot">
                                <span style="border-bottom: 1px">{{ $pasien['namapasien'] }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td width="40%" class="th-sub">
                                <span>Tanggal Lahir</span>
                            </td>
                            <td width="3%" class="th-sub">
                                <span>:</span>
                            </td>
                            <td width="50%" class="th-sub dot">
                                <span style="border-bottom: 1px">{{ $pasien['tgllahir'] }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td width="40%" class="th-sub">
                                <span>Jenis Kelamin</span>
                            </td>
                            <td width="3%" class="th-sub">
                                <span>:</span>
                            </td>
                            <td width="50%" class="th-sub dot">
                                <span>{{ $pasien['jeniskelamin'] }}</span>
                            </td>
                        </tr>
                    </table>
                </th>
            </tr>
        </table>

        <table class="table-eex" width="100%" style="border: 1px solid black !important;">
            <tr>
                <td style="padding: 10px">
                    <label>Nama Ibu : <span>{{ $pasien['namapasien'] }}</span></label>
                </td>
                <td style="padding: 10px">
                    <label>Tanggal : <span>{{ isset($data['Date']) ? date('d-m-Y', strtotime($data['Date'])) : '-'
                            }}</span></label>
                </td>
            </tr>
            <tr>
                <td style="padding: 10px;padding-top:0px">
                    <label>Nama Bayi : <span>{{ $data['TBNamaBayi'] }}</span></label>
                </td>
                <td style="padding: 10px;padding-top:0px">
                    <label>Umur Bayi : <span>{{ $data['TBUmurBayi'] }}</span></label>
                </td>
            </tr>
            <tr>
                <td style="padding: 10px;padding-top:0px" colspan="2">
                    <label>Tanda menyusui berjalan dengan baik : <span>{{ $data['TBTandaMenyusuiBayi'] }}</span></label>
                </td>
            </tr>
            <table style="border-collapse: collapse;">
                <tr>
                    <td style="border: 1px solid black;padding: 7px" width="50%">
                        <label style="font-weight:bold">UMUM IBU</label>
                        <div style="margin-top: 7px;margin-left: 5px">
                            <input type="checkbox" {{ isset($data['checkboxPM_1_0']) &&
                                $data['checkboxPM_1_0']=='Ibu tampak sehat' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Ibu tampak sehat</span>
                        </div>
                        <div style="margin-top: 7px;margin-left: 5px">
                            <input type="checkbox" {{ isset($data['checkboxPM_1_0']) &&
                                $data['checkboxPM_2_0']=='Ibu tampak tenang dan rileks' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Ibu tampak tenang dan rileks</span>
                        </div>
                        <div style="margin-top: 7px;margin-left: 5px">
                            <input type="checkbox" {{ isset($data['checkboxPM_1_0']) &&
                                $data['checkboxPM_3_0']=='Terlihat tanda bounding ibu-bayi' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Terlihat tanda bounding ibu-bayi</span>
                        </div>
                    </td>
                    <td style="border: 1px solid black;padding: 7px" width="50%">
                        <label>&nbsp;</label>
                        <div style="margin-top: 7px;margin-left: 5px">
                            <input type="checkbox" {{ isset($data['checkboxPM_1_0']) &&
                                $data['checkboxPM_5_1']=='Ibu tampak sakit atau depresi' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Ibu tampak sakit atau depresi</span>
                        </div>
                        <div style="margin-top: 7px;margin-left: 5px">
                            <input type="checkbox" {{ isset($data['checkboxPM_1_0']) &&
                                $data['checkboxPM_6_1']=='Ibu tampak tegang dan tidak nyaman' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Ibu tampak tegang dan tidak nyaman</span>
                        </div>
                        <div style="margin-top: 7px;margin-left: 5px">
                            <input type="checkbox" {{ isset($data['checkboxPM_1_0']) &&
                                $data['checkboxPM_3_1']=='Tidak ada kontak mata ibu-bayi' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Tidak ada kontak mata ibu-bayi</span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td style="border: 1px solid black;padding: 7px" width="50%">
                        <label style="font-weight:bold">UMUM BAYI</label>
                        <div style="margin-top: 7px;margin-left: 5px">
                            <input type="checkbox" {{ isset($data['checkboxPM_1_0']) &&
                                $data['checkboxPM_5_0']=='Bayi tampak sehat' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Bayi tampak sehat</span>
                        </div>
                        <div style="margin-top: 7px;margin-left: 5px">
                            <input type="checkbox" {{ isset($data['checkboxPM_1_0']) &&
                                $data['checkboxPM_6_0']=='Bayi tampak tenang dan rileks' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Bayi tampak tenang dan rileks</span>
                        </div>
                        <div style="margin-top: 7px;margin-left: 5px">
                            <input type="checkbox" {{ isset($data['checkboxPM_1_0']) &&
                                $data['checkboxPM_7_0']=='Bayi mencari payudara (rooting) bila lapar' ? 'checked' : ''
                                }} />
                            <span style="font-size: 9pt;" color="#000000">Bayi mencari payudara (rooting) bila
                                lapar</span>
                        </div>
                    </td>
                    <td style="border: 1px solid black;padding: 7px" width="50%">
                        <label>&nbsp;</label>
                        <div style="margin-top: 7px;margin-left: 5px">
                            <input type="checkbox" {{ isset($data['checkboxPM_1_0']) &&
                                $data['checkboxPM_1_1']=='Bayi tampak mengantuk atau sakit' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Bayi tampak mengantuk atau sakit</span>
                        </div>
                        <div style="margin-top: 7px;margin-left: 5px">
                            <input type="checkbox" {{ isset($data['checkboxPM_1_0']) &&
                                $data['checkboxPM_2_1']=='Bayi tampak gelisah atau menangis' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Bayi tampak gelisah atau menangis</span>
                        </div>
                        <div style="margin-top: 7px;margin-left: 5px">
                            <input type="checkbox" {{ isset($data['checkboxPM_1_0']) &&
                                $data['checkboxPM_7_1']=='Bayi tidak mencari payudara (rooting)' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Bayi tidak mencari payudara (rooting)</span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td style="border: 1px solid black;padding: 7px" width="50%">
                        <label style="font-weight:bold">POSISI BAYI</label>
                        <div style="margin-top: 7px;margin-left: 5px">
                            <input type="checkbox" {{ isset($data['checkboxPM_1_0']) &&
                                $data['checkboxPM_9_0']=='Telinga dan bahu bayi dalam garis lurus' ? 'checked' : ''
                                }} />
                            <span style="font-size: 9pt;" color="#000000">Telinga dan bahu bayi dalam garis lurus</span>
                        </div>
                        <div style="margin-top: 7px;margin-left: 5px">
                            <input type="checkbox" {{ isset($data['checkboxPM_1_0']) &&
                                $data['checkboxPM_10_0']=='Bayi dipeluk dekat badan ibu' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Bayi dipeluk dekat badan ibu</span>
                        </div>
                        <div style="margin-top: 7px;margin-left: 5px">
                            <input type="checkbox" {{ isset($data['checkboxPM_1_0']) &&
                                $data['checkboxPM_11_0']=='Seluruh badan bayi ditopang' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Seluruh badan bayi ditopang</span>
                        </div>
                        <div style="margin-top: 7px;margin-left: 5px">
                            <input type="checkbox" {{ isset($data['checkboxPM_1_0']) &&
                                $data['checkboxPM_12_0']=='Bayi dekat di payudara, hidung berhadapan dengan puting'
                                ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Bayi dekat di payudara, hidung berhadapan
                                dengan puting</span>
                        </div>
                    </td>
                    <td style="border: 1px solid black;padding: 7px" width="50%">
                        <label>&nbsp;</label>
                        <div style="margin-top: 7px;margin-left: 5px">
                            <input type="checkbox" {{ isset($data['checkboxPM_1_0']) &&
                                $data['checkboxPM_9_1']=='Leher dan kepala bayi berputar' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Leher dan kepala bayi berputar</span>
                        </div>
                        <div style="margin-top: 7px;margin-left: 5px">
                            <input type="checkbox" {{ isset($data['checkboxPM_1_0']) &&
                                $data['checkboxPM_10_1']=='Bayi tidak dipeluk dekat badan ibu' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Bayi tidak dipeluk dekat badan ibu</span>
                        </div>
                        <div style="margin-top: 7px;margin-left: 5px">
                            <input type="checkbox" {{ isset($data['checkboxPM_1_0']) &&
                                $data['checkboxPM_11_1']=='Hanya leher dan badan bayi yang ditopang' ? 'checked' : ''
                                }} />
                            <span style="font-size: 9pt;" color="#000000">Hanya leher dan badan bayi yang
                                ditopang</span>
                        </div>
                        <div style="margin-top: 7px;margin-left: 5px">
                            <input type="checkbox" {{ isset($data['checkboxPM_1_0']) &&
                                $data['checkboxPM_12_1']=='Bayi dekat payudara, hidung tidak berhadapan dengan puting'
                                ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Bayi dekat payudara, hidung tidak berhadapan
                                dengan puting</span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td style="border: 1px solid black;padding: 7px" width="50%">
                        <label style="font-weight:bold">PERLEKATAN BAYI</label>
                        <div style="margin-top: 7px;margin-left: 5px">
                            <input type="checkbox" {{ isset($data['checkboxPM_1_0']) &&
                                $data['checkboxPM_14_0']=='Tampak lebih banyak areola di atas bibir' ? 'checked' : ''
                                }} />
                            <span style="font-size: 9pt;" color="#000000">Tampak lebih banyak areola di atas
                                bibir</span>
                        </div>
                        <div style="margin-top: 7px;margin-left: 5px">
                            <input type="checkbox" {{ isset($data['checkboxPM_1_0']) &&
                                $data['checkboxPM_15_0']=='Mulut bayi terbuka lebar' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Mulut bayi terbuka lebar</span>
                        </div>
                        <div style="margin-top: 7px;margin-left: 5px">
                            <input type="checkbox" {{ isset($data['checkboxPM_1_0']) &&
                                $data['checkboxPM_16_0']=='Bibir bawah bayi berputar keluar' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Bibir bawah bayi berputar keluar</span>
                        </div>
                        <div style="margin-top: 7px;margin-left: 5px">
                            <input type="checkbox" {{ isset($data['checkboxPM_1_0']) &&
                                $data['checkboxPM_17_0']=='Dagu bayi menempel pada payudara' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Dagu bayi menempel pada payudara</span>
                        </div>
                    </td>
                    <td style="border: 1px solid black;padding: 7px" width="50%">
                        <label>&nbsp;</label>
                        <div style="margin-top: 7px;margin-left: 5px">
                            <input type="checkbox" {{ isset($data['checkboxPM_1_0']) &&
                                $data['checkboxPM_14_1']=='Tampak lebih banyak areola di bawah bibir' ? 'checked' : ''
                                }} />
                            <span style="font-size: 9pt;" color="#000000">Tampak lebih banyak areola di bawah
                                bibir</span>
                        </div>
                        <div style="margin-top: 7px;margin-left: 5px">
                            <input type="checkbox" {{ isset($data['checkboxPM_1_0']) &&
                                $data['checkboxPM_15_1']=='Mulut bayi tidak terbuka lebar' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Mulut bayi tidak terbuka lebar</span>
                        </div>
                        <div style="margin-top: 7px;margin-left: 5px">
                            <input type="checkbox" {{ isset($data['checkboxPM_1_0']) &&
                                $data['checkboxPM_16_1']=='Bibir bawah bayi berputar kedalam' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Bibir bawah bayi berputar kedalam</span>
                        </div>
                        <div style="margin-top: 7px;margin-left: 5px">
                            <input type="checkbox" {{ isset($data['checkboxPM_1_0']) &&
                                $data['checkboxPM_17_1']=='Dagu bayi tidak menempel pada payudara' ? 'checked' : ''
                                }} />
                            <span style="font-size: 9pt;" color="#000000">Dagu bayi tidak menempel pada payudara</span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td style="border: 1px solid black;padding: 7px" width="50%">
                        <label style="font-weight:bold">MENGHISAP</label>
                        <div style="margin-top: 7px;margin-left: 5px">
                            <input type="checkbox" {{ isset($data['checkboxPM_1_0']) &&
                                $data['checkboxPM_19_0']=='Hisapan lambat, dalam dan dengan istirahat' ? 'checked' : ''
                                }} />
                            <span style="font-size: 9pt;" color="#000000">Hisapan lambat, dalam dan dengan
                                istirahat</span>
                        </div>
                        <div style="margin-top: 7px;margin-left: 5px">
                            <input type="checkbox" {{ isset($data['checkboxPM_1_0']) &&
                                $data['checkboxPM_20_0']=='Pipi membulat waktu menghisap' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Pipi membulat waktu menghisap</span>
                        </div>
                        <div style="margin-top: 7px;margin-left: 5px">
                            <input type="checkbox" {{ isset($data['checkboxPM_1_0']) &&
                                $data['checkboxPM_21_0']=='Bayi melepaskan payudara waktu selesai' ? 'checked' : ''
                                }} />
                            <span style="font-size: 9pt;" color="#000000">Bayi melepaskan payudara waktu selesai</span>
                        </div>
                        <div style="margin-top: 7px;margin-left: 5px">
                            <input type="checkbox" {{ isset($data['checkboxPM_1_0']) &&
                                $data['checkboxPM_22_0']=='Ibu merasakan tanda-tanda reflek oksitosin' ? 'checked' : ''
                                }} />
                            <span style="font-size: 9pt;" color="#000000">Ibu merasakan tanda-tanda reflek
                                oksitosin</span>
                        </div>
                    </td>
                    <td style="border: 1px solid black;padding: 7px" width="50%">
                        <label>&nbsp;</label>
                        <div style="margin-top: 7px;margin-left: 5px">
                            <input type="checkbox" {{ isset($data['checkboxPM_1_0']) &&
                                $data['checkboxPM_19_1']=='Hisapan dangkal dan cepat' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Hisapan dangkal dan cepat</span>
                        </div>
                        <div style="margin-top: 7px;margin-left: 5px">
                            <input type="checkbox" {{ isset($data['checkboxPM_1_0']) &&
                                $data['checkboxPM_20_1']=='Pipi tertarik kedalam waktu menghisap' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Pipi tertarik kedalam waktu menghisap</span>
                        </div>
                        <div style="margin-top: 7px;margin-left: 5px">
                            <input type="checkbox" {{ isset($data['checkboxPM_1_0']) &&
                                $data['checkboxPM_21_1']=='Ibu melepaskan bayi dari payudara' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Ibu melepaskan bayi dari payudara</span>
                        </div>
                        <div style="margin-top: 7px;margin-left: 5px">
                            <input type="checkbox" {{ isset($data['checkboxPM_1_0']) &&
                                $data['checkboxPM_22_1']=='Tidak tampak tanda oksitosin yang jelas' ? 'checked' : ''
                                }} />
                            <span style="font-size: 9pt;" color="#000000">Tidak tampak tanda oksitosin yang jelas</span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td style="border: 1px solid black;padding: 7px" colspan="2">
                        <label id="catatan" style="font-weight:bold">Catatan : </label>
                        <div id="catatan" style="margin-top: 7px;">
                            {{ $data['TACatatan'] }}
                        </div>
                    </td>
                </tr>
                <table style="border-collapse: collapse;">
                    <tr>
                        <td style="border: 1px solid black;padding: 7px;border-right: 0px" width="60%"> </td>
                        <td style="border: 1px solid black;padding: 7px;border-left: 0px;text-align: center"
                            width="40%">
                            <div style="margin-top: 10px">
                                <label style="font-weight:bold">Tanda Tangan Konselor Menyusui</label>
                            </div>
                            <div style="margin-top: 7px;height: 140px">
                                <img src="{{ $data['TTDKonselor'] }}" width="170" height="140" alt="TTD" />
                            </div>
                        </td>
                    </tr>
                </table>
            </table>
        </table>
    </section>
</body>

</html>
<script type="text/javascript">
    // $(document).ready(function() {
    //     window.print();
    // });
</script>