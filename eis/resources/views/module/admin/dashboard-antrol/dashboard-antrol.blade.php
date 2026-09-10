@extends('template.template3')
@section('css')
    <style>
        .form-control {
            display: block;
            width: 100%;
            padding: .5rem .75rem;
            font-size: 1rem;
            /* line-height: 1.25; */
            color: #495057;
            background-color: #fff;
            background-image: none;
            background-clip: padding-box;
            border: 1px solid rgba(0, 0, 0, .15);
            border-radius: .25rem;
            transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
        }

        body table tr td {
            font-size: 15px;
        }

        #return-to-top {
            z-index: 999;
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #404e67;
            width: 50px;
            height: 50px;
            display: block;
            text-decoration: none;
            -webkit-border-radius: 35px;
            -moz-border-radius: 35px;
            border-radius: 35px;
            display: none;
            -webkit-transition: all 0.3s linear;
            -moz-transition: all 0.3s ease;
            -ms-transition: all 0.3s ease;
            -o-transition: all 0.3s ease;
            transition: all 0.3s ease;
        }

        #return-to-top i {
            color: #fff;
            margin: 0;
            position: relative;
            left: 16px;
            top: 13px;
            font-size: 19px;
            -webkit-transition: all 0.3s ease;
            -moz-transition: all 0.3s ease;
            -ms-transition: all 0.3s ease;
            -o-transition: all 0.3s ease;
            transition: all 0.3s ease;
        }

        #return-to-top:hover {
            background: rgba(0, 0, 0, 0.62);
        }

        #return-to-top:hover i {
            color: #fff;
            top: 5px;
        }

        .pad {
            padding-top: 3rem;
        }

        @media (min-width: 992px) {
            .pad {
                padding-top: 1.8rem;
            }
        }

        /*.modal-lg .kons{*/
        /*    width:1140px;*/
        /*}*/
        .bg-isi {
            background: #00c0ef !important;
        }

        .bg-kosong {
            background: #d81b60c4 !important;
        }

        .color-text {
            color: white
        }

        #return-to-top {
            z-index: 999;
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #404e67;
            width: 50px;
            height: 50px;
            display: block;
            text-decoration: none;
            -webkit-border-radius: 35px;
            -moz-border-radius: 35px;
            border-radius: 35px;
            display: none;
            -webkit-transition: all 0.3s linear;
            -moz-transition: all 0.3s ease;
            -ms-transition: all 0.3s ease;
            -o-transition: all 0.3s ease;
            transition: all 0.3s ease;
        }

        #return-to-top i {
            color: #fff;
            margin: 0;
            position: relative;
            left: 16px;
            top: 13px;
            font-size: 19px;
            -webkit-transition: all 0.3s ease;
            -moz-transition: all 0.3s ease;
            -ms-transition: all 0.3s ease;
            -o-transition: all 0.3s ease;
            transition: all 0.3s ease;
        }

        #return-to-top:hover {
            background: rgba(0, 0, 0, 0.62);
        }

        #return-to-top:hover i {
            color: #fff;
            top: 5px;
        }
    </style>
@endsection
@section('content-body')
    <a href="javascript:" id="return-to-top"><i class="fa fa-chevron-up"></i></a>
    <div class="page-wrapper pad" id="id_template" style="margin-top:20px">
        <div class="page-body">
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <div class="card">
                        <div class="card-header" style="padding: .75rem 1.25rem;">
                            <h5>Dashboard Antrian Online </h5>
                            <div class="card-header-right">
                                <ul class="list-unstyled card-option">
                                    <li><i class="feather icon-maximize full-card"></i></li>
                                    <li><i class="feather icon-minus minimize-card"></i></li>
                                    <li><i class="feather icon-trash-2 close-card"></i></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-block">
                            @if($res['message'] != '')
                            <div class="col-lg-12 col-md-12 mt-2" >
                                <div class="alert alert-danger background-warnindangerg">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <i class="icofont icofont-close-line-circled text-white"></i>
                                    </button>
                                    <strong>Informasi!</strong> {{$res['message']}}
                                </div>
                            </div>
                            @endif

                            <div class="col-lg-12 col-md-12">
                                <form action="{!! route('show_page', ['role' => session('role'), 'pages' => $r->pages]) !!}" method="get">
                                    <div class="row" style="padding: 10px">
                                        <div class="col-lg-9">
                                        </div>
                                        <div class="col-lg-2" style="margin-top: 5px">
                                            <div class="input-group">
                                                <span class="input-group-addon" id="basic-addon1"><i
                                                        class="ti-calendar"></i></span>
                                                <input type="text" id="tglawal" name="tglawal"
                                                    class="month-custom form-control"
                                                    value="{{ request()->get('tglawal') }}">
                                            </div>

                                        </div>

                                        <div class="col-lg-1" style="margin-top: 5px">
                                            <button class="btn btn-success  btn-outline-success" type="submit">
                                                <i class="icofont icofont-search"></i>Search</button>
                                        </div>
                                    </div>
                                    @php
                                        $jmlAntre = 0;
                                        $jmlAnreLengkap = 0;
                                        $rata2Waktu = 0;
                                        $rata1 = 0;
                                        $rata2 = 0;
                                        $rata3 = 0;
                                        $rata4 = 0;
                                        $rata5 = 0;
                                        $rata6 = 0;
                                        foreach ($res['data'] as $key => $d) {
                                            $jmlAntre = $jmlAntre + $d->jumlah_antrean;
                                            $rata1 = $rata1 + $d->avg_waktu_task1;
                                            $rata2 = $rata2 + $d->avg_waktu_task2;
                                            $rata3 = $rata3 + $d->avg_waktu_task3;
                                            $rata4 = $rata4 + $d->avg_waktu_task4;
                                            $rata5 = $rata5 + $d->avg_waktu_task5;
                                            $rata6 = $rata6 + $d->avg_waktu_task6;

                                            if($d->avg_waktu_task1 == 0||$d->avg_waktu_task2 ==0 ||$d->avg_waktu_task3==0
                                            ||$d->avg_waktu_task4==0||$d->avg_waktu_task5==0||$d->avg_waktu_task6 ==0){
                                                $jmlAnreLengkap =$jmlAnreLengkap+ $d->jumlah_antrean;
                                            }
                                        }
                                        if ($jmlAntre > 0) {
                                            $rata1 = $rata1 / count($res['data']);
                                            $rata2 = $rata2 /  count($res['data']);
                                            $rata3 = $rata3 /  count($res['data']);
                                            $rata4 = $rata4 /  count($res['data']);
                                            $rata5 = $rata5 /  count($res['data']);
                                            $rata6 = $rata6 /  count($res['data']);
                                            $rata2Waktu = ($rata1+$rata2+$rata3+$rata4+$rata5+$rata6)/6 ;
                                            // dd($jmlAnreLengkap);
                                            $jmlAnreLengkap = $jmlAntre - $jmlAnreLengkap;
                                        }
                                        
                                    @endphp
                                    <div class="row">
                                        <div class=" col-md-12">
                                            <div class="col-lg-12 col-md-12">
                                                <div class="card">
                                                    <div class="panel panel-primary">
                                                        <div class="panel-heading bg-primary">
                                                            Summary
                                                        </div>
                                                        <div class="panel-body">
                                                            <div class="row">
                                                                <div class=" col-md-6">
                                                                    <div class="card"  style="margin: 10px; background: #f1edf8;
                                                                    box-shadow: 10px 10px 40px 10px rgb(100 108 111 / 8%);">
                                                                        <div class="card-block">
                                                                            <div class="row align-items-center m-l-0">
                                                                                <div class="col-auto">
                                                                                    <i
                                                                                        class="feather icon-users f-30 text-c-lite-green"></i>
                                                                                </div>
                                                                                <div class="col-auto">
                                                                                    <h6 class="text-muted m-b-10">Jumlah
                                                                                        Antrean
                                                                                        Total
                                                                                    </h6>
                                                                                    <h2 class="text-muted m-b-0">
                                                                                        {{ $jmlAntre }}</h2>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                {{-- <div class=" col-md-4">
                                                                    <div class="card">
                                                                        <div class="card-block">
                                                                            <div class="row align-items-center m-l-0">
                                                                                <div class="col-auto">
                                                                                    <i
                                                                                        class="feather icon-user f-30 text-c-green"></i>
                                                                                </div>
                                                                                <div class="col-auto">
                                                                                    <h6 class="text-muted m-b-10">Jumlah
                                                                                        Antrean
                                                                                        Lengkap
                                                                                    </h6>
                                                                                    <h2 class="text-muted m-b-0">{{$jmlAnreLengkap}}</h2>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div> --}}
                                                                <div class=" col-md-6">
                                                                    <div class="card"  style="margin: 10px; background: #f1edf8;
                                                                    box-shadow: 10px 20px 50px 10px rgb(100 108 111 / 8%);">
                                                                        <div class="card-block">
                                                                            <div class="row align-items-center m-l-0">
                                                                                <div class="col-auto">
                                                                                    <i
                                                                                        class="feather icon-clock f-30 text-c-pink"></i>
                                                                                </div>
                                                                                <div class="col-auto">
                                                                                    <h6 class="text-muted m-b-10">Rata-Rata
                                                                                        Total
                                                                                        Waktu Layan (Admisi-Farmasi)
                                                                                    </h6>
                                                                                    <h2 class="text-muted m-b-0">
                                                                                    {{  gmdate('H:i:s', $rata2Waktu) }} </h2>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <div class="card bg-c-pink text-white widget-visitor-card">
                                                            <div class="card-block-small text-center">
                                                                <h2>{{  gmdate('H:i:s', $rata1) }}</h2>
                                                                <h6>Rata-Rata Waktu Tunggu Admisi</h6>
                                                                <i class="feather icon-calendar"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="card bg-c-blue text-white widget-visitor-card">
                                                            <div class="card-block-small text-center">
                                                                <h2>{{ gmdate('H:i:s', $rata2) }}</h2>
                                                                <h6>Rata-Rata Waktu Tunggu Poli</h6>
                                                                <i class="feather icon-clock"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="card bg-c-yellow text-white widget-visitor-card">
                                                            <div class="card-block-small text-center">
                                                                <h2>{{ gmdate('H:i:s', $rata3) }}</h2>
                                                                <h6>Rata-Rata Waktu Tunggu Farmasi</h6>
                                                                <i class="feather icon-clipboard"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="card bg-c-kuning text-white widget-visitor-card">
                                                            <div class="card-block-small text-center">
                                                                <h2>{{ gmdate('H:i:s', $rata4) }}</h2>
                                                                <h6>Rata-Rata Waktu Layan Admisi</h6>
                                                                <i class="feather icon-calendar"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="card bg-c-hijau text-white widget-visitor-card">
                                                            <div class="card-block-small text-center">
                                                                <h2>{{ gmdate('H:i:s', $rata5) }}</h2>
                                                                <h6>Rata-Rata Waktu Layan Poli</h6>
                                                                <i class="feather icon-clock"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="card bg-c-ungu text-white widget-visitor-card">
                                                            <div class="card-block-small text-center">
                                                                <h2>{{ gmdate('H:i:s', $rata6) }}</h2>
                                                                <h6>Rata-Rata Waktu Layan Farmasi</h6>
                                                                <i class="feather icon-clipboard"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12">
                                                <div class="card">
                                                    <div class="panel panel-purple">
                                                        <div class="panel-heading bg-purple">
                                                            Perbulan
                                                        </div>

                                                        <div class="panel-body">
                                                            <div class="row" style="padding:10px 20px;">
                                                                <div class="col-md-12">
                                                                    <div class="table-responsive">
                                                                        <table
                                                                            class="table  table-striped table-sm table-styling"
                                                                            id="t_E-RESEP" style="width:100%">
                                                                            <thead class="table-default">
                                                                                <tr>
                                                                                    <th style="color:black;width: 10%"
                                                                                        rowspan="2">NO </th>
                                                                                    <th style="color:black"
                                                                                        rowspan="2">
                                                                                        TANGGAL </th>
                                                                                    <th style="color:black"
                                                                                        rowspan="2">
                                                                                        POLI
                                                                                    </th>
                                                                                    <th style="color:black"
                                                                                        rowspan="2">
                                                                                        JUMLAH ANTREAN </th>
                                                                                    <th style="color:black;text-align:center;"
                                                                                        colspan="6">RATA-RATA WAKTU
                                                                                         (HH:mm:ss) </th>

                                                                                </tr>
                                                                                <tr>
                                                                                    <th style="color:black"> TUNGGU ADMISI
                                                                                    </th>
                                                                                    <th style="color:black"> LAYAN ADMISI
                                                                                    </th>
                                                                                    <th style="color:black"> TUNGGU POLI
                                                                                    </th>
                                                                                    <th style="color:black"> LAYAN POLI
                                                                                    </th>
                                                                                    <th style="color:black"> TUNGGU FARMASI
                                                                                    </th>
                                                                                    <th style="color:black"> LAYAN FARMASI
                                                                                    </th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>

                                                                                @forelse($res['data'] as $i => $d)
                                                                                    <tr>
                                                                                        <td>{{ $i + 1 }}</td>
                                                                                        <td>{{ $d->tanggal }}</td>
                                                                                        <td>{{ $d->namapoli }}</td>
                                                                                        <td>{{ $d->jumlah_antrean }}</td>
                                                                                        <td>{{ gmdate('H:i:s', $d->avg_waktu_task1) }}
                                                                                        </td>
                                                                                        <td>{{ gmdate('H:i:s', $d->avg_waktu_task2) }}
                                                                                        </td>
                                                                                        <td>{{ gmdate('H:i:s', $d->avg_waktu_task3) }}
                                                                                        </td>
                                                                                        <td>{{ gmdate('H:i:s', $d->avg_waktu_task4) }}
                                                                                        </td>
                                                                                        <td>{{ gmdate('H:i:s', $d->avg_waktu_task5) }}
                                                                                        </td>
                                                                                        <td>{{ gmdate('H:i:s', $d->avg_waktu_task6) }}
                                                                                        </td>
                                                                                    </tr>
                                                                                @empty
                                                                                    <tr>
                                                                                        <td colspan="10"
                                                                                            style="text-align: center">
                                                                                            Belum
                                                                                            ada
                                                                                            data yang bisa ditampikan</td>
                                                                                    </tr>
                                                                                @endforelse

                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('javascript')
    <script>
        var APP_URL = {!! json_encode(url('/')) !!}


        // setTimeout(function() {
        //     window.location.reload();
        // }, 120000);
    </script>
@endsection
