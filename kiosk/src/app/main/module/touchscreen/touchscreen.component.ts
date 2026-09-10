import { Component, ElementRef, OnInit, ViewChild, ViewEncapsulation } from '@angular/core';

import { Subject } from 'rxjs';
import { takeUntil } from 'rxjs/operators';

import { knowledgeBaseService } from 'app/main/pages/kb/knowledge-base/knowledge-base.service';
import * as snippet from 'app/main/components/carousel/carousel.snippetcode';
import { Router } from '@angular/router';
import * as moment from 'moment';
import { ToastService } from 'app/main/components/toasts/toasts.service';
import { ToastrService } from 'ngx-toastr';
import { HttpService } from '../httpService';
import { HttpClient } from '@angular/common/http';
import { Configuration } from '../config';
import { QzprinterService } from '../qzprinter.service';
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
  constructor(private _knowledgeBaseService: knowledgeBaseService,
    private _alertService: ToastrService,
    private httpService: HttpService,
    private http: HttpClient,
    private router: Router,
    private route: ActivatedRoute,
    private _Qzprinter: QzprinterService,
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
      if(localStorage.getItem('isCetakDS') == null){
        localStorage.setItem('isCetakDS', this.isCetakDSKiosk)
      }else{
        this.isCetakDSKiosk = localStorage.getItem('isCetakDS')
      }
    }, error => {
      this.isAktifSlotRuangan = 'true'
      this.isCetakDSKiosk = 'true'
      if(localStorage.getItem('isCetakDS') == null){
        localStorage.setItem('isCetakDS', this.isCetakDSKiosk)
      }else{
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
    this._Qzprinter.connect();
  }
  print(jenis) {
    if((this.loketId == 1 || this.loketId == 3) && jenis == 'UMUM'){
      this.router.navigate(['touchscreen/self-regis/verif-pasien'], { queryParams: { jenis: jenis, page: 'UMUM' } })
    } else if((this.loketId == 1 || this.loketId == 3) && jenis != 'UMUM'){
      if(jenis == 'BPJSMANDIRI'){
        this.router.navigate(['touchscreen/pasien-lama/bpjs-mandiri'], { queryParams: { jenis: jenis, page: 'BPJSMANDIRI' } })
      }else{
        this.router.navigate(['touchscreen/self-regis/verif-pasien'], { queryParams: { jenis: jenis, page: 'BPJS' } })
      }
    } else if(this.loketId == 2){
        let kode = ''
        let kebangsaanfk = 1;
        // if(jenis == 'LA'){
        //   kode = 'LA'
        // } else{
          if(jenis == 'WNI' || jenis == 'LA'){
            kebangsaanfk = 1
          } else{
            kebangsaanfk = 3
          }
          kode = 'B'
        // }
        let antrian = {
          "jenis": kode,
          "kebangsaanfk": kebangsaanfk,
          "loketid": this.loketId,
          "noantrian": null
        }
        this.isSave = true
        this.httpService.post('medifirst2000/kiosk/save-antrian-baru', antrian).subscribe(response => {
          this.isSave = false
          this._Qzprinter.prinBlade(`medifirst2000/report/cetak-antrian?pdf=true&norec=${response.noRec}`,'ANTRIAN LOKET',1)
          this.router.navigate(['touchscreen-v2']);
        })
    }
  }
  modalOpenBPJS(modalBPJS, jenis){
    this.item.jenisAntrian = jenis
    this.modalService.open(modalBPJS, {
      centered: true,
      size: 'sm' // size: 'xs' | 'sm' | 'lg' | 'xl'
    });
    // this.router.navigate([`choose-poli/${this.loketId}`], { queryParams: { jenis: jenis } })

  }

  goTo(name) {
    this.router.navigate(['touchscreen/' + name]);
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
  goToFinger(event: any, el: any){
    this._Qzprinter.sendKeysAlttab();
  }
  cekKepesertaan() {
    // <---------------JANGAN DILEPAS RETURN NYA
    return;
    if(!this.item.noKepesertaan) {
      this._alertService.error('','Harap Isi Nomor Kartu terlebih dahulu !', {
        toastClass: 'toast ngx-toastr',
        closeButton: true,
        positionClass: 'toast-bottom-center'
      });
      return
    }

    let url: any;
    if(this.item.noKepesertaan.toString().length == 16) {
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
      if(e.metaData.code !='200') {
        this._alertService.error('',e.metaData.message, {
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
  lanjutKepesertaan(){
    this.modalService.dismissAll()
    this.print(this.item.jenisAntrian)
  }
}
