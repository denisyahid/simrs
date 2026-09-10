<!DOCTYPE html>
<html lang="en">

<head>
    <title>Laporan Pihutang Pasien</title>
</head>

<body>
    <table class="custom-table" style="width: 100%" border="0">
        <tbody>
            <tr>
                {{-- <td>
                    <p>
                        @if ((isset($res['pdf']) && $res['pdf']) || (isset($res['storage']) && $res['storage']))
                            <img src="{{ 'img/logo-rs.png' }}" width="80px" border="0">
                        @else
                            <img src="{{ asset('img/logo-rs.png') }}" width="80px" border="0">
                        @endif
                    </p>
                </td> --}}
                <td colspan="14">
                    <p>
                        <font style="font-size: 12px;font-weight: 600;" color="#000000" >
                            {!! $profile->namalengkap !!} <br> {!! $profile->alamatlengkap !!} <br> {!! $profile->alamatemail !!}                                    
                        </font>
                    </p>
                </td>
            </tr>
        </tbody>
    </table>

    <div style="text-align:center">
        <h3 style="font-size: 14px"><u>DAFTAR PASIEN PIUTANG</u><br>Periode {{ $rangeDate[0] }} s/d {{ $rangeDate[1] }}</h3>
    </div>
    
    <br>

    <table class="custom-table fixed" style="width: 100%">
        <caption style="display: none"></caption>
        <thead>
            <tr>
                <th scope="col" width="100px">No</th>
                <th scope="col">Nama Pasien</th>
                <th scope="col">No RM</th>
                <th scope="col">No Registrasi</th>
                <th scope="col">Jenis Kelamin</th>
                <th scope="col">Pembayaran</th>
                <th scope="col">Tanggal Transaksi</th>
                <th scope="col">Total Harus Dibayar</th>
                <th scope="col">Total Dibayar</th>
                <th scope="col">Sisa</th>
                <th scope="col">Tanggal Dibayar</th>
                <th scope="col">Status Verifikasi</th>
                <th scope="col">Status Piutang</th>
                <th scope="col">Tanggal Registrasi</th>
                <th scope="col">Tanggal Pulang</th>
            </tr>
        </thead>
        <tbody>
            @php
                $grandtotal = 0;
            @endphp
            @foreach ($data as $key => $item)
                <tr>
                    <td width="100px">{{ $key+1 }}</td>
                    <td>{{ $item['namaPasien'] }}</td>
                    <td>{{ $item['nocm'] }}</td>
                    <td>{{ $item['noRegistrasi'] }}</td>
                    <td>{{ $item['jeniskelamin'] }}</td>
                    <td>{{ $item['jenisPasisen'] }}</td>
                    <td>{{ date('d M Y H:i:s', strtotime($item['tglTransaksi'])) }}</td>
                    <td>{{ \App\Traits\Valet::getMoneyFormatString($item['totalHarusDibayar']) }}</td>
                    <td>{{ \App\Traits\Valet::getMoneyFormatString($item['totalDibayar']) }}</td>
                    <td>{{ \App\Traits\Valet::getMoneyFormatString($item['sisa']) }}</td>
                    <td>{{ isset($item['tanggalLunas']) ? date('d M Y H:i:s', strtotime($item['tanggalLunas'])) : '-' }}</td>
                    <td>{{ $item['statusVerifikasi'] }}</td>
                    <td>{{ $item['statuspiutang'] }}</td>
                    <td>{{ $item['tglregistrasi'] }}</td>
                    <td>{{ $item['tglpulang'] }}</td>
                </tr>
                @php
                    $grandtotal += $item['totalHarusDibayar'];
                @endphp
            @endforeach
            <tr class="border-tb">
                <td class="noborder" colspan="5" align="center">GRAND TOTAL</td>
                <td class="noborder" align="right">{{ \App\Traits\Valet::getMoneyFormatString($grandtotal) }}</td>
            </tr>
        </tbody>
    </table>
</body>

</html>
