import { HttpClient } from '@angular/common/http';
import { Component, OnInit, ViewEncapsulation } from '@angular/core';
import { FormBuilder, FormControl, FormGroup } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import moment from 'moment';
import { ToastrService } from 'ngx-toastr';
import { Observable } from 'rxjs';
import { CacheService } from '../cache.service';
import { Configuration } from '../config';
import { HttpService } from '../httpService';
import { QzprinterService } from '../qzprinter.service';

@Component({
  selector: 'app-pilih-poli',
  templateUrl: './pilih-poli.component.html',
  styleUrls: ['./pilih-poli.component.scss'],

  encapsulation: ViewEncapsulation.None
})
export class PilihPoliComponent implements OnInit {
  contentHeader: any
  public selectBasic: any[] = [];
  public selectBasicLoading = false;
  url: any
  sub: any;
  formGroup: FormGroup;
  isInfoPasien: boolean = false
  isAdminOtomatisKiosk: any
  showDokter: boolean = true
  listRuangan: any[] = []
  listDokter: any[] = []
  listAsuransi: any[] = []
  listPerusahaan: any[] = []
  myControl = new FormControl();
  // options: string[] = ['One', 'Two', 'Three'];
  filteredOptions: Observable<string[]>;
  now: any = new Date()
  dataCache: any;
  item: any = {}
  showNoAntrian: boolean = false
  isPasienBaru: boolean = false
  isAsuransi: boolean = false
  isPerusahaan: boolean = false
  loading: boolean = false
  isCetakDSKiosk: any = 'true'
  constructor(private router: Router,
    private route: ActivatedRoute,
    private httpService: HttpService,
    private fb: FormBuilder,
    private cacheHelper: CacheService,
    private service: HttpClient,
    private alertService: ToastrService,
    
    private _Qzprinter: QzprinterService,) {
    let sett = localStorage.getItem('isCetakDS')
    if (sett != null) {
      this.isCetakDSKiosk = sett
    }
  }

  ngOnInit(): void {
    this._Qzprinter.connect();
    this.contentHeader = {
      headerTitle: 'Pilih Poli & Dokter',
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
    this.httpService.get('medifirst2000/kiosk/get-combo-setting').subscribe(resps => {

      this.isAdminOtomatisKiosk = resps.isAdminOtomatisKiosk

    }, error => {

      this.isAdminOtomatisKiosk = 'false'
    })
    let cache = this.cacheHelper.get('cacheSelfRegis')
    if (cache != undefined) {
      if (cache[0].idpasien != undefined) {
        this.isPasienBaru = true
      } else {
        if (cache[0].noidentitas == null || cache[0].noidentitas == "")
          cache[0].noidentitas = '-'
        if (cache[0].notelepon == null || cache[0].notelepon == "")
          cache[0].notelepon = '-'
        cache[0].tempatTglLahir = cache[0].tempatlahir + ', ' + cache[0].tgllahir
      }

      // this.item = cache[0]
      this.dataCache = cache
      // this.cacheHelper.set('cacheSelfRegis', undefined);
    }
    this.formGroup = this.fb.group({
      'idRuangan': new FormControl(null),
      'idDokter': new FormControl(null),
      'tglRegis': new FormControl(new Date()),
      'ceklisEkse': new FormControl(null),
      'idAsuransi': new FormControl(null),
      'idPerusahaan': new FormControl(null),
    });
    this.httpService.get('medifirst2000/kiosk/get-combo-kiosk2?eksek=false').subscribe(e => {
      this.listRuangan = [];
      this.listRuangan.push({ label: '--Pilih Poli --', value: null });
      e.ruanganrajal.forEach(response => {
        this.listRuangan.push({
          label: response.namaruangan, value: {
            'id': response.id,
            'namaruangan': response.namaruangan,
            'objectdepartemenfk': response.objectdepartemenfk
          }
        });
      });

      // this.listDokter = [];
      // this.listDokter.push({ label: '--Pilih Dokter --', value: null });
      // e.dokter.forEach(response => {
      //   this.listDokter.push({
      //     label: response.namalengkap, value: {
      //       'id': response.id,
      //       'dokter': response.namalengkap
      //     }
      //   });
      // });
      // this.filteredOptions = this.myControl.valueChanges
      //   .pipe(
      //     startWith(''),
      //     map(value => this._filter(value))
      //   );

    })
    this.route
    .queryParams
    .subscribe(params => {
      // Defaults to 0 if no query param provided.
      this.url = params['tipepasien'];
      switch (this.url) {
        case "Asuransi":
          this.httpService.get('medifirst2000/kiosk/get-penjaminbykelompokpasien?kdKelompokPasien=3').subscribe(datax => {
            this.listAsuransi = [];
            this.listAsuransi.push({ label: '--Pilih Perusahaan --', value: null });
            datax.rekanan.forEach(response => {
              this.listAsuransi.push({
                label: response.namarekanan, value: {
                  'id': response.id,
                  'namarekanan': response.namarekanan,
                  'id_kelompokpasien': response.id_kelompokpasien
                }
              })
            })
          })
          this.isAsuransi = true;
          break;
        case "Perusahaan-In":
          this.httpService.get('medifirst2000/kiosk/get-penjaminbykelompokpasien?kdKelompokPasien=5').subscribe(datay => {
            this.listPerusahaan = [];
            this.listPerusahaan.push({ label: '--Pilih Perusahaan --', value: null });
            for (let y = 0; y < datay.rekanan.length; y++) {
              const element = datay.rekanan[y];
              this.listPerusahaan.push({
                label: element.namarekanan, value: {
                  'id': element.id,
                  'namarekanan': element.namarekanan,
                  'id_kelompokpasien': element.id_kelompokpasien
                }
              })
            }
          })
          this.isPerusahaan = true;
          break;
      }
    });
  }

  private _filter(value: string): string[] {
    const filterValue = value.toLowerCase();

    return this.listRuangan.filter(option => option.namaruangan.toLowerCase().includes(filterValue));
  }

  viewResume() {
    // debugger
    this.isInfoPasien = true
    if (this.isPasienBaru == true) {
      this.dataCache[0].nocm = '-'
      this.dataCache[0].namapasien = this.dataCache[0].pasien.namaPasien
      this.dataCache[0].noidentitas = this.dataCache[0].pasien.noIdentitas
      this.dataCache[0].tempatTglLahir = this.dataCache[0].pasien.tempatLahir + ', ' + this.dataCache[0].pasien.tglLahir
      this.dataCache[0].noidentitas = this.dataCache[0].pasien.noIdentitas
      this.dataCache[0].alamatlengkap = this.dataCache[0].alamatLengkap
      this.dataCache[0].notelepon = this.dataCache[0].pasien.noHp
      this.dataCache[0].namaruangan = this.formGroup.get('idRuangan').value.value.namaruangan
      this.dataCache[0].idruangan = this.formGroup.get('idRuangan').value.value.id
      this.dataCache[0].objectdepartemenfk = this.formGroup.get('idRuangan').value.value.objectdepartemenfk
      this.dataCache[0].dokter = this.formGroup.get('idDokter').value != null ? this.formGroup.get('idDokter').value.value.dokter : null
      this.dataCache[0].iddokter = this.formGroup.get('idDokter').value != null ? this.formGroup.get('idDokter').value.value.id : null
      this.dataCache[0].tglregistrasi = moment(new Date()).format('YYYY-MM-DD HH:mm')
      this.dataCache[0].tipelayanan = this.formGroup.get('ceklisEkse').value != null && this.formGroup.get('ceklisEkse').value == true ? 2 : 1
      this.dataCache[0].asuransi = this.formGroup.get('idAsuransi').value != null ? this.formGroup.get('idAsuransi').value.value.namarekanan : null
      this.dataCache[0].idasuransi = this.formGroup.get('idAsuransi').value != null ? this.formGroup.get('idAsuransi').value.value.id : null
      this.dataCache[0].persuahaan = this.formGroup.get('idPerusahaan').value != null ? this.formGroup.get('idPerusahaan').value.value.namarekanan : null
      this.dataCache[0].idpersuahaan = this.formGroup.get('idPerusahaan').value != null ? this.formGroup.get('idPerusahaan').value.value.id : null
      this.item = this.dataCache[0]

    } else {
      this.dataCache[0].tglregistrasi = moment(new Date()).format('YYYY-MM-DD HH:mm')
      this.dataCache[0].namaruangan = this.formGroup.get('idRuangan').value.value.namaruangan
      this.dataCache[0].idruangan = this.formGroup.get('idRuangan').value.value.id
      this.dataCache[0].dokter = this.formGroup.get('idDokter').value != null ? this.formGroup.get('idDokter').value.value.dokter : null
      this.dataCache[0].iddokter = this.formGroup.get('idDokter').value != null ? this.formGroup.get('idDokter').value.value.id : null
      this.dataCache[0].objectdepartemenfk = this.formGroup.get('idRuangan').value.objectdepartemenfk
      this.dataCache[0].tipelayanan = this.formGroup.get('ceklisEkse').value != null && this.formGroup.get('ceklisEkse').value == true ? 2 : 1
      this.dataCache[0].asuransi = this.formGroup.get('idAsuransi').value != null ? this.formGroup.get('idAsuransi').value.value.namarekanan : null
      this.dataCache[0].idasuransi = this.formGroup.get('idAsuransi').value != null ? this.formGroup.get('idAsuransi').value.value.id : null
      this.dataCache[0].persuahaan = this.formGroup.get('idPerusahaan').value != null ? this.formGroup.get('idPerusahaan').value.value.namarekanan : null
      this.dataCache[0].idpersuahaan = this.formGroup.get('idPerusahaan').value != null ? this.formGroup.get('idPerusahaan').value.value.id : null
      this.item = this.dataCache[0]
    }


  }
  save() {
    // this.httpService.get('medifirst2000/kiosk/get-slotting-kosong?ruanganfk=' + this.item.idruangan).subscribe(es => {
    //   if (es.status == true) {
        this.lanjutSave()
      // } else {
        // this.alertService.info('Info', es.status)
        // return
      // }
    // })
  }
  lanjutSave() {
    if (this.isPasienBaru == true) {
      var postJson = {
        'pasien': {
            'id': '',
            'isPenunjang': false,
            'isbayi': false,
            'nocmfkibu': null,
            'noidentitas': this.item.noidentitas,
            'nobpjs':null,
            'namapasien': this.item.namapasien,
            'tempatlahir': null,
            'tgllahir': this.item.tglLahir,
            'objectjeniskelaminfk': null,
            'nohp': this.item.notelepon,
            'objectagamafk': null,
            'email': null,
            'namaibu': null,
            'objectstatusperkawinanfk': null,
            'objectgolongandarahfk': null,
            'objectpendidikanfk': null,
            'objectpekerjaanfk': null,
            'objectsukufk': null,
            'noaditional': null,
            'notelepon': this.item.notelepon,
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
            'objectkebangsaanfk': null,
            'objectnegarafk': null,
            'progress': 0,
        },
        'alamat': {
            'alamatlengkap': '-',
            'objectpropinsifk': null,
            'objectkotakabupatenfk': null,
            'objectkecamatanfk': null,
            'objectdesakelurahanfk': null,
            'kodepos': null,
        }      
      }
      this.httpService.post('medifirst2000/registrasi/save-pasien-fix', postJson).subscribe(e => {
        this.item.nocmfk = e.data.id
        this.item.statuspasien = 'BARU'
        this.savePsienDaftar()
      })
    } else {
      this.item.statuspasien = 'LAMA'
      this.savePsienDaftar()
    }
  }
  savePsienDaftar() {
    var kelompokpasien = null;
    switch (this.url) {
      case "Asuransi":
        kelompokpasien = 3
        this.item.objectrekananfk = this.item.idasuransi
        break;
      case "Perusahaan-In":
        kelompokpasien = 5
        this.item.objectrekananfk = this.item.idpersuahaan
        break;
      default:
        kelompokpasien = 1;
        this.item.objectrekananfk = null
        break;
    }
    var pasiendaftar = {
      'norec': '',
      'nocmfk': this.item.nocmfk ? this.item.nocmfk : '',
      'tglregistrasi': moment(new Date()).format('YYYY-MM-DD HH:mm:ss'),
      'objectruanganlastfk': this.item.idruangan,
      'asalrujukanfk': 5,
      'objectkelompokpasienlastfk': kelompokpasien,
      'jenispelayananfk': 1,
      'objectpegawaifk': this.item.iddokter,
      'objectkelasfk': 6,
      'israwatinap': false,
      'catatan': 'Kios-K',
      'statuspasien':  this.item.statuspasien ?  this.item.statuspasien : 'LAMA',
      'objectrekananfk': null,
      'nocm': this.item.nocm,
      'namapasien':this.item.namapasien,
      'antrianpasienregistrasifk': null,
      'iskiosk' : true
    }
    var antrianpasiendiperiksa = {
      'norec': '',
      'objectkamarfk': null,
      'nobed': null,      
    }
    var objSave = {
      'pasiendaftar': pasiendaftar,
      'antrianpasiendiperiksa': antrianpasiendiperiksa
    }
    this.loading = true
    this.httpService.post('medifirst2000/registrasi/save-registrasipasien', objSave).subscribe(response => {
      
      this.showNoAntrian = true
      this.item.noregistrasi = response.dataPD.noregistrasi
      this.item.norec_pds = response.dataPD.norec
      this.item.norec_apds = response.dataAPD.norec

      this.saveLogging('Pendaftaran Pasien', 'norec Pasien Daftar', response.dataPD.norec,
        'Self Registration No Registrasi (' + response.dataPD.noregistrasi + ') ')
      if (this.isAdminOtomatisKiosk == 'true') {
        this.saveAdminAuto(this.item)
      }
      this.cacheHelper.set('cacheSelfRegis', undefined)
      this.loading = false
    }, error => {
      this.loading = false
      this.showNoAntrian = false
    })
  }
  saveAdminAuto(pd) {
    let json = {
      norec: pd.norec_pds,
      norec_apd: pd.norec_apds
    }
    this.httpService.postNonMessage("medifirst2000/registrasi/save-adminsitrasi", json).subscribe(z => {

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
  nomorAntrian() {

    let petugas = '-'
    if (this.isCetakDSKiosk == 'true') {
      this._Qzprinter.prinBlade('medifirst2000/report/cetak-bukti-pendaftaran?noregistrasi='
      + this.item.noregistrasi,'ANTRIAN POLI',1)
      // this.service.get('http://127.0.0.1:1237/printvb/Pendaftaran?cetak-buktipendaftaran=1&norec='
      //   + this.item.noregistrasi + '&petugas=' + petugas + '&view=false').subscribe(response => { });
    } else if (this.isCetakDSKiosk == 'android') {
      this.httpService.get("medifirst2000/report/get-cetak-bukti-pendaftaran?noregistrasi=" 
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
      this.httpService.blade(Configuration.get().apiBackend + 'medifirst2000/report/cetak-bukti-pendaftaran?noregistrasi='
        + this.item.noregistrasi
        + '&kdprofile=21', '_blank');
    }

    // this.service.get(Configuration.get().apiBackend + ' medifirst2000/report/cetak-bukti-pendaftaran?noregistrasi='
    //   + this.item.noregistrasi
    //   + '&kdprofile=21').subscribe(response => { });
  }
  changeClick() {
    let eks = false
    if (this.formGroup.get('ceklisEkse').value == true)
      eks = true
    else
      eks = false
    this.listRuangan = [];
    this.httpService.get('medifirst2000/kiosk/get-combo-kiosk2?eksek=' + eks).subscribe(e => {
      this.listRuangan = [];
      this.listRuangan.push({ label: '--Pilih Poli --', value: null });
      e.ruanganrajal.forEach(response => {
        this.listRuangan.push({
          label: response.namaruangan, value: {
            'id': response.id,
            'namaruangan': response.namaruangan,
            'objectdepartemenfk': response.objectdepartemenfk
          }
        });
      });
    })
  }
  changePoli(event) {
    if (event.value.id == null) return
    this.listDokter = [];
    this.httpService.get("medifirst2000/kiosk/get-daftar-jadwal-dokter?ruanganId=" + event.value.id).subscribe(data => {
      if (data.data.length > 0) {
        this.listDokter = [];
        this.listDokter.push({ label: '-- Dokter --', value: null });
        data.data.forEach(response => {
          this.listDokter.push({
            label: response.namalengkap, value: {
              'id': response.objectpegawaifk,
              'dokter': response.namalengkap
            }
          });
        });
      }
      else {
        this.listDokter = [];
        this.alertService.info('Info', 'Dokter DPJP tidak ada / Belum di Jadwalkan')
      }

    });
  }
}
