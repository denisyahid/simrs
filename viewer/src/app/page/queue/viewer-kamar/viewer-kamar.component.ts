import { Component, ElementRef, OnInit, ViewChild } from '@angular/core';
import { ApiService } from 'src/app/service';
import { SocketService } from 'src/app/service/socket.service';
import { ActivatedRoute } from '@angular/router';
import { Config } from 'src/app/guard';
@Component({
  selector: 'app-kamar',
  templateUrl: './viewer-kamar.component.html',
  styleUrls: ['./viewer-kamar.component.scss']
})

export class ViewerKamarComponent implements OnInit {
  dateNow: any = new Date()
  jamSekarang: any;
  isLogin:boolean = true
  tgl: any;
  apiTimer: any;
  namaProfile = Config.getProfile().namaProfile
  color: any[] = ['bg-gradient-danger','bg-gradient-info','bg-gradient-success','bg-gradient-primary','bg-gradient-danger','bg-gradient-info','bg-gradient-success','bg-gradient-primary','bg-gradient-danger','bg-gradient-info','bg-gradient-success','bg-gradient-primary']

  listRuangan: any[] = []
  listKelas: any[] = []


  constructor(private socket: SocketService,
    private apiService: ApiService,
    private route: ActivatedRoute,
  ) {

    this.socket.on("tampilkan-kamar", (data: any) => {
      this.loadAwal();
    });

    this.apiTimer = setInterval(() => {
      this.getdate()
    }, (1000));
  }

  ngOnInit(): void {
    this.loadAwal()
  }

  loadAwal() {
    this.apiService.get('viewer/get-data-viewer-tempat-tidur').subscribe(e => {
      this.listRuangan = e.ruangan
      this.listKelas = e.kelas
      console.log(e)
    })
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
    var jam = (h + ":" + m + ":" + s + " WIB");
    var el: HTMLElement = document.getElementById('timer');

    this.jamSekarang = jam
    this.tgl = tgl
  }
}
