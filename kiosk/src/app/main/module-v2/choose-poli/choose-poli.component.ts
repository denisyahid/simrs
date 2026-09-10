
import { HttpClient } from '@angular/common/http';
import { Component, OnInit, ViewEncapsulation } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { GlobalConfig, ToastrService } from 'ngx-toastr';
import { CacheService } from '../../module/cache.service';
import { Configuration } from '../../module/config';

import { HttpService } from '../../module/httpService';
import { QzprinterService } from '../../module/qzprinter.service';

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
    console.log(this.item.loketid)
    console.log(this.item.kelompok)
    console.log(this.item.kebangsaan)
    if (this.item.loketid == 1) {
      console.log('Halo loket 1')
      if (this.item.kelompok.indexOf('BPJS') > -1) {
        jenis = 'LB'
      } else if (this.item.kelompok.indexOf('UMUM') > -1) {
        console.log('Halo umum')
        if (this.item.kebangsaan == 'WNI') {
          jenis = 'OG'
        } else if (this.item.kebangsaan.indexOf('WNA') > -1) {
          console.log('Halo WNA')
          jenis = 'PN'
        }
      }
      nocmfk = this.item.nocmfk
    } else {
      jenis = 'B'
    }

    console.log(nocmfk)

    let antrian = {
      "jenis": jenis,
      "ruanganfk": data.id,
      "nocmfk": nocmfk,
      "loketid": this.item.loketid,
      "nopeserta": pasienBPJS !== null ? pasienBPJS.peserta.noKartu : null,
      "namapeserta": pasienBPJS !== null ? pasienBPJS.peserta.nama : null,
    }
    this.httpservice.get('medifirst2000/kiosk/get-slotting-kosong?ruanganfk=' + data.id).subscribe(es => {
      let es2: any = es
      if (es2.status == true) {
        this.httpservice.post('medifirst2000/kiosk/save-antrian', antrian).subscribe(response => {
          let res: any
          if (this.isCetakDSKiosk == 'true') {
            this._Qzprinter.prinBlade(`medifirst2000/report/cetak-antrian?pdf=true&norec=${response.noRec}`, 'ANTRIAN LOKET', 1)
            // this.http.get('http://127.0.0.1:1237/printvb/cetak-antrian?cetak=1&norec=' + response.noRec).subscribe(result => { })
          }
          else {
            this.httpservice.blade(Configuration.get().apiBackend + 'medifirst2000/report/cetak-antrian?norec='
              + response.noRec
              + '&kdprofile=1', '_blank');
          }

          window.history.back()
        })
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
