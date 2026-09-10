<style>
    .normal-table {
        width: 100% !important;
        border-collapse: collapse;
        font-family: Arial, Helvetica, sans-serif !important;
        font-size: 10px !important;
    }
</style>

<table class="normal-table" style="border: 1px solid black">
    <tr>
    @foreach ($data as $record)
        <td style="width:50%;vertical-align:top;padding: 5px;border-right: 1px solid black">
            <table class="normal-table">
                <tr>
                    <td style="width: 30%">No RM</td>
                    <td style="width: 70%">: {{ $record->no_cm }}</td>
                </tr>
               
                    <tr>
                        <td>Nama</td>
                        <td>: {{ $record->nama_pasien }}</td>
                    </tr>
                <tr>
                    <td>Tanggal Lahir</td>
                    <td>: {{$record->tanggal_lahir}}</td>
                </tr>
                <tr>
                    <td>Jenis Kelamin</td>
                    <td>: {{$record->jeniskelamin}}</td>
                </tr>
                <tr>
                    <td>Tanggal</td>
                    <td>:  {{ date('d-m-Y') }}</td>
                </tr>
            </table>
        </td>
        <td style="width:50%;vertical-align: top;padding: 5px">
            <table class="normal-table">
                <tr>
                    <td style="width: 30%">Dokter yang merawat</td>
                    <td style="width: 70%">: {{$record->namalengkap}}</td>
                </tr>
                <!-- <tr>
                    <td>HP</td>
                    <td>: NO HP</td>
                </tr>
                <tr>
                    <td>Jaminan</td>
                    <td>: JAMINAN</td>
                </tr> -->
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="2" style="border-top: 1px solid black;padding:5px">
            <span>
                Klinis/Diagnosis : {{isset($record->klinis_diagnosis) ? $record->klinis_diagnosis :''}}
            </span><br><br>
            <span>Riwayat Pemakaian Antibiotik :</span><br>
            <table class="normal-table" style="margin-top: 3px">
                <tr>
                    <td style="width: 33%">
                        <!-- <input type="checkbox" {{ isset($data['sebelumab']) && $data['sebelumab'] == '' ? 'checked' : '' }} /> -->
                        <input type="checkbox" {{ isset($record->sebelumab) ? 'checked' : '' }} />
                        <span>Sebelum pemberian AB</span>
                    </td>
                    <td style="width: 33%" colspan="2">
                        <!-- <input type="checkbox" {{ isset($data['terapiab']) && $data['terapiab'] == '' ? 'checked' : '' }} /> -->
                        <input type="checkbox" {{ isset($record->terapiab ) ? 'checked' : '' }} />
                        <span>Sedang terapi AB</span>
                    </td>
                </tr>
                <tr>
                    <td style="width: 33%">
                        <!-- <input type="checkbox" {{ isset($data['namaantibiotik']) && $data['namaantibiotik'] == '' ? 'checked' : '' }} /> -->
                        <!-- <input type="checkbox" {{ isset($record->namaantibiotik )  ? 'checked' : '' }} /> -->
                        <span>Jenis/nama antibiotik :</span>
                        <br>
                        <td style="width: 30%"> {{ $record->namaantibiotik }}</td>
                    </td>
                    <td style="width: 33%">
                        <input type="checkbox" />
                        <span>...</span>
                    </td>
                    <td style="width: 33%">
                        <input type="checkbox" {{ isset($record->harike)  ? 'checked' : '' }} />
                        <span>Hari ke-...</span>
                    </td>
                </tr>
                <tr>
                    <td style="width: 33%"></td>
                    <td style="width: 33%">
                        <input type="checkbox"  />
                        <span>...</span>
                    </td>
                    <td style="width: 33%">
                        <input type="checkbox"  />
                        <span>Hari ke-...</span>
                    </td>
                </tr>
                <tr>
                    <td style="width: 33%"></td>
                    <td style="width: 33%">
                        <input type="checkbox"  />
                        <span>...</span>
                    </td>
                    <td style="width: 33%">
                        <input type="checkbox"  />
                        <span>Hari ke-...</span>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="2" style="border-top: 1px solid black;padding:5px">
            <table class="normal-table">
                <tr>
                    <td style="width: 30%;font-weight:bold;font-style:italic;vertical-align: top">Penggunaan medical
                        device</td>
                    <td style="width: 70%">
                        <table class="normal-table">
                            <tr>
                                <td style="width: 25%">
                                    <input type="checkbox" {{ isset($record->qvc ) ? 'checked' : '' }} />
                                    <span>Q CVC</span>
                                </td>
                                <td style="width: 25%">
                                    <input type="checkbox"
                                        {{ isset($record->urineKateter)  ? 'checked' : '' }} />
                                    <span>Kateter Urine</span>
                                </td>
                                <td style="width: 25%">
                                    <input type="checkbox"
                                        {{ isset($record-> eet)  ? 'checked' : '' }} />
                                    <span>EET</span>
                                </td>
                                <td style="width: 25%">
                                    <input type="checkbox"
                                        {{ isset($record->cpap)  ? 'checked' : '' }} />
                                    <span>CRAP</span>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 25%">
                                    <input type="checkbox"
                                        {{ isset($record->ivline)  ? 'checked' : '' }} />
                                    <span>IV Line</span>
                                </td>
                                <td style="width: 25%">
                                    <input type="checkbox"
                                        {{ isset($record->wsd)  ? 'checked' : '' }} />
                                    <span>WSD</span>
                                </td>
                                <td style="width: 25%">
                                    <input type="checkbox"
                                        {{ isset($record->ventilator)  ? 'checked' : '' }} />
                                    <span>Ventilator</span>
                                </td>
                                <td style="width: 25%">
                                    <input type="checkbox" />
                                    <span>...</span>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table><br>
            <span>Jenis spesimen</span>
            <table class="normal-table">
                <tr>
                    <td style="width: 20%">1. Urine :</td>
                    <td style="width: 20%">
                        <input type="checkbox" {{ isset($record->midstream) ? 'checked' : '' }} />
                        <span>Midstream</span>
                    </td>
                    <td style="width: 20%">
                        <input type="checkbox" {{ isset($record->urineKateter)  ? 'checked' : '' }} />
                        <span>Urine kateter</span>
                    </td>
                    <td style="width: 20%">
                        <input type="checkbox" {{ isset($record->supraPubik)  ? 'checked' : '' }} />
                        <span>Aspirasi supra publik</span>
                    </td>
                    <td style="width: 20%">
                        <input type="checkbox"  />
                        <span>...</span>
                    </td>
                </tr>
                <tr>
                    <td style="width: 20%">2. Darah :</td>
                    <td style="width: 20%">
                        <input type="checkbox" {{ isset($record->sisi)  ? 'checked' : '' }} />
                        <span>Sisi</span>
                    </td>
                    <td style="width: 20%">
                        <input type="checkbox" {{ isset($record->sisi2)  ? 'checked' : '' }} />
                        <span>2 sisi</span>
                    </td>
                    <td style="width: 20%">
                        <input type="checkbox" {{ isset($record->tempatEndokarditis) ? 'checked' : '' }} />
                        <span>3 tempat(endokarditis)</span>
                    </td>
                    <td style="width: 20%"></td>
                </tr>
                <tr>
                    <td style="width: 20%">3. Sputum :</td>
                    <td style="width: 20%">
                        <input type="checkbox" {{ isset($record->pagi) ? 'checked' : '' }} />
                        <span>Pagi</span>
                    </td>
                    <td style="width: 20%">
                        <input type="checkbox" {{ isset($record->sewaktu)  ? 'checked' : '' }} />
                        <span>Sewaktu</span>
                    </td>
                    <td style="width: 20%">
                        <input type="checkbox" {{ isset($record->induksi)  ? 'checked' : '' }} />
                        <span>Induksi</span>
                    </td>
                    <td style="width: 20%">
                        <input type="checkbox" {{ isset($record->sputanEET)  ? 'checked' : '' }} />
                        <span>Sputum ETT</span>
                    </td>
                </tr>
                <tr>
                    <td style="width: 20%">4. Luka :</td>
                    <td style="width: 20%">
                        <input type="checkbox" {{ isset($record->dasarluka) ? 'checked' : '' }} />
                        <span>Dasarluka</span>
                    </td>
                    <td style="width: 20%">
                        <input type="checkbox" {{ isset($record->aspirasipus) ? 'checked' : '' }} />
                        <span>Aspirasi pus</span>
                    </td>
                    <td style="width: 20%" colspan="2">
                        <input type="checkbox" {{ isset($record->pengambilan2) ? 'checked' : '' }} />
                        <span>Area pengambilan...</span>
                    </td>
                </tr>
                <tr>
                    <td style="width: 20%">5. Jaringan :</td>
                    <td style="width: 20%" colspan="4">
                        <input type="checkbox"  />
                        <span>Area pengambilan : ....</span>
                    </td>
                </tr>
                <tr>
                    <td style="width: 20%">6. LCS :</td>
                    <td style="width: 20%">
                        <input type="checkbox" {{ isset($record->lp)  ? 'checked' : '' }} />
                        <span>LP</span>
                    </td>
                    <td style="width: 20%">
                        <input type="checkbox" {{ isset($record->vpshunt)  ? 'checked' : '' }} />
                        <span>VP shunt</span>
                    </td>
                    <td style="width: 20%" colspan="2">
                        <input type="checkbox" {{ isset($record->evd ) ? 'checked' : '' }} />
                        <span>EVD</span>
                    </td>
                </tr>
                <tr>
                    <td style="width: 20%">7. Cairan :</td>
                    <td style="width: 20%">
                        <input type="checkbox" {{ isset($record->pleura)  ? 'checked' : '' }} />
                        <span>Pleura</span>
                    </td>
                    <td style="width: 20%">
                        <input type="checkbox" {{ isset($record-> ascites2)  ? 'checked' : '' }} />
                        <span>Ascites</span>
                    </td>
                    <td style="width: 20%">
                        <input type="checkbox" {{ isset($record->perikardium2 ) ? 'checked' : '' }} />
                        <span>Perikardium</span>
                    </td>
                    <!-- <td style="width: 20%">
                        <input type="checkbox" {{ isset($data['']) && $data[''] == '' ? 'checked' : '' }} />
                        <span>...</span>
                    </td> -->
                </tr>
                <tr>
                    <td style="width: 20%">8. Lain-lain :</td>
                    <td style="width: 20%" colspan="4">
                        <span{{isset($record->lainnya3) ? $record->lainnya3 : ''}}</span>
                    </td>
                </tr>
                <tr>
                    <td style="width: 20%">Volume spesimen (ml) :</td>
                    <td style="width: 20%" colspan="4">
                        <span>{{isset($record->volspes) ? $record->volspes : ''}}</span>
                    </td>
                </tr>
                <tr>
                    <td style="width: 20%">Waktu (WIB) :</td>
                    <td style="width: 40%" colspan="2">
                        <input type="text" />
                        <span>Pengambilan spesimen :</span>

                    </td>
                    <td style="width: 10%">
                        <span>Pengiriman spesimen :</span>
                    </td>
                    <td> {{$record->pengirimanspesimen}}</td>
                </tr>
                <tr>
                    <td style="width: 20%">Cara penyimpanan :</td>
                    <td> {{$record->penyimpanan}}</td>
                    <td style="width: 40%" colspan="2">
                        <input type="checkbox" {{ isset($record->empatcels)  ? 'checked' : '' }} />
                        <span>4°C</span>
                    </td>
                    <td style="width: 40%" colspan="2">
                        <input type="checkbox"  />
                        <span>Suhu Ruangan</span>
                    </td>
                </tr>
            </table>
        </td>
        @endforeach
    </tr>
</table>
