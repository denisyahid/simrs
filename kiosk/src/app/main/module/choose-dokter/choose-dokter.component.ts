
import { HttpClient } from '@angular/common/http';
import { Component, OnInit, ViewEncapsulation } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { GlobalConfig, ToastrService } from 'ngx-toastr';
import { CacheService } from '../cache.service';
import { Configuration } from '../config';
import { QzprinterService } from '../qzprinter.service';

import { HttpService } from '../httpService';
import moment from 'moment';

@Component({
  selector: 'app-choose-dokter',
  templateUrl: './choose-dokter.component.html',
  styleUrls: ['./choose-dokter.component.scss'],

  encapsulation: ViewEncapsulation.None
})
export class ChooseDokterComponent implements OnInit {
  contentHeader: any
  item: any = {}
  paramData: any
  sub: any
  listDokter: any = []
  listDokterTemp: any = []
  listBadge: any[] = [
    'bg-light-info', 'bg-light-primary', 'bg-light-danger',
    'bg-light-warning', 'bg-light-success'
  ]

  isDokterNull = false;

  // private
  private toastRef: any;
  private options: GlobalConfig
  public bookmarkText = '';
  isCetakDSKiosk: any = 'true'
  constructor(
    private router: Router,
    private route: ActivatedRoute,
    private httpservice: HttpService,
    private cacheHelper: CacheService,
    private http: HttpClient,
    private alertService: ToastrService,
    private _Qzprinter: QzprinterService,


  ) {
    this.options = this.alertService.toastrConfig;

    let sett = localStorage.getItem('isCetakDS')
    if (sett != null) {
      this.isCetakDSKiosk = sett
    }
  }

  ngOnInit(): void {
    this._Qzprinter.connect();
    this.contentHeader = {
      headerTitle: 'Pilih Dokter',
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

    this.sub = this.route
      .queryParams
      .subscribe(params => {
        this.paramData = JSON.parse(atob(params['data']));
        console.log(this.paramData)
        this.item.jenis = this.paramData.jenis;
        this.item.objectruanganfk = this.paramData.ruanganfk;
        this.item.nocmfk = this.paramData.nocmfk;
        this.item.loketid = this.paramData.loketid;
        this.item.nopeserta = this.paramData.nopeserta;
        this.item.namapeserta = this.paramData.namapeserta;
        this.item.kelompokpasien = this.paramData.kelompokpasien;
        this.item.jenispelayanan = this.paramData.jenispelayanan;
        this.item.ruanganfklama = this.paramData.ruanganfklama;
        this.contentHeader.headerTitle = 'Pilih Dokter - ' + this.paramData.namaruangan;
      });
    this.listDokter = []
    this.listDokterTemp = []
    this.httpservice.get(`medifirst2000/kiosk/get-dokterbyruangan?objectruanganfk=`
      + this.item.objectruanganfk).subscribe(e => {

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

        this.listDokter = data
        this.listDokterTemp = data
        console.log(this.listDokter)

        if (data.length > 0) {
          this.isDokterNull = false
        } else {
          this.isDokterNull = true
        }
      })
  }
  pilihDokter(data) {

    let antrian = {
        "jenis": this.item.jenis,
        "ruanganfk": this.item.objectruanganfk,
        "namaruangan": this.item.namaruangan,
        "nocmfk": this.item.nocmfk,
        "loketid": this.item.loketid,
        "nopeserta": this.item.nopeserta,
        "namapeserta": this.item.namapeserta,
        "kelompokpasien": this.item.kelompokpasien,
        "jenispelayanan": this.item.jenispelayanan,
        "objectpegawaifk": data.iddokter,
        "noantrian": null,
        "ruanganfklama": this.item.ruanganfklama
      }

    this.httpservice.get('medifirst2000/kiosk/get-slotting-kosong?ruanganfk=' + this.item.objectruanganfk).subscribe(es => {
      let es2: any = es
      if (es2.status == true) {
        // this.httpservice.get('registrasi/pasien-hari-ini?nocmfk=' + this.item.nocmfk).subscribe(er => {
        //   console.log(er)
        //   if (er != null && er.namaruangan == data.namaruangan) {
        //     this.alertService.info('Pasien sudah teregistrasi hari ini di ' + er.namaruangan, er.namaruangan, {
        //       toastClass: 'toast ngx-toastr',
        //       closeButton: true,
        //       positionClass: 'toast-bottom-center'
        //     });
        //   } else {
            this.httpservice.post('medifirst2000/kiosk/save-antrian', antrian).subscribe(response => {
                let res: any
                if (this.isCetakDSKiosk == 'true') {
                  if(this.item.kelompokpasien == 1 && this.item.loketid == 1){
                    
                    this._Qzprinter.prinBlade(`dashboard/registrasi/cetak-label-pasien?noregistrasi=` + response.noregistrasi, 'LABEL PASIEN', 5)

                    //this.httpservice.blade(Configuration.get().apiBackend + 'dashboard/registrasi/cetak-label-pasien?pdf=true&noregistrasi='+ response.noregistrasi, '_blank')

                  }

                  this._Qzprinter.prinBlade(`medifirst2000/report/cetak-antrian?norec=` + response.noRec + '&noregistrasi=' + response.noregistrasi, 'ANTRIAN LOKET', 1)
                } 
                else {
                  if(this.item.kelompokpasien == 1 && this.item.loketid == 1){
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
                "objectkebangsaanfk": kebangsaan,
              }
              this.httpservice.post('registrasi/save-adminsitrasi', json).subscribe(responsex => {
                this.router.navigate(['touchscreen-v2']);
              })
            })
        //   }
        // })
      } else {
        this.alertService.info('', es2.status, {
          toastClass: 'toast ngx-toastr',
          closeButton: true,
          positionClass: 'toast-bottom-center'
        });
        // this.alertService.info('Info', es2.status)
        return
      }


    })


    // if(this.item.jenispasien == "BPJS" && localStorage.getItem('isCetakSep') == "true"){
    //   this.router.navigate(['touchscreen/self-regis/verif-pasien-bpjs'], { queryParams: { page: 'BPJS' } })
    //   return;
    // }
    // let jenispasien: any = this.item.jenispasien.toLowerCase();
    // let jamSkrg = moment(new Date()).format('YYYY-MM-DD HH:mm:ss');

    // if (jenispasien == 'umum') {
    //   if (waktu == 'pagi') this.item.jenis = 'A'
    //   if (waktu == 'siang') this.item.jenis = 'C'
    // } else if (jenispasien == 'bpjs') {
    //   if (waktu == 'pagi') this.item.jenis = 'B'
    //   if (waktu == 'siang') this.item.jenis = 'D'
    // } else {
    //   this.alertService.warning('Silahkan mengambil antrian dengan benar!!', 'Informasi', {
    //     toastClass: 'toast ngx-toastr',
    //     closeButton: true,
    //     positionClass: 'toast-bottom-center'
    //   });
    //   return;
    // }

    // let antrian = {
    //   "jenis": this.item.jenis,
    //   "tipepasien": this.item.tipepasien,
    //   "nocm": this.item.nocmfk,
    //   "ruanganfk": this.item.objectruanganfk,
    //   "iddokter": data.iddokter,
    //   "jenispasien": this.item.jenispasien
    // }

    // if (waktu == 'pagi') {
    //   console.log(data.pagi);
    //   if (data.pagi.jammulai == null) {
    //     this.alertService.warning('Jadwal tidak tersedia!!', 'Informasi', {
    //       toastClass: 'toast ngx-toastr',
    //       closeButton: true,
    //       positionClass: 'toast-bottom-center'
    //     });
    //     return;
    //   }
    //   let pagiJamakhirDefault = moment(new Date()).format('YYYY-MM-DD') + ' ' + data.pagi.jamakhir;
    //   let pagiJamakhir = moment(pagiJamakhirDefault).subtract(15, 'm').format('YYYY-MM-DD HH:mm:ss')
    //   if (pagiJamakhir == jamSkrg) {
    //     this.alertService.warning('Poli sudah tutup!!', 'Informasi', {
    //       toastClass: 'toast ngx-toastr',
    //       closeButton: true,
    //       positionClass: 'toast-bottom-center'
    //     });
    //     return;
    //   }
    // }
    // if (waktu == 'siang') {
    //   if (data.siang.jammulai == null) {
    //     this.alertService.warning('Jadwal tidak tersedia!!', 'Informasi', {
    //       toastClass: 'toast ngx-toastr',
    //       closeButton: true,
    //       positionClass: 'toast-bottom-center'
    //     });
    //     return;
    //   }
    //   let siangJamakhirDefault = moment(new Date()).format('YYYY-MM-DD') + ' ' + data.siang.jamakhir;
    //   let siangJamakhir = moment(siangJamakhirDefault).subtract(15, 'm').format('YYYY-MM-DD HH:mm:ss')
    //   if (siangJamakhir == jamSkrg) {
    //     this.alertService.warning('Poli sudah tutup!!', 'Informasi', {
    //       toastClass: 'toast ngx-toastr',
    //       closeButton: true,
    //       positionClass: 'toast-bottom-center'
    //     });
    //     return;
    //   }
    // }



    // this.httpservice.get('medifirst2000/kiosk/get-slotting-kosong-kiosknew?ruanganfk=' + this.item.objectruanganfk
    //   + '&iddokter=' + data.iddokter
    //   + '&jenis=' + this.item.jenis
    //   + '&waktu=' + waktu).subscribe(es => {
    //     let es2: any = es

    //     if (es2.status == true) {
    //       this.httpservice.post('medifirst2000/kiosk/save-antrian', antrian).subscribe(response => {
    //         let res: any
    //         console.log(response);
    //         if(response.status == 400){
    //           // this.alertService.warning(''+response.message, 'Informasi', {
    //           //   toastClass: 'toast ngx-toastr',
    //           //   closeButton: true,
    //           //   positionClass: 'toast-bottom-center'
    //           // });
    //           return
    //         }else if(response.status == 200 || response.status == 201){
    //           if (localStorage.getItem('isCetakDS') == 'true') {
    //             this.http.get('http://127.0.0.1:1237/printvb/cetak-antrian?cetak=1&norec=' + response.noRec).subscribe(result => { })
    //           } else {
    //             window.open(Configuration.get().apiBackend + 'medifirst2000/report/cetak-antrian-v2?norec='
    //               + response.noRec
    //               + '&kdprofile=43&poli='+response.noRecPoli, '_blank');

    //             // # reservasi lama
    //               // window.open(Configuration.get().apiBackend + 'medifirst2000/report/cetak-antrian?norec=' + response.noRec + '&kdprofile=43', '_blank');
    //             // window.open('http://localhost:8400/service/medifirst2000/report/cetak-antrian?norec=3edc68e0-f1f9-11ed-a839-a1509c7c&kdprofile=43');
    //           }
    //           // window.history.back()
    //           // window.open('#/touchscreen');
    //           this.router.navigate(['touchscreen-v2']);
    //         }else{
    //           // this.alertService.warning(''+response.message, 'Informasi', {
    //           //   toastClass: 'toast ngx-toastr',
    //           //   closeButton: true,
    //           //   positionClass: 'toast-bottom-center'
    //           // });
    //           return
    //         }
            
    //       })
    //     } else {
    //       this.alertService.info('', es2.status, {
    //         toastClass: 'toast ngx-toastr',
    //         closeButton: true,
    //         positionClass: 'toast-bottom-center'
    //       });
    //       return
    //     }


    //   })


  }
}
