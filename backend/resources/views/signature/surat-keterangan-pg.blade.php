<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <title>TRANSMEDIC - RSUD BALI MANDARA | SIGNATURE QRCODE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        h3 {
            font-size: 1rem;
        }

        h4 {
            font-size: 0.8rem;
        }

        h5 {
            font-size: 0.6rem;
        }

        h6 {
            font-size: 0.4rem;
        }

        hr {
            border: 1px solid;
            border-radius: 5px;
        }

        /* body {
            background-color: lightcoral;
        } */

        @media screen and (min-width: 400px) {
            /* body {
                background-color: lightgreen;
            } */

            h3 {
                font-size: 1.1rem;
            }

            h4 {
                font-size: 0.9rem;
            }

            h5 {
                font-size: 0.6rem;
            }

            h6 {
                font-size: 0.5rem;
            }
        }

        @media screen and (min-width: 600px) {
            /* body {
                background-color: lightyellow;
            } */

            h3 {
                font-size: 1.4rem;
            }

            h4 {
                font-size: 1.2rem;
            }

            h5 {
                font-size: 0.8rem;
            }

            h6 {
                font-size: 0.6rem;
            }
        }

        @media screen and (min-width: 800px) {
            /* body {
                background-color: lavender;
            } */

            h3 {
                font-size: 1.6rem;
            }

            h4 {
                font-size: 1.4rem;
            }

            h5 {
                font-size: 1rem;
            }

            h6 {
                font-size: 0.8rem;
            }

            hr {
                border: 1px solid;
                border-radius: 5px;
            }

        }
    </style>

</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="row mb-3">
                    <div id="small-img" class="col-6 ml-auto text-end" style="margin-top: 20px">
                        <img src="{{ asset('img/provinsi-rs.svg') }}" alt="Responsive image"
                            style="width: 15%;" />
                    </div>
                    <div id="small-img" class="col-6 mr-auto text-start" style="margin-top: 20px">
                        <img src="{{ asset('img/logo-rs.png') }}" alt="Responsive image" style="width: 15%;" />
                    </div>
                </div>
                <div class="row text-center">
                    <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <h4 class="mx-auto" style="font-weight: bold;">PEMERINTAH PROVINSI BALI</h4>
                        <h4 class="mx-auto" style="font-weight: bold; margin-top: -9px;">DINAS KESEHATAN</h4>
                        <h4 class="mx-auto" style="font-weight: bold; margin-top: -9px;">RUMAH SAKIT UMUM DAERAH BALI
                            MANDARA</h4>
                        <h5 class="mx-auto text-secondary" style="font-weight: bold; margin-top: -6px;">JALAN BY PASS
                            NGURAH
                            RAI NOMOR 548 SANUR - DENPASAR, BALI (80227), TELEPON (0361) 4490566</h5>
                        <h5 class="mx-auto text-secondary" style="font-weight: bold; margin-top: -6px;">email :
                            rsud.balimandara@gmail.com | website : <a href="http://rsbm.baliprov.go.id"
                                target="_blank">rsbm.baliprov.go.id</a></h5>
                    </div>
                </div>
                <hr style="margin-top: 2px;">
                {{-- test --}}
                <div class="row text-center">
                    <h3 class="mx-auto" style="font-weight: bold;">PERNYATAAN</h3>
                            <h5 class="mx-auto text-secondary" style="font-weight: bold;">
                                Bahwa benar
                                <span class="text-dark" style="font-weight: bold;">SURAT KETERANGAN LAHIR</span>
                                ini dikeluarkan di RSUD BALI MANDARA
                            </h5>
                    <h5 class="mx-auto text-secondary" style="font-weight: bold;">
                        untuk pasien dengan nomor rekam medis
                        <span class="text-dark" style="font-weight: bold;">"{{ $data->norm }}"</span> <br>
                        Atas Nama
                        <span class="text-dark" style="font-weight: bold;">
                            {{ implode(' ', array_map(function($word) { return strtoupper($word[0]); }, explode(' ', $data->namaanak))) }}
                        </span>

                    </h5>
                    <h5 class="mx-auto text-secondary" style="font-weight: bold;">Tanggal Registrasi <span
                        class="text-dark" style="font-weight: bold;">{{\Carbon\Carbon::parse($data->tglcetak)->setTimezone('Asia/Jakarta')->format('d-m-Y H:i')}}</span>
                    </h5>
                    <h5 class="mx-auto text-secondary" style="font-weight: bold;">Tanggal Pulang <span
                        class="text-dark" style="font-weight: bold;">{{\Carbon\Carbon::parse($data->tglpulang)->setTimezone('Asia/Jakarta')->format('d-m-Y H:i')}}</span>
                    </h5>
                    <h5 class="mx-auto text-secondary" style="font-weight: bold;">diverifikasi oleh Dokter
                        <span class="text-dark" style="font-weight: bold;">{{ $data->dokterPenolong }}</span>
                    </h5>
                </div>
                <div class="text-center text-break">
                    <h6 class="mx-auto text-secondary " style="font-weight: bold; margin-top: 20px;">
                        key.
                        {{ \Request::get('key') }}
                    </h6>
                    {{-- <div class="col-12"> --}}
                    {{-- </div> --}}
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>
