<!DOCTYPE html>
<html>

<head>
    <title>@yield('title')</title>
    @yield('page-style')
</head>

<body>

    <table width="100%" cellspacing="0" cellpadding="0"
        style="border-bottom: 1px solid black;border-collapse: collapse; padding">
        <tr>
            <td style="text-align: center; padding: 15px;">
                <img src="{{ 'img/logo-cetakan-obgyn.png' }}" width="100px" height="100px" style="display: block;">
            </td>

            <td style="text-align: center;">
                <span style="font-weight: bold;">PEMERINTAH PROVINSI BALI </span><br>
                <span style="font-weight: bold;">DINAS KESEHATAN</span> <br>
                <span style="font-weight: bold;">RUMAH SAKIT UMUM DAERAH BALI MANDARA</span> <br>
                <span>Jalan ByPass Ngurah Rai No.548 Sanur,Garut-Bali</span>
                <span>No.Telp : (0361) 4490566, E-mail : rsud.balimandara@gmail.com</span>
            </td>
            <td style="text-align: center; padding: 15px;">
                <img src="{{ 'img/logo-rs.png' }}" width="100px" height="100px" style="display: block;">
            </td>
        </tr>
    </table>

    <table width="100%" cellspacing="0" cellpadding="0" style="border-collapse: collapse; padding; margin-top: 15px;">
        <tr>
            <td style="text-align: center;">
                <span style="font-weight: bold;">PEMERIKSAAN FETAL</span>
            </td>
        </tr>
    </table>

    <table width="100%" cellspacing="0" cellpadding="0" style="border-collapse: collapse; padding; margin-top: 15px;">
        <tr>
            <td style="border: 1px solid black; width: 15%; padding: 10px;">
                <span>No RM</span>
            </td>
            <td style="border: 1px solid black; padding-left: 10px;">
                <span>{{isset($data['norm']) ? $data['norm'] : '-' }}</span>
            </td>
            <td style="border: 1px solid black; padding: 10px;">
                <span>Tanggal / Jam Periksa</span>
            </td>
            <td style="border: 1px solid black; padding-left: 10px;">
                <span>
                    {{ isset($data['tglPembuatan'])
                        ? \Carbon\Carbon::parse($data['tglPembuatan'])->setTimezone('Asia/Jakarta')->format('d-m-Y H:i')
                        : '-' }}
                </span>
            </td>
        </tr>
        <tr>
            <td style="border: 1px solid black; width: 15%; padding: 10px; width: 20%;">
                <span>Nama Pasien</span>
            </td>
            <td style="border: 1px solid black; padding-left: 10px;">
                <span>{{isset($data['namaPasien']) ? $data['namaPasien'] : '-' }}</span>
            </td>
            <td style="border: 1px solid black; padding: 10px; width: 20%;">
                <span>Dokter Pemeriksa</span>
            </td>
            <td style="border: 1px solid black; padding-left: 10px;">
                <span>{{isset($data['dokterPemeriksa']) ? $data['dokterPemeriksa'] : '-' }}</span>
            </td>
        </tr>
        <tr>
            <td style="border: 1px solid black; width: 15%; padding: 10px;">
                <span>Alamat Pasien</span>
            </td>
            <td style="border: 1px solid black; padding-left: 10px;">
                <span>{{isset($data['pasien']['alamatlengkap']) ? $data['pasien']['alamatlengkap'] : '-' }}</span>
            </td>
            <td style="border: 1px solid black; padding: 10px;">
                <span>HPHT</span>
            </td>
            <td style="border: 1px solid black; padding-left: 10px;">
                <span>{{isset($data['hpht']) ? $data['hpht'] : '-' }}</span>
            </td>
        </tr>
        <tr>
            <td style="border: 1px solid black; width: 15%; padding: 10px;">
                <span>Tanggal Lahir</span>
            </td>
            <td style="border: 1px solid black; padding-left: 10px;">
                <span>{{isset($data['tanggalLahirPasien']) ? $data['tanggalLahirPasien'] : '-' }} {{isset($data['umur']) ? $data['umur'] : '-' }}</span>
            </td>
            <td style="border: 1px solid black; padding: 10px;">
                <span>Diagnosa</span>
            </td>
            <td style="border: 1px solid black; padding-left: 10px;">
                <span>{{isset($data['diagnosa']) ? $data['diagnosa'] : '-' }}</span>
            </td>
        </tr>
    </table>

    <table width="100%" cellspacing="0" cellpadding="0" style="border-collapse: collapse; padding; margin-top: 15px;">
        <tr>
            <td style="padding: 5px;">
                <span style="font-weight: bold;">I. Kondisi Teknis : </span>
                <span>{{isset($data['kondisiTeknisFetal']) ? $data['kondisiTeknisFetal'] : '-' }}</span>
                <span>Terbatas, karena {{isset($data['karenaFetal']) ? $data['karenaFetal'] : '-' }}</span>
            </td>
        </tr>
        <tr>
            <td style="padding: 5px;">
                <span style="font-weight: bold;">II. Janin :</span><span>{{isset($data['janin']) ? $data['janin'] : '-' }}, </span>
                <span style="font-weight: bold;">Khorionisitas: </span> <span>{{isset($data['khorionisitas']) ? $data['khorionisitas'] : '-' }}, </span>
                <span style="font-weight: bold;">DJJ: </span> <span>{{isset($data['djj']) ? $data['djj'] : '-' }},{{isset($data['ketDJJ']) ? $data['ketDJJ'] : '-' }} x/menit, </span>
                <span style="font-weight: bold;">Fetal Movement: </span> <span>{{isset($data['fetalmovement']) ? $data['fetalmovement'] : '-' }}</span>
            </td>
        </tr>
        <tr>
            <td style="padding: 5px;">
                <span style="font-weight: bold;">III. Biometri:</span>
            </td>
        </tr>
    </table>

    <table width="100%" cellspacing="0" cellpadding="0" style="border-collapse: collapse; padding; margin-top: 15px;">
        <thead>
            <tr>
                <th style="border: 1px solid black; padding: 10px;">PENGUKURAN</th>
                <th style="border: 1px solid black; padding: 10px;">MM</th>
                <th style="border: 1px solid black; padding: 10px;">USIA KEHAMILAN</th>
                <th style="border: 1px solid black; padding: 10px;">KETERANGAN</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="border: 1px solid black; padding: 10px;">Gestasional Sac</td>
                <td style="border: 1px solid black; padding: 10px;">{{isset($data['gestasionalsac']) ? $data['gestasionalsac'] : '-' }}</td>
                <td style="border: 1px solid black; padding: 10px;">{{isset($data['usiaGestasional']) ? $data['usiaGestasional'] : '-' }}</td>
                <td style="border: 1px solid black; padding: 10px;">{{isset($data['ketGestasional']) ? $data['ketGestasional'] : '-' }}</td>
            </tr>
            <tr>
                <td style="border: 1px solid black; padding: 10px;">Crown-rump Length</td>
                <td style="border: 1px solid black; padding: 10px;">{{isset($data['crown']) ? $data['crown'] : '-' }}</td>
                <td style="border: 1px solid black; padding: 10px;">{{isset($data['usiaCrown']) ? $data['usiaCrown'] : '-' }}</td>
                <td style="border: 1px solid black; padding: 10px;">{{isset($data['ketCrown']) ? $data['ketCrown'] : '-' }}</td>
            </tr>
            <tr>
                <td style="border: 1px solid black; padding: 10px;">Biparietal Diameter</td>
                <td style="border: 1px solid black; padding: 10px;">{{isset($data['biparietal']) ? $data['biparietal'] : '-' }}</td>
                <td style="border: 1px solid black; padding: 10px;">{{isset($data['usiaBiparietal']) ? $data['usiaBiparietal'] : '-' }}</td>
                <td style="border: 1px solid black; padding: 10px;">{{isset($data['ketBiparietal']) ? $data['ketBiparietal'] : '-' }}</td>
            </tr>
            <tr>
                <td style="border: 1px solid black; padding: 10px;">Head Circumference</td>
                <td style="border: 1px solid black; padding: 10px;">{{isset($data['headcircum']) ? $data['headcircum'] : '-' }}</td>
                <td style="border: 1px solid black; padding: 10px;">{{isset($data['usiaHeadCircum']) ? $data['usiaHeadCircum'] : '-' }}</td>
                <td style="border: 1px solid black; padding: 10px; vertical-align: top;" rowspan="3">Temuan abnormal : {{isset($data['Ketabdominalcircum']) ? $data['Ketabdominalcircum'] : '-' }}</td>
            </tr>
            <tr>
                <td style="border: 1px solid black; padding: 10px;">Abdominal Circumference</td>
                <td style="border: 1px solid black; padding: 10px;">{{isset($data['abdominalcircum']) ? $data['abdominalcircum'] : '-' }}</td>
                <td style="border: 1px solid black; padding: 10px;">{{isset($data['usiaAbdominalcircum']) ? $data['usiaAbdominalcircum'] : '-' }}</td>
            </tr>
            <tr>
                <td style="border: 1px solid black; padding: 10px;">Femoral Lenght</td>
                <td style="border: 1px solid black; padding: 10px;">{{isset($data['femoral']) ? $data['femoral'] : '-' }}</td>
                <td style="border: 1px solid black; padding: 10px;">{{isset($data['usiaFemoral']) ? $data['usiaFemoral'] : '-' }}</td>
            </tr>
        </tbody>
    </table>

    <table width="100%" cellspacing="0" cellpadding="0" style="border-collapse: collapse; padding; margin-top: 15px;">
        <tr>
            <td style="padding: 5px;">
                <span style="font-weight: bold;">IV. Plasenta : </span>
                <span>{{isset($data['plasenta']) ? $data['plasenta'] : '-' }}, </span>
                <span>{{isset($data['menutupi']) ? $data['menutupi'] : '-' }}, </span>
                <span>{{isset($data['ukuranMenutupi']) ? $data['ukuranMenutupi'] : '-' }}</span>
                <span>{{isset($data['maturasi']) ? $data['maturasi'] : '-' }}</span>
            </td>
        </tr>
        <tr>
            <td style="padding: 5px;">
                <span style="font-weight: bold;">V. Cairan Amnion : </span>
                <span>{{isset($data['cairanaminion']) ? $data['cairanaminion'] : '-' }}, </span>
                <span style="font-weight: bold;">AFI : </span>
                <span>{{isset($data['AFI']) ? $data['AFI'] : '-' }}, </span>
                <span style="font-weight: bold;">SDP : </span>
                <span>{{isset($data['SDP']) ? $data['SDP'] : '-' }}</span> <br>
                <span>Temuan abnormal: {{isset($data['temuanAbnormal']) ? $data['temuanAbnormal'] : '-' }}</span>
            </td>
        </tr>
        <tr>
            <td style="padding: 5px;">
                <span style="font-weight: bold;">VI. Kelainan Kongenital Mayor : </span>
                <span>{{isset($data['kongenitalMayor']) ? $data['kongenitalMayor'] : '-' }}, </span>
                <br>
                <span>Temuan abnormal: {{isset($data['temuanAbnormalKongenital']) ? $data['temuanAbnormalKongenital'] : '-' }}</span>
            </td>
        </tr>

        <tr>
            <td style="padding: 5px;">
                <span style="font-weight: bold;">VII. Adneksa : </span>
                <span>{{isset($data['adneksa']) ? $data['adneksa'] : '-' }}, </span>
                <br>
                <span>Temuan abnormal: {{isset($data['fetalSaran']) ? $data['fetalSaran'] : '-' }}</span>
            </td>
        </tr>

    </table>

    <table width="100%" cellspacing="0" cellpadding="0" style="border-collapse: collapse; margin-top: 15px;">
        <tr>
            <td style="padding: 5px; width: 50%;">
                <span style="font-weight: bold;">VII. Doppler Velocimetry :</span>
                <br>
                <span>Arteri Uterina : {{isset($data['arteriUterina']) ? $data['arteriUterina'] : '-' }}, </span> <br>
                <span>RI : {{isset($data['riUterina']) ? $data['riUterina'] : '-' }}, </span> <br>
                <span>PI : {{isset($data['piUterina']) ? $data['piUterina'] : '-' }}, </span> <br>
                <span>S/D Ratio : {{isset($data['ratioUterina']) ? $data['ratioUterina'] : '-' }}, </span> <br>
            </td>

            <td style="padding: 5px;  width: 50%;">
                <br>
                <span>Arteri Umbilicalis : {{isset($data['arteriUmbilicalis']) ? $data['arteriUmbilicalis'] : '-' }}, </span> <br>
                <span>RI : {{isset($data['riUmbilicalis']) ? $data['riUmbilicalis'] : '-' }}, </span> <br>
                <span>PI : {{isset($data['piUmbilicalis']) ? $data['piUmbilicalis'] : '-' }}, </span> <br>
                <span>S/D Ratio : {{isset($data['ratioUmbilicalis']) ? $data['ratioUmbilicalis'] : '-' }}, </span> <br>
            </td>
        </tr>
        <tr>
            <td style="padding: 5px; width: 50%;">
                <span>Ductus Venosus: {{isset($data['ductusVenosus']) ? $data['ductusVenosus'] : '-' }}, </span> <br>
                <span>RI : {{isset($data['riDuctus']) ? $data['riDuctus'] : '-' }}, </span> <br>
                <span>PI : {{isset($data['piDuctus']) ? $data['piDuctus'] : '-' }}, </span> <br>
                <span>S/D Ratio : {{isset($data['ratioDuctus']) ? $data['ratioDuctus'] : '-' }}, </span> <br>
            </td>

            <td style="padding: 5px;  width: 50%;">
                <span>Arteri Serebri Media: {{isset($data['arteriSerebi']) ? $data['arteriSerebi'] : '-' }}, </span> <br>
                <span>RI : {{isset($data['riSerebi']) ? $data['riSerebi'] : '-' }}, </span> <br>
                <span>PI : {{isset($data['piSerebi']) ? $data['piSerebi'] : '-' }}, </span> <br>
                <span>S/D Ratio : {{isset($data['ratioSerebi']) ? $data['ratioSerebi'] : '-' }}, </span> <br>
            </td>
        </tr>
    </table>

    <table width="100%" cellspacing="0" cellpadding="0" style="border-collapse: collapse; margin-top: 15px;">
        <thead style="background-color: #60cdee">
            <tr>
                <th style="border: 1px solid black; padding: 10px; width: 38%;">
                    <span>USG ANATOMI :</span> <br>
                    <span>(N = Normal ; Abn = Abnormal ; TT = Tidak dapat ditampakkan ; Abu - abu = opsional)</span>
                </th>
                <th style="border: 1px solid black; padding: 10px;">N</th>
                <th style="border: 1px solid black; padding: 10px;">Abn</th>
                <th style="border: 1px solid black; padding: 10px;">TT</th>
                <th style="border: 1px solid black; padding: 10px;">Abu - Abu</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="border: 1px solid black; padding: 10px;" colspan="5">Kepala</td>
            </tr>
            <tr>
                <td style="border: 1px solid black; padding: 10px;">Bentuk</td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['bentukkepala'])) && $data['bentukkepala'] == "Normal" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['bentukkepala'])) && $data['bentukkepala'] == "Abnormal" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['bentukkepala'])) && $data['bentukkepala'] == "Tidak dapat ditampakkan" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['bentukkepala'])) && $data['bentukkepala'] == "Opsional" ? 'checked' : ''; ?>>
                </td>
            </tr>

            <tr>
                <td style="border: 1px solid black; padding: 10px;">Cavum septum pellucidum</td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['cavumseptum'])) && $data['cavumseptum'] == "Normal" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['cavumseptum'])) && $data['cavumseptum'] == "Abnormal" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['cavumseptum'])) && $data['cavumseptum'] == "Tidak dapat ditampakkan" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['cavumseptum'])) && $data['cavumseptum'] == "Opsional" ? 'checked' : ''; ?>>
                </td>
            </tr>
            <tr>
                <td style="border: 1px solid black; padding: 10px;">Falx cerebri</td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['falx'])) && $data['falx'] == "Normal" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['falx'])) && $data['falx'] == "Abnormal" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['falx'])) && $data['falx'] == "Tidak dapat ditampakkan" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['falx'])) && $data['falx'] == "Opsional" ? 'checked' : ''; ?>>
                </td>
            </tr>
            <tr>
                <td style="border: 1px solid black; padding: 10px;">Thalamus</td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['thalamus'])) && $data['thalamus'] == "Normal" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['thalamus'])) && $data['thalamus'] == "Abnormal" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['thalamus'])) && $data['thalamus'] == "Tidak dapat ditampakkan" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['thalamus'])) && $data['thalamus'] == "Opsional" ? 'checked' : ''; ?>>
                </td>
            </tr>
            <tr>
                <td style="border: 1px solid black; padding: 10px;">Ventrikel lateral</td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['ventrikellateral'])) && $data['ventrikellateral'] == "Normal" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['ventrikellateral'])) && $data['ventrikellateral'] == "Abnormal" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['ventrikellateral'])) && $data['ventrikellateral'] == "Tidak dapat ditampakkan" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['ventrikellateral'])) && $data['ventrikellateral'] == "Opsional" ? 'checked' : ''; ?>>
                </td>
            </tr>
            <tr>
                <td style="border: 1px solid black; padding: 10px;">Cerebellum</td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['cerebellum'])) && $data['cerebellum'] == "Normal" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['cerebellum'])) && $data['cerebellum'] == "Abnormal" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['cerebellum'])) && $data['cerebellum'] == "Tidak dapat ditampakkan" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['cerebellum'])) && $data['cerebellum'] == "Opsional" ? 'checked' : ''; ?>>
                </td>
            </tr>
            <tr>
                <td style="border: 1px solid black; padding: 10px;">Cisterna magna</td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['cistema'])) && $data['cistema'] == "Normal" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['cistema'])) && $data['cistema'] == "Abnormal" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['cistema'])) && $data['cistema'] == "Tidak dapat ditampakkan" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['cistema'])) && $data['cistema'] == "Opsional" ? 'checked' : ''; ?>>
                </td>
            </tr>

            <tr>
                <td style="border: 1px solid black; padding: 10px;">Orbita</td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['orbita'])) && $data['orbita'] == "Normal" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['orbita'])) && $data['orbita'] == "Abnormal" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['orbita'])) && $data['orbita'] == "Tidak dapat ditampakkan" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['orbita'])) && $data['orbita'] == "Opsional" ? 'checked' : ''; ?>>
                </td>
            </tr>
            <tr>
                <td style="border: 1px solid black; padding: 10px;">Hidung</td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['hidung'])) && $data['hidung'] == "Normal" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['hidung'])) && $data['hidung'] == "Abnormal" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['hidung'])) && $data['hidung'] == "Tidak dapat ditampakkan" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['hidung'])) && $data['hidung'] == "Opsional" ? 'checked' : ''; ?>>
                </td>
            </tr>
            <tr>
                <td style="border: 1px solid black; padding: 10px;">Lubang hidung</td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['lubanghidung'])) && $data['lubanghidung'] == "Normal" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['lubanghidung'])) && $data['lubanghidung'] == "Abnormal" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['lubanghidung'])) && $data['lubanghidung'] == "Tidak dapat ditampakkan" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['lubanghidung'])) && $data['lubanghidung'] == "Opsional" ? 'checked' : ''; ?>>
                </td>
            </tr>
            <tr>
                <td style="border: 1px solid black; padding: 10px;">Leher</td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['leher'])) && $data['leher'] == "Normal" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['leher'])) && $data['leher'] == "Abnormal" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['leher'])) && $data['leher'] == "Tidak dapat ditampakkan" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['leher'])) && $data['leher'] == "Opsional" ? 'checked' : ''; ?>>
                </td>
            </tr>

            <tr>
                <td style="border: 1px solid black; padding: 10px;" colspan="5">Wajah</td>
            </tr>
            <tr>
                <td style="border: 1px solid black; padding: 10px;">Bibir atas</td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['bibiratas'])) && $data['bibiratas'] == "Normal" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['bibiratas'])) && $data['bibiratas'] == "Abnormal" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['bibiratas'])) && $data['bibiratas'] == "Tidak dapat ditampakkan" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['bibiratas'])) && $data['bibiratas'] == "Opsional" ? 'checked' : ''; ?>>
                </td>
            </tr>
            <tr>
                <td style="border: 1px solid black; padding: 10px;">Profil mediana</td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['mediana'])) && $data['mediana'] == "Normal" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['mediana'])) && $data['mediana'] == "Abnormal" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['mediana'])) && $data['mediana'] == "Tidak dapat ditampakkan" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['mediana'])) && $data['mediana'] == "Opsional" ? 'checked' : ''; ?>>
                </td>
            </tr>
            <tr>
                <td style="border: 1px solid black; padding: 10px;">Orbita</td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['orbita'])) && $data['orbita'] == "Normal" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['orbita'])) && $data['orbita'] == "Abnormal" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['orbita'])) && $data['orbita'] == "Tidak dapat ditampakkan" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['orbita'])) && $data['orbita'] == "Opsional" ? 'checked' : ''; ?>>
                </td>
            </tr>
            <tr>
                <td style="border: 1px solid black; padding: 10px;">Hidung</td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['hidung'])) && $data['hidung'] == "Normal" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['hidung'])) && $data['hidung'] == "Abnormal" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['hidung'])) && $data['hidung'] == "Tidak dapat ditampakkan" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['hidung'])) && $data['hidung'] == "Opsional" ? 'checked' : ''; ?>>
                </td>
            </tr>
            <tr>
                <td style="border: 1px solid black; padding: 10px;">Lubang hidung</td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['lubanghidung'])) && $data['lubanghidung'] == "Normal" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['lubanghidung'])) && $data['lubanghidung'] == "Abnormal" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['lubanghidung'])) && $data['lubanghidung'] == "Tidak dapat ditampakkan" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['lubanghidung'])) && $data['lubanghidung'] == "Opsional" ? 'checked' : ''; ?>>
                </td>
            </tr>
            <tr>
                <td style="border: 1px solid black; padding: 10px;">Leher</td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['leher'])) && $data['leher'] == "Normal" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['leher'])) && $data['leher'] == "Abnormal" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['leher'])) && $data['leher'] == "Tidak dapat ditampakkan" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['leher'])) && $data['leher'] == "Opsional" ? 'checked' : ''; ?>>
                </td>
            </tr>

            <tr>
                <td style="border: 1px solid black; padding: 10px;" colspan="5">Thorax</td>
            </tr>
            <tr>
                <td style="border: 1px solid black; padding: 10px;">Bentuk</td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['bentukThorax'])) && $data['bentukThorax'] == "Normal" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['bentukThorax'])) && $data['bentukThorax'] == "Abnormal" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['bentukThorax'])) && $data['bentukThorax'] == "Tidak dapat ditampakkan" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['bentukThorax'])) && $data['bentukThorax'] == "Opsional" ? 'checked' : ''; ?>>
                </td>
            </tr>
            <tr>
                <td style="border: 1px solid black; padding: 10px;">Tidak tampak massa</td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['tidakTampakMassa'])) && $data['tidakTampakMassa'] == "Normal" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['tidakTampakMassa'])) && $data['tidakTampakMassa'] == "Abnormal" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['tidakTampakMassa'])) && $data['tidakTampakMassa'] == "Tidak dapat ditampakkan" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['tidakTampakMassa'])) && $data['tidakTampakMassa'] == "Opsional" ? 'checked' : ''; ?>>
                </td>
            </tr>

            <tr>
                <td style="border: 1px solid black; padding: 10px;" colspan="5">Jantung</td>
            </tr>
            <tr>
                <td style="border: 1px solid black; padding: 10px;">Aktifitas Jantung</td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['aktifitasJantung'])) && $data['aktifitasJantung'] == "Normal" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['aktifitasJantung'])) && $data['aktifitasJantung'] == "Abnormal" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['aktifitasJantung'])) && $data['aktifitasJantung'] == "Tidak dapat ditampakkan" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['aktifitasJantung'])) && $data['aktifitasJantung'] == "Opsional" ? 'checked' : ''; ?>>
                </td>
            </tr>
            <tr>
                <td style="border: 1px solid black; padding: 10px;">Ukuran</td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['ukuranJantung'])) && $data['ukuranJantung'] == "Normal" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['ukuranJantung'])) && $data['ukuranJantung'] == "Abnormal" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['ukuranJantung'])) && $data['ukuranJantung'] == "Tidak dapat ditampakkan" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['ukuranJantung'])) && $data['ukuranJantung'] == "Opsional" ? 'checked' : ''; ?>>
                </td>
            </tr>
            <tr>
                <td style="border: 1px solid black; padding: 10px;">Axis jantung</td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['axisJantung'])) && $data['axisJantung'] == "Normal" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['axisJantung'])) && $data['axisJantung'] == "Abnormal" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['axisJantung'])) && $data['axisJantung'] == "Tidak dapat ditampakkan" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['axisJantung'])) && $data['axisJantung'] == "Opsional" ? 'checked' : ''; ?>>
                </td>
            </tr>
            <tr>
                <td style="border: 1px solid black; padding: 10px;">Faur chamber view</td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['faur'])) && $data['faur'] == "Normal" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['faur'])) && $data['faur'] == "Abnormal" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['faur'])) && $data['faur'] == "Tidak dapat ditampakkan" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['faur'])) && $data['faur'] == "Opsional" ? 'checked' : ''; ?>>
                </td>
            </tr>
            <tr>
                <td style="border: 1px solid black; padding: 10px;">Left venticular outflow</td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['leftVerticular'])) && $data['leftVerticular'] == "Normal" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['leftVerticular'])) && $data['leftVerticular'] == "Abnormal" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['leftVerticular'])) && $data['leftVerticular'] == "Tidak dapat ditampakkan" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['leftVerticular'])) && $data['leftVerticular'] == "Opsional" ? 'checked' : ''; ?>>
                </td>
            </tr>
            <tr>
                <td style="border: 1px solid black; padding: 10px;">Right venticular outflow</td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['rightVerticular'])) && $data['rightVerticular'] == "Normal" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['rightVerticular'])) && $data['rightVerticular'] == "Abnormal" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['rightVerticular'])) && $data['rightVerticular'] == "Tidak dapat ditampakkan" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['rightVerticular'])) && $data['rightVerticular'] == "Opsional" ? 'checked' : ''; ?>>
                </td>
            </tr>

            <tr>
                <td style="border: 1px solid black; padding: 10px;" colspan="5">Abdomen</td>
            </tr>
            <tr>
                <td style="border: 1px solid black; padding: 10px;">Gaster</td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['gaster'])) && $data['gaster'] == "Normal" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['gaster'])) && $data['gaster'] == "Abnormal" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['gaster'])) && $data['gaster'] == "Tidak dapat ditampakkan" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['gaster'])) && $data['gaster'] == "Opsional" ? 'checked' : ''; ?>>
                </td>
            </tr>
            <tr>
                <td style="border: 1px solid black; padding: 10px;">Usus</td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['usus'])) && $data['usus'] == "Normal" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['usus'])) && $data['usus'] == "Abnormal" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['usus'])) && $data['usus'] == "Tidak dapat ditampakkan" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['usus'])) && $data['usus'] == "Opsional" ? 'checked' : ''; ?>>
                </td>
            </tr>
            <tr>
                <td style="border: 1px solid black; padding: 10px;">Ginjal</td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['ginjal'])) && $data['ginjal'] == "Normal" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['ginjal'])) && $data['ginjal'] == "Abnormal" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['ginjal'])) && $data['ginjal'] == "Tidak dapat ditampakkan" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['ginjal'])) && $data['ginjal'] == "Opsional" ? 'checked' : ''; ?>>
                </td>
            </tr>
            <tr>
                <td style="border: 1px solid black; padding: 10px;">Vesica urinaria</td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['vesicaUrinaria'])) && $data['vesicaUrinaria'] == "Normal" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['vesicaUrinaria'])) && $data['vesicaUrinaria'] == "Abnormal" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['vesicaUrinaria'])) && $data['vesicaUrinaria'] == "Tidak dapat ditampakkan" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['vesicaUrinaria'])) && $data['vesicaUrinaria'] == "Opsional" ? 'checked' : ''; ?>>
                </td>
            </tr>
            <tr>
                <td style="border: 1px solid black; padding: 10px;">Insersi umbiculus di abdomen</td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['insersiUmbilicilus'])) && $data['insersiUmbilicilus'] == "Normal" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['insersiUmbilicilus'])) && $data['insersiUmbilicilus'] == "Abnormal" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['insersiUmbilicilus'])) && $data['insersiUmbilicilus'] == "Tidak dapat ditampakkan" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['insersiUmbilicilus'])) && $data['insersiUmbilicilus'] == "Opsional" ? 'checked' : ''; ?>>
                </td>
            </tr>
            <tr>
                <td style="border: 1px solid black; padding: 10px;">Vascular umbikulus</td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['vescularUmbilicilus'])) && $data['vescularUmbilicilus'] == "Normal" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['vescularUmbilicilus'])) && $data['vescularUmbilicilus'] == "Abnormal" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['vescularUmbilicilus'])) && $data['vescularUmbilicilus'] == "Tidak dapat ditampakkan" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['vescularUmbilicilus'])) && $data['vescularUmbilicilus'] == "Opsional" ? 'checked' : ''; ?>>
                </td>
            </tr>
            <tr>
                <td style="border: 1px solid black; padding: 10px;">Vertebra</td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['vertebra'])) && $data['vertebra'] == "Normal" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['vertebra'])) && $data['vertebra'] == "Abnormal" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['vertebra'])) && $data['vertebra'] == "Tidak dapat ditampakkan" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['vertebra'])) && $data['vertebra'] == "Opsional" ? 'checked' : ''; ?>>
                </td>
            </tr>

            <tr>
                <td style="border: 1px solid black; padding: 10px;" colspan="5">Ekstremitas</td>
            </tr>
            <tr>
                <td style="border: 1px solid black; padding: 10px;">Tangan kanan (termasuk telapak)</td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['eksTanganKanan'])) && $data['eksTanganKanan'] == "Normal" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['eksTanganKanan'])) && $data['eksTanganKanan'] == "Abnormal" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['eksTanganKanan'])) && $data['eksTanganKanan'] == "Tidak dapat ditampakkan" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['eksTanganKanan'])) && $data['eksTanganKanan'] == "Opsional" ? 'checked' : ''; ?>>
                </td>
            </tr>

            <!-- Repeat similarly for other rows -->

            <tr>
                <td style="border: 1px solid black; padding: 10px;">Kaki kanan (termasuk telapak)</td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['eksKakiKanan'])) && $data['eksKakiKanan'] == "Normal" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['eksKakiKanan'])) && $data['eksKakiKanan'] == "Abnormal" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['eksKakiKanan'])) && $data['eksKakiKanan'] == "Tidak dapat ditampakkan" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['eksKakiKanan'])) && $data['eksKakiKanan'] == "Opsional" ? 'checked' : ''; ?>>
                </td>
            </tr>

            <tr>
                <td style="border: 1px solid black; padding: 10px;">Tangan kiri (termasuk telapak)</td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['eksTanganKiri'])) && $data['eksTanganKiri'] == "Normal" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['eksTanganKiri'])) && $data['eksTanganKiri'] == "Abnormal" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['eksTanganKiri'])) && $data['eksTanganKiri'] == "Tidak dapat ditampakkan" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['eksTanganKiri'])) && $data['eksTanganKiri'] == "Opsional" ? 'checked' : ''; ?>>
                </td>
            </tr>

            <tr>
                <td style="border: 1px solid black; padding: 10px;">Kaki kiri (termasuk telapak)</td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['eksKakiKiri'])) && $data['eksKakiKiri'] == "Normal" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['eksKakiKiri'])) && $data['eksKakiKiri'] == "Abnormal" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['eksKakiKiri'])) && $data['eksKakiKiri'] == "Tidak dapat ditampakkan" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['eksKakiKiri'])) && $data['eksKakiKiri'] == "Opsional" ? 'checked' : ''; ?>>
                </td>
            </tr>

            <tr>
                <td style="border: 1px solid black; padding: 10px;">Jenis Kelamin : <?php echo isset($data['jenisKelamin']) ? htmlspecialchars($data['jenisKelamin']) : ''; ?></td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['jenisKelamin2'])) && $data['jenisKelamin2'] == "Normal" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['jenisKelamin2'])) && $data['jenisKelamin2'] == "Abnormal" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['jenisKelamin2'])) && $data['jenisKelamin2'] == "Tidak dapat ditampakkan" ? 'checked' : ''; ?>>
                </td>
                <td style="border: 1px solid black; padding: 10px; text-align: center;">
                    <input type="checkbox" <?php echo (isset($data['jenisKelamin2'])) && $data['jenisKelamin2'] == "Opsional" ? 'checked' : ''; ?>>
                </td>
            </tr>

            <tr>
                <td style="border: 1px solid black; padding: 10px;" colspan="5">lain - lain : {{isset($data['fetalLainnya']) ? $data['fetalLainnya'] : '-' }}</td>
            </tr>
            <tr>
                <td style="border: 1px solid black; padding: 10px;" colspan="5">Kesimpulan dan Saran : {{isset($data['fetalSaran']) ? $data['fetalSaran'] : '-' }}</td>
            </tr>
        </tbody>
    </table>


    <table width="100%" cellspacing="0" cellpadding="0" style="border-collapse: collapse; padding; margin-top: 15px;">
        <tr>
            <td width="60%"></td>
            <td style="text-align: center" width="40%">
                <br>
                Garut, {{ isset($data['tglPembuatan'])
                        ? \Carbon\Carbon::parse($data['tglPembuatan'])->setTimezone('Asia/Jakarta')->format('d-m-Y H:i')
                        : '-' }}
            </td>
        </tr>
        <tr>
            <td width="60%"></td>
            <td style="text-align: center" width="40%">
                Dokter Pemeriksa
            </td>
        </tr>
        <tr>
            <td width="60%"></td>
            <td style="text-align: center" width="40%">
                <br>
                <img src="data:image/png;base64, {!! $tte !!}">
                <br><br>
            </td>
        </tr>
        <tr>
            <td width="60%"></td>
            <td style="text-align: center;font-weight: bold;font-size:8pt" width="40%">
                {{$data['dokterPemeriksa']}}
            </td>
        </tr>
                @if (isset($data['nip']->nip))
            <tr>
                <td width="60%"></td>
                <td style="text-align: center;font-weight: bold;font-size:8pt" width="40%">
                    NIP :
                    @if (isset($data['nip']->nip))
                        {{ $data['nip']->nip }}
                    @else
                        {{ '-' }}
                    @endif
                </td>
            </tr>
        @endif
    </table>
</body>

</html>
