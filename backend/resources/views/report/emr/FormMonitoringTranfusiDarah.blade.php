<!DOCTYPE html>
<html>

<head>
    <style>
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
    </style>
</head>

<body>
        <table width="100%" cellspacing="0" cellpadding="0" border="1" style="border-top: 1px solid black">
            <tr>
                <td width="60%" style="text-align:right" colspan=2>
                    <table width="100%" cellspacing="0" cellpadding="0" border="1" style="border-top: 1px solid black">
                        <tr class="bg-blue">
                            <td>
                                <td width="50%" style="text-align:left; font-size: 14px">RSUD BALI MANDARA</td>
                                <td width="50%" style="font-size: 14px; text-align: right;">RM 10/SIR/00</td>
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
                        <span style="font-size: 16px">MONITORING TRANSFUSI DARAH/PRODUK DARAH
                        </span>
                    </b><br>
                    <span style="font-size: 16px">UNTUK SATU UNIT DARAH/PRODUK DARAH
                    </span>
                </td>
                <td width="35%" style="padding: 10px; border-left: 1px solid black;">
                    <div class="box" style="text-align: left">
                        <table style="padding: 3px;">
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
        <table width="100%" cellspacing="0" cellpadding="0" style="border: 1px solid black; border-collapse: collapse" border ="0">
            <tr>
                <td>
                    <div style="padding:7px">
                        <div style="margin-left: 20px;margin-top: 10px;margin-bottom: 10px">
                            <table width="100%" style="border-spacing: 0px;">
                                <tr>
                                    <td width="25%"> Jenis Komponen Darah </td>
                                    <td width="25%">:  {{ isset($data['JenisKomponenDarah']) ? $data['JenisKomponenDarah'] : '-' }}</td>
                                    <td width="25%"> Pengirim/ pemberi Darah  </td>
                                </tr>
                                <tr>
                                    <td width="25%">Nomor Kantong Darah</td>
                                    <td width="25%">: {{ isset($data['NoKantongDarah']) ? $data['NoKantongDarah'] : '-' }}</td>
                                    <td width="25%">Waktu</td>
                                    <td width="25%">: {{ isset($data['tglKirimDarah']) ? date("d F Y H:i:s", strtotime($data['tglKirimDarah'])) : '-' }}</td>
                                </tr>
                                <tr>
                                    <td width="25%">Volume Transfusi</td>
                                    <td width="25%">: {{ isset($data['VolumeTransfusi']) ? $data['VolumeTransfusi'] : '-' }}</td>
                                    <td width="25%">Nama</td>
                                    <td width="25%">: {{ isset($data['PengirimDarah']) ? $data['PengirimDarah'] : '-' }}</td>
                                </tr>
                                <tr>
                                    <td width="25%"></td>
                                    <td width="25%"></td>
                                    <td width="25%">Tanda Tangan</td>
                                    <td class="tc vt" width="25%">
                                        @isset($data['PenerimaDarah'])
                                        <img src="data:image/svg+xml;base64,{{ base64_encode(QrCode::format('svg')->margin(2)->size(50)->generate($data['PenerimaDarah'] ?? '')) }}" 
                                        alt="QR Code">
                                        @endisset
                                    </td>
                                </tr>
                                <tr>
                                    <td width="25%"></td>
                                    <td width="25%"></td>
                                    <td width="25%"><b>Penerima darah</b></td>
                                </tr>
                                <tr>
                                    <td width="25%"></td>
                                    <td width="25%"></td>
                                    <td width="25%">Waktu</td>
                                    <td width="25%">: {{ isset($data['tglTerimaDarah']) ? date("d F Y H:i:s", strtotime($data['tglTerimaDarah'])) : '-' }}</td>
                                </tr>
                                <tr>
                                    <td width="25%"></td>
                                    <td width="25%"></td>
                                    <td width="25%">Nama</td>
                                    <td width="25%">: {{ isset($data['PenerimaDarah']) ? $data['PenerimaDarah'] : '-' }}</td>
                                </tr>
                                <tr>
                                    <td width="25%"></td>
                                    <td width="25%"></td>
                                    <td width="25%">Tanda Tangan</td>
                                    <td class="tc vt" width="25%">
                                        @isset($data['PenerimaDarah'])
                                        <img src="data:image/svg+xml;base64,{{ base64_encode(QrCode::format('svg')->margin(2)->size(50)->generate($data['PenerimaDarah'] ?? '')) }}" 
                                        alt="QR Code">
                                        @endisset
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>       
                </td> 
            </tr>    
        </table>
        <table width="100%" cellspacing="0" cellpadding="0" style="border: 1px solid black; border-collapse: collapse;table-layout: fixed; " border ="1">
            <tr>
                <td width="10%" rowspan="2" style="text-align: center; font-size: 14px;">
                    <span><b>Kondisi</b></span>
                </td>
                <td width="10%" rowspan="1" style="text-align: center; font-size: 14px;">
                    <span><b>Sebelum Transfusi dimulai</b> </span>
                </td>
                <td width="10%" rowspan="1" style="text-align: center; font-size: 14px;">
                    <span><b>15 menit Setelah Transfusi dimulai</b></span>
                </td>
                <td width="10%" colspan="4" style="text-align: center; font-size: 14px;">
                    <span><b>Setiap Jam </b></span>
                </td>
                <td width="10%" rowspan="1" style="text-align: center; font-size: 14px;">
                    <span><b>Saat Transfusi Berakhir</b></span>
                </td>
                <td width="10%" rowspan="1" style="text-align: center; font-size: 14px;">
                    <span><b>4 Jam Setelah Transfusi</b></span>
                </td>
            </tr>
            <tr>
                <td style="width: 10%; font-size: 14px;">
                    {{ isset($data['jamSebelumTransfusi']) && date('Y', strtotime($data['jamSebelumTransfusi'])) !== '1970' ? date('H:i:s', strtotime($data['jamSebelumTransfusi'])) : '-' }}
                </td>
                <td style="width: 10%; font-size: 14px;">
                    {{ isset($data['Jam15menitSebelumTransfusi']) && date('Y', strtotime($data['Jam15menitSebelumTransfusi'])) !== '1970' ? date('H:i:s', strtotime($data['Jam15menitSebelumTransfusi'])) : '-' }}
                </td>
                <td style="width: 10%; font-size: 14px;"><span><b>JAM I</b></span></td>
                <td style="width: 10%; font-size: 14px;"><span><b>JAM II</b></span></td>
                <td style="width: 10%; font-size: 14px;"><span><b>JAM III</b></span></td>
                <td style="width: 10%; font-size: 14px;"><span><b>JAM IV</b></span></td>
                <td style="width: 10%; font-size: 14px;">
                    {{ isset($data['jamTransfusiberakhir']) && date('Y', strtotime($data['jamTransfusiberakhir'])) !== '1970' ? date('H:i:s', strtotime($data['jamTransfusiberakhir'])) : '-' }}
                </td>
                <td style="width: 10%; font-size: 14px;">
                    {{ isset($data['jamSetelahTransfusi']) && date('Y', strtotime($data['jamSetelahTransfusi'])) !== '1970' ? date('H:i:s', strtotime($data['jamSetelahTransfusi'])) : '-' }}
                </td>
            </tr>
            <tr>
                <td><span>Keadaan Umum:</span></td>
                <td><span>{{ isset($data['details'][0]['kantong11']) ? $data['details'][0]['kantong11'] : '' }}</span></td>
                <td><span>{{ isset($data['details'][0]['tekananDarah']) ? $data['details'][0]['tekananDarah'] : '' }}</span></td>
                <td><span>{{ isset($data['details'][0]['nadi']) ? $data['details'][0]['nadi'] : '' }}</span></td>
                <td><span>{{ isset($data['details'][0]['pernapasan']) ? $data['details'][0]['pernapasan'] : '' }}</span></td>
                <td><span>{{ isset($data['details'][0]['suhu']) ? $data['details'][0]['suhu'] : '' }}</span></td>
                <td><span>{{ isset($data['details'][0]['lokasiInsersi']) ? $data['details'][0]['lokasiInsersi'] : '' }}</span></td>
                <td><span>{{ isset($data['details'][0]['jam23']) ? $data['details'][0]['jam23'] : '' }}</span></td>
                <td><span>{{ isset($data['details'][0]['test1']) ? $data['details'][0]['test1'] : '' }}</span></td>
            </tr>
            <tr>
                <td><span>Suhu:</span></td>
                <td><span>{{ isset($data['details'][0]['kantong1']) ? $data['details'][0]['kantong1'] : '' }}</span></td>
                <td><span>{{ isset($data['details'][0]['tekananDarah1']) ? $data['details'][0]['tekananDarah1'] : '' }}</span></td>
                <td><span>{{ isset($data['details'][0]['nadi1']) ? $data['details'][0]['nadi1'] : '' }}</span></td>
                <td><span>{{ isset($data['details'][0]['pernapasan1']) ? $data['details'][0]['pernapasan1'] : '' }}</span></td>
                <td><span>{{ isset($data['details'][0]['suhu1']) ? $data['details'][0]['suhu1'] : '' }}</span></td>
                <td><span>{{ isset($data['details'][0]['lokasiInsersi1']) ? $data['details'][0]['lokasiInsersi1'] : '' }}</span></td>
                <td><span>{{ isset($data['details'][0]['jam34']) ? $data['details'][0]['jam34'] : '' }}</span></td>
                <td><span>{{ isset($data['details'][0]['test']) ? $data['details'][0]['test'] : '' }}</span></td>
            </tr>
            <tr>
                <td><span>Nadi:</span></td>
                <td><span>{{ isset($data['details'][0]['kantong2']) ? $data['details'][0]['kantong2'] : '' }}</span></td>
                <td><span>{{ isset($data['details'][0]['tekananDarah2']) ? $data['details'][0]['tekananDarah2'] : '' }}</span></td>
                <td><span>{{ isset($data['details'][0]['nadi2']) ? $data['details'][0]['nadi2'] : '' }}</span></td>
                <td><span>{{ isset($data['details'][0]['pernapasan2']) ? $data['details'][0]['pernapasan2'] : '' }}</span></td>
                <td><span>{{ isset($data['details'][0]['suhu2']) ? $data['details'][0]['suhu2'] : '' }}</span></td>
                <td><span>{{ isset($data['details'][0]['lokasiInsersi2']) ? $data['details'][0]['lokasiInsersi2'] : '' }}</span></td>
                <td><span>{{ isset($data['details'][0]['jam1']) ? $data['details'][0]['jam1'] : '' }}</span></td>
                <td><span>{{ isset($data['details'][0]['test12']) ? $data['details'][0]['test12'] : '' }}</span></td>
            </tr>
            <tr>
                <td><span>Tekanan Darah:</span></td>
                <td><span>{{ isset($data['details'][0]['kantong3']) ? $data['details'][0]['kantong3'] : '' }}</span></td>
                <td><span>{{ isset($data['details'][0]['tekananDarah3']) ? $data['details'][0]['tekananDarah3'] : '' }}</span></td>
                <td><span>{{ isset($data['details'][0]['nadi3']) ? $data['details'][0]['nadi3'] : '' }}</span></td>
                <td><span>{{ isset($data['details'][0]['pernapasan3']) ? $data['details'][0]['pernapasan3'] : '' }}</span></td>
                <td><span>{{ isset($data['details'][0]['suhu3']) ? $data['details'][0]['suhu3'] : '' }}</span></td>
                <td><span>{{ isset($data['details'][0]['lokasiInsersi3']) ? $data['details'][0]['lokasiInsersi3'] : '' }}</span></td>
                <td><span>{{ isset($data['details'][0]['jam00']) ? $data['details'][0]['jam00'] : '' }}</span></td>
                <td><span>{{ isset($data['details'][0]['test90']) ? $data['details'][0]['test90'] : '' }}</span></td>
            </tr>
            <tr>
                <td><span>Respiratory rate:</span></td>
                <td><span>{{ isset($data['details'][0]['kantong4']) ? $data['details'][0]['kantong4'] : '' }}</span></td>
                <td><span>{{ isset($data['details'][0]['tekananDarah4']) ? $data['details'][0]['tekananDarah4'] : '' }}</span></td>
                <td><span>{{ isset($data['details'][0]['nadi4']) ? $data['details'][0]['nadi4'] : '' }}</span></td>
                <td><span>{{ isset($data['details'][0]['pernapasan4']) ? $data['details'][0]['pernapasan4'] : '' }}</span></td>
                <td><span>{{ isset($data['details'][0]['suhu4']) ? $data['details'][0]['suhu4'] : '' }}</span></td>
                <td><span>{{ isset($data['details'][0]['lokasiInsersi4']) ? $data['details'][0]['lokasiInsersi4'] : '' }}</span></td>
                <td><span>{{ isset($data['details'][0]['jam567']) ? $data['details'][0]['jam567'] : '' }}</span></td>
                <td><span>{{ isset($data['details'][0]['test34']) ? $data['details'][0]['test34'] : '' }}</span></td>
            </tr>
            <tr>
                <td><span>Produksi urine dan Warna:</span></td>
                <td><span>{{ isset($data['details'][0]['kantong5']) ? $data['details'][0]['kantong5'] : '' }}</span></td>
                <td><span>{{ isset($data['details'][0]['tekananDarah5']) ? $data['details'][0]['tekananDarah5'] : '' }}</span></td>
                <td><span>{{ isset($data['details'][0]['nadi5']) ? $data['details'][0]['nadi5'] : '' }}</span></td>
                <td><span>{{ isset($data['details'][0]['pernapasan5']) ? $data['details'][0]['pernapasan5'] : '' }}</span></td>
                <td><span>{{ isset($data['details'][0]['suhu5']) ? $data['details'][0]['suhu5'] : '' }}</span></td>
                <td><span>{{ isset($data['details'][0]['lokasiInsersi5']) ? $data['details'][0]['lokasiInsersi5'] : '' }}</span></td>
                <td><span>{{ isset($data['details'][0]['jam2367']) ? $data['details'][0]['jam2367'] : '' }}</span></td>
                <td><span>{{ isset($data['details'][0]['test78']) ? $data['details'][0]['test78'] : '' }}</span></td>
            </tr>
            <tr>
                <td><span>Tanda Reaksi Transfusi:</span></td>
                <td style="text-align:center">
                    <table style="border: none; border-spacing: 5px;">
                        <tr>
                            <td style="border: 1px solid black; width: 15px;height: 20px; text-align: center;">{{ isset($data['reaksiTransfusi1']) ? $data['reaksiTransfusi1'] : '' }}</td>
                            <td style="border: 1px solid black; width: 15px;height: 20px; text-align: center;">{{ isset($data['reaksiTransfusi2']) ? $data['reaksiTransfusi2'] : '' }}</td>
                            <td style="border: 1px solid black; width: 15px;height: 20px; text-align: center;">{{ isset($data['reaksiTransfusi3']) ? $data['reaksiTransfusi3'] : '' }}</td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid black; width: 15px;height: 20px; text-align: center;">{{ isset($data['reaksiTransfusi4']) ? $data['reaksiTransfusi4'] : '' }}</td>
                            <td style="border: 1px solid black; width: 15px;height: 20px; text-align: center;">{{ isset($data['reaksiTransfusi5']) ? $data['reaksiTransfusi5'] : '' }}</td>
                            <td style="border: 1px solid black; width: 15px;height: 20px; text-align: center;">{{ isset($data['reaksiTransfusi6']) ? $data['reaksiTransfusi6'] : '' }}</td>
                        </tr>
                    </table>
                </td>
                <td style="text-align:center">
                    <table style="border: none; border-spacing: 5px;">
                        <tr>
                            <td style="border: 1px solid black; width: 15px;height: 20px; text-align: center;">{{ isset($data['reaksiTransfusi7']) ? $data['reaksiTransfusi7'] : '' }}</td>
                            <td style="border: 1px solid black; width: 15px;height: 20px; text-align: center;">{{ isset($data['reaksiTransfusi8']) ? $data['reaksiTransfusi8'] : '' }}</td>
                            <td style="border: 1px solid black; width: 15px;height: 20px; text-align: center;">{{ isset($data['reaksiTransfusi9']) ? $data['reaksiTransfusi9'] : '' }}</td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid black; width: 15px;height: 20px; text-align: center;">{{ isset($data['reaksiTransfusi10']) ? $data['reaksiTransfusi10'] : '' }}</td>
                            <td style="border: 1px solid black; width: 15px;height: 20px; text-align: center;">{{ isset($data['reaksiTransfusi11']) ? $data['reaksiTransfusi11'] : '' }}</td>
                            <td style="border: 1px solid black; width: 15px;height: 20px; text-align: center;">{{ isset($data['reaksiTransfusi12']) ? $data['reaksiTransfusi12'] : '' }}</td>
                        </tr>
                    </table>
                </td>
                <td style="text-align:center">
                    <table style="border: none; border-spacing: 5px;">
                        <tr>
                            <td style="border: 1px solid black; width: 15px;height: 20px; text-align: center;">{{ isset($data['reaksiTransfusi13']) ? $data['reaksiTransfusi13'] : '' }}</td>
                            <td style="border: 1px solid black; width: 15px;height: 20px; text-align: center;">{{ isset($data['reaksiTransfusi14']) ? $data['reaksiTransfusi14'] : '' }}</td>
                            <td style="border: 1px solid black; width: 15px;height: 20px; text-align: center;">{{ isset($data['reaksiTransfusi15']) ? $data['reaksiTransfusi15'] : '' }}</td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid black; width: 15px;height: 20px; text-align: center;">{{ isset($data['reaksiTransfusi16']) ? $data['reaksiTransfusi16'] : '' }}</td>
                            <td style="border: 1px solid black; width: 15px;height: 20px; text-align: center;">{{ isset($data['reaksiTransfusi17']) ? $data['reaksiTransfusi17'] : '' }}</td>
                            <td style="border: 1px solid black; width: 15px;height: 20px; text-align: center;">{{ isset($data['reaksiTransfusi18']) ? $data['reaksiTransfusi18'] : '' }}</td>
                        </tr>
                    </table>
                </td>
                <td style="text-align:center">
                    <table style="border: none; border-spacing: 5px;">
                        <tr>
                            <td style="border: 1px solid black; width: 15px;height: 20px; text-align: center;">{{ isset($data['reaksiTransfusi19']) ? $data['reaksiTransfusi19'] : '' }}</td>
                            <td style="border: 1px solid black; width: 15px;height: 20px; text-align: center;">{{ isset($data['reaksiTransfusi20']) ? $data['reaksiTransfusi20'] : '' }}</td>
                            <td style="border: 1px solid black; width: 15px;height: 20px; text-align: center;">{{ isset($data['reaksiTransfusi21']) ? $data['reaksiTransfusi21'] : '' }}</td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid black; width: 15px;height: 20px; text-align: center;">{{ isset($data['reaksiTransfusi22']) ? $data['reaksiTransfusi22'] : '' }}</td>
                            <td style="border: 1px solid black; width: 15px;height: 20px; text-align: center;">{{ isset($data['reaksiTransfusi23']) ? $data['reaksiTransfusi23'] : '' }}</td>
                            <td style="border: 1px solid black; width: 15px;height: 20px; text-align: center;">{{ isset($data['reaksiTransfusi24']) ? $data['reaksiTransfusi24'] : '' }}</td>
                        </tr>
                    </table>
                </td>
                <td style="text-align:center">
                    <table style="border: none; border-spacing: 5px;">
                        <tr>
                            <td style="border: 1px solid black; width: 15px;height: 20px; text-align: center;">{{ isset($data['reaksiTransfusi25']) ? $data['reaksiTransfusi25'] : '' }}</td>
                            <td style="border: 1px solid black; width: 15px;height: 20px; text-align: center;">{{ isset($data['reaksiTransfusi26']) ? $data['reaksiTransfusi26'] : '' }}</td>
                            <td style="border: 1px solid black; width: 15px;height: 20px; text-align: center;">{{ isset($data['reaksiTransfusi27']) ? $data['reaksiTransfusi27'] : '' }}</td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid black; width: 15px;height: 20px; text-align: center;">{{ isset($data['reaksiTransfusi28']) ? $data['reaksiTransfusi28'] : '' }}</td>
                            <td style="border: 1px solid black; width: 15px;height: 20px; text-align: center;">{{ isset($data['reaksiTransfusi29']) ? $data['reaksiTransfusi29'] : '' }}</td>
                            <td style="border: 1px solid black; width: 15px;height: 20px; text-align: center;">{{ isset($data['reaksiTransfusi30']) ? $data['reaksiTransfusi30'] : '' }}</td>
                        </tr>
                    </table>
                </td>
                <td style="text-align:center">
                    <table style="border: none; border-spacing: 5px;">
                        <tr>
                            <td style="border: 1px solid black; width: 15px;height: 20px; text-align: center;">{{ isset($data['reaksiTransfusi31']) ? $data['reaksiTransfusi31'] : '' }}</td>
                            <td style="border: 1px solid black; width: 15px;height: 20px; text-align: center;">{{ isset($data['reaksiTransfusi32']) ? $data['reaksiTransfusi32'] : '' }}</td>
                            <td style="border: 1px solid black; width: 15px;height: 20px; text-align: center;">{{ isset($data['reaksiTransfusi33']) ? $data['reaksiTransfusi33'] : '' }}</td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid black; width: 15px;height: 20px; text-align: center;">{{ isset($data['reaksiTransfusi34']) ? $data['reaksiTransfusi34'] : '' }}</td>
                            <td style="border: 1px solid black; width: 15px;height: 20px; text-align: center;">{{ isset($data['reaksiTransfusi35']) ? $data['reaksiTransfusi35'] : '' }}</td>
                            <td style="border: 1px solid black; width: 15px;height: 20px; text-align: center;">{{ isset($data['reaksiTransfusi36']) ? $data['reaksiTransfusi36'] : '' }}</td>
                        </tr>
                    </table>
                </td>
                <td style="text-align:center">
                    <table style="border: none; border-spacing: 5px;">
                        <tr>
                            <td style="border: 1px solid black; width: 15px;height: 20px; text-align: center;">{{ isset($data['reaksiTransfusi37']) ? $data['reaksiTransfusi37'] : '' }}</td>
                            <td style="border: 1px solid black; width: 15px;height: 20px; text-align: center;">{{ isset($data['reaksiTransfusi38']) ? $data['reaksiTransfusi38'] : '' }}</td>
                            <td style="border: 1px solid black; width: 15px;height: 20px; text-align: center;">{{ isset($data['reaksiTransfusi39']) ? $data['reaksiTransfusi39'] : '' }}</td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid black; width: 15px;height: 20px; text-align: center;">{{ isset($data['reaksiTransfusi40']) ? $data['reaksiTransfusi40'] : '' }}</td>
                            <td style="border: 1px solid black; width: 15px;height: 20px; text-align: center;">{{ isset($data['reaksiTransfusi41']) ? $data['reaksiTransfusi41'] : '' }}</td>
                            <td style="border: 1px solid black; width: 15px;height: 20px; text-align: center;">{{ isset($data['reaksiTransfusi42']) ? $data['reaksiTransfusi42'] : '' }}</td>
                        </tr>
                    </table>
                </td>
                <td style="text-align:center">
                    <table style="border: none; border-spacing: 5px;">
                        <tr>
                            <td style="border: 1px solid black; width: 15px;height: 20px; text-align: center;">{{ isset($data['reaksiTransfusi43']) ? $data['reaksiTransfusi43'] : '' }}</td>
                            <td style="border: 1px solid black; width: 15px;height: 20px; text-align: center;">{{ isset($data['reaksiTransfusi44']) ? $data['reaksiTransfusi44'] : '' }}</td>
                            <td style="border: 1px solid black; width: 15px;height: 20px; text-align: center;">{{ isset($data['reaksiTransfusi45']) ? $data['reaksiTransfusi45'] : '' }}</td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid black; width: 15px;height: 20px; text-align: center;">{{ isset($data['reaksiTransfusi46']) ? $data['reaksiTransfusi46'] : '' }}</td>
                            <td style="border: 1px solid black; width: 15px;height: 20px; text-align: center;">{{ isset($data['reaksiTransfusi47']) ? $data['reaksiTransfusi47'] : '' }}</td>
                            <td style="border: 1px solid black; width: 15px;height: 20px; text-align: center;">{{ isset($data['reaksiTransfusi48']) ? $data['reaksiTransfusi48'] : '' }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="width: 10%;"><span>Petugas nama:</span></td>
                <td style="width: 10%;text-align:center;"><span style="font-size: 7pt;">{{ isset($data['Petugas1']['label']) ? $data['Petugas1']['label'] : ' ' }}</span></td>
                <td style="width: 10%;text-align:center;"><span style="font-size: 7pt;">{{ isset($data['Petugas2']['label']) ? $data['Petugas2']['label'] : ' ' }}</span></td>
                <td style="width: 10%;text-align:center;"><span style="font-size: 7pt;">{{ isset($data['Petugas3']['label']) ? $data['Petugas3']['label'] : ' ' }}</span></td>
                <td style="width: 10%;text-align:center;"><span style="font-size: 7pt;">{{ isset($data['Petugas4']['label']) ? $data['Petugas4']['label'] : ' ' }}</span></td>
                <td style="width: 10%;text-align:center;"><span style="font-size: 7pt;">{{ isset($data['Petugas5']['label']) ? $data['Petugas5']['label'] : ' ' }}</span></td>
                <td style="width: 10%;text-align:center;"><span style="font-size: 7pt;">{{ isset($data['Petugas6']['label']) ? $data['Petugas6']['label'] : ' ' }}</span></td>
                <td style="width: 10%;text-align:center;"><span style="font-size: 7pt;">{{ isset($data['Petugas7']['label']) ? $data['Petugas7']['label'] : ' ' }}</span></td>
                <td style="width: 10%;text-align:center;"><span style="font-size: 7pt;">{{ isset($data['Petugas8']['label']) ? $data['Petugas8']['label'] : ' ' }}</span></td>
            </tr>
            <tr>
                <td><span>Paraf/tanda tangan:</span></td>
                <td>
                    @isset($data['Petugas1']['label'])
                    <img src="data:image/svg+xml;base64,{{ base64_encode(QrCode::format('svg')->margin(2)->size(70)->generate($data['Petugas1']['label'] ?? '')) }}" 
                    alt="QR Code">
                    @endisset
                </td>
                <td>
                    @isset($data['Petugas2']['label'])
                    <img src="data:image/svg+xml;base64,{{ base64_encode(QrCode::format('svg')->margin(2)->size(70)->generate($data['Petugas2']['label'] ?? '')) }}" 
                    alt="QR Code">
                    @endisset
                </td>
                <td>
                    @isset($data['Petugas3']['label'])
                    <img src="data:image/svg+xml;base64,{{ base64_encode(QrCode::format('svg')->margin(2)->size(70)->generate($data['Petugas3']['label'] ?? '')) }}" 
                    alt="QR Code">
                    @endisset
                </td>
                <td>
                    @isset($data['Petugas4']['label'])
                    <img src="data:image/svg+xml;base64,{{ base64_encode(QrCode::format('svg')->margin(2)->size(70)->generate($data['Petugas4']['label'] ?? '')) }}" 
                    alt="QR Code">
                    @endisset
                </td>
                <td>
                    @isset($data['Petugas5']['label'])
                    <img src="data:image/svg+xml;base64,{{ base64_encode(QrCode::format('svg')->margin(2)->size(70)->generate($data['Petugas5']['label'] ?? '')) }}" 
                    alt="QR Code">
                    @endisset
                </td>
                <td>
                    @isset($data['Petugas6']['label'])
                    <img src="data:image/svg+xml;base64,{{ base64_encode(QrCode::format('svg')->margin(2)->size(70)->generate($data['Petugas6']['label'] ?? '')) }}" 
                    alt="QR Code">
                    @endisset
                </td>
                <td>
                    @isset($data['Petugas7']['label'])
                    <img src="data:image/svg+xml;base64,{{ base64_encode(QrCode::format('svg')->margin(2)->size(70)->generate($data['Petugas7']['label'] ?? '')) }}" 
                    alt="QR Code">
                    @endisset
                </td>
                <td>
                    @isset($data['Petugas8']['label'])
                    <img src="data:image/svg+xml;base64,{{ base64_encode(QrCode::format('svg')->margin(2)->size(70)->generate($data['Petugas8']['label'] ?? '')) }}" 
                    alt="QR Code">
                    @endisset
                </td>
            </tr>
        </table>
        <table width="100%" cellspacing="0" cellpadding="0" style="border: 1px solid black; border-collapse: collapse" border ="0">
            <tr>
                <td>
                    <div style="padding:7px">
                            <div style="margin-left: 20px;margin-top: 10px;margin-bottom: 10px">
                                <table width="100%" style="border-spacing: 0px;">
                                    <tr>
                                        <td width="100%" colspan="4"> Tanda tanda reaksi transfusi (ditulis nomor saja)</td>
                                    </tr>
                                    <tr>
                                        <td width="25%">(1) Urtikaria</td>
                                        <td width="25%">(2) Demam</td>
                                        <td width="25%">(3) Gatal</td>
                                        <td width="25%">(4) Hemoglobinuria</td>
                                    </tr>
                                    <tr>
                                        <td width="25%">(5) Nyeri dada</td>
                                        <td width="25%">(6) Nyeri kepala</td>
                                        <td width="25%">(7) Sesak</td>
                                        <td width="25%">(8) Syok</td>
                                    </tr>
                                </table>
                            </div>
                    </div>
                </td>
            </tr>
        </table>
                                

</body>

</html>
