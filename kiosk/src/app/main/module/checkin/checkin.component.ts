import { HttpClient } from '@angular/common/http';
import { Component, OnInit, ViewEncapsulation } from '@angular/core';
import { FormBuilder, FormControl, FormGroup } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import moment from 'moment';
import { ToastrService } from 'ngx-toastr';
import { CacheService } from '../cache.service';
import { Configuration } from '../config';
import { HttpService } from '../httpService';
import { QzprinterService } from '../qzprinter.service';
@Component({
  selector: 'app-checkin',
  templateUrl: './checkin.component.html',
  styleUrls: ['./checkin.component.scss'],

  encapsulation: ViewEncapsulation.None
})
export class CheckinComponent implements OnInit {
  contentHeader: any
  url: any
  sub: any;
  type: any;
  resultdokter: any;
  resultdata: any;
  resultkelompokpasien: any;
  resultpoli: any;
  resultpasien: any;
  resultagama: any;
  resultkebangsaan: any;
  resultjeniskelamin: any;
  formGroup: FormGroup;
  isInfoPasien: boolean = false
  item: any = {}
  dataCache: any
  batasJamCheckin = 1
  isloading: boolean = false

  isCetakDSKiosk: any = 'true'
  constructor(private router: Router,
    private route: ActivatedRoute,
    private httpservice: HttpService,
    private fb: FormBuilder,
    private cacheHelper: CacheService,
    private service: HttpClient,
    private _Qzprinter: QzprinterService,
    private alertService: ToastrService,) {
    let sett = localStorage.getItem('isCetakDS')
    if (sett != null) {
      this.isCetakDSKiosk = sett
    }
  }

  ngOnInit(): void {
    this._Qzprinter.connect();
    this.httpservice.get('medifirst2000/sysadmin/settingdatafixed/get/batasJamCheckin').subscribe(resp => {
      this.batasJamCheckin = resp
    })


    this.formGroup = this.fb.group({
      'noReservasi': new FormControl(''),

    })
    this.sub = this.route
      .queryParams
      .subscribe(params => {
        // Defaults to 0 if no query param provided.
        this.url = params['page'];
      });
    let noreservasi = this.cacheHelper.get('cacheAutoNoReservasi')
    if (noreservasi != undefined) {
      this.formGroup.get('noReservasi').setValue(noreservasi)
      this.getInfoByNoReservasi()
      this.cacheHelper.set('cacheAutoNoReservasi', undefined)
    }
    this.contentHeader = {
      headerTitle: 'Check-In Reservasi Online',
      actionButton: true,
      breadcrumb: {
        type: '',
        links: [
          {
            name: 'Menu Utama',
            isLink: true,
            link: '/touchscreen-v2'
          },

        ]
      }
    };
  }
  diff_hours(dt2, dt1) {
    var diff = (dt2.getTime() - dt1.getTime()) / 1000;
    diff /= (60 * 60);
    return Math.abs(diff);//Math.abs(Math.round(diff));    
  }
  getInfoByNoReservasi() {
    this.isloading = true

    // this.httpservice.get('registrasi/pasien-hari-ini?nocm=' + this.formGroup.get('noReservasi').value).subscribe(er => {
    //   console.log(er)
    //   if (er != null) {
    //     this.isloading = false
    //     this.alertService.info('Pasien sudah teregistrasi hari ini di ' + er.namaruangan, er.namaruangan, {
    //       toastClass: 'toast ngx-toastr',
    //       closeButton: true,
    //       positionClass: 'toast-bottom-center'
    //     });
    //   } else {
        this.httpservice.get('registrasi/cek-pasien-piutang?nocm=' + this.formGroup.get('noReservasi').value).subscribe(er => {
          console.log(er)
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
            this.httpservice.get('medifirst2000/reservasionline/get-history?noReservasi=' + this.formGroup.get('noReservasi').value
            +'&cekin=true').subscribe(e => {
              this.isloading = false
              if (e.data.length > 0) {

                let result = e.data[0]
                console.log(result)
                this.resultdata = e.data[0]
                let now = new Date();// new Date(new Date(tglRes).setHours(new Date(tglRes).getHours() - 1))
                let tglResDate = new Date(result.tanggalreservasi)
                var hours = this.diff_hours(tglResDate, now)
                console.log(new Date())
                console.log(new Date(result.UntukTanggal))
                if (moment(new Date()).format('YYYY-MM-DD') > moment(new Date(result.UntukTanggal)).format('YYYY-MM-DD')) {
                  this.alertService.error('', 'Batas Waktu Check-In anda melebihi batas yang ditentukan', {
                    toastClass: 'toast ngx-toastr',
                    closeButton: true,
                    positionClass: 'toast-bottom-center'
                  });
                  return
                }

                if (result.type == null)
                  result.type = '-'
                else
                  result.type
                if (result.NRM == null)
                  result.NRM = '-'
                else
                  result.NRM
                if (result.tgllahir == null)
                  result.tgllahir = '-'
                else
                  result.tgllahir = moment(new Date(result.tgllahir)).format('YYYY-MM-DD')
                if (result.tempatlahir == null)
                  result.tempatlahir = '-'
                if (result.namaruangan == null)
                  result.namaruangan = '-'
                if (result.kelompokpasien == null)
                  result.kelompokpasien = '-'
                if (result.nobpjs == null)
                  result.nobpjs = '-' 
                if (result.alamatlengkap == null)
                  result.alamatlengkap = '-'
                if (result.notelepon == null || result.notelepon == "")
                  result.notelepon = '-'
                result.tanggalreservasi = moment(new Date(result.tanggalreservasi)).format('YYYY-MM-DD')
                this.isInfoPasien = true
                this.item = result
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
        })
    //   }
    // })




    
  }
  cetakBukti() {
    if (this.isCetakDSKiosk == 'true') {
      this._Qzprinter.prinBlade('medifirst2000/report/cetak-bukti-pendaftaran?noregistrasi='
      + this.item.noregistrasi
      + '&noReservasi=' + this.item.noreservasi ,'ANTRIAN POLI',1)
    
      // this.service.get('http://127.0.0.1:1237/printvb/Pendaftaran?cetak-buktipendaftaran-online=1&norec='
      //   + this.item.noregistrasi + '&view=false'
      //   + '&noReservasi=' + this.item.noreservasi).subscribe(response => {
      //     // do something with response
      //   });
    } else if (this.isCetakDSKiosk == 'android') {
      this.httpservice.get("medifirst2000/report/get-cetak-bukti-pendaftaran?noregistrasi=" 
      + this.item.noregistrasi
      ).subscribe(e => {
        window.open(
          'https://apps.transmedic.co.id/cetak-antrian?isBukti=true'
          + '&namaProfile=' + Configuration.profile().nama
          + '&alamat=' + Configuration.profile().alamat
          + '&noregistrasi=' + e.noregistrasi
          + '&norm=' + e.norm
          + '&namapasien=' + e.namapasien
          + '&kelompokpasien=' + e.kelompokpasien
          + '&noantrian=' + e.noantrian
          + '&statuspasien=' + e.statuspasien
          + '&tanggalreservasi=' + e.tanggalreservasi
          + '&ruangan=' + e.ruangan
          + '&status=' + e.statusonline
          + '&namadokter=' + e.namadokter
          + '&tglregistrasi=' + e.tglregistrasi
          + '&link=' + Configuration.profile().link
        );
      })
    } else {
      this.httpservice.blade(Configuration.get().apiBackend + 'medifirst2000/report/cetak-bukti-pendaftaran?noregistrasi='
        + this.item.noregistrasi
        + '&noReservasi=' + this.item.noreservasi
        + '&kdprofile=21', '_blank');
    }

  }
  checkIn() {
    if (this.item.type == "BARU") {
      this.savePasienPerjanjian()
      return
    } else{
      this.savePasienDaftar()
    }
    // if (this.item.objectkelompokpasienfk == 2 && this.item.type != "BARU") {
    //   this.cacheHelper.set('cacheOnlineBPJS', this.item)
    //   this.router.navigate(['touchscreen/self-regis/verif-pasien-bpjs'], { queryParams: { page: 'BPJS' } })
    //   return
    // }
    // else {
    //   //this.savePasienDaftar()
    //   this.updateStatusConfirm()
    //   return
    // }
  }
  savePasienPerjanjian() {
      let json = {
        'pasien': {
            'id': '',
            'isPenunjang': false,
            'isJenazah':  false,
            'isbayi': false,
            'nocmfkibu': null,
            'noidentitas': this.resultdata.noidentitas,
            'nobpjs': this.resultdata.nobpjs,
            'namapasien': this.resultdata.namapasien,
            'tempatlahir': this.resultdata.tempatlahir,
            'tgllahir': moment(new Date(this.resultdata.tgllahir)).format('YYYY-MM-DD'),
            'objectjeniskelaminfk': this.resultdata.objectjeniskelaminfk,
            'nohp': this.resultdata.notelepon,
            'objectagamafk': this.resultdata.objectagamafk != undefined ? this.resultdata.objectagamafk : null,
            'email': this.resultdata.email,
            'namaibu': null,
            'kode_pasien_baru': null,
            'objectstatusperkawinanfk': null,
            'objectgolongandarahfk': null,
            'objectpendidikanfk': null,
            'objectpekerjaanfk': null,
            'objectsukufk': null,
            'noaditional': null,
            'notelepon': this.resultdata.notelepon,
            'namaayah': null,
            'namakeluarga': null,
            'namasuamiistri': null,
            'penanggungjawab': null,
            'hubungankeluargapj': null,
            'telponpenanggungjawab': null,
            'bahasa': null,
            'jeniskelaminpenanggungjawab': null,
            'umurpenanggungjawab': null,
            'pekerjaanpenangggungjawab': null,
            'alamatrmh': null,
            'objectkebangsaanfk': this.resultdata.objectkebangsaanfk,
            'objectnegarafk': null,
            'progress': 0,
            'isReservasi' : true,
            'antrianpasienregistrasifk': null,
            'norecEMR' : null,
            'isIGD' : false,
        },
        'alamat': {
            'alamatlengkap': this.resultdata.alamatlengkap,
            'rtrw': null,
            'objectpropinsifk': null,
            'objectkotakabupatenfk': null,
            'objectkecamatanfk': null,
            'objectdesakelurahanfk': null,
            'kodepos': null,
        }
      }

    this.httpservice.post('registrasi/save-pasien', json).subscribe(responses => {
      this.resultdata.nocmfk = responses.data.id
      this.savePasienDaftar()
    })
  }
  savePasienDaftar() {
    
    var antrian ={
      "jenis": this.resultdata.jenis,
      "ruanganfk": this.resultdata.idruangan,
      "namaruangan": this.resultdata.namaruangan,
      "nocmfk": this.resultdata.nocmfk,
      "loketid": this.resultdata.loketkiosk,
      "nopeserta": this.resultdata.nobpjs !== null ? this.resultdata.nobpjs : null,
      "namapeserta": this.resultdata.namapasien !== null ? this.resultdata.namapasien : null,
      "kelompokpasien": this.resultdata.objectkelompokpasienfk,
      "objectpegawaifk": this.resultdata.iddokter,
      "jenispelayanan": 1,
      "norec": this.resultdata.norec,
      "noantrian": this.resultdata.noantrian,
      "noantrianpoli": this.resultdata.noantrianpoli
    }

    console.log(antrian)

        // this.httpservice.get('registrasi/pasien-hari-ini?nocmfk=' + this.resultdata.nocmfk).subscribe(er => {
        //   console.log(er)
        //   if (er != null && er.namaruangan == this.resultdata.namaruangan) {
        //     this.alertService.info('Pasien sudah teregistrasi hari ini di ' + er.namaruangan, er.namaruangan, {
        //       toastClass: 'toast ngx-toastr',
        //       closeButton: true,
        //       positionClass: 'toast-bottom-center'
        //     });
        //   } else {
            this.httpservice.post('medifirst2000/kiosk/save-antrian', antrian).subscribe(response => {

              let res: any
                if (this.isCetakDSKiosk == 'true') {

                  if(this.resultdata.objectkelompokpasienfk == 1){

                    this._Qzprinter.prinBlade(`dashboard/registrasi/cetak-label-pasien?noregistrasi=` + response.noregistrasi, 'LABEL PASIEN', 5)

                  }

                  this._Qzprinter.prinBlade(`medifirst2000/report/cetak-antrian?norec=` + response.noRec + '&noregistrasi=' + response.noregistrasi, 'ANTRIAN LOKET', 1)

                } 
                else {

                  if(this.resultdata.objectkelompokpasienfk == 1){
                    this._Qzprinter.prinBlade(`dashboard/registrasi/cetak-label-pasien?noregistrasi=` + response.noregistrasi, 'LABEL PASIEN', 5)


                  }
                  

                  this._Qzprinter.prinBlade(`medifirst2000/report/cetak-antrian?norec=` + response.noRec + '&noregistrasi=' + response.noregistrasi, 'ANTRIAN LOKET', 1)
                }

              let kebangsaan = 0
              if(response.pasien.objectkebangsaanfk != null){
                kebangsaan = response.pasien.objectkebangsaanfk
              } else{
                if(this.item.jenis == 'PN'){
                  kebangsaan = 3
                } else{
                  kebangsaan = 1
                }
              }

              let json = {
                "norec": response.norec_pd,
                "norec_apd": response.norec_apd,
                "objectkebangsaanfk": kebangsaan
              }
              this.httpservice.post('registrasi/save-adminsitrasi', json).subscribe(responsex => {
                this.router.navigate(['touchscreen-v2']);
              })
            })
        //   }
        // })


    

    // this.httpservice.post('medifirst2000/kiosk/save-antrian', antrian).subscribe(response => {

    // })

    // this.httpservice.post('medifirst2000/registrasi/save-registrasipasien', objSave).subscribe(response => {

    //   this.item.noregistrasi = response.dataPD.noregistrasi
    //   this.saveAdminAuto({
    //     norec_pds : response.dataPD.norec,
    //     norec_apds : response.dataAPD.norec,
    //   })
   
    //   // this.cetakBukti()
    //   // if (this.item.objectkelompokpasienfk == 2 && this.item.type != "BARU") {
    //   //   this.alertService.info('Peringatan','Pastikan SEP Di Cetak di Loket Pendaftaran !!')
    //   // }
    //   // this.saveLogging('Pendaftaran Pasien', 'norec Pasien Daftar', response.dataPD.norec,
    //   //   'Check-In No Registrasi (' + response.dataPD.noregistrasi + ') ')
    //   // this.updateStatusConfirm()
    //   // window.history.back()
    // }, error => {

    // })
  }
  saveAdminAuto(pd) {
    let json = {
      norec: pd.norec_pds,
      norec_apd: pd.norec_apds
    }
    this.httpservice.postNonMessage("medifirst2000/registrasi/save-adminsitrasi", json).subscribe(z => {

    })
  }
  updateStatusConfirm() {
    let data = {
      "noreservasi": this.item.noreservasi,
    }
    this.httpservice.post('medifirst2000/reservasionline/update-data-status-reservasi', data).subscribe(e => {
      this.httpservice.blade(Configuration.get().apiBackend + 'report/bukti-pendaftaran?pdf=true&noregistrasi='
                    + e.registrasi.noregistrasi, '_blank');
                    
      let json = {
        "norec": e.registrasi.norec_pd,
        "norec_apd": e.registrasi.norec_apd,
        "objectkebangsaanfk": e.objectkebangsaanfk
      }
      this.httpservice.post('registrasi/save-adminsitrasi', json).subscribe(response => {
        
      })
    })
  }
  saveLogging(jenis, referensi, noreff, ket) {
    // this.httpservice.get("medifirst2000/sysadmin/logging/save-log-all?jenislog=" + jenis
    //   + "&referensi=" + referensi
    //   + "&noreff=" + noreff
    //   + "&keterangan=" + ket
    // ).subscribe(e => {

    // })
  }

  noRmFormat() {
    let val = this.formGroup.get('noReservasi').value
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
    this.formGroup.get('noReservasi').setValue(formatted)
    // item.qnocm = formatted;
  }
  
}
