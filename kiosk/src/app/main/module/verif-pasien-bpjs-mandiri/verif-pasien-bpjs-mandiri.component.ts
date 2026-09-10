import { HttpClient } from '@angular/common/http';
import { Component, OnInit, ViewEncapsulation } from '@angular/core';
import { FormBuilder, FormControl, FormGroup } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { NgbModal } from '@ng-bootstrap/ng-bootstrap';
import moment from 'moment';
import { ToastrService } from 'ngx-toastr';
import { Observable } from 'rxjs';
import { map } from 'rxjs/internal/operators/map';
import { catchError } from 'rxjs/operators';
import { AlertService } from '../alert.service';
import { CacheService } from '../cache.service';
import { Configuration } from '../config';
import { HttpService } from '../httpService';
import { QzprinterService } from '../qzprinter.service';
import { CoreConfigService } from '@core/services/config.service';
import { takeUntil } from 'rxjs/operators';
import { Subject } from 'rxjs';



@Component({
  selector: 'app-verif-pasien-bpjs-mandiri',
  templateUrl: './verif-pasien-bpjs-mandiri.component.html',
  styleUrls: ['./verif-pasien-bpjs-mandiri.component.scss'],
  encapsulation: ViewEncapsulation.None,
})
export class VerifPasienBpjsMandiriComponent implements OnInit {
  public coreConfig: any;
  private _unsubscribeAll: Subject<any>;
  validasi_data: boolean = false;
  contentHeader: any
  isCetakDSKiosk: any = 'true'
  resultdata: any;
  formGroup: FormGroup;
  isHalamanAwal: boolean = true;
  isHalamanPilihPoli: boolean = false;
  isHalamanCetak: boolean = false;
  isPenunjang: boolean = false;
  isFlagProcedur: boolean = false;
  isLoadingHalAwal: boolean = false;
  isLoadingScreen: boolean = false;
  isReservasiPoli: boolean = false;
  isloading: boolean = false;
  isInfoPasien: boolean = false;
  isTemporaryBrigding: string;
  ppkPelayananRS: any = "0233R779"
  eksekutif: any = 1
  kodeDokter: any = null
  noReservasi: any
  idRuanganReservasi: any
  listRuanganReserv: any[] = []
  isAdminOtomatisKiosk: any
  kelasrawat: any
  notelepons: any
  jnspeserta: any
  umrskrg: any
  item: any = {
    pasien: {},
    peserta: {},
    rujukan: {},
    diagnosa: {},
    antrian: {},
    reservasi: {},
    provPerujuk: {},
    livebridging : false
  }
  openFileName = "sidikjari:";
  btn_col: any = "col-6";
  fingercek: any
  isnotfinger: boolean = false
  isfinger: boolean = false
  pasienDaftar: any = {}
  pemAsuransi: any = {}
  dataSEP: any = {}
  listRuangan: any = []
  listRuanganTemp: any = []
  listDPJP: any[] = []
  listTujuanKunj: any[] = []
  listFlagProc: any[] = []
  listAssesment: any[] = []
  listSurkon: any[] = []
  listPenunjang: any[] = []
  listRujukan: any[] = []
  listMonitoringPelayanan: any[] = []
  listPendaftaranReservasi: any = {}
  listBadge: any[] = [
    'bg-light-info', 'bg-light-primary', 'bg-light-danger',
    'bg-light-warning', 'bg-light-success'
  ]
  loketId: any;
  constructor(private router: Router,
    private route: ActivatedRoute,
    private httpService: HttpService,
    private _Qzprinter: QzprinterService,
    private fb: FormBuilder,
    private cacheHelper: CacheService,
    private service: HttpClient,
    private alertService: ToastrService,
    private msgService: AlertService,
    private modalService: NgbModal,
    private _coreConfigService: CoreConfigService,
  ) {
    this._unsubscribeAll = new Subject();
    // Configure the layout
    this._coreConfigService.config = {
      layout: {
        navbar: {
          hidden: true
        },
        footer: {
          hidden: false
        },
        menu: {
          hidden: true
        },
        customizer: false,
        enableLocalStorage: false
      }
    };
    let sett = localStorage.getItem('isCetakDS')
    if (sett != null) {
      this.isCetakDSKiosk = sett
    }
  }

  ngOnInit(): void {
    // get loketId
    // this.route.params.subscribe(params => {
    //   this.loketId = localStorage.getItem('isLoket');
    // })

    this.loketId = localStorage.getItem('isLoket');

    this._coreConfigService.config.pipe(takeUntil(this._unsubscribeAll)).subscribe(config => {
      this.coreConfig = config;
    });

    this.kosongkanStorage();
    this.listTujuanKunj = [
      { id: "0", name: 'Normal' },
      { id: "1", name: 'Prosedur' },
      { id: "2", name: 'Konsul Dokter' },
    ]
    this.listFlagProc = [
      {
        id: "0", name: "Prosedur Tidak Berkelanjutan",
        details: [
          { id: "7", name: "Laboratorium" },
          { id: "8", name: "USG" },
          { id: "11", name: "MRI" },
          { id: "9", name: "Farmasi" },
          { id: "10", name: "Lain-Lain" },
        ]
      },
      {
        id: "1", name: "Prosedur dan Terapi Berkelanjutan",
        details: [
          { id: "1", name: "Radioterapi" },
          { id: "2", name: "Kemoterapi" },
          { id: "3", name: "Rehabilitasi Medik" },
          { id: "4", name: "Rehabilitasi Psikososial" },
          { id: "5", name: "Transfusi Darah" },
          { id: "6", name: "Pelayanan Gigi" },
          { id: "12", name: "HEMODIALISA" },
        ]
      },
    ]
    //id no 3 , 4 diganti ke 1 semua karna user suka keplese pilih itu. id 3.4 tidak generate sep baru. ambil sep sebelumnya
    this.listAssesment = [
      { id: "1", name: "Poli spesialis tidak tersedia pada hari sebelumnya" },
      { id: "2", name: "Jam Poli telah berakhir pada hari sebelumnya" },
      { id: "1", name: "Dokter Spesialis yang dimaksud tidak praktek pada hari sebelumnya" },
      { id: "1", name: "Atas Instruksi RS" },
      { id: "5", name: "Tujuan Kontrol" },
    ]

    this.listRuangan = []
    this.listRuanganTemp = []
    this.httpService.get('medifirst2000/kiosk/get-ruangan?loketid=' + localStorage.getItem('isLoket')).subscribe(e => {

      let data: any = e
      let x = 0;
      for (let i = 0; i < data.length; i++) {
        const element = data[i];

        if (x == 4) {
          x = 0
        } else {
          x = x + 1
        }
        element.badge = this.listBadge[x]
      }
      this.listRuangan = data
      this.listRuanganTemp = data
    })

    this.httpService.get('medifirst2000/kiosk/get-combo-setting').subscribe(resps => {
      this.ppkPelayananRS = resps.ppkpelayanan
      this.isTemporaryBrigding = resps.isTemporaryBrigding
      this.isAdminOtomatisKiosk = resps.isAdminOtomatisKiosk

    }, error => {
      this.isTemporaryBrigding = 'false'
      this.isAdminOtomatisKiosk = 'false'
      this.ppkPelayananRS = '0233R779'
    })

    this.formGroup = this.fb.group({
      'frmRujukan': new FormControl(''),
      'frmSurkon': new FormControl(''),
      'frmDpjp': new FormControl(''),
      'frmTujuanKunj': new FormControl(''),
      'frmFlagProcedur': new FormControl(''),
      'frmPenunjang': new FormControl(''),
      'frmAssesment': new FormControl(''),
    })
    // this.validasi_data = true;
    this._Qzprinter.connect();
    // this.loadCache()
  }

  goTo(name) {
    this.router.navigate([`touchscreen` + name]);
  }
  goToTouchscreen() {
    this.router.navigate([`touchscreen-v2`]);
    this.kosongkanStorage();
  }


  kosongkanStorage() {
    this.listSurkon = []
    this.listRujukan = []
    this.listMonitoringPelayanan = []
    this.listPendaftaranReservasi = []
    this.isReservasiPoli = false
    delete this.item.pasien
    delete this.item.peserta
    delete this.noReservasi
    delete this.idRuanganReservasi
    localStorage.removeItem("dataPasienLokal");
    localStorage.removeItem("dataRujukanLokal");
  }

  async cariDataPasien() {
    if (!this.item.frmIdentitas) {
      this.alertService.error('', 'Harap isi terlebih dahulu !', {
        toastClass: 'toast ngx-toastr',
        closeButton: true,
        positionClass: 'toast-top-left'
      });
      return;
    }

    this.isLoadingScreen = true;
    await this.httpService.get('registrasi/cek-pasien-piutang?nocm=' + this.item.frmIdentitas).subscribe(async er => {
      if (er.closing != null) {
        this.isloading = false
        this.alertService.info('Pasien belum diclosing pada kunjungan sebelumnya, silahkan ke kasir', er.closing.namapasien, {
          toastClass: 'toast ngx-toastr',
          closeButton: true,
          positionClass: 'toast-bottom-center'
        });
      } else if (er.piutang != null) {
        this.isloading = false
        this.alertService.info('Pasien mempunyai piutang yang belum dibayar, silahkan ke kasir', er.piutang.namapasien, {
          toastClass: 'toast ngx-toastr',
          closeButton: true,
          positionClass: 'toast-bottom-center'
        });
      } else {
        await this.httpService.get('medifirst2000/reservasionline/get-history?noReservasi=' + this.item.frmIdentitas + '&cekin=true').subscribe(async e => {
          this.isloading = false
          if (e.data.length > 0) {
            let result = e.data[0]

            if (result.objectkelompokpasienfk != 2) {
              this.alertService.error('', 'Bukan pasien BPJS, tidak bisa Generate SEP', {
                toastClass: 'toast ngx-toastr',
                closeButton: true,
                positionClass: 'toast-bottom-center'
              });
              return
            }

            this.resultdata = e.data[0]
            let now = new Date();
            let tglResDate = new Date(result.tanggalreservasi)
            var hours = this.diff_hours(tglResDate, now)

            if (moment(new Date()).format('YYYY-MM-DD') > moment(new Date(result.UntukTanggal)).format('YYYY-MM-DD')) {
              this.alertService.error('', 'Batas Waktu Check-In anda melebihi batas yang ditentukan', {
                toastClass: 'toast ngx-toastr',
                closeButton: true,
                positionClass: 'toast-bottom-center'
              });
              return
            }

            result.type == null ? result.type = '-' : result.type
            result.NRM == null ? result.NRM = '-' : result.NRM
            // result.nocm == null ? result.nocm = '-' : result.nocm
            result.tgllahir == null ? result.tgllahir = '-' : moment(new Date(result.tgllahir)).format('YYYY-MM-DD')
            result.tempatlahir == null ? result.tempatlahir = '-' : result.tempatlahir
            result.namaruangan == null ? result.namaruangan = '-' : result.namaruangan
            result.kelompokpasien == null ? result.kelompokpasien = '-' : result.kelompokpasien
            result.nobpjs == null ? result.nobpjs = '-' : result.nobpjs
            result.alamatlengkap == null ? result.alamatlengkap = '-' : result.alamatlengkap
            result.notelepon == null ? result.notelepon = '-' : result.notelepon
            result.tanggalreservasi = moment(new Date(result.tanggalreservasi)).format('YYYY-MM-DD')
            result.norujukan == null ? result.norujukan = '-' : result.norujukan
            result.nosuratkontrol == null ? result.nosuratkontrol = '-' : result.nosuratkontrol

            if (result.notelepon == null || result.notelepon == "")
              result.notelepon = '000000000000'


            this.isInfoPasien = true
            this.item.reservasi = result
            this.item.pasien = result;

            if (this.item.reservasi.nobpjs == "-" || this.item.reservasi.nobpjs == null) {
              this.isLoadingHalAwal = false;
              this.isLoadingScreen = false;
              
              this.alertService.error('', 'No BPJS belum terdaftar di simrs, silahkan menuju loket pendaftaran', {
                toastClass: 'toast ngx-toastr',
                closeButton: true,
                positionClass: 'toast-bottom-center'
              });
              await this.saveReservasi(this.item.reservasi);
              return
            } else {
              var tglakhir = moment(new Date()).format("YYYY-MM-DD");
              var jsonGetMonitoring = {
                "url": `Peserta/nokartu/${this.item.reservasi.nobpjs}/tglSEP/${tglakhir}`,
                "method": "GET",
                "data": null
              }
              await this.httpService.postNonMessage('medifirst2000/bridging/bpjs/tools', jsonGetMonitoring).subscribe(async result => {
                if (!Array.isArray(result) && result.metaData.code == "200") {
                  var res = result.response.peserta
                  this.kelasrawat = res.hakKelas.kode
                  this.notelepons = (res.mr.noTelepon == 'null' || res.mr.noTelepon == null || res.mr.noTelepon == '') ? '000000000000' : res.mr.noTelepon
                  this.jnspeserta = res.jenisPeserta.keterangan
                  this.umrskrg = res.umur.umurSekarang
                  this.item.peserta = res;
                  console.log('item peserta', res);
                  console.log('item peserta from item', this.item.peserta);

                  var bln = moment(new Date()).format("MM");
                  var thn = moment(new Date()).format("YYYY");

                  var nobpjs = this.item.peserta.noKartu

                  var jsonGetMonitoring = {
                    "url": `RencanaKontrol/ListRencanaKontrol/Bulan/${bln}/Tahun/${thn}/Nokartu/${nobpjs}/filter/${"2"}`,
                    "method": "GET",
                    "data": null
                  }

                  var tmpSurkon = null;
                  //Temp Surat Kontrol dulu :
                  await this.httpService.postNonMessage('medifirst2000/bridging/bpjs/tools', jsonGetMonitoring).subscribe(async res2 => {
                    if (res2.metaData.code == "200") {
                      this.item.lisSurkon = [];
                      this.item.listSurkon = res2.response.list;
                      this.item.listSurkon.forEach(response => {
                        if(tmpSurkon == null){
                          if (response.tglRencanaKontrol == tglakhir) {
                            tmpSurkon = response;
                            return;
                          } else {
                            tmpSurkon = null;
                          }
                        }
                      });
                    } else {
                      this.item.lisSurkon = [];
                      this.isLoadingScreen = false;
                      this.alertService.error('', 'Rencana Kontrol BPJS belum dibuat, silahkan menuju loket pendaftaran!', {
                        toastClass: 'toast ngx-toastr',
                        closeButton: true,
                        positionClass: 'toast-top-left'
                      });
                      await this.saveReservasi(this.item.reservasi);
                      return
                    }

                    this.validasi_data = false;
                    if (tmpSurkon != null) {
                      var jsonGet = {
                        "url": `/RencanaKontrol/noSuratKontrol/${tmpSurkon.noSuratKontrol}`,
                        "method": "GET",
                        "data": null
                      }
                      await this.httpService.postNonMessage('medifirst2000/bridging/bpjs/tools', jsonGet).subscribe(async res3 => {
                        if (res3.metaData.code == "200") {
                          this.item.suratkontrol = undefined;
                          this.item.suratkontrol = res3.response;

                          if (this.item.suratkontrol != undefined) {
                            this.validasi_data = true;
                            this.fingercek = await this.getfinger();
                            await this.sendAntrol(3)
                            this.isnotfinger = false;
                            this.isfinger = false;

                            this.isLoadingScreen = false;
                            this.isLoadingHalAwal = true;
                            if (this.fingercek == 'belum') {
                              // this.openExternalFile();
                              this.isnotfinger = true;
                              this.isfinger = true;
                              this.btn_col = "col-6 pl-1";
                              return;
                            } else {
                              this.isnotfinger = false;
                              this.isfinger = true;
                              this.btn_col = "col-12 pl-0";
                              // await this.sendAntrol(3)
                              await this.insertLiveBridging(this.item);

                            }
                          }
                        } else {
                          this.item.peserta.suratkontrol = undefined;
                          this.alertService.error('', 'Tidak dapat terhubung ke bpjs, silahkan mengambil antrian manual !', {
                            toastClass: 'toast ngx-toastr',
                            closeButton: true,
                            positionClass: 'toast-top-left'
                          });
                          return
                        }
                      })
                    } else {
                      this.item.lisSurkon = [];
                      this.isLoadingScreen = false;
                      this.alertService.error('', 'Tidak ada rencana kontrol BPJS untuk hari ini, silahkan menuju loket pendaftaran!', {
                        toastClass: 'toast ngx-toastr',
                        closeButton: true,
                        positionClass: 'toast-top-left'
                      });
                      await this.saveReservasi(this.item.reservasi);
                      return
                    }
                  })

                  // New Surkon get
                  // OPEN THIS IF TRYING POLI SORE
                  let isSuratKontrol = false;
                  // if(this.item.reservasi.nosuratkontrol != null && this.item.reservasi.nosuratkontrol != "" && this.item.reservasi.nosuratkontrol != "-") {
                  //   isSuratKontrol = true;
                  //   var jsonGet = {
                  //     "url": `/RencanaKontrol/noSuratKontrol/${this.item.reservasi.nosuratkontrol}`,
                  //     "method": "GET",
                  //     "data": null
                  //   }
                  //   await this.httpService.postNonMessage('medifirst2000/bridging/bpjs/tools', jsonGet).subscribe(async res3 => {
                  //     if (res3.metaData.code == "200") {
                  //       if(res3.response.tglRencanaKontrol != tglakhir) {
                  //         this.alertService.error('', 'Rencana kontrol tidak sesuai dengan tanggal Reservasi, silahkan menuju loket pendaftaran!', {
                  //           toastClass: 'toast ngx-toastr',
                  //           closeButton: true,
                  //           positionClass: 'toast-top-left'
                  //         });
                  //         await this.saveReservasi(this.item.reservasi, false);
                  //         return
                  //       }
                  //       this.item.suratkontrol = undefined;
                  //       this.item.suratkontrol = res3.response;

                  //       if (this.item.suratkontrol != undefined) {
                  //         this.validasi_data = true;
                  //         this.fingercek = await this.getfinger();
                  //         this.isnotfinger = false;
                  //         this.isfinger = false;

                  //         this.isLoadingScreen = false;
                  //         this.isLoadingHalAwal = true;
                  //         if (this.fingercek == 'belum') {
                  //           // this.openExternalFile();
                  //           this.isnotfinger = true;
                  //           this.isfinger = true;
                  //           this.btn_col = "col-6 pl-1";
                  //           return;
                  //         } else {
                  //           this.isnotfinger = false;
                  //           this.isfinger = true;
                  //           this.btn_col = "col-12 pl-0";
                  //           await this.sendAntrol(3)
                  //           await this.insertLiveBridging(this.item);
                  //         }
                  //       }
                  //     } else {
                  //       this.item.peserta.suratkontrol = undefined;
                  //       this.alertService.error('', 'Tidak dapat terhubung ke BPJS, silahkan mengambil antrian manual atau coba lagi!', {
                  //         toastClass: 'toast ngx-toastr',
                  //         closeButton: true,
                  //         positionClass: 'toast-top-left'
                  //       });
                  //       return
                  //     }
                  //   })
                  // }
                  // if(isSuratKontrol) return;

                  // if(isSuratKontrol == false && (this.item.reservasi.norujukan != null && this.item.reservasi.norujukan != "" && this.item.reservasi.norujukan != '-')) {
                  //   // Rujukan internal
                  //   let jeniskunjungan = 2;
                  //   let noref = this.item.reservasi.norujukan;

                  //   var jsonRujukan = {
                  //     "url": `Rujukan/${noref}`,
                  //     "jenis": "rujukan",
                  //     "method": "GET",
                  //     "data": null
                  //   }
                  //   await this.httpService.postNonMessage('medifirst2000/bridging/bpjs/tools', jsonRujukan).subscribe(async (resRujukan) => {
                  //     if (resRujukan.metaData.code == "200") {
                  //       let dataRujukan = resRujukan.response.rujukan;
                  //       if(dataRujukan.provPerujuk.kode != null || dataRujukan.provPerujuk.kode != 'null' || dataRujukan.provPerujuk.kode != '') {
                  //         noref = noref.replace(dataRujukan.provPerujuk.kode, '');
                  //       }
                  //       if(noref.indexOf('P') > -1 || noref.indexOf('Y') > -1) {
                  //         jeniskunjungan = 1;
                  //       }else if(noref.indexOf('K') > -1) {
                  //         jeniskunjungan = 3;
                  //       }
                  //       else if(noref.indexOf('B') > -1 ) {
                  //         jeniskunjungan = 4;
                  //       }
                  //       // bisi ilang left handed update
                  //       this.item.reservasi.norujukan  = dataRujukan.noKunjungan;
                  //       this.item.suratkontrol = undefined;
                  //       this.item.suratkontrol = {
                  //         poliTujuan: dataRujukan.poliRujukan.kode,
                  //         kodeDokter: this.item.reservasi.kddokterbpjs,
                  //         namaDokter: this.item.reservasi.dokter,
                  //         noSuratKontrol: dataRujukan.noKunjungan,
                  //         sep: {
                  //           diagnosa: dataRujukan.diagnosa.kode,
                  //           poli: dataRujukan.poliRujukan.kode,
                  //           provPerujuk: {
                  //             asalRujukan: dataRujukan.asalFaskes,
                  //             tglRujukan: dataRujukan.tglKunjungan,
                  //             noRujukan: dataRujukan.noKunjungan,
                  //             kdProviderPerujuk: dataRujukan.provPerujuk.kode,

                  //           }
                  //         },
                  //       }
                  //       if (this.item.suratkontrol != undefined) {
                  //         this.validasi_data = true;
                  //         this.fingercek = await this.getfinger();
                  //         this.isnotfinger = false;
                  //         this.isfinger = false;

                  //         this.isLoadingScreen = false;
                  //         this.isLoadingHalAwal = true;
                  //         if (this.fingercek == 'belum') {
                  //           // this.openExternalFile();
                  //           this.isnotfinger = true;
                  //           this.isfinger = true;
                  //           this.btn_col = "col-6 pl-1";
                  //           return;
                  //         } else {
                  //           this.isnotfinger = false;
                  //           this.isfinger = true;
                  //           this.btn_col = "col-12 pl-0";
                  //           await this.sendAntrol(jeniskunjungan)
                  //           await this.insertLiveBridging(this.item, jeniskunjungan);

                  //         }
                  //       }

                  //     }else {
                  //       this.item.peserta.suratkontrol = undefined;
                  //       this.alertService.error('', 'Rujukan tidak valid, silahkan ke loket pendaftaran!', {
                  //         toastClass: 'toast ngx-toastr',
                  //         closeButton: true,
                  //         positionClass: 'toast-top-left'
                  //       });
                  //       await this.saveReservasi(this.item.reservasi, false);
                  //       return
                  //     }
                  //   })
                  // }

                }else {
                  this.isInfoPasien = false
                  this.alertService.error('', 'BPJS tidak ditemukan atau peserta tidak aktif', {
                    toastClass: 'toast ngx-toastr',
                    closeButton: true,
                    positionClass: 'toast-bottom-center'
                  });
                }
              })
            }
          } else {
            this.isInfoPasien = false
            this.alertService.error('', 'Data tidak ditemukan', {
              toastClass: 'toast ngx-toastr',
              closeButton: true,
              positionClass: 'toast-bottom-center'
            });
          }
        }, error => {
          this.isloading = false
          this.isInfoPasien = false
          this.alertService.error('', 'Data tidak ditemukan', {
            toastClass: 'toast ngx-toastr',
            closeButton: true,
            positionClass: 'toast-bottom-center'
          });
        })
      }
      this.isLoadingScreen = false;
    })
  }

  saveAntrianLoket(data) {
    let jenis = ''
    let nocmfk = ''
    let kelompokpasien = 2
    let jenispelayanan = 1

    if (data.kebangsaan.indexOf('WNA') > -1) {
      jenis = 'PN'
    } else {
      jenis = 'LB'
    }

    if (data.namaruangan.indexOf('VIP') > -1) {
      jenispelayanan = 1
    }

    let antrian = {
      "jenis": jenis,
      "ruanganfk": data.id,
      "namaruangan": data.namaruangan,
      "nocmfk": data.nocmfk,
      "loketid": data.loketid,
      "nopeserta": data.noKartu !== undefined ? data.noKartu : null,
      "namapeserta": data.nama !== null ? data.nama : null,
      "kelompokpasien": data.kelompokpasien,
      "jenispelayanan": jenispelayanan,
      "noantrian": null,
      "ruanganfklama": data.ruanganfklama
    }

    this.httpService.get('medifirst2000/kiosk/get-slotting-kosong?ruanganfk=' + data.id).subscribe(es => {
      let es2: any = es
      if (es2.status == true) {
        this.httpService.post('medifirst2000/kiosk/save-antrian', antrian).subscribe(response => {
          if(response.metaData.code != 200) {
            this.alertService.info('', response.metaData.message, {
              toastClass: 'toast ngx-toastr',
              closeButton: true,
              positionClass: 'toast-bottom-center'
            });
            return
          }else {
            this.pasienDaftar.norec_pd = response.norec_pd
            this.simpanPemakaianAsuransiCheckin(response.norec_pd, response.noregistrasi);
  
            let res: any
            // this._Qzprinter.prinBlade(`dashboard/registrasi/cetak-label-pasien?noregistrasi=` + response.noregistrasi, 'LABEL PASIEN', 10)
            this._Qzprinter.prinBlade(`medifirst2000/report/cetak-antrian?norec=` + response.noRec + '&noregistrasi=' + response.noregistrasi, 'ANTRIAN LOKET', 1)
  
            let kebangsaan = 0
            if (response.pasien.objectkebangsaanfk != null) {
              kebangsaan = response.pasien.objectkebangsaanfk
            } else {
              if (jenis == 'PN') {
                kebangsaan = 3
              } else {
                kebangsaan = 1
              }
            }
  
            let json = {
              "norec": response.norec_pd,
              "norec_apd": response.norec_apd,
              "objectkebangsaanfk": kebangsaan
            }
            this.httpService.post('registrasi/save-adminsitrasi', json).subscribe(responsex => {
              this.router.navigate(['touchscreen-v2']);
            })
          }
        })

      } else {
        this.alertService.info('', es2.status, {
          toastClass: 'toast ngx-toastr',
          closeButton: true,
          positionClass: 'toast-bottom-center'
        });
        return
      }
    })
  }

  async saveReservasi(data, statuskirim = true) {
    var antrian = {
      "jenis": data.jenis,
      "ruanganfk": data.idruangan,
      "namaruangan": data.namaruangan,
      "nocmfk": data.nocmfk,
      "loketid": data.loketkiosk,
      "nopeserta": data.nobpjs !== null ? data.nobpjs : null,
      "namapeserta": data.namapasien !== null ? data.namapasien : null,
      "kelompokpasien": data.objectkelompokpasienfk,
      "objectpegawaifk": data.iddokter,
      "jenispelayanan": 1,
      "norec": data.norec,
      "noantrian": data.noantrian,
      "noantrianpoli": data.noantrianpoli
    }

    this.httpService.post('medifirst2000/kiosk/save-antrian', antrian).subscribe(async response => {
      console.log("RESPONSE", response);
      if(!response.metaData && response.length == 0) {
        // terdaftar di poli biasanya errornya 

        // this.alertService.info('', 'Terjadi kesalahan', {
        //   toastClass: 'toast ngx-toastr',
        //   closeButton: true,
        //   positionClass: 'toast-bottom-center'
        // });
        return
      }else {
        if(statuskirim == true) await this.simpanPemakaianAsuransiCheckin(response.norec_pd, response.noregistrasi);
        await this.simpanMonitoring(response.norec_pd)
  
        let kebangsaan = 0
        if (response.pasien.objectkebangsaanfk != null) {
          kebangsaan = response.pasien.objectkebangsaanfk
        } else {
          if (data.jenis == 'PN') {
            kebangsaan = 3
          } else {
            kebangsaan = 1
          }
        }
  
        let json = {
          "norec": response.norec_pd,
          "norec_apd": response.norec_apd,
          "objectkebangsaanfk": kebangsaan
        }
        await this.httpService.post('registrasi/save-adminsitrasi', json).subscribe(async responsex => {
          let res: any
          console.log('LIVE', this.item.livebridging)
          if (this.isCetakDSKiosk == 'true') {
            if (this.item.livebridging == true) {
              console.log('CETAKAN', 'ADA LABEL')
  
              this._Qzprinter.prinBlade(`dashboard/registrasi/cetak-label-pasien?noregistrasi=` + response.noregistrasi, 'LABEL PASIEN', 5)
              // this._Qzprinter.prinBlade(`medifirst2000/report/bukti-pendaftaran?noregistrasi=` + response.noregistrasi, 'ANTRIAN POLI', 1)
              this._Qzprinter.prinBlade(`medifirst2000/report/cetak-antrian?norec=` + response.noRec + '&noregistrasi=' + response.noregistrasi, 'ANTRIAN LOKET', 1)
            } else{
              console.log('CETAKAN', 'TANPA LABEL')
  
              this._Qzprinter.prinBlade(`medifirst2000/report/cetak-antrian?norec=` + response.noRec + '&noregistrasi=' + response.noregistrasi, 'ANTRIAN LOKET', 1)
            }
            // this._Qzprinter.prinBlade(`dashboard/registrasi/cetak-label-pasien?noregistrasi=` + response.noregistrasi, 'LABEL PASIEN', 10)
          }
          else {
            if (this.item.livebridging == true) {
              console.log('CETAKAN', 'ADA LABEL')
  
              this._Qzprinter.prinBlade(`dashboard/registrasi/cetak-label-pasien?noregistrasi=` + response.noregistrasi, 'LABEL PASIEN', 5)
              // this._Qzprinter.prinBlade(`medifirst2000/report/bukti-pendaftaran?noregistrasi=` + response.noregistrasi, 'ANTRIAN POLI', 1)
              this._Qzprinter.prinBlade(`medifirst2000/report/cetak-antrian?norec=` + response.noRec + '&noregistrasi=' + response.noregistrasi, 'ANTRIAN LOKET', 1)
            } else{
              console.log('CETAKAN', 'TANPA LABEL')
  
              this._Qzprinter.prinBlade(`medifirst2000/report/cetak-antrian?norec=` + response.noRec + '&noregistrasi=' + response.noregistrasi, 'ANTRIAN LOKET', 1)
            }
            // this._Qzprinter.prinBlade(`dashboard/registrasi/cetak-label-pasien?noregistrasi=` + response.noregistrasi, 'LABEL PASIEN', 10)
          }
          this.router.navigate(['touchscreen-v2']);
        })
      }
    })
  }

  openExternalFile() {
    // window.location.href = this.openFileName;
    window.open(this.openFileName, '_blank')
  }

  noRmFormat() {
    let val = this.item.frmIdentitas

    let rules = {
      2: '.',
      5: '.',
    }
    let formatter = val.split('')
    for (let key in rules) {
      let i = Number(key);
      if (formatter.length >= (i + 1) && formatter[i] !== rules[i]) {
        formatter[i - 1] = formatter[i - 1] + rules[i];
      }
    }
    let formatted = formatter.join('');
    this.item.frmIdentitas=formatted
    // item.qnocm = formatted;
  }

  async getfinger() {
    return new Promise((resolve, rejects) => {
      var time = new Date().getFullYear() - new Date(this.item.peserta.tglLahir).getFullYear();
      if (time >= 17) {
        var dataSend = {
          "url": `SEP/FingerPrint/Peserta/${this.item.peserta.noKartu}/TglPelayanan/${moment(new Date()).format('YYYY-MM-DD')}`,
          "method": "GET",
          "data": null
        }
        this.httpService.postNonMessage("medifirst2000/bridging/bpjs/tools", dataSend).subscribe(resFinger => {
          if (resFinger.metaData.code == 200) {
            if (resFinger.response.kode == "1") {
              return resolve("sudah");
            } else {
              this.alertService.error(resFinger.response.status, "Fingerprint");
              return resolve("belum");
            }
          } else {
            this.alertService.error(resFinger.metaData.message, "Fingerprint");
          }
        })
      } else {
        return resolve("sudah");
      }
    })
  }

  async simpanBridging() {
    await this.insertLiveBridging(this.item);
  }

  async insertLiveBridging(data, jenis = 3) {
    let eksekutif = "0"

    var tujuankunjungan: any = "0";
    var assesmentPel: any = "0";
    var tmpDiag = data.suratkontrol.sep.diagnosa.split("-");
    var diagnosa = tmpDiag[0].trim();
    var tmpPoli = data.suratkontrol.sep.poli.split("-");
    var poliSebelumnya = tmpPoli[0].trim(); 
    if(jenis == 3 ) {
      if(poliSebelumnya == data.suratkontrol.poliTujuan){
        tujuankunjungan = "2" //konsul dokter
        assesmentPel = "5";   //Tujuan Kontrol
  
      }else if(poliSebelumnya !== data.suratkontrol.poliTujuan){
        tujuankunjungan = "0" //normal
        assesmentPel = "2"    //Jam Poli telah berakhir pada hari sebelumnya
      }
    }else {
      tujuankunjungan = "0" //normal
      assesmentPel = "2"    //Jam Poli telah berakhir pada hari sebelumnya
    }



    let resfinger = await this.getfinger();

    if (resfinger && resfinger == "sudah") {
      var dataSend = {
        "url": "SEP/2.0/insert",
        "method": "POST",
        "data": {
          "request": {
            "t_sep": {
              "noKartu": data.peserta.noKartu,
              "tglSep": moment(new Date()).format('YYYY-MM-DD'),
              "ppkPelayanan": "0233R779",
              "jnsPelayanan": "2",
              "klsRawat": {
                "klsRawatHak": data.peserta.hakKelas.kode.toString(),
                "klsRawatNaik": "",
                "pembiayaan": "",
                "penanggungJawab": ""
              },
              "noMR": data.peserta.mr.noMR,
              "rujukan": {
                "asalRujukan": data.suratkontrol.sep.provPerujuk.asalRujukan,
                "tglRujukan": data.suratkontrol.sep.provPerujuk.tglRujukan,
                "noRujukan": data.suratkontrol.sep.provPerujuk.noRujukan,
                "ppkRujukan": data.suratkontrol.sep.provPerujuk.kdProviderPerujuk
              },
              "catatan": "e-kiosk-mandiri",
              "diagAwal": diagnosa,
              "poli": {
                "tujuan": data.suratkontrol.poliTujuan,
                "eksekutif": eksekutif
              },
              "cob": {
                "cob": "0"
              },
              "katarak": {
                "katarak": "0"
              },
              "jaminan": {
                "lakaLantas": "0",
                "penjamin": {
                  "tglKejadian": "",
                  "keterangan": "",
                  "suplesi": {
                    "suplesi": "0",
                    "noSepSuplesi": "",
                    "lokasiLaka": {
                      "kdPropinsi": "",
                      "kdKabupaten": "",
                      "kdKecamatan": ""
                    }
                  }
                }
              },
              "tujuanKunj": tujuankunjungan,
              "flagProcedure": "",
              "kdPenunjang": "",
              "assesmentPel": assesmentPel,
              "skdp": {
                "noSurat": jenis == 3 ? (data.suratkontrol.noSuratKontrol == null  ? "" : data.suratkontrol.noSuratKontrol) : "",
                "kodeDPJP": jenis == 3 ? (data.suratkontrol.kodeDokterPembuat == null ? "" : data.suratkontrol.kodeDokterPembuat) : ""
              },
              "dpjpLayan": data.suratkontrol.kodeDokter == null ? "" : data.suratkontrol.kodeDokter,
              "noTelp": data.peserta.mr.noTelepon,
              "user": "e-kiosk-mandiri"
            }
          }
        }
      }

      this.item.provPerujuk.kode=data.suratkontrol.sep.provPerujuk.kdProviderPerujuk
      this.item.provPerujuk.nama=data.suratkontrol.sep.provPerujuk.asalRujukan
      this.item.provPerujuk.kddokter=data.suratkontrol.kodeDokter == null ? "" : data.suratkontrol.kodeDokter
      this.item.provPerujuk.namadokter=data.suratkontrol.namaDokter == null ? "" : data.suratkontrol.namaDokter
      this.item.provPerujuk.diagnosanama=data.suratkontrol.sep.diagnosa
      this.item.provPerujuk.diagnosakode=diagnosa
      this.item.provPerujuk.klsrawathakkode=data.peserta.hakKelas.kode
      this.item.provPerujuk.klsrawathaknama='Kelas' + data.peserta.hakKelas.kode
      this.item.provPerujuk.tujuankunjungankode=tujuankunjungan
      this.item.provPerujuk.nosurat= jenis == 3 ? data.suratkontrol.noSuratKontrol : ""
      this.item.provPerujuk.notelp=data.peserta.mr.noTelepon
      this.item.provPerujuk.norujukan=data.suratkontrol.sep.provPerujuk.noRujukan
      this.item.provPerujuk.tglrujukan=data.suratkontrol.sep.provPerujuk.tglRujukan

      
      if(tujuankunjungan == "2"){
      this.item.provPerujuk.tujuankunjungannama='Konsul Dokter'
      } else if(tujuankunjungan == "0"){
        this.item.provPerujuk.tujuankunjungannama='Normal'
      } else{
        this.item.provPerujuk.tujuankunjungannama=''
      }
      this.item.provPerujuk.isrujukaninternal=null
      this.item.provPerujuk.assesmentpelkode=assesmentPel
      if(assesmentPel == "5"){
      this.item.provPerujuk.assesmentpelnama='Tujuan Kontrol'
      } else if(assesmentPel == "2"){
        this.item.provPerujuk.assesmentpelnama='Jam Poli telah berakhir pada hari sebelumnya'
        this.item.provPerujuk.isrujukaninternal=true
      } else{
        this.item.provPerujuk.assesmentpelnama=''
      }
      
      // console.log(dataSend);
      // return;
      await this.httpService.postNonMessage("medifirst2000/bridging/bpjs/tools", dataSend).subscribe(async resSEP => {
        if( resSEP.metaData.code == 200){
          this.pasienDaftar.noregistrasi = null;//es.data[0].noregistrasi
          this.pasienDaftar.norec_pd = null;//es.data[0].norec_pd
          if (resSEP.response != null) {
            this.dataSEP.nosep = resSEP.response.sep.noSep
            this.dataSEP.tglSep = resSEP.response.sep.tglSep
            this.msgService.success('Status', 'Generate SEP Success. No SEP : ' + resSEP.response.sep.noSep);
            this.item.livebridging = true
            this.saveReservasi(this.item.reservasi);
          } else {
            this.msgService.error('Gagal Generate SEP', resSEP.metaData.message);
            this.item.livebridging = false

            console.log(dataSend)
          }
        }else{
          this.msgService.error('Gagal Generate SEP', resSEP.metaData.message);
          this.item.livebridging = false

          console.log(dataSend)
        }
      }, err => {
        this.msgService.error('Gagal Generate SEP', JSON.stringify(err));
        this.item.livebridging = false

        return 'gagal';
      });
    }
  }

  diff_hours(dt2, dt1) {
    var diff = (dt2.getTime() - dt1.getTime()) / 1000;
    diff /= (60 * 60);
    return Math.abs(diff);//Math.abs(Math.round(diff));    
  }

  async simpanMonitoring(norec_pd) {
    var data = {
      "url": "antrean/updatewaktu",
      "jenis": "antrean",
      "method": "POST",
      "data":
      {
        "kodebooking": this.item.reservasi.noreservasi,
        "taskid": 1,
        "waktu": new Date().getTime()
      }
    }
    this.httpService.postNonMessage('medifirst2000/bridging/bpjs/tools', data).subscribe(e => {
    this.saveLogging('Antrol Task ID'
      , 'norec Pasien Daftar'
      , this.item.reservasi.noreservasi
      , 'Tambah Task Id 1 ' + this.item.reservasi.noreservasi + ' | ' + JSON.stringify(data) + ' | ' + JSON.stringify(e))

      this.saveMonitoringTaksId(norec_pd, 1, new Date().getTime(), false);
      if (e.metaData.code == 200) {
        var data = {
          "url": "antrean/updatewaktu",
          "jenis": "antrean",
          "method": "POST",
          "data":
          {
            "kodebooking": this.item.reservasi.noreservasi,
            "taskid": 2,
            "waktu": moment(new Date()).add(5, 'm').valueOf()
          }
        }
        this.httpService.postNonMessage('medifirst2000/bridging/bpjs/tools', data).subscribe(e => {
        this.saveLogging('Antrol Task ID'
          , 'norec Pasien Daftar'
          , this.item.reservasi.noreservasi
          , 'Tambah Task Id 2 ' + this.item.reservasi.noreservasi + ' | ' + JSON.stringify(data) + ' | ' + JSON.stringify(e))
    
          this.saveMonitoringTaksId(norec_pd, 2, moment(new Date()).add(5, 'm').valueOf(), false);
          if (e.metaData.code == 200) {
            var data = {
              "url": "antrean/updatewaktu",
              "jenis": "antrean",
              "method": "POST",
              "data":
              {
                "kodebooking": this.item.reservasi.noreservasi,
                "taskid": 3,
                "waktu": moment(new Date()).add(10, 'm').valueOf()
              }
            }
            this.httpService.postNonMessage('medifirst2000/bridging/bpjs/tools', data).subscribe(e => {
            this.saveLogging('Antrol Task ID'
              , 'norec Pasien Daftar'
              , this.item.reservasi.noreservasi
              , 'Tambah Task Id 3 ' + this.item.reservasi.noreservasi + ' | ' + JSON.stringify(data) + ' | ' + JSON.stringify(e))
        
              this.saveMonitoringTaksId(norec_pd, 3, moment(new Date()).add(10, 'm').valueOf(), false);
        
              if (e.metaData.code == 200) {
                var data = {
                  "url": "antrean/updatewaktu",
                  "jenis": "antrean",
                  "method": "POST",
                  "data":
                  {
                    "kodebooking": this.item.reservasi.noreservasi,
                    "taskid": 4,
                    "waktu": moment(new Date()).add(20, 'm').valueOf(),
                  }
                }
                this.httpService.postNonMessage('medifirst2000/bridging/bpjs/tools', data).subscribe(e => {
                this.saveLogging('Antrol Task ID'
                  , 'norec Pasien Daftar'
                  , this.item.reservasi.noreservasi
                  , 'Tambah Task Id 4 ' + this.item.reservasi.noreservasi + ' | ' + JSON.stringify(data) + ' | ' + JSON.stringify(e))
        
                  this.saveMonitoringTaksId(norec_pd, 4, moment(new Date()).add(20, 'm').valueOf(), false);
        
                  if (e.metaData.code == 200) {
                    var data = {
                      "url": "antrean/updatewaktu",
                      "jenis": "antrean",
                      "method": "POST",
                      "data":
                      {
                        "kodebooking": this.item.reservasi.noreservasi,
                        "taskid": 5,
                        "waktu": moment(new Date()).add(30, 'm').valueOf(),
                      }
                    }
                    this.httpService.postNonMessage('medifirst2000/bridging/bpjs/tools', data).subscribe(e => {
                    this.saveLogging('Antrol Task ID'
                      , 'norec Pasien Daftar'
                      , this.item.reservasi.noreservasi
                      , 'Tambah Task Id 5 ' + this.item.reservasi.noreservasi + ' | ' + JSON.stringify(data) + ' | ' + JSON.stringify(e))
                    })
        
                    this.saveMonitoringTaksId(norec_pd, 5, moment(new Date()).add(30, 'm').valueOf(), false);
        
                    
                  }
        
                })
                
              }
        
            })
          }
        })
      }
    })
  }

  async simpanPemakaianAsuransiCheckin(norec_pd_checkin, noregistrasi) {
    this.httpService.get('medifirst2000/reservasionline/get-pasien-by-no-rm?norm=' + this.item.pasien.nocm).subscribe((resRM) => {
      this.item.pasien = resRM.data;
      console.log('PASIEN', this.item.pasien)
      // if(!this.item.pasien) {
      // }
      let kelas: any = ""
      if (this.item.peserta.hakKelas.kode == "1")
        kelas = 3
      else if (this.item.peserta.hakKelas.kode == "2")
        kelas = 2
      else if (this.item.peserta.hakKelas.kode == "3")
        kelas = 1
  
      let asuransipasien = {
        'id': '',
        'noregistrasi': noregistrasi,
        'nocm': this.item.pasien.nocm,
        'alamatlengkap': '',
        'objecthubunganpesertafk': 1,//Peserta
        'objectjeniskelaminfk': this.item.peserta.sex == 'P' ? 2 : 1,
        'kdinstitusiasal': 2552,
        'kdpenjaminpasien': 2552,
        'objectkelasdijaminfk': kelas,
        'namapeserta': this.item.peserta.nama,
        'nikinstitusiasal': 2552,
        'noasuransi': this.item.peserta.noKartu,
        'alamat': '',
        'nocmfk': this.item.pasien.id,
        'noidentitas': this.item.peserta.nik,
        'qasuransi': 2,
        'kelompokpasien': 2,
        'tgllahir': moment(new Date(this.item.peserta.tglLahir)).format('YYYY-MM-DD'),
        'jenispeserta': this.item.peserta.jenisPeserta.keterangan,
        'kdprovider': this.item.provPerujuk.kode,
        'nmprovider': this.item.provPerujuk.nama,
        'notelpmobile': this.item.peserta.mr.noTelepon,
      }
      let asalrujukan = "2"
      // if (this.formGroup.get('pCare').value != null) {
      //   if (this.formGroup.get('pCare').value == 'pcare') {
      //     asalrujukan = '1'
      //   } else {
      //     asalrujukan = '2'
      //   }
      // }
  
      let pemakaianasuransi = {
        'norec': '',
        'noregistrasifk': norec_pd_checkin,
        'tglregistrasi': moment(new Date()).format('YYYY-MM-DD HH:mm'),
        'tglsep': moment(new Date()).format('YYYY-MM-DD'),
        'diagnosisfk': this.item.diagnosa.id != null ? this.item.diagnosa.id : null,
        'lakalantas': 0,
        'nokepesertaan': this.item.peserta.noKartu,
        'nokartu': this.item.peserta.noKartu,
        'norujukan': this.item.provPerujuk.norujukan,
        'nosep': this.dataSEP.nosep,
        'tglrujukan': this.item.provPerujuk.tglrujukan,
        'objectdiagnosafk': this.item.diagnosa.id != null ? this.item.diagnosa.id : null,
        'tanggalsep': this.dataSEP.tglSep,
        'catatan': '',
        'lokasilaka': null,
        'penjaminlaka': null,
        'cob': false,
        'katarak': false,
        'keterangan': "",
        'tglkejadian': null,
        'suplesi': false,
        'nosepsuplesi': "",
        'kdpropinsi_kode': null,
        'kdpropinsi_nama': null,
        'kdkabupaten_kode': null,
        'kdkabupaten_nama': null,
        'kdkecamatan_kode': null,
        'kdkecamatan_nama': null,
        'kdprovider': this.item.provPerujuk.kode,
        'nmprovider': this.item.provPerujuk.nama,
        'nosuratskdp': "",
        'kodedpjp': this.item.provPerujuk.kddokter,
        'namadpjp': this.item.provPerujuk.namadokter,
        'dpjplayan_kode': this.item.provPerujuk.kddokter,
        'dpjplayan_nama': this.item.provPerujuk.namadokter,
        'prolanisprb': null,
        'diagawal_kode': this.item.provPerujuk.diagnosakode,
        'diagawal_nama': this.item.provPerujuk.diagnosanama,
        'klsrawathak_kode': this.item.provPerujuk.klsrawathakkode,
        'klsrawathak_nama': this.item.provPerujuk.klsrawathaknama,
        'klsrawatnaik_kode': null,
        'klsrawatnaik_nama': null,
        'pembiayaan_kode': null,
        'pembiayaan_nama': null,
        'poli_kode': null,
        'poli_nama': null,
        'eksekutif': null,
        'flagprocedure_kode': null,
        'flagprocedure_nama': null,
        'kdpenunjang_kode': null,
        'kdpenunjang_nama': null,
        'isrujukaninternal': null,
        'lakalantas_kode': 0,
        'lakalantas_nama': 'Bukan Kecelakaan',
        'nolp': null,
        'kelasfk': kelas,
        'asalrujukan': asalrujukan,
        'nosurat': this.item.provPerujuk.nosurat,
        'ppkrujukan': this.item.provPerujuk.kode,
        'ppkrujukan_nama': this.item.provPerujuk.nama,
      'ppkpelayanan': '0233R779',
      'jnspelayanan': 2,
        'tujuankun_kode': this.item.provPerujuk.tujuankunjungankode,
        'tujuankun_nama': this.item.provPerujuk.tujuankunjungannama,
        'assesmentpel_kode': this.item.provPerujuk.assesmentpelkode,
        'assesmentpel_nama': this.item.provPerujuk.assesmentpelnama,
        'user': 'Kiosk',
        'notelp': this.item.provPerujuk.notelp,
        'nomr': this.item.pasien.nocm,
        'backdate': false,
        'LOG': 'Tambah No. SEP ' + this.dataSEP.nosep
      }
      var objSave = {
        'asuransipasien': asuransipasien,
        'pemakaianasuransi': pemakaianasuransi
      }
      this.httpService.post('registrasi/pemakaian-asuransi/save', objSave).subscribe(e => {
  
      })
    })
  }

  cariDataPasienLagi() {
    
  }

  cariDataPasienOld() {
    if (!this.item.frmIdentitas) {
      this.alertService.error('', 'Harap isi terlebih dahulu !', {
        toastClass: 'toast ngx-toastr',
        closeButton: true,
        positionClass: 'toast-top-left'
      });
      return;
    }

    this.isLoadingHalAwal = true;
    this.httpService.get('medifirst2000/kiosk/get-data-pasien/' + this.item.frmIdentitas).subscribe(e => {
      if (e.data === null) {
        delete this.noReservasi;
        this.isLoadingHalAwal = false;
        this.alertService.error('', 'Data pasien tidak ditemukan !', {
          toastClass: 'toast ngx-toastr',
          closeButton: true,
          positionClass: 'toast-top-left'
        });
        return
      } else {
        if (e.data.nobpjs == "" || e.data.nobpjs == null) {
          delete this.noReservasi;
          this.isLoadingHalAwal = false;
          this.alertService.error('', 'Harap menuju loket untuk validasi nomor bpjs !', {
            toastClass: 'toast ngx-toastr',
            closeButton: true,
            positionClass: 'toast-top-left'
          });
          return
        }
      }

      this.isReservasiPoli = e.flagresevpoli
      if (e.flagresevpoli) {
        this.noReservasi = e.pendaftaran.statusschedule;
        this.idRuanganReservasi = e.pendaftaran.objectruanganlastfk;
        this.listPendaftaranReservasi = e.pendaftaran;
      }

      if (e.pendaftaran == null || this.isReservasiPoli == true) {
        localStorage.setItem("dataPasienLokal", JSON.stringify(e.data));
        localStorage.setItem("dataKunjungan", JSON.stringify(e.kunjterakhir));
        var tglawal = moment(e.kunjterakhir.tglregistrasi).format("YYYY-MM-DD");
        var tglakhir = moment(new Date()).format("YYYY-MM-DD");
        var nobpjs = ''
        nobpjs = e.data.nobpjs
        // console.log(e.data)

        if (e.kunjterakhir.objectdepartemenfk == 16 || e.kunjterakhir.objectdepartemenfk == 66 || e.kunjterakhir.objectdepartemenfk == 67) { //cek kunjungan terakhir rawat inap
          var jsonGetMonitoring = {
            "url": `Peserta/nokartu/${e.data.nobpjs}/tglSEP/${tglakhir}`,
            "method": "GET",
            "data": null
          }
          this.httpService.postNonMessage('medifirst2000/bridging/bpjs/tools', jsonGetMonitoring).subscribe(result => {
            if (result.metaData.code == "200") {
              var res = result.response.peserta
              this.kelasrawat = res.hakKelas.kode
              this.notelepons = res.mr.noTelepon
              this.jnspeserta = res.jenisPeserta.keterangan
              this.umrskrg = res.umur.umurSekarang
            }
          })

          var jsonGetMonitoring = {
            "url": `monitoring/HistoriPelayanan/NoKartu/${e.data.nobpjs}/tglMulai/${tglawal}/tglAkhir/${tglakhir}`,
            "method": "GET",
            "data": null
          }
          this.httpService.postNonMessage('medifirst2000/bridging/bpjs/tools', jsonGetMonitoring).subscribe(res2 => {
            if (res2.metaData.code == "200") {
              var sepkunjungan = res2.response.histori;
              for (let x = 0; x < sepkunjungan.length; x++) {
                const element = sepkunjungan[x];
                if (element.jnsPelayanan == "1" && element.noSep.substr(0, 8) == "0233R779") {
                  var dx = element.diagnosa.split('-')
                  element.asalRujukan = "2";
                  element.tglKunjungan = element.tglSep
                  element.diagnosa = { kode: dx[0].trim(), nama: dx[1] }
                  element.noKunjungan = element.noSep
                  element.provPerujuk = { kode: '0233R779', nama: 'RSUD BALI MANDARA' }
                  element.peserta = {
                    mr: {
                      noMR: e.data.nocm,
                      noTelepon: this.notelepons
                    },
                    hakKelas: { kode: this.kelasrawat },
                    nama: e.data.namapasien,
                    noKartu: e.data.nobpjs,
                    nik: e.data.noidentitas,
                    tglLahir: e.data.tgllahir,
                    jenisPeserta: { keterangan: '-' },
                    sex: e.data.jeniskelamin,
                    umur: { umurSekarang: e.data.tgllahir }

                  }
                  element.poliRujukan = { nama: 'Post-RI' }
                  // element.poliRujukan='Post-RI'
                  var optionListRujukan = {
                    label: element.noSep + "#POST RAWAT INAP#" + e.kunjterakhir.namaruangan + "#" + element.ppkPelayanan,
                    value: element
                  }
                  this.listRujukan.push(optionListRujukan);
                }
              }
            }
            // console.log(this.listRujukan)
            if (this.listRujukan.length == 0) {
              delete this.item.frmIdentitas;
              delete this.noReservasi;
              this.isLoadingHalAwal = false;
              this.alertService.warning('', res2.metaData.message, {
                toastClass: 'toast ngx-toastr',
                closeButton: true,
                positionClass: 'toast-top-left'
              });
            } else {
              this.isLoadingHalAwal = false;
              localStorage.setItem("dataRujukanLokal", JSON.stringify(this.listRujukan));
              // set ruangan hanya tujuan apabila pasien online
              if (this.noReservasi) {
                this.listRuanganReserv = this.listRuangan.filter(e => e.id === this.idRuanganReservasi);
              }
              // //this.nextStep('halamanpilihpoli');
            }
          })

        } else {
          var jsonGetRujukanPcare = {
            "url": "Rujukan/List/Peserta/" + e.data.nobpjs,
            "method": "GET",
            "data": null
          }
          this.httpService.postNonMessage('medifirst2000/bridging/bpjs/tools', jsonGetRujukanPcare).subscribe(res => {
            if (res.metaData.code == "200") {
              var DataPcare = res.response.rujukan;
              for (let i = 0; i < DataPcare.length; i++) {
                const element = DataPcare[i];
                var hari = this.cekBerapaHari(element.tglKunjungan);
                if (hari <= 90) {
                  element.asalRujukan = "1";
                  var optionListRujukan = {
                    label: element.noKunjungan + "#" + element.poliRujukan.nama + "#" + element.provPerujuk.nama + "#" + element.tglKunjungan,
                    value: element
                  }
                  this.listRujukan.push(optionListRujukan);
                }
              }
            }
            var jsonGetRujukanRs = {
              "url": "Rujukan/RS/List/Peserta/" + e.data.nobpjs,
              "method": "GET",
              "data": null
            }
            this.httpService.postNonMessage('medifirst2000/bridging/bpjs/tools', jsonGetRujukanRs).subscribe(res2 => {
              if (res2.metaData.code == "200") {
                var DataRS = res2.response.rujukan;
                for (let x = 0; x < DataRS.length; x++) {
                  const element = DataRS[x];
                  var hari = this.cekBerapaHari(element.tglKunjungan);
                  if (hari <= 90) {
                    element.asalRujukan = "2";
                    var optionListRujukan = {
                      label: element.noKunjungan + "#" + element.poliRujukan.nama + "#" + element.provPerujuk.nama + "#" + element.tglKunjungan,
                      value: element
                    }
                    this.listRujukan.push(optionListRujukan);
                  }
                }
              }
              if (this.listRujukan.length == 0) {
                delete this.item.frmIdentitas;
                delete this.noReservasi;
                this.isLoadingHalAwal = false;
                this.alertService.warning('', res2.metaData.message, {
                  toastClass: 'toast ngx-toastr',
                  closeButton: true,
                  positionClass: 'toast-top-left'
                });
              } else {
                this.isLoadingHalAwal = false;
                localStorage.setItem("dataRujukanLokal", JSON.stringify(this.listRujukan));
                // set ruangan hanya tujuan apabila pasien online
                if (this.noReservasi) {
                  this.listRuanganReserv = this.listRuangan.filter(e => e.id === this.idRuanganReservasi);
                }
                // //this.nextStep('halamanpilihpoli');
              }
            })
          })
        }
      } else {
        if (e.pendaftaran.nosep === null) {
          this.isLoadingHalAwal = false;
          this.alertService.error('', 'Pasien sudah melakukan pendaftaran dihari ini!', {
            toastClass: 'toast ngx-toastr',
            closeButton: true,
            positionClass: 'toast-top-left'
          });
          return
        }

        var jsonGetRujukanPcareNo = {
          "url": "Rujukan/" + e.pendaftaran.norujukan,
          "method": "GET",
          "data": null
        }
        this.httpService.postNonMessage('medifirst2000/bridging/bpjs/tools', jsonGetRujukanPcareNo).subscribe(resx => {
          if (resx.metaData.code == "200") {
            this.isLoadingHalAwal = false;
            this.dataSEP.noSep = e.pendaftaran.nosep
            this.dataSEP.poli = e.pendaftaran.namaruangan
            this.item.peserta = resx.response.rujukan
            this.pasienDaftar = e.pendaftaran
            // //this.nextStep('halamancetak');
          } else {
            var jsonGetRujukanRsNo = {
              "url": "Rujukan/RS/" + e.pendaftaran.norujukan,
              "method": "GET",
              "data": null
            }
            this.httpService.postNonMessage('medifirst2000/bridging/bpjs/tools', jsonGetRujukanRsNo).subscribe(resx2 => {
              if (resx2.metaData.code == "200") {
                this.isLoadingHalAwal = false;
                this.dataSEP.noSep = e.pendaftaran.nosep
                this.dataSEP.poli = e.pendaftaran.namaruangan
                this.item.peserta = resx2.response.rujukan
                this.pasienDaftar = e.pendaftaran
                // //this.nextStep('halamancetak');
              } else {
                var jsonGetMonitoring = {
                  "url": "SEP/" + e.pendaftaran.nosep,
                  "method": "GET",
                  "data": null
                }
                this.httpService.postNonMessage('medifirst2000/bridging/bpjs/tools', jsonGetMonitoring).subscribe(resx2 => {
                  if (resx2.metaData.code == "200") {
                    var element = resx2.response
                    element.asalRujukan = "2";
                    element.tglKunjungan = element.tglSep
                    element.diagnosa = { nama: element.diagnosa }
                    element.noKunjungan = element.noRujukan
                    element.provPerujuk = { kode: '0233R779', nama: 'RSUD BALI MANDARA' }
                    element.peserta = {
                      mr: {
                        noMR: element.peserta.noMr,
                        noTelepon: e.data.notelepon
                      },
                      hakKelas: { kode: element.kelasRawat },
                      nama: e.data.namapasien,
                      noKartu: element.peserta.noKartu,
                      nik: e.data.noidentitas,
                      tglLahir: e.data.tgllahir,
                      jenisPeserta: { keterangan: element.peserta.jnsPeserta },
                      sex: e.data.jeniskelamin,
                      umur: { umurSekarang: element.peserta.tglLahir }

                    }
                    element.poliRujukan = { nama: 'Post-RI' }

                    this.isLoadingHalAwal = false;
                    this.dataSEP = resx2.response
                    this.item.peserta = element
                    this.pasienDaftar = e.pendaftaran
                    console.log(this.dataSEP.poli)
                    // //this.nextStep('halamancetak');
                  } else {
                    this.isLoadingHalAwal = false;
                    this.alertService.error('', 'Pasien sudah melakukan pendaftaran dihari ini!', {
                      toastClass: 'toast ngx-toastr',
                      closeButton: true,
                      positionClass: 'toast-top-left'
                    });
                  }
                })
              }
            })
          }
        })
      }
    })
  }

  cekBerapaHari(tanggal) {
    var dayformat1 = moment(tanggal).format('MM/DD/YYYY');
    var dayformat2 = moment(new Date()).format('MM/DD/YYYY');
    const firstDate = new Date(dayformat1.toString());
    const secondDate = new Date(dayformat2.toString());
    const difference = secondDate.getTime() - firstDate.getTime();
    const diffDays = Math.ceil(difference / (1000 * 3600 * 24));
    return diffDays;
  }

  changeFlagProcedur(event) {
    this.listPenunjang = [];
    if (event == undefined) {
      this.isPenunjang = false;
      return
    }
    event.details.forEach(response => {
      this.listPenunjang.push({
        'id': response.id,
        'name': response.name,
      });
    });
    this.isPenunjang = true;
  }

  changeTujuanKunj(event) {
    if (event == undefined) {
      this.isFlagProcedur = false;
      return
    }

    if (event.id === "0") {
      this.isFlagProcedur = false;
    } else {
      this.isFlagProcedur = true;
    }
  }

  changefrmRujukan(event) {
    if (event == undefined) {
      this.formGroup.get('frmSurkon').setValue('')
      return
    }
    // cari data monitoring pelayanan peserta dengan tanggal awal dirubah dari tgl kunjungan rujukan
    var tglawal = moment(event.value.tglKunjungan).format("YYYY-MM-DD");
    var tglakhir = moment(new Date()).format("YYYY-MM-DD");
    var listRiwayatRujukan = []
    var jsonGetMonitoring = {
      "url": `monitoring/HistoriPelayanan/NoKartu/${this.item.pasien.nobpjs}/tglMulai/${tglawal}/tglAkhir/${tglakhir}`,
      "method": "GET",
      "data": null
    }
    this.httpService.postNonMessage('medifirst2000/bridging/bpjs/tools', jsonGetMonitoring).subscribe(resMonitoring => {
      if (resMonitoring.metaData.code == "200") {
        this.listMonitoringPelayanan = resMonitoring.response.histori
        listRiwayatRujukan = resMonitoring.response.histori
        var pelayananByRujukan = []
        var countKunjungan = 0;
        for (let i = 0; i < listRiwayatRujukan.length; i++) {
          const element = listRiwayatRujukan[i];
          // console.log([element.jnsPelayanan,element.noRujukan,event.value.noKunjungan])

          if (element.noRujukan == event.value.noKunjungan) {
            countKunjungan = countKunjungan + 1
            if (element.poliTujSep == this.item.poliPilih.kdsubspesialisbpjs && element.tglSep != moment(new Date()).format('YYYY-MM-DD')) {
              pelayananByRujukan.push(element);
            }
          }
          if (element.noSep == event.value.noSep) {
            countKunjungan = countKunjungan + 1
            if (element.jnsPelayanan == "1" && element.tglSep != moment(new Date()).format('YYYY-MM-DD')) {
              pelayananByRujukan.push(element);
            }
          }
        }
        // console.log(pelayananByRujukan)
        countKunjungan++
        this.alertService.info('', "Kunjungan ke- " + countKunjungan + " Dengan Rujukan yang sama.", {
          toastClass: 'toast ngx-toastr',
          closeButton: true,
          positionClass: 'toast-top-left'
        });
        this.getDiagnosaByKode(event.value.diagnosa.kode);
        this.formGroup.get('frmTujuanKunj').setValue('0')

        if (this.item.kunjungan.objectdepartemenfk == 16 || this.item.kunjungan.objectdepartemenfk == 66 || this.item.kunjungan.objectdepartemenfk == 67) {
          // console.log(pelayananByRujukan[0].jnsPelayanan)
          if (countKunjungan > 1 && pelayananByRujukan[0].jnsPelayanan == "1") {

            this.formGroup.get('frmTujuanKunj').setValue('')
            var noSepTerakhir = pelayananByRujukan[0].noSep
            this.setSuratKontrol(noSepTerakhir, pelayananByRujukan[0].tglSep)
          }
        } else {
          // buat kan surat kontrol apabila kunjungan lebih dari 1 dan kode poli sama dengan sep poli yang sama
          if (countKunjungan > 1 && event.value.poliRujukan.kode == this.item.poliPilih.kdsubspesialisbpjs) {
            this.formGroup.get('frmTujuanKunj').setValue('')
            var noSepTerakhir = pelayananByRujukan[0].noSep
            this.setSuratKontrol(noSepTerakhir, pelayananByRujukan[0].tglSep)
          }
        }
      }
    })
    this.formGroup.get('frmTujuanKunj').setValue('0')
    this.getDiagnosaByKode(event.value.diagnosa.kode);
  }

  setSuratKontrol(noSepTerakhir, tglsep) {
    var rencanaKontrol = []
    var year = moment(tglsep).format('YYYY');
    var year2 = moment(tglsep).add(1, 'month').format('YYYY');
    var year3 = moment(tglsep).add(2, 'month').format('YYYY');
    var blnsep = moment(tglsep).format('MM');
    var blnsepplus2 = moment(tglsep).add(1, 'month').format('MM');
    var blnsepplus3 = moment(tglsep).add(2, 'month').format('MM');
    var ketemu = false;
    var riwskdpsudahterbit = false;
    var jsonGetSurkon = {
      "url": `RencanaKontrol/ListRencanaKontrol/Bulan/${blnsep}/Tahun/${year}/Nokartu/${this.item.pasien.nobpjs}/filter/2`,
      "method": "GET",
      "data": null
    }
    this.isLoadingScreen = true;
    this.httpService.postNonMessage('medifirst2000/bridging/bpjs/tools', jsonGetSurkon).subscribe(resSurkon => {
      if (resSurkon.metaData.code == "200") {
        rencanaKontrol = resSurkon.response.list
      }
    })
    var jsonGetSurkon = {
      "url": `RencanaKontrol/ListRencanaKontrol/Bulan/${blnsepplus2}/Tahun/${year2}/Nokartu/${this.item.pasien.nobpjs}/filter/2`,
      "method": "GET",
      "data": null
    }
    this.isLoadingScreen = true;
    this.httpService.postNonMessage('medifirst2000/bridging/bpjs/tools', jsonGetSurkon).subscribe(resSurkon => {
      if (resSurkon.metaData.code == "200") {
        rencanaKontrol = rencanaKontrol.concat(resSurkon.response.list);
      }
    })
    var jsonGetSurkon = {
      "url": `RencanaKontrol/ListRencanaKontrol/Bulan/${blnsepplus3}/Tahun/${year3}/Nokartu/${this.item.pasien.nobpjs}/filter/2`,
      "method": "GET",
      "data": null
    }
    this.isLoadingScreen = true;
    this.httpService.postNonMessage('medifirst2000/bridging/bpjs/tools', jsonGetSurkon).subscribe(resSurkon => {
      if (resSurkon.metaData.code == "200") {
        rencanaKontrol = rencanaKontrol.concat(resSurkon.response.list);
      }
      if (rencanaKontrol.length > 0) {
        console.log(rencanaKontrol)
        for (let i = 0; i < rencanaKontrol.length; i++) {
          const element = rencanaKontrol[i]
          if (element.poliTujuan == this.item.poliPilih.kdsubspesialisbpjs &&
            element.noSepAsalKontrol == noSepTerakhir &&
            element.jnsKontrol == "2") {
            // cek tgl kontrolnya sesuai gak
            if (element.tglRencanaKontrol == moment(new Date()).format('YYYY-MM-DD')) {
              //cek suratkontrol hari ini sudah terbit SEP atau belum 
              if (element.terbitSEP == "Sudah") {
                riwskdpsudahterbit = true;
                var optionListSurkon = {
                  label: element.noSuratKontrol,
                  value: element
                }
                this.listSurkon.push(optionListSurkon);
                this.formGroup.get('frmSurkon').setValue('');
                this.formGroup.get('frmSurkon').setValue(optionListSurkon);
                break;
              } else if (element.terbitSEP == "Belum") {
                if (element.kodeDokter == this.item.frmDpjp.kode) {
                  ketemu = true;
                  var optionListSurkon = {
                    label: element.noSuratKontrol,
                    value: element
                  }

                  this.listSurkon.push(optionListSurkon);
                  this.formGroup.get('frmSurkon').setValue(optionListSurkon);
                  break;
                } else {
                  var JsonDelate = {
                    "url": `RencanaKontrol/Delete`,
                    "method": "DELETE",
                    "data": {
                      "request": {
                        "t_suratkontrol": {
                          "noSuratKontrol": element.noSuratKontrol,
                          "user": "KIOSK"
                        }
                      }
                    }
                  }
                  this.isLoadingScreen = true;
                  this.httpService.postNonMessage('medifirst2000/bridging/bpjs/tools', JsonDelate).subscribe(resSurkonDel => {
                    this.isLoadingScreen = false;
                  })
                }
              }
            } else {
              this.updateSuratKontrol(element.noSuratKontrol, noSepTerakhir, this.item.frmDpjp.kode, this.item.poliPilih.kdsubspesialisbpjs);
              riwskdpsudahterbit = true;
              // insert ke table monitoring kontrol
            }
          }
        }
      }

      this.isLoadingScreen = false;
      if (!ketemu) {
        var noSep = noSepTerakhir
        var kdDokter = this.item.frmDpjp.kode
        var kdPoli = this.item.poliPilih.kdsubspesialisbpjs
        if (riwskdpsudahterbit == false) {
          this.createSuratKontrol(noSep, kdDokter, kdPoli)
        }
      }
    })
  }

  createSuratKontrol(noSep, kdDokter, kdPoli) {
    var JsonSave = {
      "url": `RencanaKontrol/insert`,
      "method": "POST",
      "data": {
        "request": {
          "noSEP": noSep,
          "kodeDokter": kdDokter,
          "poliKontrol": kdPoli,
          "tglRencanaKontrol": moment(new Date()).format("YYYY-MM-DD"),
          "user": "KIOSK"
        }
      }
    }
    this.isLoadingScreen = true;
    this.httpService.postNonMessage('medifirst2000/bridging/bpjs/tools', JsonSave).subscribe(resSurkon => {
      this.isLoadingScreen = false;
      this.formGroup.get('frmSurkon').setValue('')
      this.alertService.warning('', resSurkon.metaData.message, {
        toastClass: 'toast ngx-toastr',
        closeButton: true,
        positionClass: 'toast-top-left'
      });
      if (resSurkon.metaData.code == "200") {
        this.setSuratKontrol(noSep, moment(new Date()).format("YYYY-MM-DD"));
      }
    })
  }
  updateSuratKontrol(noSuratKontrol, noSep, kdDokter, kdPoli) {
    var JsonSave = {
      "url": `RencanaKontrol/Update`,
      "method": "PUT",
      "data": {
        "request": {
          "noSuratKontrol": noSuratKontrol,
          "noSEP": noSep,
          "kodeDokter": kdDokter,
          "poliKontrol": kdPoli,
          "tglRencanaKontrol": moment(new Date()).format("YYYY-MM-DD"),
          "user": "KIOSK"
        }
      }
    }
    this.isLoadingScreen = true;
    this.httpService.postNonMessage('medifirst2000/bridging/bpjs/tools', JsonSave).subscribe(resSurkon => {
      this.isLoadingScreen = false;
      this.formGroup.get('frmSurkon').setValue('')
      this.alertService.warning('', resSurkon.metaData.message, {
        toastClass: 'toast ngx-toastr',
        closeButton: true,
        positionClass: 'toast-top-left'
      });
      if (resSurkon.metaData.code == "200") {
        this.setSuratKontrol(noSep, moment(new Date()).format("YYYY-MM-DD"));
      }
    })
  }

  getDiagnosaByKode(kode) {
    this.httpService.get('medifirst2000/kiosk/get-diagnosabykode/' + kode).subscribe(res => {
      this.item.diagnosa.id = res.data.id
    })
  }
  buatSEP() {
    var rujukan = this.formGroup.get('frmRujukan').value
    var surkon = this.formGroup.get('frmSurkon').value
    var frmTujuanKunj = this.formGroup.get('frmTujuanKunj').value
    var frmFlagProcedur = this.formGroup.get('frmFlagProcedur').value
    var frmPenunjang = this.formGroup.get('frmPenunjang').value
    var frmAssesment = this.formGroup.get('frmAssesment').value

    if (rujukan === "" || rujukan === null) {
      this.alertService.warning('', "Harap pilih terlebih dahulu rujukan !", { toastClass: 'toast ngx-toastr', closeButton: true, positionClass: 'toast-top-left' });
      return
    }

    if (frmTujuanKunj === "" || frmTujuanKunj === null) {
      this.alertService.warning('', "Harap pilih terlebih tujuan kunjungan !", { toastClass: 'toast ngx-toastr', closeButton: true, positionClass: 'toast-top-left' });
      return
    }

    rujukan = rujukan.value
    surkon = surkon.value == undefined ? "" : surkon.value;
    let eksekutif = "0"
    if (this.item.poliPilih.iseksekutif) {
      eksekutif = "1"
    }

    if (rujukan.peserta.mr.noTelepon == null) {
      rujukan.peserta.mr.noTelepon = '12345678'
    } else if (rujukan.peserta.mr.noTelepon.length > 12) {
      rujukan.peserta.mr.noTelepon = '12345678'
    }


    var jsonGetRiwayatSEP = {
      "url": `monitoring/HistoriPelayanan/NoKartu/${this.item.pasien.nobpjs}/tglMulai/${moment(new Date()).format('YYYY-MM-DD')}/tglAkhir/${moment(new Date()).format('YYYY-MM-DD')}`,
      "method": "GET",
      "data": null
    }
    this.httpService.postNonMessage('medifirst2000/bridging/bpjs/tools', jsonGetRiwayatSEP).subscribe(resx2 => {
      if (resx2.metaData.code == "200") {
        if (resx2.response.histori[0].poliTujSep == this.item.poliPilih.kdsubspesialisbpjs && resx2.response.histori[0].noSep.substr(0, 8) == "0233R779") {
          this.dataSEP = resx2.response.histori[0]
          this.msgService.success('Status', 'Generate SEP Success. No SEP : ' + this.dataSEP.noSep);
          if (this.isReservasiPoli) {
            // kondisi reservasi langsung ke poli
            this.saveKonfirmasiDaftar()
          } else {
            // kondisi reservasi tidak ke poli
            this.savePasienDaftar();
          }
        }
      } else {
        var jsonSend = {
          "url": "SEP/2.0/insert",
          "method": "POST",
          "data": {
            "request": {
              "t_sep": {
                "noKartu": this.item.pasien.nobpjs,
                "tglSep": moment(new Date()).format('YYYY-MM-DD'),
                "ppkPelayanan": "0233R779",
                "jnsPelayanan": "2",
                "klsRawat": {
                  "klsRawatHak": rujukan.peserta.hakKelas.kode,
                  "klsRawatNaik": "",
                  "pembiayaan": "",
                  "penanggungJawab": ""
                },
                "noMR": this.item.pasien.nocm,
                "rujukan": {
                  "asalRujukan": rujukan.asalRujukan,
                  "tglRujukan": rujukan.tglKunjungan,
                  "noRujukan": rujukan.noKunjungan,
                  "ppkRujukan": rujukan.provPerujuk.kode
                },
                "catatan": "REGISTRASI MANDIRI",
                "diagAwal": rujukan.diagnosa.kode,
                "poli": {
                  "tujuan": this.item.poliPilih.kdsubspesialisbpjs,
                  "eksekutif": eksekutif
                },
                "cob": {
                  "cob": "0"
                },
                "katarak": {
                  "katarak": "0"
                },
                "jaminan": {
                  "lakaLantas": "0",
                  "noLP": "",
                  "penjamin": {
                    "tglKejadian": "",
                    "keterangan": "",
                    "suplesi": {
                      "suplesi": "0",
                      "noSepSuplesi": "",
                      "lokasiLaka": {
                        "kdPropinsi": "",
                        "kdKabupaten": "",
                        "kdKecamatan": ""
                      }
                    }
                  }
                },
                "tujuanKunj": frmTujuanKunj,
                "flagProcedure": frmFlagProcedur !== "" && frmFlagProcedur !== null ? frmFlagProcedur : "",
                "kdPenunjang": frmPenunjang !== "" && frmPenunjang !== null ? frmPenunjang : "",
                "assesmentPel": frmAssesment !== "" && frmAssesment !== null ? frmAssesment : "",
                "skdp": {
                  "noSurat": surkon !== "" && surkon !== null ? surkon.noSuratKontrol : "",
                  "kodeDPJP": surkon !== "" && surkon !== null ? surkon.kodeDokter : "",
                },
                "dpjpLayan": this.item.frmDpjp.kode,
                "noTelp": rujukan.peserta.mr.noTelepon,
                "user": "KIOSK"
              }
            }
          }
        }
        this.isLoadingScreen = true;
        this.httpService.postNonMessage('medifirst2000/bridging/bpjs/tools', jsonSend).subscribe(resSEP => {
          if (resSEP.response != null) {
            this.dataSEP = resSEP.response.sep
            this.msgService.success('Status', 'Generate SEP Success. No SEP : ' + this.dataSEP.noSep);
            if (this.isReservasiPoli) {
              // kondisi reservasi langsung ke poli
              this.saveKonfirmasiDaftar()
            } else {
              // kondisi reservasi tidak ke poli
              this.savePasienDaftar();
            }
          } else {
            this.msgService.error('Gagal Generate SEP', resSEP.metaData.message);
            // console.log(jsonSend);
          }
          this.isLoadingScreen = false;
        }, err => {
          this.isLoadingScreen = false;
          this.msgService.error('Gagal Generate SEP', JSON.stringify(err));
        })
      }

    })
  }
  savePasienDaftar() {
    this.kodeDokter = null
    this.httpService.get('medifirst2000/kiosk/get-dokter-internal?kode=' + this.item.frmDpjp.kode).subscribe(e => {
      if (e != false) {
        this.kodeDokter = e.id
      }
      this.savePasienDaftarFix()
    })
  }
  savePasienDaftarFix() {
    var pasiendaftar = {
      'norec': '',
      'nocmfk': this.item.pasien.id,
      'tglregistrasi': moment(new Date()).format('YYYY-MM-DD HH:mm:ss'),
      'objectruanganlastfk': this.item.poliPilih.id,
      'asalrujukanfk': 2,
      'objectkelompokpasienlastfk': 2,
      'jenispelayananfk': this.eksekutif,//reguler
      'objectpegawaifk': this.kodeDokter,
      'objectkelasfk': 6,
      'israwatinap': false,
      'catatan': 'Kios-K',
      'statuspasien': 'LAMA',
      'objectrekananfk': 2552,
      'nocm': this.item.pasien.nocm,
      'namapasien': this.item.namapasien,
      'iskiosk': true,
      'statusschedule': this.noReservasi != undefined ? this.noReservasi : 'Kios-K',

    }
    var antrianpasiendiperiksa = {
      'norec': '',
      'objectkamarfk': null,
      'nobed': null,
      'israwatgabung': null,
    }
    var objSave = {
      'pasiendaftar': pasiendaftar,
      'antrianpasiendiperiksa': antrianpasiendiperiksa
    }

    this.isLoadingScreen = true;
    this.httpService.post('medifirst2000/registrasi/save-registrasipasien', objSave).subscribe(resPendaftaran => {
      this.isLoadingScreen = false;
      this.pasienDaftar.noregistrasi = resPendaftaran.dataPD.noregistrasi
      this.pasienDaftar.norec_pd = resPendaftaran.dataPD.norec
      this.pasienDaftar.tglregistrasi = resPendaftaran.dataPD.tglregistrasi
      this.pasienDaftar.norec_apd = resPendaftaran.dataAPD.norec

      // this.saveLogging('Pendaftaran Pasien', 'norec Pasien Daftar', resPendaftaran.dataPD.norec,
      //   'Self Registration No Registrasi (' + resPendaftaran.dataPD.noregistrasi + ') ')
      this.simpanPemakaianAsuransi()
      // if(this.noReservasi){
      //   this.updateStatusConfirm()
      // }
      this.saveAntrol(resPendaftaran.dataPD.norec)
      if (this.isAdminOtomatisKiosk == 'true') {
        this.saveAdminAuto(this.pasienDaftar)
      }
    }, error => {

    })
  }
  simpanPemakaianAsuransi() {
    var rujukan = this.formGroup.get('frmRujukan').value.value
    let kelas: any = ""
    if (rujukan.peserta.hakKelas.kode == "1")
      kelas = 3
    else if (rujukan.peserta.hakKelas.kode == "2")
      kelas = 2
    else if (rujukan.peserta.hakKelas.kode == "3")
      kelas = 1
    console.log(rujukan)
    let asuransipasien = {
      'id': '',
      'noregistrasi': this.pasienDaftar.noregistrasi,
      'nocm': this.item.pasien.nocm,
      'alamatlengkap': '',
      'objecthubunganpesertafk': 1,
      'objectjeniskelaminfk': this.item.pasien.id_jeniskelamin,
      'kdinstitusiasal': 1,
      'kdpenjaminpasien': 1,
      'objectkelasdijaminfk': kelas,
      'namapeserta': rujukan.peserta.nama,
      'nikinstitusiasal': 1,
      'noasuransi': rujukan.peserta.noKartu,
      'alamat': '',
      'nocmfkpasien': this.item.pasien.id,
      'noidentitas': rujukan.peserta.nik,
      'qasuransi': 2,
      'kelompokpasien': 2,
      'tgllahir': moment(new Date(rujukan.peserta.tglLahir)).format('YYYY-MM-DD'),
      'jenispeserta': rujukan.peserta.jenisPeserta.keterangan,
      'kdprovider': rujukan.provPerujuk.kode,
      'nmprovider': rujukan.provPerujuk.nama,
      'notelpmobile': rujukan.peserta.mr.noTelepon,
    }
    let statuskunjungan = null
    if (this.formGroup.get("frmTujuanKunj").value === "0") {
      statuskunjungan = 1
    }
    if (this.formGroup.get("frmTujuanKunj").value === "1") {
      statuskunjungan = 2
    }
    if (this.formGroup.get("frmTujuanKunj").value === "2") {
      statuskunjungan = 3
    }

    let asalrujukan = "2"
    let pemakaianasuransi = {
      'norec': '',
      'noregistrasifk': this.pasienDaftar.norec_pd,
      'tglregistrasi': moment(new Date(this.pasienDaftar.tglregistrasi)).format('YYYY-MM-DD HH:mm'),
      'tglsep': moment(new Date()).format('YYYY-MM-DD'),
      'diagnosisfk': this.item.diagnosa.id != null ? this.item.diagnosa.id : null,
      'lakalantas': 0,
      'nokartu': this.item.peserta.noKartu,
      'norujukan': rujukan != undefined ? rujukan.noKunjungan : null,
      'nosep': this.dataSEP.noSep,
      'tglrujukan': rujukan.tglKunjungan,
      'objectdiagnosafk': this.item.diagnosa.id != null ? this.item.diagnosa.id : null,
      'tanggalsep': this.dataSEP.tglSep,
      'ppkpelayanan': '0233R779',
      'jnspelayanan': 2,
      'klsrawathak_kode': this.item.provPerujuk.klsrawathakkode,
      'klsrawathak_nama': this.item.provPerujuk.klsrawathaknama,
      'klsrawatnaik_kode': null,
      'klsrawatnaik_nama': null,
      'pembiayaan_kode': null,
      'pembiayaan_nama': null,
      'isrujukaninternal': null,
      'catatan': '',
      'lokasilaka': null,
      'penjaminlaka': null,
      'cob': false,
      'katarak': false,
      'keteranganlaka': "",
      'tglkejadian': null,
      'suplesi': false,
      'nosepsuplesi': "",
      'kdpropinsi': null,
      'namapropinsi': null,
      'kdkabupaten': null,
      'namakabupaten': null,
      'kdkecamatan': null,
      'namakecamatan': null,
      'nosuratskdp': this.formGroup.get("frmSurkon").value !== '' && this.formGroup.get("frmSurkon").value !== null ? this.formGroup.get("frmSurkon").value.value.noSuratKontrol : "",
      'prolanisprb': null,
      'asalrujukanfk': rujukan.asalRujukan,
      'kodedpjpmelayani': this.item.frmDpjp !== undefined ? this.item.frmDpjp.kode : null,
      'namadjpjpmelayanni': this.item.frmDpjp !== undefined ? this.item.frmDpjp.nama : null,
      'polirujukankode': this.item.poliPilih.kdsubspesialisbpjs,
      'polirujukannama': this.item.poliPilih.namaruangan,
      'asalrujukan': asalrujukan,
      'ppkrujukan': this.item.provPerujuk.kode,
      'klsrawatnaik': null,
      'pembiayaan': null,
      'penanggungjawab': null,
      'tujuankunj': this.formGroup.get("frmTujuanKunj").value !== '' && this.formGroup.get("frmTujuanKunj").value !== null ? this.formGroup.get("frmTujuanKunj").value : null,
      'flagprocedure': this.formGroup.get("frmFlagProcedur").value !== '' && this.formGroup.get("frmFlagProcedur").value !== null ? this.formGroup.get("frmFlagProcedur").value : null,
      'kdpenunjang': this.formGroup.get("frmPenunjang").value !== '' && this.formGroup.get("frmPenunjang").value !== null ? this.formGroup.get("frmPenunjang").value : null,
      'assesmentpel': this.formGroup.get("frmAssesment").value !== '' && this.formGroup.get("frmAssesment").value !== null ? this.formGroup.get("frmAssesment").value : null,
      'statuskunjungan': statuskunjungan,
      'poliasalkode': rujukan.poliRujukan.nama,
      'nomr': this.item.pasien.nocm,
      'politujuankode': this.item.poliPilih.namaruangan,
      'ppkrujukan_nama': this.item.provPerujuk.nama,
      'kdprovider': this.item.provPerujuk.kode,
        'nmprovider': this.item.provPerujuk.nama,
        'diagawal_kode': this.item.provPerujuk.diagnosakode,
        'diagawal_nama': this.item.provPerujuk.diagnosanama,
        'poli_kode': null,
        'poli_nama': null,
        'eksekutif': null,
        'lakalantas_kode': 0,
        'lakalantas_nama': 'Bukan Kecelakaan',
        'nolp': null,
        'keterangan': "",
        'kdpropinsi_kode': null,
        'kdpropinsi_nama': null,
        'kdkabupaten_kode': null,
        'kdkabupaten_nama': null,
        'kdkecamatan_kode': null,
        'kdkecamatan_nama': null,
        'tujuankun_kode': this.item.provPerujuk.tujuankunjungankode,
        'tujuankun_nama': this.item.provPerujuk.tujuankunjungannama,
        'flagprocedure_kode': null,
        'flagprocedure_nama': null,
        'kdpenunjang_kode': null,
        'kdpenunjang_nama': null,
        'assesmentpel_kode': this.item.provPerujuk.assesmentpelkode,
        'assesmentpel_nama': this.item.provPerujuk.assesmentpelnama,
        'nosurat': this.item.provPerujuk.nosurat,
        'kodedpjp': this.item.provPerujuk.kddokter,
        'namadpjp': this.item.provPerujuk.namadokter,
        'dpjplayan_kode': this.item.provPerujuk.kddokter,
        'dpjplayan_nama': this.item.provPerujuk.namadokter,
        'user': 'Kiosk',
        'notelp': this.item.provPerujuk.notelp,
        'backdate': false,
        'kelasfk': kelas,
        'LOG': 'Tambah No. SEP'
    }

    var objSave = {
      'asuransipasien': asuransipasien,
      'pemakaianasuransi': pemakaianasuransi
    }
    this.isLoadingScreen = true;
    this.httpService.postNonMessage('registrasi/pemakaian-asuransi/save', objSave).subscribe(e => {
      this.isLoadingScreen = false;
      this.modalService.dismissAll()
      this.item.peserta = rujukan;
      //this.nextStep('halamancetak')
      this.cetakSep()
      setTimeout(function () {
        this.cetakLabel(this.item.poliPilih.jmlcetak);
      }, 3000);
    })
  }
  saveAdminAuto(pd) {
    let json = {
      norec: pd.norec_pd,
      norec_apd: pd.norec_apd
    }
    this.httpService.postNonMessage("medifirst2000/registrasi/save-adminsitrasi", json).subscribe(z => {

    })
  }
  updateStatusConfirm() {
    let data = {
      "noreservasi": this.noReservasi,
    }
    this.httpService.postNonMessage('medifirst2000/reservasionline/update-data-status-reservasi', data).subscribe(e => {

    })
  }
  saveLogging(jenis, referensi, noreff, ket, jsonsend = null, jsonres = null) {
    this.httpService.get("medifirst2000/sysadmin/logging/save-log-all?jenislog=" + jenis
      + "&referensi=" + referensi
      + "&noreff=" + noreff
      + "&keterangan=" + ket
      + "&data=" + jsonsend
      + "&response=" + jsonres
    ).subscribe(e => {

    })
  }
  cetakSep() {
    if (this.isCetakDSKiosk == 'true') {
      this._Qzprinter.prinBlade('registrasi/pemakaian-asuransi/sep?noregistrasi=' + this.pasienDaftar.noregistrasi + "&pdf=true",
        'SEP', 1)
      // this.service.get('http://127.0.0.1:1237/printvb/Pendaftaran?cetak-sep-new=1&norec=' + this.pasienDaftar.noregistrasi + '&view=false').subscribe(e => { });
    } else {
      window.open(Configuration.get().apiBackend + 'medifirst2000/report/cetak-sep?noregistrasi='
        + this.pasienDaftar.noregistrasi + '&kdprofile=33', '_blank');
    }
  }
  cetakLabel(qty) {
    if (qty == null) {
      qty = 1
    }
    this.service.get('http://127.0.0.1:1237/printvb/Pendaftaran?cetak-labelpasien-satu=1&norec=' + this.pasienDaftar.noregistrasi + '&view=false&qty=' + qty).subscribe(e => { });
  }
  saveAntrol(norec_pd) {
    this.saveMonitoringTaksId(norec_pd, 3, new Date().getTime(), false);
    this.httpService.get('medifirst2000/registrasi/get-data-antrean?norec_pd=' + norec_pd).subscribe(res2 => {
      var data = {
        "url": "antrean/add",
        "jenis": "antrean",
        "method": "POST",
        "data": res2
      }
      this.httpService.postNonMessage('medifirst2000/bridging/bpjs/tools', data).subscribe(e => {
        // save logging
        this.saveLogging('Antrol Task ID'
          , 'norec Pasien Daftar'
          , res2.kodebooking
          , 'Tambah Antrean Kode ' + res2.kodebooking + ' | ' + JSON.stringify(data) + ' | ' + JSON.stringify(e))

        // save waktu lamun asup antrean
        if (e.metaData.code == 200) {
          var data = {
            "url": "antrean/updatewaktu",
            "jenis": "antrean",
            "method": "POST",
            "data":
            {
              "kodebooking": res2.kodebooking,
              "taskid": 3,
              "waktu": new Date().getTime()
            }
          }
          this.httpService.postNonMessage('medifirst2000/bridging/bpjs/tools', data).subscribe(e => {
            if (e.metaData.code == 200) {
              this.saveMonitoringTaksId(norec_pd, 3, new Date().getTime(), true);
            }
          })
        }
      })
    })
  }
  saveMonitoringTaksId(noregistrasifk, taskid, waktu, statuskirim) {
    var json = {
      "noregistrasifk": noregistrasifk,
      "taskid": taskid,
      "waktu": waktu,
      "statuskirim": statuskirim
    }
    this.httpService.postNonMessage('medifirst2000/bridging/antrol/saveMonitoringTaksId', json).subscribe(e => { })
  }
  saveKonfirmasiDaftar() {
    this.pasienDaftar.noregistrasi = this.listPendaftaranReservasi.noregistrasi
    this.pasienDaftar.norec_pd = this.listPendaftaranReservasi.norec
    this.pasienDaftar.tglregistrasi = this.listPendaftaranReservasi.tglregistrasi
    this.pasienDaftar.norec_apd = this.listPendaftaranReservasi.norec_apd

    this.saveLogging('Pendaftaran Pasien', 'norec Pasien Daftar', this.listPendaftaranReservasi.norec,
      'Konfirmasi Registrasi No Registrasi (' + this.listPendaftaranReservasi.noregistrasi + ') ')

    this.simpanPemakaianAsuransi()
    if (this.noReservasi) {
      this.updateStatusConfirm()
    }
    this.saveAntrol(this.listPendaftaranReservasi.norec)
    if (this.isAdminOtomatisKiosk == 'true') {
      this.saveAdminAuto(this.pasienDaftar)
    }
  }
  cetakBuktiDaftar() {
    this._Qzprinter.prinBlade('medifirst2000/report/cetak-bukti-pendaftaran?noregistrasi='
      + this.pasienDaftar.noregistrasi, 'ANTRIAN POLI', 1)
  }

  async sendAntrol(jeniskunjungan) {
    console.log('MASUK ANTROL')
    return new Promise((resolve, reject) => {
      try {
        var jsonSendAntrol = {
          "url": "antrean/add",
          "jenis": "antrean",
          "method": "POST",
          "data": {
              "kodebooking": this.item.reservasi.noreservasi,
              "jenispasien": 'JKN',
              "nomorkartu": this.item.reservasi.nobpjs,
              "nik": this.item.reservasi.noidentitas,
              "nohp": this.item.reservasi.notelepon != null || this.item.reservasi.notelepon != '' ? this.item.reservasi.notelepon : '0000000000000',
              "kodepoli": this.item.suratkontrol.poliTujuan,
              "namapoli": this.item.reservasi.namaruangan,
              "pasienbaru": 0,
              // "norm": this.item.pasien.nocm,
              "norm": this.item.peserta.mr.noMR,
              "tanggalperiksa": this.item.reservasi.tanggalreservasi,
              "kodedokter": this.item.suratkontrol.kodeDokter,
              "namadokter": this.item.suratkontrol.namaDokter,
              "jampraktek": "08:00-14:00",
              "jeniskunjungan": jeniskunjungan,
              "nomorreferensi": this.item.suratkontrol.noSuratKontrol,
              "nomorantrean": this.item.reservasi.jenis+'-'+(this.item.reservasi.noantrian == null ? 0 : this.item.reservasi.noantrian),
              "angkaantrean": this.item.reservasi.noantrian == null ? 0 : this.item.reservasi.noantrian,
              "estimasidilayani": new Date().getTime(),
              "sisakuotajkn": 0,
              "kuotajkn": 0,
              "sisakuotanonjkn": 0,
              "kuotanonjkn": 0,
              "keterangan": ""
            }
          }
  
          this.httpService.postNonMessage('medifirst2000/bridging/bpjs/tools', jsonSendAntrol).subscribe(e => {
            // save logging
            this.saveLogging('Antrian Online'
              , 'antrianpasienregistrasi_t'
              , this.item.reservasi.norec
              , 'Tambah Antrean Kode ' + this.item.reservasi.noreservasi + ' Dari Kiosk'
              ,  jsonSendAntrol.data //JSON.stringify(jsonSendAntrol)
              ,  e.metaData //JSON.stringify(e)
            )

            resolve(true);
            // save waktu lamun asup antrean
            // if (e.metaData.code == 200) {
            //   var data = {
            //     "url": "antrean/updatewaktu",
            //     "jenis": "antrean",
            //     "method": "POST",
            //     "data":
            //     {
            //       "kodebooking": this.item.reservasi.noreservasi,
            //       "taskid": 3,
            //       "waktu": new Date().getTime()
            //     }
            //   }
            //   this.httpService.postNonMessage('medifirst2000/bridging/bpjs/tools', data).subscribe(e => {
            //   this.saveLogging('Antrol Task ID'
            //     , 'norec Pasien Daftar'
            //     , this.item.reservasi.noreservasi
            //     , 'Tambah Task Id 3 ' + this.item.reservasi.noreservasi + ' | ' + JSON.stringify(data) + ' | ' + JSON.stringify(e))

            //     if (e.metaData.code == 200) {
            //       var data = {
            //         "url": "antrean/updatewaktu",
            //         "jenis": "antrean",
            //         "method": "POST",
            //         "data":
            //         {
            //           "kodebooking": this.item.reservasi.noreservasi,
            //           "taskid": 4,
            //           "waktu": moment(new Date()).add(20, 'm').valueOf(),
            //         }
            //       }
            //       this.httpService.postNonMessage('medifirst2000/bridging/bpjs/tools', data).subscribe(e => {
            //       this.saveLogging('Antrol Task ID'
            //         , 'norec Pasien Daftar'
            //         , this.item.reservasi.noreservasi
            //         , 'Tambah Task Id 4 ' + this.item.reservasi.noreservasi + ' | ' + JSON.stringify(data) + ' | ' + JSON.stringify(e))

            //         if (e.metaData.code == 200) {
            //           var data = {
            //             "url": "antrean/updatewaktu",
            //             "jenis": "antrean",
            //             "method": "POST",
            //             "data":
            //             {
            //               "kodebooking": this.item.reservasi.noreservasi,
            //               "taskid": 5,
            //               "waktu": moment(new Date()).add(30, 'm').valueOf(),
            //             }
            //           }
            //           this.httpService.postNonMessage('medifirst2000/bridging/bpjs/tools', data).subscribe(e => {
            //           this.saveLogging('Antrol Task ID'
            //             , 'norec Pasien Daftar'
            //             , this.item.reservasi.noreservasi
            //             , 'Tambah Task Id 5 ' + this.item.reservasi.noreservasi + ' | ' + JSON.stringify(data) + ' | ' + JSON.stringify(e))
            //           })
                      
            //         }

            //       })
                  
            //     }

            //   })
              
            // }
          })
      } catch (error) {
        // this.saveLogging('Antrian Online'
        //   , 'antrianpasienregistrasi_t'
        //   , this.item.reservasi.norec
        //   , 'Tambah Antrean Kode ' + this.item.reservasi.noreservasi)
        //   , JSON.stringify(jsonSendAntrol)
        //   , JSON.stringify(e)
        console.error('Error in sendAntrol:', error);
        resolve(true);
      }
    })
  }

}
