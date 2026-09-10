import { HttpClient } from '@angular/common/http';
import { Component, OnInit, ViewEncapsulation } from '@angular/core';
import { FormBuilder, FormControl, FormGroup } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import moment from 'moment';
import { ToastrService } from 'ngx-toastr';
import { CacheService } from '../../module/cache.service';
import { Configuration } from '../../module/config';
import { HttpService } from '../../module/httpService';
import { QzprinterService } from '../../module/qzprinter.service';
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
            link: '/setting-loket'
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
    this.httpservice.get('medifirst2000/reservasionline/get-history?noReservasi=' + this.formGroup.get('noReservasi').value
      + '&cekin=true').subscribe(e => {
        this.isloading = false
        if (e.data.length > 0) {

          let result = e.data[0]
          let now = new Date();// new Date(new Date(tglRes).setHours(new Date(tglRes).getHours() - 1))
          let tglResDate = new Date(result.tanggalreservasi)
          var hours = this.diff_hours(tglResDate, now)
          // if (hours > this.batasJamCheckin) {
          //   this.alertService.error('', 'Check-In hanya bisa dilakukan SATU Jam sebelum jam reservasi', {
          //     toastClass: 'toast ngx-toastr',
          //     closeButton: true,
          //     positionClass: 'toast-bottom-center'
          //   });
          //   // this.alertService.error('Info', 'Check-In hanya bisa dilakukan SATU Jam sebelum jam reservasi')
          //   return
          // }
          if (new Date() > new Date(result.tanggalreservasi)) {
            this.alertService.error('', 'Batas Waktu Check-In anda melebihi batas yang ditentukan', {
              toastClass: 'toast ngx-toastr',
              closeButton: true,
              positionClass: 'toast-bottom-center'
            });
            // this.alertService.error('Info', 'Batas Waktu Check-In anda melebihi batas yang ditentukan')
            return
          }

          if (result.tgllahir == null)
            result.tgllahir = '-'
          else
            result.tgllahir = moment(new Date(result.tgllahir)).format('YYYY-MM-DD')
          if (result.tempatlahir == null)
            result.tempatlahir = '-'
          if (result.dokter == null)
            result.dokter = '-'
          if (result.alamatlengkap == null)
            result.alamatlengkap = '-'
          if (result.notelepon == null || result.notelepon == "")
            result.notelepon = '-'
          result.tanggalreservasi = moment(new Date(result.tanggalreservasi)).format('YYYY-MM-DD HH:mm')
          this.isInfoPasien = true
          this.item = result
          // this.namaPasien = result.namapasien
          // this.jenisKelamin = result.jeniskelamin
          // this.noIdentitas = result.noidentitas
          // this.tempatTglLahir = result.tempatlahir + ', ' + result.tgllahir
          // this.alamat = result.alamatlengkap
          // this.noTelpon = result.notelepon
          // this.idPasien = result.nocmfk

        } else {
          this.isInfoPasien = false
          this.alertService.error('', 'Data tidak ditemukan', {
            toastClass: 'toast ngx-toastr',
            closeButton: true,
            positionClass: 'toast-bottom-center'
          });
          // this.alertService.error('Info', 'Data tidak ditemukan')
        }
      }, error => {
        this.isloading = false
        this.isInfoPasien = false
        this.alertService.error('', 'Data tidak ditemukan', {
          toastClass: 'toast ngx-toastr',
          closeButton: true,
          positionClass: 'toast-bottom-center'
        });
        // this.alertService.error('Info', 'Data tidak ditemukan')
      })
  }
  cetakBukti() {
    if (this.isCetakDSKiosk == 'true') {
      this._Qzprinter.prinBlade('medifirst2000/report/cetak-bukti-pendaftaran?noregistrasi='
        + this.item.noregistrasi
        + '&noReservasi=' + this.item.noreservasi, 'ANTRIAN POLI', 1)

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
    }
    if (this.item.objectkelompokpasienfk == 2 && this.item.type != "BARU") {
      this.cacheHelper.set('cacheOnlineBPJS', this.item)
      this.router.navigate(['v2/touchscreen/self-regis/verif-pasien-bpjs'], { queryParams: { page: 'BPJS' } })
      return
    }
    else {
      this.savePasienDaftar()
      return
    }
  }
  savePasienPerjanjian() {

    let antrian = {
      "jenis": this.item.prefixnoantrian//"D"
    }
    this.httpservice.post('medifirst2000/kiosk/save-antrian', antrian).subscribe(response => {
      // window.open(Configuration.get().apiBackend + 'medifirst2000/report/cetak-antrian?norec='
      // + response.noRec
      // + '&noReservasi=' + this.item.noreservasi
      // + '&kdprofile=21',  '_blank');
      this.service.get('http://127.0.0.1:1237/printvb/cetak-antrian-online?cetak=1&norec=' + response.noRec
        + '&noReservasi=' + this.item.noreservasi).subscribe(result => {
          // do something with response

        })
      window.history.back()
    }, error => {

    })
  }
  savePasienDaftar() {
    var pasiendaftar = {
      'norec': '',
      'nocmfk': this.item.nocmfk ? this.item.nocmfk : '',
      'tglregistrasi': moment(new Date()).format('YYYY-MM-DD HH:mm:ss'),
      'objectruanganlastfk': this.item.idruangan,
      'asalrujukanfk': 5,
      'objectkelompokpasienlastfk': this.item.objectkelompokpasienfk != null ? this.item.objectkelompokpasienfk : 1,
      'jenispelayananfk': 1,
      'objectpegawaifk': this.item.iddokter,
      'objectkelasfk': 6,
      'israwatinap': false,
      'catatan': 'Kios-K',
      'statuspasien': this.item.statuspasien ? this.item.statuspasien : 'LAMA',
      'objectrekananfk': null,
      'nocm': this.item.nocm,
      'namapasien': this.item.namapasien,
      'antrianpasienregistrasifk': this.item.norec ? this.item.norec : null,
      'iskiosk': true,
      'statusschedule': this.item.noreservasi
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

    this.httpservice.post('medifirst2000/registrasi/save-registrasipasien', objSave).subscribe(response => {

      this.item.noregistrasi = response.dataPD.noregistrasi
      this.saveAdminAuto({
        norec_pds: response.dataPD.norec,
        norec_apds: response.dataAPD.norec,
      })

      // this.cetakBukti()
      // if (this.item.objectkelompokpasienfk == 2 && this.item.type != "BARU") {
      //   this.alertService.info('Peringatan','Pastikan SEP Di Cetak di Loket Pendaftaran !!')
      // }
      // this.saveLogging('Pendaftaran Pasien', 'norec Pasien Daftar', response.dataPD.norec,
      //   'Check-In No Registrasi (' + response.dataPD.noregistrasi + ') ')
      // this.updateStatusConfirm()
      // window.history.back()
    }, error => {

    })
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
    this.httpservice.postNonMessage('medifirst2000/reservasionline/update-data-status-reservasi', data).subscribe(e => {

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
}
