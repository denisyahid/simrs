<div style="border: 1px solid #8a7a7a;padding: 20px;text-align: center;align-items: center">
    <table style="width:100%">
        <tr width="100%">
            @if(isset($rad) && $israd != true)
                <td width="30%">
                    <span style="font-size: 11pt;" color="#000000">{{ date('d/m/Y H:i') }}</span>
                </td>
            @endif
            <td width="70%">
                <table width="100%">
                    <tr width="100%">
                        <td width="40%" style="font-weight: bold;  width:50px">No RM</td>
                        <td>:</td>
                        <td style="font-weight: bold">{{ $datas[0]->nocm ?? '' }}</td>
                    </tr>
                    <tr>
                        <td width="40%"style="font-weight: bold;  width:50px">Nama Pasien</td>
                        <td>:</td>
                        <td style="font-weight: bold">{{ $datas[0]->namapasien ?? '' }}</td>
                    </tr>
                    <tr>
                        <td width="40%"><span style="font-size: 11pt;" color="#000000">Tgl Lahir</span></td>
                        <td>:</td>
                        <td style="font-weight: bold">{{ $datas[0]->tgllahir ?? '' }}</td>
                    </tr>
                    <tr>
                        <td width="40%"><span style="font-size: 11pt;" color="#000000">Tgl Masuk </span></td>
                        <td>:</td>
                        <td><span style="font-size: 11pt;" color="#000000">
                                {{ $datas[0]->tglmasuk ?? '' }}
                            </span></td>
                    </tr>
                    <tr>
                        <td width="40%"><span style="font-size: 11pt;" color="#000000">Pemeriksaan</span></td>
                        <td>:</td>
                        <td>
                            @foreach ($datas as $key => $data)
                                <span style="font-size: 13pt;" color="#000000">
                                    {{ $loop->iteration }} . {{ $data->pemeriksaan }} <br>
                                </span>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <td width="40%"><span style="font-size: 11pt;" color="#000000">Dokter DPJP</span></td>
                        <td>:</td>
                        <td>
                            @foreach ($datas as $key => $data)
                                <span style="font-size: 13pt;" color="#000000">
                                    {{ $data->dokter }} <br>
                                </span>
                            @endforeach
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>
