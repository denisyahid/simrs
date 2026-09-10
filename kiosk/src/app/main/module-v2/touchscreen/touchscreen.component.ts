import { Component, ElementRef, OnInit, ViewChild, ViewEncapsulation } from '@angular/core';

import { Subject } from 'rxjs';
import { takeUntil } from 'rxjs/operators';

import { knowledgeBaseService } from 'app/main/pages/kb/knowledge-base/knowledge-base.service';
import * as snippet from 'app/main/components/carousel/carousel.snippetcode';
import { Router } from '@angular/router';
import * as moment from 'moment';
import { ToastService } from 'app/main/components/toasts/toasts.service';
import { ToastrService } from 'ngx-toastr';
import { HttpService } from '../../module/httpService';
import { HttpClient } from '@angular/common/http';
import { Configuration } from '../../module/config';
import { QzprinterService } from '../../module/qzprinter.service';
import { ActivatedRoute } from '@angular/router';
import { NgbModal } from '@ng-bootstrap/ng-bootstrap';

// CarouselImages interface
export interface CarouselImages {
  one?: string;
  two?: string;
  three?: string;
  four?: string;
  five?: string;
  six?: string;
}
@Component({
  selector: 'app-touchscreen',
  templateUrl: './touchscreen.component.html',
  styleUrls: ['./touchscreen.component.scss'],
  encapsulation: ViewEncapsulation.None
})
export class TouchscreenComponent implements OnInit {
  // public

  public contentHeader: object;
  public searchText: any;
  public data: any;
  model: any

  @ViewChild("scanBarcode") nameBarcode: ElementRef;
  now: any = moment(new Date()).format('DD MMM, YYYY')
  // private
  private _unsubscribeAll: Subject<any>;
  public listImage: any[] = [
    'assets/images/slider/1.png',
    'assets/images/slider/2.png',
    'assets/images/slider/3.png'
  ];
  public carouselImages: CarouselImages = {
    one: 'assets/images/slider/01.jpg',
    two: 'assets/images/slider/02.jpg',
    three: 'assets/images/slider/03.jpg',
    four: 'assets/images/slider/04.jpg',
    five: 'assets/images/slider/05.jpg',
    six: 'assets/images/slider/06.jpg'
  };
  // snippet code variables
  public _snippetCodeBasicExample = snippet.snippetCodeBasicExample;
  public _snippetCodeOptionalCaptions = snippet.snippetCodeOptionalCaptions;
  public _snippetCodeIntervalOption = snippet.snippetCodeIntervalOption;
  public _snippetCodePauseOption = snippet.snippetCodePauseOption;
  public _snippetCodeWrapOption = snippet.snippetCodeWrapOption;
  public _snippetCodeKeyboardOption = snippet.snippetCodeKeyboardOption;
  public _snippetCodeNavigationArrow = snippet.snippetCodeNavigationArrow;
  public _snippetCodeNavigationIndicators = snippet.snippetCodeNavigationIndicators;
  public _snippetCodeCrossfade = snippet.snippetCodeCrossfade;
  public _snippetCodeActiveId = snippet.snippetCodeActiveId;
  /**
   * Constructor
   *
   * @param {knowledgeBaseService} _knowledgeBaseService
   */
  isSave: boolean
  isAktifSlotRuangan: any = 'true'
  isCetakDSKiosk: any = 'true'
  loketId: any;
  item: any = {}
  isLoadingBPJS: boolean = false
  isValidateKewarganegaraan: boolean = false
  constructor(private _knowledgeBaseService: knowledgeBaseService,
    private _alertService: ToastrService,
    private httpService: HttpService,
    private http: HttpClient,
    private router: Router,
    private route: ActivatedRoute,
    // private _Qzprinter: QzprinterService,
    private modalService: NgbModal) {
    this._unsubscribeAll = new Subject();


  }

  // Lifecycle Hooks
  // -----------------------------------------------------------------------------------------------------

  /**
   * On Changes
   */
  ngOnInit(): void {
    // kosongkan storage
    localStorage.removeItem("dataPasienAPI");
    localStorage.removeItem("dataRujukanAPI");
    this.loketId = localStorage.getItem('isLoket')

    this.httpService.get('medifirst2000/kiosk/get-combo-setting').subscribe(resps => {
      this.isAktifSlotRuangan = resps.isAktifSlotRuanganKiosk
      this.isCetakDSKiosk = resps.isCetakDSKiosk
      if (localStorage.getItem('isCetakDS') == null) {
        localStorage.setItem('isCetakDS', this.isCetakDSKiosk)
      } else {
        this.isCetakDSKiosk = localStorage.getItem('isCetakDS')
      }
    }, error => {
      this.isAktifSlotRuangan = 'true'
      this.isCetakDSKiosk = 'true'
      if (localStorage.getItem('isCetakDS') == null) {
        localStorage.setItem('isCetakDS', this.isCetakDSKiosk)
      } else {
        this.isCetakDSKiosk = localStorage.getItem('isCetakDS')
      }
    })
    // this._knowledgeBaseService.onDatatablessChanged.pipe(takeUntil(this._unsubscribeAll)).subscribe(response => {
    //   this.data = response;
    // });


    // content header
    this.contentHeader = {
      headerTitle: 'Kios-K',
      actionButton: true,
      breadcrumb: {
        type: '',
        links: [
          {
            name: 'Home',
            isLink: false,
            link: '/kiosk'
          },

        ]
      }
    };
    // this._Qzprinter.connect();
  }
  print(jenis) {
    if (this.loketId == 1 && jenis == 'UMUM') {
      this.router.navigate(['v2/touchscreen/self-regis/verif-pasien'], { queryParams: { jenis: jenis, page: 'UMUM' } })
    } else if (this.loketId == 1 && jenis != 'UMUM') {
      this.router.navigate(['v2/touchscreen/self-regis/verif-pasien'], { queryParams: { jenis: jenis, page: 'BPJS' } })
    } else {
      if (this.isAktifSlotRuangan == 'true') {
        this.router.navigate(["v2/choose-poli"], { queryParams: { jenis: jenis } })
      } else {
        let antrian = {
          "jenis": jenis,
          "ruanganfk": null
        }
        this.isSave = true
        this.httpService.post('medifirst2000/kiosk/save-antrian', antrian).subscribe(response => {
          this.isSave = false

          if (localStorage.getItem('isCetakDS') == 'false') {
            this.httpService.blade(Configuration.get().apiBackend + 'medifirst2000/report/cetak-antrian?norec='
              + response.noRec
              + '&kdprofile=21', '_blank');
          } else if (localStorage.getItem('isCetakDS') == 'android') {
            let loket = ''
            if (jenis == "A") {
              loket = "Loket 1";
            } else if (jenis == "B") {
              loket = "Loket 2";
            } else if (jenis == "C") {
              loket = "Loket 3";
            } else if (jenis == "D") {
              loket = "Loket 4";
            }
            let dataCetak = {
              namaProfile: Configuration.profile().nama,
              alamat: Configuration.profile().alamat,
              date: moment(new Date()).format('YYYY-MM-DD HH:mm'),
              last: response.last,
              jenis: loket,
              noantri: response.noAntri,
            }
            window.open(
              'https://apps.transmedic.co.id/cetak-antrian?namaProfile=' + dataCetak.namaProfile
              + '&alamat=' + dataCetak.alamat
              + '&date=' + dataCetak.date
              + '&jenis=' + dataCetak.jenis
              + '&last=' + dataCetak.last
              + '&noantri=' + dataCetak.noantri

            );
          } else {
            // this._Qzprinter.prinBlade(`medifirst2000/report/cetak-antrian?pdf=true&norec=${response.noRec}`, 'ANTRIAN LOKET', 1)
            // this.http.get('http://127.0.0.1:1237/printvb/cetak-antrian?cetak=1&norec=' + response.noRec).subscribe(result => { })
          }


        }, error => {
          this.isSave = false
        })
      }
    }
  }

  validate(kewarganegaraan) {
    if (kewarganegaraan == '' || kewarganegaraan == undefined) {
      this._alertService.warning('', 'Silahkan pilih kewarganegaraan terlebih dahulu!!, (Please select a nationality first !!)', {
        toastClass: 'toast ngx-toastr',
        closeButton: true,
        positionClass: 'toast-bottom-center'
      });
      return;
    }
    var isPasienLama = false;
    var jenisBaruLama = 'Baru';
    var data = {};
    if (this.loketId == 1) {
      data = {
        isPasienLama: true,
        jenisBaruLama: 'Lama',
      }
    }

    this.router.navigate(['v2/touchscreen/self-regis'], {
      queryParams: {
        data: btoa(JSON.stringify(data))
      }
    });
  }

  modalOpenBPJS(modalBPJS, jenis) {
    this.item.jenisAntrian = jenis
    this.modalService.open(modalBPJS, {
      centered: true,
      size: 'sm' // size: 'xs' | 'sm' | 'lg' | 'xl'
    });
    // this.router.navigate([`choose-poli/${this.loketId}`], { queryParams: { jenis: jenis } })

  }

  goTo(name) {
    this.router.navigate(['v2/touchscreen/' + name]);
  }
  onChangeNoreservasi(value: string) {
    if (value.length == 7) {

    } else {

    }
    alert(value);
  }
  clickBtn(url) {
    this.router.navigate([url]);
    // this._alertService.info('','Under Construction', {
    //   toastClass: 'toast ngx-toastr',
    //   closeButton: true,
    //   positionClass: 'toast-bottom-center'
    // });
  }
  goToFinger(event: any, el: any) {
    // this._Qzprinter.sendKeysAlttab();
  }
  cekKepesertaan() {
    // <---------------JANGAN DILEPAS RETURN NYA
    return;
    if (!this.item.noKepesertaan) {
      this._alertService.error('', 'Harap Isi Nomor Kartu terlebih dahulu !', {
        toastClass: 'toast ngx-toastr',
        closeButton: true,
        positionClass: 'toast-bottom-center'
      });
      return
    }

    let url: any;
    if (this.item.noKepesertaan.toString().length == 16) {
      url = `Peserta/nik/${this.item.noKepesertaan}/tglSEP/${moment(new Date()).format('YYYY-MM-DD')}`
    } else {
      url = `Peserta/nokartu/${this.item.noKepesertaan}/tglSEP/${moment(new Date()).format('YYYY-MM-DD')}`
    }

    var jsonPeserta = {
      "url": url,
      "method": "GET",
      "data": null
    }
    this.isLoadingBPJS = true
    this.httpService.postNonMessage("medifirst2000/bridging/bpjs/tools", jsonPeserta).subscribe(e => {
      this.isLoadingBPJS = false
      if (e.metaData.code != '200') {
        this._alertService.error('', e.metaData.message, {
          toastClass: 'toast ngx-toastr',
          closeButton: true,
          positionClass: 'toast-bottom-center'
        });
        localStorage.removeItem("dataPasienAPI");
        return
      }

      // set data pasien from bpjs
      localStorage.setItem("dataPasienAPI", JSON.stringify(e.response));
      this.lanjutKepesertaan() // matiin kalo hidupin kodingan cek rujukan

      // hidupin kalo mau ada cek rujukan
      // var jsonPcare = {
      //   "url": `Rujukan/Peserta/${e.response.peserta.noKartu}`,
      //   "method": "GET",
      //   "data": null
      // }
      // // GET RUJUKAN PCARE / FASKES 1
      // this.isLoadingBPJS = true
      // this.httpService.postNonMessage("medifirst2000/bridging/bpjs/tools", jsonPcare).subscribe(e2 => {
      //   this.isLoadingBPJS = false
      //   if(e2.metaData.code =='200') {
      //     var jsonRS = {
      //       "url": `Rujukan/RS/Peserta/${e.response.peserta.noKartu}`,
      //       "method": "GET",
      //       "data": null
      //     }
      //     // GET RUJUKAN RS
      //     this.isLoadingBPJS = true
      //     this.httpService.postNonMessage("medifirst2000/bridging/bpjs/tools", jsonRS).subscribe(e3 => {
      //       this.isLoadingBPJS = false
      //       if(e3.metaData.code !='200') {
      //         this._alertService.warning('',e3.metaData.message, {
      //           toastClass: 'toast ngx-toastr',
      //           closeButton: true,
      //           positionClass: 'toast-bottom-center'
      //         });
      //         // localStorage.removeItem("dataPasienAPI");
      //         this.lanjutKepesertaan()
      //       } else {
      //         localStorage.setItem("dataRujukanAPI", JSON.stringify(e3.response));
      //         this.lanjutKepesertaan()
      //       }
      //     })
      //   } else {
      //     localStorage.setItem("dataRujukanAPI", JSON.stringify(e2.response));
      //     this.lanjutKepesertaan()
      //   }
      // })
    })
  }
  lanjutKepesertaan() {
    this.modalService.dismissAll()
    this.print(this.item.jenisAntrian)
  }
}
