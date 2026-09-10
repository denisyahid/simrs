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
import { AlertService } from '../../module/alert.service';
import { CacheService } from '../../module/cache.service';
import { Configuration } from '../../module/config';
import { HttpService } from '../../module/httpService';
import { QzprinterService } from '../../module/qzprinter.service';
import { CoreConfigService } from '@core/services/config.service';
import { takeUntil } from 'rxjs/operators';
import { Subject } from 'rxjs';

@Component({
  selector: 'app-self-regis-bpjs',
  templateUrl: './self-regis-bpjs.component.html',
  styleUrls: ['./self-regis-bpjs.component.scss'],
  encapsulation: ViewEncapsulation.None
})
export class SelfRegisBpjsComponent implements OnInit {
  public coreConfig: any;
  private _unsubscribeAll: Subject<any>;
  contentHeader: any
  isCetakDSKiosk: any = 'true'
  formGroup: FormGroup;
  isHalamanAwal: boolean = true;
  isHalamanPilihPoli: boolean = false;
  isHalamanCetak: boolean = false;
  isPenunjang: boolean = false;
  isFlagProcedur: boolean = false;
  isLoadingHalAwal: boolean = false;
  isLoadingScreen: boolean = false;
  isReservasiPoli: boolean = false;
  isTemporaryBrigding: string;
  ppkPelayananRS: any = "1019R001"
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
  }
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
    this.route.params.subscribe(params => {
      this.loketId = params['loketId'];
    })

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
      this.ppkPelayananRS = '1019R001'
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
    this._Qzprinter.connect();
    this.loadCache()
  }

  loadCache() {
    let cacheOnlineBPJS = this.cacheHelper.get('cacheOnlineBPJS')
    if (cacheOnlineBPJS != undefined) {
      this.noReservasi = cacheOnlineBPJS.noreservasi
      this.idRuanganReservasi = cacheOnlineBPJS.objectruanganfk
      this.item.frmIdentitas = cacheOnlineBPJS.nocm
      this.cacheHelper.set('cacheOnlineBPJS', undefined)
      this.cariDataPasien()
    }
  }

  // modal Open Success
  pilihRuangan(modalSuccess, data) {
    // set kosongkan formnya
    this.formGroup.get('frmRujukan').setValue('')
    this.formGroup.get('frmSurkon').setValue('')
    this.formGroup.get('frmTujuanKunj').setValue('')
    this.formGroup.get('frmFlagProcedur').setValue('')
    this.formGroup.get('frmPenunjang').setValue('')
    this.formGroup.get('frmAssesment').setValue('')

    var jsonGet = {
      "url": `referensi/dokter/pelayanan/2/tglPelayanan/${moment(new Date()).format('YYYY-MM-DD')}/Spesialis/${data.kdsubspesialisbpjs}`,
      "method": "GET",
      "data": null
    }
    this.isLoadingScreen = true;
    this.listDPJP = []
    this.httpService.postNonMessage('medifirst2000/bridging/bpjs/tools', jsonGet).subscribe(res => {
      if (res.metaData.code == "200") {
        this.listDPJP = res.response.list;
        this.item.frmDpjp = this.listDPJP[Math.floor(Math.random() * this.listDPJP.length)]
      }

      // cari data monitoring pelayanan peserta
      var datenow = new Date();
      var tglawal = moment(datenow.setMonth(datenow.getMonth() - 1)).format("YYYY-MM-DD");
      var tglakhir = moment(new Date()).format("YYYY-MM-DD");
      // console.log(this.listRujukan)
      var jsonGetMonitoring = {
        "url": `monitoring/HistoriPelayanan/NoKartu/${this.item.pasien.nobpjs}/tglMulai/${tglawal}/tglAkhir/${tglakhir}`,
        "method": "GET",
        "data": null
      }
      this.httpService.postNonMessage('medifirst2000/bridging/bpjs/tools', jsonGetMonitoring).subscribe(resMonitoring => {
        if (resMonitoring.metaData.code == "200") {
          this.listMonitoringPelayanan = resMonitoring.response.histori
        }

        this.isLoadingScreen = false;
        this.item.poliPilih = data
        this.modalService.open(modalSuccess, {
          centered: true,
          size: 'lg',
          windowClass: 'modal modal-success'
        });
      })
    })

  }

  goTo(name) {
    this.router.navigate([`v2/touchscreen` + name]);
  }
  goToTouchscreen() {
    this.router.navigate([`v2/touchscreen`]);
    this.kosongkanStorage();
  }

  nextStep(name) {
    switch (name) {
      case "halamanawal":
        this.kosongkanStorage();

        delete this.item.frmIdentitas;
        this.isHalamanAwal = true
        this.isHalamanPilihPoli = false;
        this.isHalamanCetak = false;
        break;
      case "halamanpilihpoli":

        this.item.pasien = JSON.parse(localStorage.getItem("dataPasienLokal"));
        this.item.rujukan = JSON.parse(localStorage.getItem("dataRujukanLokal"));
        this.item.kunjungan = JSON.parse(localStorage.getItem("dataKunjungan"));
        this.item.pasien.tgllahirformat = moment(this.item.pasien.tgllahir).format('DD-MM-YYYY')
        this.isHalamanAwal = false
        this.isHalamanPilihPoli = true;
        this.isHalamanCetak = false;
        break;
      case "halamancetak":
        this.isHalamanAwal = false
        this.isHalamanPilihPoli = false;
        this.isHalamanCetak = true;
        break;
    }
  }

  kosongkanStorage() {
    this.listSurkon = []
    this.listRujukan = []
    this.listMonitoringPelayanan = []
    this.listPendaftaranReservasi = []
    this.isReservasiPoli = false
    delete this.noReservasi
    delete this.idRuanganReservasi
    localStorage.removeItem("dataPasienLokal");
    localStorage.removeItem("dataRujukanLokal");
  }

  cariDataPasien() {
    if (!this.item.frmIdentitas) {
      this.alertService.error('', 'Harap isi terlebih dahulu !', {
        toastClass: 'toast ngx-toastr',
        closeButton: true,
        positionClass: 'toast-top-right'
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
          positionClass: 'toast-top-right'
        });
        return
      } else {
        if (e.data.nobpjs == "" || e.data.nobpjs == null) {
          delete this.noReservasi;
          this.isLoadingHalAwal = false;
          this.alertService.error('', 'Harap menuju loket untuk validasi nomor bpjs !', {
            toastClass: 'toast ngx-toastr',
            closeButton: true,
            positionClass: 'toast-top-right'
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
                if (element.jnsPelayanan == "1" && element.noSep.substr(0, 8) == "1019R001") {
                  var dx = element.diagnosa.split('-')
                  element.asalRujukan = "2";
                  element.tglKunjungan = element.tglSep
                  element.diagnosa = { kode: dx[0].trim(), nama: dx[1] }
                  element.noKunjungan = element.noSep
                  element.provPerujuk = { kode: '1019R001', nama: 'RSD GUNUNG JATI' }
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
                positionClass: 'toast-top-right'
              });
            } else {
              this.isLoadingHalAwal = false;
              localStorage.setItem("dataRujukanLokal", JSON.stringify(this.listRujukan));
              // set ruangan hanya tujuan apabila pasien online
              if (this.noReservasi) {
                this.listRuanganReserv = this.listRuangan.filter(e => e.id === this.idRuanganReservasi);
              }
              this.nextStep('halamanpilihpoli');
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
                  positionClass: 'toast-top-right'
                });
              } else {
                this.isLoadingHalAwal = false;
                localStorage.setItem("dataRujukanLokal", JSON.stringify(this.listRujukan));
                // set ruangan hanya tujuan apabila pasien online
                if (this.noReservasi) {
                  this.listRuanganReserv = this.listRuangan.filter(e => e.id === this.idRuanganReservasi);
                }
                this.nextStep('halamanpilihpoli');
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
            positionClass: 'toast-top-right'
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
            this.nextStep('halamancetak');
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
                this.nextStep('halamancetak');
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
                    element.provPerujuk = { kode: '1019R001', nama: 'RSD GUNUNG JATI' }
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
                    this.nextStep('halamancetak');
                  } else {
                    this.isLoadingHalAwal = false;
                    this.alertService.error('', 'Pasien sudah melakukan pendaftaran dihari ini!', {
                      toastClass: 'toast ngx-toastr',
                      closeButton: true,
                      positionClass: 'toast-top-right'
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
          positionClass: 'toast-top-right'
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
        positionClass: 'toast-top-right'
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
        positionClass: 'toast-top-right'
      });
      if (resSurkon.metaData.code == "200") {
        this.setSuratKontrol(noSep, moment(new Date()).format("YYYY-MM-DD"));
      }
    })
  }
  deleteSuratKontrol(noSuratKontrol) {
    var JsonDelate = {
      "url": `RencanaKontrol/Delete`,
      "method": "DELETE",
      "data": {
        "request": {
          "t_suratkontrol": {
            "noSuratKontrol": noSuratKontrol,
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
      this.alertService.warning('', "Harap pilih terlebih dahulu rujukan !", { toastClass: 'toast ngx-toastr', closeButton: true, positionClass: 'toast-top-right' });
      return
    }

    if (frmTujuanKunj === "" || frmTujuanKunj === null) {
      this.alertService.warning('', "Harap pilih terlebih tujuan kunjungan !", { toastClass: 'toast ngx-toastr', closeButton: true, positionClass: 'toast-top-right' });
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
        if (resx2.response.histori[0].poliTujSep == this.item.poliPilih.kdsubspesialisbpjs && resx2.response.histori[0].noSep.substr(0, 8) == "1019R001") {
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
                "ppkPelayanan": this.ppkPelayananRS,
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
    let pemakaianasuransi = {
      'norec': '',
      'noregistrasifk': this.pasienDaftar.norec_pd,
      'tglregistrasi': moment(new Date(this.pasienDaftar.tglregistrasi)).format('YYYY-MM-DD HH:mm'),
      'diagnosisfk': this.item.diagnosa.id != null ? this.item.diagnosa.id : null,
      'lakalantas': 0,
      'nokepesertaan': rujukan.peserta.noKartu,
      'norujukan': rujukan.noKunjungan,
      'nosep': this.dataSEP.noSep,
      'tglrujukan': rujukan.tglKunjungan,
      'objectdiagnosafk': this.item.diagnosa.id != null ? this.item.diagnosa.id : null,
      'tanggalsep': this.dataSEP.tglSep,
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
      'kodedpjp': this.formGroup.get("frmSurkon").value !== '' && this.formGroup.get("frmSurkon").value !== null ? this.formGroup.get('frmSurkon').value.value.kodeDokter : null,
      'namadpjp': this.formGroup.get("frmSurkon").value !== '' && this.formGroup.get("frmSurkon").value !== null ? this.formGroup.get('frmSurkon').value.value.namaDokter : null,
      'prolanisprb': null,
      'asalrujukanfk': rujukan.asalRujukan,
      'kodedpjpmelayani': this.item.frmDpjp !== undefined ? this.item.frmDpjp.kode : null,
      'namadjpjpmelayanni': this.item.frmDpjp !== undefined ? this.item.frmDpjp.nama : null,
      'polirujukankode': this.item.poliPilih.kdsubspesialisbpjs,
      'polirujukannama': this.item.poliPilih.namaruangan,
      'klsrawatnaik': null,
      'pembiayaan': null,
      'penanggungjawab': null,
      'tujuankunj': this.formGroup.get("frmTujuanKunj").value !== '' && this.formGroup.get("frmTujuanKunj").value !== null ? this.formGroup.get("frmTujuanKunj").value : null,
      'flagprocedure': this.formGroup.get("frmFlagProcedur").value !== '' && this.formGroup.get("frmFlagProcedur").value !== null ? this.formGroup.get("frmFlagProcedur").value : null,
      'kdpenunjang': this.formGroup.get("frmPenunjang").value !== '' && this.formGroup.get("frmPenunjang").value !== null ? this.formGroup.get("frmPenunjang").value : null,
      'assesmentpel': this.formGroup.get("frmAssesment").value !== '' && this.formGroup.get("frmAssesment").value !== null ? this.formGroup.get("frmAssesment").value : null,
      'statuskunjungan': statuskunjungan,
      'poliasalkode': rujukan.poliRujukan.nama,
      'politujuankode': this.item.poliPilih.namaruangan,
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
      this.nextStep('halamancetak')
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
  saveLogging(jenis, referensi, noreff, ket) {
    this.httpService.get("medifirst2000/sysadmin/logging/save-log-all?jenislog=" + jenis
      + "&referensi=" + referensi
      + "&noreff=" + noreff
      + "&keterangan=" + ket
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

  // cetakSepQz(){
  //   this._Qzprinter.connect();
  //   let datres:any;
  //   if (this.isCetakDSKiosk == 'true') {
  //       this.httpService.get("medifirst2000/bridging/bpjs/get-data-cetak-sep?noreg=" + this.pasienDaftar.noregistrasi).subscribe(e => {
  //         datres = e.data[0];
  //         if(datres == undefined){
  //           this.msgService.error('Zzzz::Tidak ada SEP!!!', JSON.stringify(e));
  //         }
  //         else{
  //           let base64String = this._JsPdfService.printSEPbase64(datres)
  //           let datas = [{ 
  //               type: 'pixel',
  //               format: 'pdf',
  //               flavor: 'base64',
  //               data: base64String
  //           }]; 

  //           this._Qzprinter.printData('SEP', datas).subscribe(err => {});
  //         }
  //       })
  //     } else {
  //     window.open(Configuration.get().apiBackend + 'medifirst2000/report/cetak-sep?noregistrasi='
  //       + this.pasienDaftar.noregistrasi + '&kdprofile=33', '_blank');
  //   }
  // }

  // cetakLabelQz(qty : number){
  //   if(qty == null) {
  //     qty = 1
  //   }
  //   this._Qzprinter.connect();
  //   let norec = this.pasienDaftar.noregistrasi ;
  //   let umur = '-';
  //   let JK = '-'
  //                   //var user = $scope.selectedData2[0].detail.userData.namauser
  //                   // var connect = connect();
  //   let data = {
  //               "gambarLogo": "logo.png",
  //               "paramKey": ["norec","umur","JK"],
  //               "paramValue": [norec,umur,JK],
  //               "paramType": ["","",""]// "paramType" : ["","date"] selain date, isinya kosong saja
  //             };
  //   this._JasperService.jasperCall('EtiketIdentitasKiosk.pdf',data).subscribe(resJasper => { 
  //       if (resJasper.response.byteLength > 1000){
  //         let binary = '';
  //         const bytes = new Uint8Array(resJasper.response);
  //         const len = bytes.byteLength;
  //         for (let i = 0; i < len; i++) {
  //           binary += String.fromCharCode(bytes[i]);
  //         }
  //         const base64String = window.btoa(binary);
  //         //var url = window.URL.createObjectURL(this.response)
  //         //debugger
  //         let datas = [{ 
  //             type: 'pixel',
  //             format: 'pdf',
  //             flavor: 'base64',
  //             data: base64String
  //         }]; 

  //         this._Qzprinter.printData('ETIKET-PUTIH', datas, qty).subscribe(e =>{} );
  //       }
  //   });
  //   //this.service.get('http://127.0.0.1:1237/printvb/Pendaftaran?cetak-labelpasien-satu=1&norec=' + this.pasienDaftar.noregistrasi + '&view=false&qty=' + qty).subscribe(e => { });
  // }

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
    this.httpService.postNonMessage('medifirst2000/rawatjalan/save-monitoring-taskid', json).subscribe(e => { })
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

}
