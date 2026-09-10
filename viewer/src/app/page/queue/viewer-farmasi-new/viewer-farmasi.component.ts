import { DOCUMENT } from '@angular/common';
import { Component, Inject, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import * as moment from 'moment';
import { Table } from 'primeng/table';
import { Config } from 'src/app/guard';
import { ApiService, AuthService } from 'src/app/service';
import { AlertService } from 'src/app/service/component/alert/alert.service';
import { SocketService } from 'src/app/service/socket.service';
@Component({
  selector: 'app-viewer-farmasi',
  templateUrl: './viewer-farmasi.component.html',
  styleUrls: ['./viewer-farmasi.component.scss']
})
export class ViewerFarmasiNewComponent implements OnInit {
  dateNow: any = new Date()
  jamSekarang: any;
  isLogin:boolean = true
  item: any = {}
  tgl: any;
  apiTimer: any;
  namaProfile = Config.getProfile().namaProfile
  dataNonRacikan: any[] = []
  dataRacikan: any[] = []
  dataSelesai: any[] = []
  paramRuang: any
  elem: any;
  isFullscreen: boolean
  loadData: boolean = true;
  dipanggilN: boolean = false;
  dipanggilR: boolean = false;
  AntrianNoAntrianAktif_N: any;
  AntrianNamaAktif_N: any;
  AntrianRuanganAktif_N: any;

  AntrianNoAntrianAktif_R: any;
  AntrianNamaAktif_R: any;
  AntrianRuanganAktif_R: any;

  scrollHeightN: number = 0;
  scrolltopN: boolean = true;
  autoScroll: any;

  scrollHeightR: number = 0;
  scrolltopR: boolean = true;
  autoScrollR: any;

  scrollHeightS: number = 0;
  scrolltopS: boolean = true;
  autoScrollS: any;

  constructor(
    private apiService: ApiService,
    private alertService: AlertService,
    private socket: SocketService,
    private route: ActivatedRoute,
    @Inject(DOCUMENT) private document: any
  ) {
    this.AntrianNoAntrianAktif_N = "-"
    this.AntrianNamaAktif_N = ""
    this.AntrianRuanganAktif_N = ""

    this.AntrianNoAntrianAktif_R = "-"
    this.AntrianNamaAktif_R = ""
    this.AntrianRuanganAktif_R = ""

    this.socket.on("tampilkan-farmasi-new", (data: any) => {
      let result = JSON.parse(data)
      if (result.status == 'load') {
        this.load();
      } else if (result.status == 'panggilload') {
        this.load();
        if(result.antrianaktif) {
          if(result.antrianaktif.jenis=='N') {
            this.AntrianNoAntrianAktif_N = result.antrianaktif.noantri;
            this.AntrianNamaAktif_N = result.antrianaktif.namapasien;
            this.AntrianRuanganAktif_N = result.antrianaktif.namaruangan;
            this.playAudio('Nomor Antrian '+this.AntrianNoAntrianAktif_N.toLocaleLowerCase()+'. Atas Nama '+this.AntrianNamaAktif_N+'. Ke Loket . Farmasi');
          } else if(result.antrianaktif.jenis=='R') {
            this.AntrianNoAntrianAktif_R = result.antrianaktif.noantri;
            this.AntrianNamaAktif_R = result.antrianaktif.namapasien;
            this.AntrianRuanganAktif_R = result.antrianaktif.namaruangan;
            this.playAudio('Nomor Antrian '+this.AntrianNoAntrianAktif_R.toLocaleLowerCase()+'. Atas Nama '+this.AntrianNamaAktif_R+'. Ke Loket . Farmasi');
          }
        }
      } else if (result.status == 'panggil') {
        if(result.antrianaktif) {
          if(result.antrianaktif.jenis=='N') {
            this.AntrianNoAntrianAktif_N = result.antrianaktif.noantri;
            this.AntrianNamaAktif_N = result.antrianaktif.namapasien;
            this.AntrianRuanganAktif_N = result.antrianaktif.namaruangan;
            this.playAudio('Nomor Antrian '+this.AntrianNoAntrianAktif_N.toLocaleLowerCase()+'. Atas Nama '+this.AntrianNamaAktif_N+'. Ke Loket . Farmasi');
          } else if(result.antrianaktif.jenis=='R') {
            this.AntrianNoAntrianAktif_R = result.antrianaktif.noantri;
            this.AntrianNamaAktif_R = result.antrianaktif.namapasien;
            this.AntrianRuanganAktif_R = result.antrianaktif.namaruangan;
            this.playAudio('Nomor Antrian '+this.AntrianNoAntrianAktif_R.toLocaleLowerCase()+'. Atas Nama '+this.AntrianNamaAktif_R+'. Ke Loket . Farmasi');
          }
        }
      }
    });

    this.paramRuang = ''
    this.route.params.subscribe(params => {
      this.paramRuang = params['ruanganid'];
    })

    this.apiTimer = setInterval(() => {
      this.getdate()
    }, (1000));

    this.autoScroll = setInterval(() => {
      if(!this.loadData) {
        this.startAutoScrollN()
        this.startAutoScrollR()
        this.startAutoScrollS()
      }
    }, (50));
  }

  ngOnInit(): void {
    this.elem = document.documentElement;
    this.load()
    if ("speechSynthesis" in window) {
        console.log("Text-to-speech on.");
    } else {
        console.log("Text-to-speech not supported.");
    }
  }



  load() {
    this.loadData = true;
    this.apiService.get('viewer/get-list-antrian-farmasi-new?ruanganId='+this.paramRuang).subscribe(e => {
      this.loadData = false;
      this.dataNonRacikan = e.nonracikan;
      this.dataRacikan = e.racikan;
      this.dataSelesai = e.selesai;
    })
  }

  openFullscreen() {
    if (this.elem.requestFullscreen) {
      this.elem.requestFullscreen();
    } else if (this.elem.mozRequestFullScreen) {
      /* Firefox */
      this.elem.mozRequestFullScreen();
    } else if (this.elem.webkitRequestFullscreen) {
      /* Chrome, Safari and Opera */
      this.elem.webkitRequestFullscreen();
    } else if (this.elem.msRequestFullscreen) {
      /* IE/Edge */
      this.elem.msRequestFullscreen();
    }
    this.isFullscreen = true
  }
  closeFullscreen() {
    if (this.document.exitFullscreen) {
      this.document.exitFullscreen();
    } else if (this.document.mozCancelFullScreen) {
      /* Firefox */
      this.document.mozCancelFullScreen();
    } else if (this.document.webkitExitFullscreen) {
      /* Chrome, Safari and Opera */
      this.document.webkitExitFullscreen();
    } else if (this.document.msExitFullscreen) {
      /* IE/Edge */
      this.document.msExitFullscreen();
    }
    this.isFullscreen = false
  }

    replaceGelar(nomor: string): string {
        return nomor
            .replace(/\.M/g, " .M")
            .replace(/\.S/g, " .S")
            .replace(/ RUANG PERAWAT\. /g, " nurse station ")
            .replace(/ dr\. /g, " Dokter. ")
            .replace(/ H\. /gi, " Haji. ")
            .replace(/ HJ\. /gi, " Hajjah. ")
            .replace(/ IR\. /gi, " Insinyur. ")
            .replace(/ A\.MD\. /gi, " Ahli Madya. ")
            .replace(/ PROF\. /gi, " Professor. ")
            .replace(/ DR\. /gi, " Doktor. ")
            .replace(/ S\.T\. /gi, " Sarjana Tehnik. ")
            .replace(/ ST\./g, " Sarjana Tehnik. ")
            .replace(/ M\.T\. /gi, " Magister Tehnik. ")
            .replace(/ S\.H\. /gi, " Sarjana Hukum. ")
            .replace(/ SH\. /gi, " Sarjana Hukum. ")
            .replace(/ M\.H\. /gi, " Magister Hukum. ")
            .replace(/ S\.SI\. /gi, " Sarjana Sain. ")
            .replace(/ M.SI\. /gi, " Magister Sain. ")
            .replace(/ S\.PD\. /gi, " Sarjana Pendidikan. ")
            .replace(/ M.PD\. /gi, " Magister Pendidikan. ")
            .replace(/ SE\. /gi, " Sarjana Ekonomi. ")
            .replace(/ S\.E\. /gi, " Sarjana Ekonomi. ")
            .replace(/ S.MN\. /gi, " Sarjana Manajemen. ")
            .replace(/ S.MB\. /gi, " Sarjana Manajemen Bisnis. ")
            .replace(/ PH\.D\. /gi, " Doktor of Filosofi. ")
            .replace(/ TN\. /gi, " Tuan. ")
            .replace(/ NY\. /gi, " Nyonya. ")
            .replace(/ NN\. /gi, " Nona. ")
            .replace(/ AN\. /gi, " ")
            .replace(/ DRS\. /gi, " Doktorandus. ");
    }

  playAudio(nomor: any) {

    // setTimeout(() => {

    var synthesis = window.speechSynthesis;
    // Get the first `en` language voice in the list
    var voice = synthesis.getVoices().filter(function (voice) {
      return voice.lang === 'id-ID';
    })[0];
    // Create an utterance object
    // var utterance = new SpeechSynthesisUtterance(nomor);
    var utterance = new SpeechSynthesisUtterance(
        this.replaceGelar(nomor).toLocaleLowerCase()
    );

    // Set utterance properties
    utterance.voice = voice;
    utterance.lang = 'id-ID';
    // utterance.text = document.querySelector("textarea").value;
    utterance.pitch = 1;
    utterance.rate = 0.8;
    utterance.volume = 1;
    // Speak the utterance
    synthesis.speak(utterance);
    // this.audio.pause();

    setTimeout(() => {
      this.dipanggilN = false
      this.dipanggilR = false
    }, (5000));

  }


  startAutoScrollN() {
    var element = document.querySelector(".scrollPanelN .p-scrollpanel-content");
    var Height = (element.scrollHeight - element.clientHeight) + 100;
    if(this.scrolltopN==true) {
        this.scrollHeightN = this.scrollHeightN + 1;
        element.scrollTop += 1;
        if(this.scrollHeightN>=Height) {
          this.scrolltopN=false
        }

        // console.log('toBottom');
        // console.log(this.scrollHeightN+' - '+Height);

    } else {
        this.scrollHeightN = this.scrollHeightN - 1;
        element.scrollTop -= 1;
        if(this.scrollHeightN<=0) {
          this.scrolltopN=true
        }
        // console.log('toTop');
        // console.log(this.scrollHeightN+' - '+Height);
    }
  }


  startAutoScrollR() {
    var element = document.querySelector(".scrollPanelR .p-scrollpanel-content");
    var Height = (element.scrollHeight - element.clientHeight) + 100;
    if(this.scrolltopR==true) {
        this.scrollHeightR = this.scrollHeightR + 1;
        element.scrollTop += 1;
        if(this.scrollHeightR>=Height) {
          this.scrolltopR=false
        }

        // console.log('toBottom');
        // console.log(this.scrollHeightR+' - '+Height);

    } else {
        this.scrollHeightR = this.scrollHeightR - 1;
        element.scrollTop -= 1;
        if(this.scrollHeightR<=0) {
          this.scrolltopR=true
        }
        // console.log('toTop');
        // console.log(this.scrollHeightR+' - '+Height);
    }
  }

  startAutoScrollS() {
    var element = document.querySelector(".scrollPanelS .p-scrollpanel-content");
    var Height = (element.scrollHeight - element.clientHeight) + 100;
    if(this.scrolltopS==true) {
        this.scrollHeightS = this.scrollHeightS + 1;
        element.scrollTop += 1;
        if(this.scrollHeightS>=Height) {
          this.scrolltopS=false
        }

        // console.log('toBottom');
        // console.log(this.scrollHeightS+' - '+Height);

    } else {
        this.scrollHeightS = this.scrollHeightS - 1;
        element.scrollTop -= 1;
        if(this.scrollHeightS<=0) {
          this.scrolltopS=true
        }
        // console.log('toTop');
        // console.log(this.scrollHeightS+' - '+Height);
    }
  }


  getdate() {
    var today = new Date();
    var h: any = today.getHours();
    var m: any = today.getMinutes();
    var s: any = today.getSeconds();
    if (h < 10) {
      h = "0" + h;
    }
    if (m < 10) {
      m = "0" + m;
    }
    if (s < 10) {
      s = "0" + s;
    }

    var months: any = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    var myDays: any = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    var date: any = new Date();
    var day: any = date.getDate();
    var month: any = date.getMonth();
    var thisDay: any = date.getDay(),
      thisDay = myDays[thisDay];
    var yy: any = date.getYear();
    var year = (yy < 1000) ? yy + 1900 : yy;

    var tgl = (thisDay + ', ' + day + ' ' + months[month] + ' ' + year);
    var jam = (h + ":" + m + ":" + s );
    var el: HTMLElement = document.getElementById('timer');

    this.jamSekarang = jam
    this.tgl = tgl
  }
}
