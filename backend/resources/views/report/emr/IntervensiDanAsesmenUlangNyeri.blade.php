<!DOCTYPE html>
<html>

<head>
    <style>
        * {
            font-family: Arial, Helvetica, sans-serif;
        }
        .checkbox-wrapper {
            white-space: nowrap
        }

        .checkbox {
            vertical-align: top;
            display: inline-block
        }

        .checkbox-label {
            white-space: normal display:inline-block
        }
        .bg-blue {
            background-color: #91CEDE;
        }
        input[type=checkbox] {
            margin-bottom: -5px;
            margin-right: 5px;
        }
        .bg-gray {
            background-color: #d3d3d3;
            text-align: center;
            font-weight: bold;
        }

        .padding-y {
            padding: 10px 0;
        }

        .dikit {
            margin-left: 5px;
        }
        .bottom {
            border-bottom: none;
        }
        .top {
            border-top: none;
        }
        .text-center {
            text-align: center;
        }
        .logo {
            font-family: Dejavu Sans;
        }

    </style>
</head>

<body>
    @php

        $imgDefault =
        'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAJYAAACWCAYAAAA8AXHiAAAAAXNSR0IArs4c6QAAAqFJREFUeF7t0jENAAAMw7CVP+mhyOcC6BF5ZwoEBRZ8ulTgwIIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjp9QYIAl6bSsVAAAAAASUVORK5CYII=';

        function convertToMakassarDate($isoDateString)
        {
            $date = new DateTime($isoDateString, new DateTimeZone('UTC'));
            $date->setTimezone(new DateTimeZone('Asia/Jakarta'));
            return $date->format('d-m-Y H:i:s');
        }
    @endphp
        <table width="100%" cellspacing="0" cellpadding="0" border="1">
            <tr>
                <td width="60%" style="text-align:right" colspan=2>
                    <table width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr class="bg-blue">
                            <td>
                                <td width="50%" style="text-align:left; font-size: 14px">RSUD BALI MANDARA</td>
                                <td width="50%" style="font-size: 14px; text-align: right;">RM 12/SIR/01</td>
                            </td>
                        </tr>
                    </tabel>
                </td>
                <!-- <td width="60%"></td> -->
            </tr>
        </table>

        <table width="100%" cellspacing="0" cellpadding="0" border="0" style="border-top: 1px solid black">
            <tr>
                <td width="10%" style="padding: 5px; border-right: 1px solid black;">
                    <img src="{{ 'img/logo-rs.png' }}" style="width: 90px;">
                </td>
                <td style="text-align: center" style="border-right: 1px solid black;">
                    <b>
                        <span style="font-size: 16px">INTERVENSI DAN ASESMEN ULANG NYERI</span>
                    </b>
                </td>
                <td width="35%" style="padding: 10px; border-left: 1px solid black;">
                    <div class="box" style="text-align: left">
                        <table style="padding: 3px;">
                            
                            <tr>
                                <td class="f-s-15 bold  text-top">Nama</td>
                                <td class="f-s-15 bold  text-top">:</td>
                                <td class="f-s-15 bold  text-top"><b>{{ $pasien['namapasien'] }}</b>
                                </td>
                            </tr>
                            <tr>
                                <td class="f-s-15 bold  text-top">Tgl Lahir</td>
                                <td class="f-s-15 bold  text-top">:</td>
                                <td class="f-s-15 bold  text-top">
                                    <b>{{ $pasien['tgllahir'] }}</b>
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
                                <td class="f-s-15 bold  text-top" style="width: 100px">No. RM</td>
                                <td class="f-s-15 bold  text-top">:</td>
                                <td class="f-s-15 bold text-top"><b>{{ $pasien['nocm'] }}</b></td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
            
        </table>
        <table width="100%" cellspacing="0" cellpadding="0" border="1">
            <tr class="bg-gray">
                <th width="25%">Skor Nyeri</th>
                <th width="25%">Pasero-Mc Caffery Opioid-Induced Sedation Scale (POSS)</th>
                <th width="25%">Intervensi Non-Farmakologi</th>
                <th width="25%">Pengkajian Ulang</th>
            </tr>
            <tr>
                <td rowspan="2">
                    <div class="dikit"><b>(NRS, WBS, FLACC):</b></div>
                    <div class="dikit">0 = Tidak nyeri</div>
                    <div class="dikit">1-4 = Nyeri ringan</div>
                    <div class="dikit">5-6 = Nyeri sedang</div>
                    <div class="dikit">7-10 = Nyeri berat</div>
                    <br>
                    <div class="dikit"><b>BPS:</b></div>
                    <div class="dikit">&lt;5 = Pasien bebas nyeri</div>
                    <div class="dikit"><span class="logo">&ge;</span>5 = Pasien nyeri perlu diterapi</div>
                    <br>
                    <div class="dikit"><b>NPA:</b></div>
                    <div class="dikit">&lt;5 = Nyeri ringan (Nurse Comfort Measure)</div>
                    <div class="dikit">&gt;5 = Nyeri sedang (Paracetamol)</div>
                    <div class="dikit">&gt;10 = Nyeri berat (NCM, paracetamol, narkotik)</div>
                </td>
                <td class="bottom">
                    <div class="dikit">4 : Somnolent, minimal/tidak respon terhadap rangsangan fisik</div>
                    <div class="dikit">3 : Sering mengantuk, bisa dibangunkan, mudah tertidur saat sedang bicara</div>
                    <div class="dikit">2 : Agak mengantuk, mudah dibangunkan</div>
                    <div class="dikit">1 : Bangun dan sadar</div>
                    <div class="dikit">S : Tidur, mudah dibangunkan</div>
                    <div class="dikit">0 : Tidak menggunakan sedasi</div>
                </td>
                <td class="bottom">
                    <div class="dikit">1 : Pemberian kompres dingin</div>
                    <div class="dikit">2 : Pemberian kompres hangat</div>
                    <div class="dikit">3 : Pemberian posisi</div>
                    <div class="dikit">4 : Pijat</div>
                    <div class="dikit">5 : Pemberian terapi musik</div>
                    <div class="dikit">6 : TENS</div>
                    <div class="dikit">7 : Relaksasi dan pernapasan</div>
                </td>
                <td class="bottom">
                    <div class="dikit">1 : Nyeri ringan diulang setiap 6 jam</div>
                    <div class="dikit">2 : Nyeri sedang, setiap 60 menit setelah obat diberikan sampai intensitas nyeri ringan yang tolerable</div>
                    <div class="dikit">3 : Nyeri berat setiap 30 menit setelah obat diberikan sampai intensitas nyeri ringan yang tolerable</div>
                </td>
            </tr>
            <tr>
                <td class="top"></td>
                <td class="top"></td>
                <td class="top"></td>
            </tr>
        </table>
        <table  cellspacing="0" cellpadding="0" border="1">
            <tr>
                <td class="bg-gray" rowspan="2">Tanggal & Jam</td>
                <td class="bg-gray" rowspan="2">Skor Nyeri</td>
                <td class="bg-gray" rowspan="2">Skor Sedasi</td>
                <td class="bg-gray" rowspan="2">Tekanan Darah</td>
                <td class="bg-gray" rowspan="2">Nadi</td>
                <td class="bg-gray" rowspan="2">Suhu</td>
                <td class="bg-gray" rowspan="2">Respirasi</td>
                <td class="bg-gray" colspan="2">Perawat / Bidan</td>
                <td class="bg-gray" rowspan="2">Tanggal & Jam</td>
                <td class="bg-gray" colspan="3">Intervensi Farmakologi</td>
                <td class="bg-gray" rowspan="2">Intervensi Non Farmakologi</td>
                <td class="bg-gray" colspan="2">Perawat / Bidan</td>
                <td class="bg-gray" rowspan="2">Wakti Kaji Ulang</td>
            </tr>
            <tr>
                <td class="bg-gray">Nama Perawat / Bidan</td>
                <td class="bg-gray">Paraf</td>
                <td class="bg-gray">Nama Obat</td>
                <td class="bg-gray">Dosis & Frekuensi</td>
                <td class="bg-gray">Rute</td>
                <td class="bg-gray">Nama Perawat / Bidan</td>
                <td class="bg-gray">Paraf</td>
            </tr>
              @foreach ($data['details'] as $key => $item)
              <tr class="text-center">
                <td>
                    {{ isset($item['tanggalJam']) ? convertToMakassarDate($item['tanggalJam']) : ' ' }}
                </td>
                <td>
                    {{ isset($item['skornyeri']) ? $item['skornyeri'] : '' }}
                </td>
                <td>
                    {{ isset($item['skorsedasi']) ? $item['skorsedasi'] : '' }}
                </td>
                <td>
                    {{ isset($item['tekanandarah']) ? $item['tekanandarah'] : '' }}
                </td>
                <td>
                    {{ isset($item['nadi']) ? $item['nadi'] : '' }}
                </td>
                <td>
                    {{ isset($item['suhu']) ? $item['suhu'] : '' }}
                </td>
                <td>
                    {{ isset($item['respirasi']) ? $item['respirasi'] : '' }}
                </td>
                <td style="/* width: 10% */" colspan="2">
                    {{ isset($item['parafPerawatNama']['label']) ? $item['parafPerawatNama']['label'] : ' ' }}
                </td>
                {{-- <td>
                    @if (isset($item['parafPerawat']) && $item['parafPerawat'] != $imgDefault)
                        <img style="width: 100px;height: 100px;" src="{{ $item['parafPerawat'] }}">
                    @endif
                </td> --}}
                <td>
                    {{ isset($item['tanggalJamKaji']) ? convertToMakassarDate($item['tanggalJamKaji']) : ' ' }}
                </td>
                <td>
                    {{ isset($item['namaObat']['namaproduk']) ? $item['namaObat']['namaproduk'] : '' }}
                </td>
                <td>
                    {{ isset($item['dosisFrekuensi']) ? $item['dosisFrekuensi'] : '' }}
                </td>
                <td>
                    {{ isset($item['rute']) ? $item['rute'] : '' }}
                </td>
                <td>
                    {{ isset($item['intervensiNonFarmakologi']) ? $item['intervensiNonFarmakologi'] : '' }}
                </td>
                <td style="/* width: 10% */" colspan="2">
                    {{ isset($item['parafBidanNama']['label']) ? $item['parafBidanNama']['label'] : ' ' }}
                </td>
                {{-- <td>
                    @if (isset($item['parafBidan']) && $item['parafBidan'] != $imgDefault)
                        <img style="width: 100px;height: 100px;" src="{{ $item['parafBidan'] }}">
                    @endif
                </td> --}}
                <td>
                    {{ isset($item['waktuKajiUlang']) ? convertToMakassarDate($item['waktuKajiUlang']) : ' ' }}

                </td>
              </tr>
              @endforeach
        </table>
                                

</body>

</html>
