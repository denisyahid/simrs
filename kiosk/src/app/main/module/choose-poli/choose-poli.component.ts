
import { HttpClient } from '@angular/common/http';
import { Component, OnInit, ViewEncapsulation } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { GlobalConfig, ToastrService } from 'ngx-toastr';
import { CacheService } from '../cache.service';
import { Configuration } from '../config';

import { HttpService } from '../httpService';
import { QzprinterService } from '../qzprinter.service';

@Component({
  selector: 'app-choose-poli',
  templateUrl: './choose-poli.component.html',
  styleUrls: ['./choose-poli.component.scss'],

  encapsulation: ViewEncapsulation.None
})
export class ChoosePoliComponent implements OnInit {
  contentHeader: any
  item: any = {}
  sub: any
  listRuangan: any = []
  listRuanganTemp: any = []
  listBadge: any[] = [
    'bg-light-info', 'bg-light-primary', 'bg-light-danger',
    'bg-light-warning', 'bg-light-success'
  ]

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

    this.item.loketid = localStorage.getItem('isLoket')
    this._Qzprinter.connect();
    this.contentHeader = {
      headerTitle: 'Pilih Poli',
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
        console.log(params)
        this.item.jenis = params['jenis'];
          this.item.kebangsaan = params['kebangsaan'];
          this.item.kelompok = params['tipepasien'];
          this.item.nocmfk = params['nocmfk'];
          console.log(this.item.kelompok)
        
        // alert(this.item.jenis)
      });
    this.listRuangan = []
    this.listRuanganTemp = []
    this.httpservice.get(`medifirst2000/kiosk/get-ruangan?loketid=${this.item.loketid}`).subscribe(e => {

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
  }
  pilihRuangan(data) {
    const pasienBPJS = JSON.parse(localStorage.getItem("dataPasienAPI"));

    let jenis = ''
    let nocmfk = ''
    let kelompokpasien = null
    let jenispelayanan = 1
    console.log(this.item.loketid)
    console.log(this.item.kelompok)
    console.log(this.item.kebangsaan)
    if(this.item.loketid == 1){
      console.log('Halo loket 1')
      if(this.item.kelompok.indexOf('BPJS') > -1){
        if(this.item.kebangsaan.indexOf('WNA') > -1){
          jenis = 'PN'
        } else{
          jenis = 'LB'
        }
        kelompokpasien = 2
      } else if(this.item.kelompok.indexOf('UMUM') > -1){
        kelompokpasien = 1
        console.log('Halo umum')
        if(this.item.kebangsaan == 'WNI'){
          jenis = 'OG'
        } else if(this.item.kebangsaan.indexOf('WNA') > -1){
          console.log('Halo WNA')
          jenis = 'PN'
        }  
      }
      nocmfk = this.item.nocmfk
    } else{
      
      if(this.item.kebangsaan == 'LA'){
        jenis = 'LA'
      } else if(this.item.kebangsaan.indexOf('WNA') > -1){
        jenis = 'PN'
      }  else{
        jenis = 'B'
      }
      if(this.item.jenis.indexOf('BPJS') > -1){
        kelompokpasien = 2
      } else if(this.item.jenis.indexOf('UMUM') > -1){
        kelompokpasien = 1
      }
    }

    if(data.namaruangan.indexOf('VIP') > -1){
      jenispelayanan = 1
    }

    console.log(jenis)

    let antrian = {
      "jenis": jenis,
      "ruanganfk": data.id,
      "namaruangan": data.namaruangan,
      "nocmfk": nocmfk,
      "loketid": this.item.loketid,
      "nopeserta": pasienBPJS !== null ? pasienBPJS.peserta.noKartu : null,
      "namapeserta": pasienBPJS !== null ? pasienBPJS.peserta.nama : null,
      "kelompokpasien": kelompokpasien,
      "jenispelayanan": jenispelayanan,
      "noantrian": null,
      "ruanganfklama": data.ruanganfklama
    }

    console.log(antrian)

    

    if(data.objectpegawaifk != null){
      this.router.navigate(['choose-dokter'], { queryParams: { data: btoa(JSON.stringify(antrian)) } });
      return;
    } else{
      this.httpservice.get('medifirst2000/kiosk/get-slotting-kosong?ruanganfk=' + data.id).subscribe(es => {
        let es2: any = es
        if (es2.status == true) {
          // this.httpservice.get('registrasi/pasien-hari-ini?nocmfk=' + nocmfk).subscribe(er => {
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
                    if(kelompokpasien == 1 && this.item.loketid == 1){

                      this._Qzprinter.prinBlade(`dashboard/registrasi/cetak-label-pasien?noregistrasi=` + response.noregistrasi, 'LABEL PASIEN', 5)
  
                    }

                    this._Qzprinter.prinBlade(`medifirst2000/report/cetak-antrian?norec=` + response.noRec + '&noregistrasi=' + response.noregistrasi, 'ANTRIAN LOKET', 1)
                  } 
                  else {
                    if(kelompokpasien == 1 && this.item.loketid == 1){

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
    }
  }
}
