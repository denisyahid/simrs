import { Component, ElementRef, OnInit, ViewChild } from '@angular/core';
import { VgApiService } from '@videogular/ngx-videogular/core';
import { DPlayerService } from 'angular-dplayer';
import { StreamState } from 'src/app/interfaces/stream-state';
import { ApiService } from 'src/app/service';
import { NotificationService } from 'src/app/service/notification.service';
import { SocketService } from 'src/app/service/socket.service';
import Terbilang from 'terbilang-ts'
import { Howl, Howler } from 'howler';
import { ActivatedRoute } from '@angular/router';
import { Config } from 'src/app/guard';
import { DomSanitizer } from '@angular/platform-browser';
@Component({
    selector: 'app-viewer',
    templateUrl: './viewer.component.html',
    styleUrls: ['./viewer.component.scss']
})
export class ViewerComponent implements OnInit {
    dateNow: any = new Date()
    jamSekarang: any;
    isLogin: boolean = true
    tgl: any;
    apiTimer: any;
    listAntri: any[] = []
    audio = new Audio();
    dipanggil: boolean = false;
    sound: any = null
    listLoket: any[] = []
    color: any[] = ['badge-light-primary', 'badge-light-info', 'badge-light-warning', 'badge-light-success', 'badge-light-danger', 'badge-light-primary', 'badge-light-info', 'badge-light-warning', 'badge-light-success', 'badge-light-danger']
    videoSource: any[] = [
        // {
        //   name: 'LOKAL', url: "assets/tv/jasmed.mp4",
        //   // 'https://video.detik.com/trans7/smil:trans7.smil/playlist.m3u',
        //   type: 'dpHls'
        // },
        // {
        //   name: 'TRANS 7', url: "assets/tv/playlist.m3u8",
        //   // 'https://video.detik.com/trans7/smil:trans7.smil/playlist.m3u',
        //   type: 'dpHls'
        // },
        { name: 'CNN', url: 'https://live.cnnindonesia.com/livecnn/smil:cnntv.smil/playlist.m3u', type: 'dpHls' },
        { name: 'TVRI', url: 'https://cors-anywhere.herokuapp.com/http://wpc.d1627.nucdn.net:80/80D1627/o-tvri/Content/HLS/Live/Channel(TVRINASIONAL)/Stream(03)/index.m3u8', type: 'dpHls' },
        { name: 'SCTV', url: 'https://cors-anywhere.herokuapp.com/http://210.210.155.35/qwr9ew/s/s03/02.m3u8', type: 'dpFlv' },
        { name: 'Kompas', url: 'https://cors-anywhere.herokuapp.com/http://103.130.186.138:8800/oxygenplay/kompastv/index.m3u8', type: 'dpFlv' },
        { name: 'ANTV', url: 'https://cors-anywhere.herokuapp.com/http://210.210.155.35/qwr9ew/s/s07/02.m3u8', type: 'dpFlv' },
        { name: 'Net TV', url: 'https://cors-anywhere.herokuapp.com/http://210.210.155.35/qwr9ew/s/s08/01.m3u8', type: 'dpFlv' },
        { name: 'Indosiar', url: 'https://cors-anywhere.herokuapp.com/http://210.210.155.35/qwr9ew/s/s04/02.m3u8', type: 'dpFlv' },

    ]
    isVoiceBrowser: boolean = false
    item: any = {
        channel: this.videoSource[0]
    }
    pilihVideo: any
    showConfirm: boolean
    // videoSource: any
    videoItems = [
        {
            name: 'Video one',
            src: 'https://video.detik.com/trans7/smil:trans7.smil/playlist.m3u',
            type: 'application/x-mpegURL'
        },
        {
            name: 'Video one',
            src: 'https://video.detik.com/trans7/smil:trans7.smil/playlist.m3u',
            type: 'application/x-mpegURL'
        },
    ];
    activeIndex = 0;
    currentVideo = this.videoItems[this.activeIndex];
    data;
    api: any
    popUp: boolean
    @ViewChild('videoPlayer') videoplayer: ElementRef;
    $player: HTMLAudioElement;
    state: StreamState
    files: Array<any> = [];
    jmlLoket = 4
    currentFile: any = { index: -1 };
    sub: any;
    namaProfile = Config.getProfile().namaProfile
    brand = Config.getProfile().brand
    loketna: any
    bahasa: any
    dataTableDetail: any
    dataLoketDetail: any
    columndetail: any[];
    currentPage: number = 1;
    soundFilesJenis: any[] = [];
    private queue: string[] = [];
    listCaller = [{id: 1, label: 'Indonesian'},{id : 2, label: 'English'}]
    safeURL: any
    videoURL: string = 'https://www.youtube.com/embed/1ozGKlOzEVc?autoplay=1&mute=1'

    scrollHeightS: number = 0;
    scrolltopS: boolean = true;
    isIndo: boolean = false;
    isEnglish: boolean = false;
    autoScrollS: any;
    constructor(private socket: SocketService,
        private notif: NotificationService,
        private apiService: ApiService,
        private DPService: DPlayerService,
        private route: ActivatedRoute,
        public audioService: ApiService,
        private _sanitizer: DomSanitizer
    ) {
        this.safeURL = this._sanitizer.bypassSecurityTrustResourceUrl(this.videoURL);
        // this.apiService.getJSON('sep').subscribe(e => {

        // })

        this.sub = this.route
            .queryParams
            .subscribe(params => {
                this.loketna = params['loket'];
                this.bahasa = params['bahasa'];
            });

        if(this.bahasa == 'Indonesian'){

        }  
        this.apiService.get('sysadmin/settingdatafixed/get/jumlahLoket').subscribe(e => {
            this.jmlLoket = parseInt(e)
            for (let x = 0; x < this.jmlLoket; x++) {
                const element = this.jmlLoket[x];
                if(this.bahasa == 'Indonesian'){
                    this.listLoket.push({
                        label: 'Loket ' + (x + 1),
                        value: ['-'],
                        id: x + 1
                    })
                } else{
                    this.listLoket.push({
                        label: 'Counter ' + (x + 1),
                        value: ['-'],
                        id: x + 1
                    })
                }
                
            }

            if (this.loketna != undefined) {
                let arr = this.loketna.split(',')
                let loketFilter = []
                for (var z = 0; z < arr.length; z++) {
                    for (var i = 0; i < this.listLoket.length; i++) {
                        if (arr[z] == this.listLoket[i].label) {
                            loketFilter.push(this.listLoket[i])
                        }
                    }
                }
                this.listLoket = loketFilter
            }

            if(this.bahasa != undefined){
                    if(this.bahasa == 'English'){
                        this.isIndo = false
                        this.isEnglish = true
                    } else if(this.bahasa == 'Indonesian'){
                        this.isIndo = true
                        this.isEnglish = false
                    }
            }

            this.lihatDetail()
        })

        if (localStorage.getItem('isVoiceBrowser') != null) {
            if (localStorage.getItem('isVoiceBrowser') == 'true') {
                this.isVoiceBrowser = true
            } else {
                this.isVoiceBrowser = false
            }
        }
        if (this.isVoiceBrowser == true) {
            this.socket.on('tampilkan', (data: any) => {
                let result = JSON.parse(data)
                let namaloket = ''
                this.audio.play();
                this.listAntri.push({ no: result.no, loket: result.loket })
                if (this.listAntri.length > 0) {
                    if(this.bahasa == 'Indonesian'){
                        this.item.loketPanggil = 'Loket ' + this.listAntri[this.listAntri.length - 1].loket
                    } else{
                        this.item.loketPanggil = 'Counter ' + this.listAntri[this.listAntri.length - 1].loket
                    }
                    
                    this.item.antriTerakhir = this.listAntri[this.listAntri.length - 1].no
                    namaloket = this.listAntri[this.listAntri.length - 1].loket
                    for (let x = 0; x < this.listLoket.length; x++) {
                        const element2 = this.listLoket[x];
                        if (element2.id == result.loket) {
                            element2.value = this.listAntri[this.listAntri.length - 1].no
                        }
                    }

                }
                let nomor = this.listAntri[this.listAntri.length - 1].no
                nomor = nomor.toString().split('-')
                this.dipanggil = true

                if(result.jenis == 'Indo'){
                    console.log('Halo indo')
                    this.playAudio('Nomor Antrian ' + nomor[0] + ' ' + Terbilang(nomor[1])
                    + ' Ke Loket ' + namaloket);
                } else if(result.jenis == 'English'){
                    console.log('Halo english')
                    this.playAudio('Registration Number ' + nomor[0] + ' ' + Terbilang(nomor[1])
                    + ' Please Come to Registration Counter ' + namaloket);
                }
                

                this.loadInCaller()
            });
        } else {
            this.socket.on('tampilkan', (data: any) => {

                let result = JSON.parse(data)
                for (let i = 0; i < this.listLoket.length; i++) {
                    const element = this.listLoket[i];
                    if (element.id == result.loket) {
                        this.enqueue(result)
                        break;
                    }
                }
            });
        }

        this.apiTimer = setInterval(() => {
            this.getdate()
        }, (1000)); //1 second

        this.autoScrollS = setInterval(() => {
            this.startAutoScrollS()
        }, (50));
    }

    lanjutpanggil(result) {
        this.lihatDetail()
        let namaloket = ''

        let angka: any
        let jenis: any
        let loket: any
        let namaSoundAngka: any
        let bahasa: any
        for (let i = 0; i < this.listLoket.length; i++) {
            const element = this.listLoket[i];
            if (element.id == result.loket) {
                this.listAntri.push({ no: result.no, loket: result.loket })
                break;
            }
        }
        if (this.listAntri.length > 0) {
            if(this.bahasa == 'Indonesian'){
                this.item.loketPanggil = 'Loket ' + this.listAntri[this.listAntri.length - 1].loket
            } else{
                this.item.loketPanggil = 'Counter ' + this.listAntri[this.listAntri.length - 1].loket
            }
            
            this.item.antriTerakhir = this.listAntri[this.listAntri.length - 1].no
            for (let x = 0; x < this.listLoket.length; x++) {
                const element2 = this.listLoket[x];
                if (element2.id == result.loket) {
                    const antriannya = this.listAntri[this.listAntri.length - 1].no
                    const indexToRemove = element2.value.findIndex((item) => item === antriannya);
                    if (indexToRemove !== -1) {
                        element2.value.splice(indexToRemove, 1);
                    }
                    element2.value.unshift(antriannya)
                }
            }

            let nomor = this.listAntri[this.listAntri.length - 1].no
            nomor = nomor.toString().split('-')


            jenis = this.item.antriTerakhir.split('-')[0]
            loket = this.listAntri[this.listAntri.length - 1].loket
            bahasa = result.jenis

            if (this.dipanggil == true) {
                this.dipanggil = false
                // if (this.sound != null) {
                //   this.sound.stop();
                //   this.sound.unload();
                //   this.sound = null;
                // }
            }
            this.dipanggil = true
            if(result.jenis == 'Indo'){
                this.sound = new Howl({
                    src: ['assets/sound/in.wav'],
                    autoplay: true,
                    volume: 5,
                    rate: 1.1,
                    onend: () => {
                        this.sound = new Howl({
                            src: ['assets/sound/nomorantrian.mp3'],
                            autoplay: true,
                            volume: 5,
                            rate: 1.1,
                            onend: () => {
                                this.soundFilesJenis = []
                                for (let i = 0; i < jenis.length; i++) {
                                    this.soundFilesJenis.push('assets/sound/' + jenis[i] + '.mp3')
                                }
                                this.playSequentially(0, namaloket, angka, namaSoundAngka, loket, bahasa)
                            }
                        });
                    }
                });
            } else{
                this.sound = new Howl({
                    src: ['assets/sound/in.wav'],
                    autoplay: true,
                    volume: 5,
                    rate: 1.1,
                    onend: () => {
                        this.sound = new Howl({
                            src: ['assets/sound/registrationnumber.mp3'],
                            autoplay: true,
                            volume: 5,
                            rate: 1.1,
                            onend: () => {
                                this.soundFilesJenis = []
                                for (let i = 0; i < jenis.length; i++) {
                                    this.soundFilesJenis.push('assets/sound/' + jenis[i] + '-english.mp3')
                                }
                                this.playSequentially(0, namaloket, angka, namaSoundAngka, loket, bahasa)
                            }
                        });
                    }
                });
            }
            
        }
        // this.playAudio2(jenis, angka, namaloket);

        this.loadInCaller()
    }

    leadingZerosString(numberStr: string): string {
        // Gunakan ekspresi reguler untuk mencocokkan dan mengambil jumlah nol di depan
        const match = /^0+/g.exec(numberStr);

        // Kembalikan string yang terdiri dari jumlah nol atau string kosong jika tidak ditemukan
        return match ? "0".repeat(match[0].length) : "";
    }

    playSequentially(index, namaloket, angka, namaSoundAngka, loket, bahasa) {
        if (index < this.soundFilesJenis.length) {
            this.sound = new Howl({
                src: [this.soundFilesJenis[index]],
                volume: 5,
                rate: 1.1,
                onend: () => {
                    // Panggil rekursif untuk memainkan suara berikutnya setelah suara saat ini selesai
                    this.playSequentially(index + 1, namaloket, angka, namaSoundAngka, loket, bahasa);
                }
            });
            this.sound.play();
        } else {
            angka = parseInt(this.item.antriTerakhir.split('-')[1])
            namaSoundAngka = ''
            // var cariNol = this.item.antriTerakhir.includes("00");
            // if (cariNol == true) {
            //     namaSoundAngka = '00'
            // } else {
            //     var cariNol2 = this.item.antriTerakhir.includes("0");
            //     if (cariNol2 == true) {
            //         namaSoundAngka = '0'
            //     }
            // }
            namaSoundAngka = this.leadingZerosString(this.item.antriTerakhir.split('-')[1])
            console.log(angka)
            console.log(namaSoundAngka)


            if(this.bahasa == 'Indonesian'){
                namaloket = 'Loket ' + this.listAntri[this.listAntri.length - 1].loket
            } else{
                namaloket = 'Counter ' + this.listAntri[this.listAntri.length - 1].loket
            }

            let belas = false
            let puluh = false
            let ratus = false
            let ribu = false

            if(bahasa == 'Indo'){
                if (angka.toString().length >=  4 && angka < 2000) {
                    // this.setSoundRIBUAN(angka, loket)
                    namaSoundAngka = 'seribu'
                    angka = parseInt(angka.toString().substring(1,4))
                    // return
                }
                if (angka.toString().length >=  4 && angka > 1999 &&   angka < 10000) {
                    // namaSoundAngka = 'seribu'
                    // angka = parseInt(angka.toString().substring(1,4))
                    switch (parseInt(angka.toString().substring(0,1)) ) {
                        case 1:
                            namaSoundAngka += ' 1'
                            break;
                        case 2:
                            namaSoundAngka += ' 2'
                            break;
                        case 3:
                            namaSoundAngka += ' 3'
                            break;
                        case 4:
                            namaSoundAngka += ' 4'
                            break;
                        case 5:
                            namaSoundAngka += ' 5'
                            break;
                        case 6:
                            namaSoundAngka += ' 6'
                            break;
                        case 7:
                            namaSoundAngka += ' 7'
                            break;
                        case 8:
                            namaSoundAngka += ' 8'
                            break;
                        case 9:
                            namaSoundAngka += ' 9'
                            break;
                        default:
    
                    }
                    namaSoundAngka += ' ribu'
    
                    let angkana = parseInt(angka.toString().substring(1,4))
                    if(angkana> 0){
                        angka = parseInt(angka.toString().substring(1,4))
                    }else{
                        this.playSound(namaSoundAngka,loket, bahasa)
                        return
                    }
                }
                if (angka > 199 && angka < 1000) ratus = true
                if (angka > 99 && angka < 200) {
                    namaSoundAngka += ' seratus'
                    angka = angka - 100
                }
                if (angka > 19 && angka < 100) puluh = true
    
                if (angka < 20 && angka > 11) {
                    angka = angka - 10
                    belas = true
                }
                if (angka.toString().length == 4 && angka == 1000) {
                    namaSoundAngka += ' seribu'
                    this.sound = new Howl({
                        src: ['assets/sound/' + namaSoundAngka + '.mp3'],
                        autoplay: true,
                        volume: 5,
                        rate: 1.1,
                        onend: () => {
                            this.goLoket(loket, bahasa)
                        }
                    });
                    return
                }
    
    
                if (angka.toString().length == 2 && angka == 10) {
                    namaSoundAngka += ' sepuluh'
                    namaSoundAngka = namaSoundAngka.trim()
                    namaSoundAngka = namaSoundAngka.split(' ')
                    if (namaSoundAngka.length == 1) {
                        this.sound = new Howl({
                            src: ['assets/sound/' + namaSoundAngka[0] + '.mp3'],
                            autoplay: true,
                            volume: 5,
                            rate: 1.1,
                            onend: () => {
                                this.goLoket(loket, bahasa)
                            }
                        });
                    }
                    if (namaSoundAngka.length == 2) {
                        this.sound = new Howl({
                            src: ['assets/sound/' + namaSoundAngka[0] + '.mp3'],
                            autoplay: true,
                            volume: 5,
                            rate: 1.1,
                            onend: () => {
                                this.sound = new Howl({
                                    src: ['assets/sound/' + namaSoundAngka[1] + '.mp3'],
                                    autoplay: true,
                                    volume: 5,
                                    rate: 1.1,
                                    onend: () => {
                                        this.goLoket(loket, bahasa)
                                    }
                                });
    
                            }
                        });
                    }
                    if (namaSoundAngka.length == 3) {
                        this.sound = new Howl({
                            src: ['assets/sound/' + namaSoundAngka[0] + '.mp3'],
                            autoplay: true,
                            volume: 5,
                            rate: 1.1,
                            onend: () => {
                                this.sound = new Howl({
                                    src: ['assets/sound/' + namaSoundAngka[1] + '.mp3'],
                                    autoplay: true,
                                    volume: 5,
                                    rate: 1.1,
                                    onend: () => {
                                        this.sound = new Howl({
                                            src: ['assets/sound/' + namaSoundAngka[2] + '.mp3'],
                                            autoplay: true,
                                            volume: 5,
                                            rate: 1.1,
                                            onend: () => {
                                                this.goLoket(loket, bahasa)
                                            }
                                        });
                                    }
                                });
    
                            }
                        });
                    }
    
    
                    return
                }
                if (angka.toString().length == 2 && angka == 11) {
                    namaSoundAngka += ' sebelas'
                    this.playSound(namaSoundAngka,loket, bahasa)
                    return
                }
                if (angka.toString().length == 3 && angka == 100) {
                    namaSoundAngka = 'seratus'
                    this.sound = new Howl({
                        src: ['assets/sound/' + namaSoundAngka + '.mp3'],
                        autoplay: true,
                        volume: 5,
                        rate: 1.1,
                        onend: () => {
                            this.goLoket(loket, bahasa)
                        }
                    });
                    return
                }
    
    
    
    
                for (let i = 0; i < angka.toString().length; i++) {
    
                    if (ratus == false && angka > 200 && (parseInt(angka.toString().substr(1, 2)) == NaN ? 0 : parseInt(angka.toString().substr(1, 2))) == 10) {
                        namaSoundAngka += ' sepuluh'
                        // this.sound = new Howl({
                        //     src: ['assets/sound/' + namaSoundAngka + '.mp3'],
                        //     autoplay: true,
                        //     onend: () => {
                        //         this.goLoket(loket, bahasa)
                        //     }
                        // });
                        break
                    }
    
                    if (ratus == false && angka > 200 && (parseInt(angka.toString().substr(1, 2)) == NaN ? 0 : parseInt(angka.toString().substr(1, 2))) == 11) {
                        namaSoundAngka += ' sebelas'
                        // this.sound = new Howl({
                        //     src: ['assets/sound/' + namaSoundAngka + '.mp3'],
                        //     autoplay: true,
                        //     onend: () => {
                        //         this.goLoket(loket, bahasa)
                        //     }
                        // });
                        break
                    }
                    if (ratus == false && angka > 200) {
                        if ((parseInt(angka.toString().substr(1, 2)) == NaN ? 0 : parseInt(angka.toString().substr(1, 2))) > 19 && puluh == false) {
                            puluh = true
                        } else {
                            puluh = false
                        }
                    }
                    // if (ratus == false && angka > 200
                    //     && (parseInt(angka.toString().substr(1, 2)) == NaN ? 0 : parseInt(angka.toString().substr(1, 2))) < 20
                    //     && (parseInt(angka.toString().substr(1, 2)) == NaN ? 0 : parseInt(angka.toString().substr(1, 2))) > 11) {
                    //     switch (angka) {
                    //         case 12:
                    //             namaSoundAngka += ' 2'
                    //             namaSoundAngka += ' belas'
                    //             break;
                    //         case 13:
                    //             namaSoundAngka += ' 3'
                    //             namaSoundAngka += ' belas'
                    //             break;
                    //         case 14:
                    //             namaSoundAngka += ' 4'
                    //             namaSoundAngka += ' belas'
                    //             break;
                    //         case 15:
                    //             namaSoundAngka += ' 5'
                    //             namaSoundAngka += ' belas'
                    //             break;
                    //         case 16:
                    //             namaSoundAngka += ' 6'
                    //             namaSoundAngka += ' belas'
                    //             break;
                    //         case 17:
                    //             namaSoundAngka += ' 7'
                    //             namaSoundAngka += ' belas'
                    //             break;
                    //         case 18:
                    //             namaSoundAngka += ' 8'
                    //             namaSoundAngka += ' belas'
                    //             break;
                    //         case 19:
                    //             namaSoundAngka += ' 9'
                    //             namaSoundAngka += ' belas'
                    //             break;
                    //         default:
                    //     }
                    // }
                    if (ratus == false && angka > 200
                        && (parseInt(angka.toString().substr(1, 2)) == NaN ? 0 : parseInt(angka.toString().substr(1, 2))) < 20
                        && (parseInt(angka.toString().substr(1, 2)) == NaN ? 0 : parseInt(angka.toString().substr(1, 2))) > 11) {
                        let belasna = parseInt(angka.toString().substr(1, 2))
                        switch (belasna) {
                            case 12:
                                namaSoundAngka += ' 2'
                                namaSoundAngka += ' belas'
                                break;
                            case 13:
                                namaSoundAngka += ' 3'
                                namaSoundAngka += ' belas'
                                break;
                            case 14:
                                namaSoundAngka += ' 4'
                                namaSoundAngka += ' belas'
                                break;
                            case 15:
                                namaSoundAngka += ' 5'
                                namaSoundAngka += ' belas'
                                break;
                            case 16:
                                namaSoundAngka += ' 6'
                                namaSoundAngka += ' belas'
                                break;
                            case 17:
                                namaSoundAngka += ' 7'
                                namaSoundAngka += ' belas'
                                break;
                            case 18:
                                namaSoundAngka += ' 8'
                                namaSoundAngka += ' belas'
                                break;
                            case 19:
                                namaSoundAngka += ' 9'
                                namaSoundAngka += ' belas'
                                break;
                            default:
                        }
                        break
                    }
                    switch ((parseInt(angka.toString().substr(i, 1)) == NaN ? 0 : parseInt(angka.toString().substr(i, 1)))) {
                        case 1:
                            namaSoundAngka += ' 1'
                            break;
                        case 2:
                            namaSoundAngka += ' 2'
                            break;
                        case 3:
                            namaSoundAngka += ' 3'
                            break;
                        case 4:
                            namaSoundAngka += ' 4'
                            break;
                        case 5:
                            namaSoundAngka += ' 5'
                            break;
                        case 6:
                            namaSoundAngka += ' 6'
                            break;
                        case 7:
                            namaSoundAngka += ' 7'
                            break;
                        case 8:
                            namaSoundAngka += ' 8'
                            break;
                        case 9:
                            namaSoundAngka += ' 9'
                            break;
                        default:
    
                    }
    
    
                    if (belas == true) { namaSoundAngka += ' belas' }
                    if (puluh == true) { namaSoundAngka += ' puluh' }
                    if (angka > 19 && angka < 100) { puluh = false }
                    if (ratus == true) {
                        namaSoundAngka += ' ratus'
                        ratus = false
                    }
                }

                this.playSound(namaSoundAngka,loket,bahasa)
            } else{
                console.log('Halo panggil wna')
                namaSoundAngka += angka
                this.playSound(namaSoundAngka,loket,bahasa)
                console.log(namaSoundAngka)
            }

            
            

        }
    }
    playSound(namaSoundAngka,loket,bahasa){

        if(bahasa == 'Indo'){
            namaSoundAngka = namaSoundAngka.trim()
            namaSoundAngka = namaSoundAngka.split(' ')
        } else{
            namaSoundAngka = namaSoundAngka.trim()
            namaSoundAngka = namaSoundAngka.split('')
        }
        

        console.log(bahasa)
        console.log(namaSoundAngka.length)

        if(bahasa == 'Indo'){
            if (namaSoundAngka.length == 1) {
                this.sound = new Howl({
                    src: ['assets/sound/' + namaSoundAngka[0] + '.mp3'],
                    autoplay: true,
                    volume: 5,
                    rate: 1.1,
                    onend: () => {
                        this.goLoket(loket, bahasa)
                    }
                });
            }
            if (namaSoundAngka.length == 2) {
                this.sound = new Howl({
                    src: ['assets/sound/' + namaSoundAngka[0] + '.mp3'],
                    autoplay: true,
                    volume: 5,
                    rate: 1.1,
                    onend: () => {
                        this.sound = new Howl({
                            src: ['assets/sound/' + namaSoundAngka[1] + '.mp3'],
                            autoplay: true,
                            volume: 5,
                            rate: 1.1,
                            onend: () => {
                                this.goLoket(loket, bahasa)
                            }
                        });
    
                    }
                });
            }
            if (namaSoundAngka.length == 3) {
                this.sound = new Howl({
                    src: ['assets/sound/' + namaSoundAngka[0] + '.mp3'],
                    autoplay: true,
                    volume: 5,
                    rate: 1.1,
                    onend: () => {
                        this.sound = new Howl({
                            src: ['assets/sound/' + namaSoundAngka[1] + '.mp3'],
                            autoplay: true,
                            volume: 5,
                            rate: 1.1,
                            onend: () => {
                                this.sound = new Howl({
                                    src: ['assets/sound/' + namaSoundAngka[2] + '.mp3'],
                                    autoplay: true,
                                    volume: 5,
                                    rate: 1.1,
                                    onend: () => {
                                        this.goLoket(loket, bahasa)
                                    }
                                });
                            }
                        });
    
                    }
                });
            }
            if (namaSoundAngka.length == 4) {
                this.sound = new Howl({
                    src: ['assets/sound/' + namaSoundAngka[0] + '.mp3'],
                    autoplay: true,
                    volume: 5,
                    rate: 1.1,
                    onend: () => {
                        this.sound = new Howl({
                            src: ['assets/sound/' + namaSoundAngka[1] + '.mp3'],
                            autoplay: true,
                            volume: 5,
                            rate: 1.1,
                            onend: () => {
                                this.sound = new Howl({
                                    src: ['assets/sound/' + namaSoundAngka[2] + '.mp3'],
                                    autoplay: true,
                                    volume: 5,
                                    rate: 1.1,
                                    onend: () => {
                                        this.sound = new Howl({
                                            src: ['assets/sound/' + namaSoundAngka[3] + '.mp3'],
                                            autoplay: true,
                                            volume: 5,
                                            rate: 1.1,
                                            onend: () => {
                                                this.goLoket(loket, bahasa)
                                            }
                                        });
                                    }
                                });
                            }
                        });
    
                    }
                });
            }
            if (namaSoundAngka.length == 5) {
                this.sound = new Howl({
                    src: ['assets/sound/' + namaSoundAngka[0] + '.mp3'],
                    autoplay: true,
                    volume: 5,
                                                    rate: 1.1,
                    onend: () => {
                        this.sound = new Howl({
                            src: ['assets/sound/' + namaSoundAngka[1] + '.mp3'],
                            autoplay: true,
                            volume: 5,
                            rate: 1.1,
                            onend: () => {
                                this.sound = new Howl({
                                    src: ['assets/sound/' + namaSoundAngka[2] + '.mp3'],
                                    autoplay: true,
                                    volume: 5,
                                    rate: 1.1,
                                    onend: () => {
                                        this.sound = new Howl({
                                            src: ['assets/sound/' + namaSoundAngka[3] + '.mp3'],
                                            autoplay: true,
                                            volume: 5,
                                            rate: 1.1,
                                            onend: () => {
                                                this.sound = new Howl({
                                                    src: ['assets/sound/' + namaSoundAngka[4] + '.mp3'],
                                                    autoplay: true,
                                                    volume: 5,
                                                    rate: 1.1,
                                                    onend: () => {
                                                        this.goLoket(loket, bahasa)
                                                    }
                                                });
                                            }
                                        });
                                    }
                                });
                            }
                        });
    
                    }
                });
            }
            if (namaSoundAngka.length == 6) {
                this.sound = new Howl({
                    src: ['assets/sound/' + namaSoundAngka[0] + '.mp3'],
                    autoplay: true,
                    volume: 5,
                    rate: 1.1,
                    onend: () => {
                        this.sound = new Howl({
                            src: ['assets/sound/' + namaSoundAngka[1] + '.mp3'],
                            autoplay: true,
                            volume: 5,
                            rate: 1.1,
                            onend: () => {
                                this.sound = new Howl({
                                    src: ['assets/sound/' + namaSoundAngka[2] + '.mp3'],
                                    autoplay: true,
                                    volume: 5,
                                    rate: 1.1,
                                    onend: () => {
                                        this.sound = new Howl({
                                            src: ['assets/sound/' + namaSoundAngka[3] + '.mp3'],
                                            autoplay: true,
                                            volume: 5,
                                            rate: 1.1,
                                            onend: () => {
                                                this.sound = new Howl({
                                                    src: ['assets/sound/' + namaSoundAngka[4] + '.mp3'],
                                                    autoplay: true,
                                                    volume: 5,
                                                    rate: 1.1,
                                                    onend: () => {
                                                        this.sound = new Howl({
                                                            src: ['assets/sound/' + namaSoundAngka[5] + '.mp3'],
                                                            autoplay: true,
                                                            volume: 5,
                                                            rate: 1.1,
                                                            onend: () => {
                                                                this.goLoket(loket, bahasa)
                                                            }
                                                        });
                                                    }
                                                });
                                            }
                                        });
                                    }
                                });
                            }
                        });
    
                    }
                });
            }
            if (namaSoundAngka.length == 7) {
                this.sound = new Howl({
                    src: ['assets/sound/' + namaSoundAngka[0] + '.mp3'],
                    autoplay: true,
                    volume: 5,
                    rate: 1.1,
                    onend: () => {
                        this.sound = new Howl({
                            src: ['assets/sound/' + namaSoundAngka[1] + '.mp3'],
                            autoplay: true,
                            volume: 5,
                            rate: 1.1,
                            onend: () => {
                                this.sound = new Howl({
                                    src: ['assets/sound/' + namaSoundAngka[2] + '.mp3'],
                                    autoplay: true,
                                    volume: 5,
                                    rate: 1.1,
                                    onend: () => {
                                        this.sound = new Howl({
                                            src: ['assets/sound/' + namaSoundAngka[3] + '.mp3'],
                                            autoplay: true,
                                            volume: 5,
                                            rate: 1.1,
                                            onend: () => {
                                                this.sound = new Howl({
                                                    src: ['assets/sound/' + namaSoundAngka[4] + '.mp3'],
                                                    autoplay: true,
                                                    volume: 5,
                                                    rate: 1.1,
                                                    onend: () => {
                                                        this.sound = new Howl({
                                                            src: ['assets/sound/' + namaSoundAngka[5] + '.mp3'],
                                                            autoplay: true,
                                                            volume: 5,
                                                            rate: 1.1,
                                                            onend: () => {
                                                                this.sound = new Howl({
                                                                    src: ['assets/sound/' + namaSoundAngka[6] + '.mp3'],
                                                                    autoplay: true,
                                                                    volume: 5,
                                                                    rate: 1.1,
                                                                    onend: () => {
                                                                        this.goLoket(loket, bahasa)
                                                                    }
                                                                });
                                                            }
                                                        });
                                                    }
                                                });
                                            }
                                        });
                                    }
                                });
                            }
                        });
    
                    }
                });
            }
        } else{
            if (namaSoundAngka.length == 3) {
                this.sound = new Howl({
                    src: ['assets/sound/' + namaSoundAngka[0] + '-english.mp3'],
                    autoplay: true,
                    volume: 5,
                    rate: 1.1,
                    onend: () => {
                        this.sound = new Howl({
                            src: ['assets/sound/' + namaSoundAngka[1] + '-english.mp3'],
                            autoplay: true,
                            volume: 5,
                            rate: 1.1,
                            onend: () => {
                                this.sound = new Howl({
                                    src: ['assets/sound/' + namaSoundAngka[2] + '-english.mp3'],
                                    autoplay: true,
                                    volume: 5,
                                    rate: 1.1,
                                    onend: () => {
                                        this.goLoket(loket, bahasa)
                                    }
                                });
                            }
                        });
    
                    }
                });
            }
        }
        
    }
    setSoundRIBUAN(number, loket, bahasa) {

        var soundFiles = ["1", "2", "3", "4", "5", "6", "7", "8", "9"];
        var tensFiles = ["belas", "puluh"];
        var hundredFiles = ["seratus"];
        var thousandFiles = ["ribu", "sepuluh"];
        var thousandOneFiles = ["seribu", "sebelas"];

        var result = [];

        if (number == 1000) {
          result.push(thousandOneFiles[0]);
        } else {
          var digits = number.toString().split('').map(Number);

          if (digits.length >= 4) {
            result.push(thousandOneFiles[0]);
          }

          if (digits.length >= 3) {
            var hundred = digits[digits.length - 3];
            if (hundred > 0) {
              result.push(hundredFiles[hundred - 1]);
            }
          }

          if (digits.length >= 2) {
            var ten = digits[digits.length - 2];
            if (ten > 1) {
              result.push(tensFiles[1]);
              if (digits[digits.length - 1] > 0) {
                result.push(soundFiles[digits[digits.length - 1] - 1]);
              }
            } else if (ten === 1) {
              if (digits[digits.length - 1] === 0 && digits.length === 2) {
                result.push(tensFiles[1]);
              } else if (digits[digits.length - 1] === 1 && digits.length === 2) {
                result.push(thousandOneFiles[1]);
              } else {
                result.push(thousandOneFiles[1]);
              }
            } else if (ten === 0 && digits.length > 2 && digits[digits.length - 3] > 0) {
              result.push(tensFiles[1]);
            }

          } else {
            var one = digits[digits.length - 1];
            if (one > 0) {
              result.push(soundFiles[one - 1]);
            }
          }
        }


        let namaSoundAngka = result
        if (namaSoundAngka.length == 1) {
            this.sound = new Howl({
                src: ['assets/sound/' + namaSoundAngka[0] + '.mp3'],
                autoplay: true,
                volume: 5,
                rate: 1.1,
                onend: () => {
                    this.goLoket(loket, bahasa)
                }
            });
        }
        if (namaSoundAngka.length == 2) {
            this.sound = new Howl({
                src: ['assets/sound/' + namaSoundAngka[0] + '.mp3'],
                autoplay: true,
                volume: 5,
                rate: 1.1,
                onend: () => {
                    this.sound = new Howl({
                        src: ['assets/sound/' + namaSoundAngka[1] + '.mp3'],
                        autoplay: true,
                        volume: 5,
                        rate: 1.1,
                        onend: () => {
                            this.goLoket(loket, bahasa)
                        }
                    });

                }
            });
        }
        if (namaSoundAngka.length == 3) {
            this.sound = new Howl({
                src: ['assets/sound/' + namaSoundAngka[0] + '.mp3'],
                autoplay: true,
                volume: 5,
                rate: 1.1,
                onend: () => {
                    this.sound = new Howl({
                        src: ['assets/sound/' + namaSoundAngka[1] + '.mp3'],
                        autoplay: true,
                        volume: 5,
                        rate: 1.1,  
                        onend: () => {
                            this.sound = new Howl({
                                src: ['assets/sound/' + namaSoundAngka[2] + '.mp3'],
                                autoplay: true,
                                volume: 5,
                                rate: 1.1,  
                                onend: () => {
                                    this.goLoket(loket, bahasa)
                                }
                            });
                        }
                    });

                }
            });
        }
        if (namaSoundAngka.length == 4) {
            this.sound = new Howl({
                src: ['assets/sound/' + namaSoundAngka[0] + '.mp3'],
                autoplay: true,
                volume: 5,
                rate: 1.1,
                onend: () => {
                    this.sound = new Howl({
                        src: ['assets/sound/' + namaSoundAngka[1] + '.mp3'],
                        autoplay: true,
                        volume: 5,
                        rate: 1.1,
                        onend: () => {
                            this.sound = new Howl({
                                src: ['assets/sound/' + namaSoundAngka[2] + '.mp3'],
                                autoplay: true,
                                volume: 5,
                                rate: 1.1,
                                onend: () => {
                                    this.sound = new Howl({
                                        src: ['assets/sound/' + namaSoundAngka[3] + '.mp3'],
                                        autoplay: true,
                                        volume: 5,
                                        rate: 1.1,
                                        onend: () => {
                                            this.goLoket(loket, bahasa)
                                        }
                                    });
                                }
                            });
                        }
                    });

                }
            });
        }
        if (namaSoundAngka.length == 5) {
            this.sound = new Howl({
                src: ['assets/sound/' + namaSoundAngka[0] + '.mp3'],
                autoplay: true,
                volume: 5,
                rate: 1.1,
                onend: () => {
                    this.sound = new Howl({
                        src: ['assets/sound/' + namaSoundAngka[1] + '.mp3'],
                        autoplay: true,
                        volume: 5,
                        rate: 1.1,
                        onend: () => {
                            this.sound = new Howl({
                                src: ['assets/sound/' + namaSoundAngka[2] + '.mp3'],
                                autoplay: true,
                                volume: 5,
                                rate: 1.1,
                                onend: () => {
                                    this.sound = new Howl({
                                        src: ['assets/sound/' + namaSoundAngka[3] + '.mp3'],
                                        autoplay: true,
                                        volume: 5,
                                        rate: 1.1,
                                        onend: () => {
                                            this.sound = new Howl({
                                                src: ['assets/sound/' + namaSoundAngka[4] + '.mp3'],
                                                autoplay: true,
                                                volume: 5,
                                                rate: 1.1,
                                                onend: () => {
                                                    this.goLoket(loket, bahasa)
                                                }
                                            });
                                        }
                                    });
                                }
                            });
                        }
                    });

                }
            });
        }

    }

    goLoket(loket, bahasa) {
        if(bahasa == 'Indo'){
            this.sound = new Howl({
                src: ['assets/sound/loket.mp3'],
                autoplay: true,
                volume: 5,
                rate: 1.1,
                onend: () => {
                    this.sound = new Howl({
                        src: ['assets/sound/' + loket + '.mp3'],
                        autoplay: true,
                        volume: 5,
                        rate: 1.1,
                        onend: () => {
                            this.dipanggil = false
                            this.dequeue()
                            this.processQueue();
                        }
                    });
                }
            });
        } else{
            this.sound = new Howl({
                src: ['assets/sound/pleasecometoregistrationcounter.mp3'],
                autoplay: true,
                volume: 5,
                rate: 1.1,
                onend: () => {
                    this.sound = new Howl({
                        src: ['assets/sound/' + loket + '-english.mp3'],
                        autoplay: true,
                        volume: 5,
                        rate: 1.1,
                        onend: () => {
                            this.dipanggil = false
                            this.dequeue()
                            this.processQueue();
                        }
                    });
                }
            });
        }
        
    }
    // playHowl(url) {
    //   this.sound = new Howl({
    //     src: [url],
    //     autoplay: true,
    //     onload: function () {

    //     },
    //     onplay: function (getSoundId) {
    //       //sound playing
    //     },
    //     onend: () => {

    //     }
    //   });
    //   if (sound.duration) {
    //     setTimeout(() => {
    //       this.playHowl(url)
    //     }, sound.duration * 1000)
    //   }
    // }
    next(files) {
        const index = this.currentFile.index + 1;
        const file = files[index];
        this.openFile(file, index);
    }

    callNext(url) {
        this.audioService.stop();
        this.audioService.playStream(url).subscribe(events2 => {

        })
    }

    openFile(file, index) {
        this.currentFile = { index, file };
        this.audioService.stop();
        this.playStream(file.url);
    }
    playStream(url) {
        this.audioService.playStream(url).subscribe(events => {
            // listening for fun here
        });
    }
    playAudio2(jenis, nomor, loket) {


        // dingdong
        let audio = new Audio();
        audio.src = "assets/sound/in.wav";
        audio.load();
        audio.pause();
        audio.currentTime = 0;
        audio.play();


        //SET DELAY UNTUK MEMAINKAN REKAMAN NOMOR URUT
        let totalwaktu = 2 * 1000;

        // let audio2 = new Audio();
        // audio2.src = "assets/sound/" + jenis + ".mp3";
        // audio2.load();
        // audio2.pause();
        // audio2.currentTime = 0;
        // audio2.play();

        // let audio3 = new Audio();
        // audio3.src = "assets/sound/" + nomor + ".mp3";
        // audio3.load();
        // audio3.pause();
        // audio3.currentTime = 0;
        // audio3.play();

        // let audio4 = new Audio();
        // audio4.src = "assets/sound/loket.mp3";
        // audio4.load();
        // audio4.pause();
        // audio4.currentTime = 0;
        // audio4.play();



        // let audio5 = new Audio();
        // audio5.src = "assets/sound/" + loket + ".mp3";
        // audio5.load();
        // audio5.pause();
        // audio5.currentTime = 0;
        // audio5.play();


        setTimeout(function () {
            let audio = new Audio();
            audio.src = "assets/sound/nomorantrian.mp3";
            audio.load();
            audio.pause();
            audio.currentTime = 0;
            audio.play();
            totalwaktu = totalwaktu * 3;
        }, totalwaktu);


        setTimeout(function () {
            let audio = new Audio();
            audio.src = "assets/sound/" + jenis + ".mp3";
            audio.load();
            audio.pause();
            audio.currentTime = 0;
            audio.play();
            totalwaktu = totalwaktu * 3;
        }, totalwaktu);


        setTimeout(function () {
            let audio = new Audio();
            audio.src = "assets/sound/" + nomor + ".mp3";
            audio.load();
            audio.pause();
            audio.currentTime = 0;
            audio.play();
            totalwaktu = totalwaktu * 3;
        }, totalwaktu);


        setTimeout(function () {
            let audio = new Audio();
            audio.src = "assets/sound/loket.mp3";
            audio.load();
            audio.pause();
            audio.currentTime = 0;
            audio.play();
            totalwaktu = totalwaktu * 3;
        }, totalwaktu);



        setTimeout(function () {
            let audio = new Audio();
            audio.src = "assets/sound/" + loket + ".mp3";
            audio.load();
            audio.pause();
            audio.currentTime = 0;
            audio.play();
            totalwaktu = totalwaktu * 3;
        }, totalwaktu);

    }
    loadInCaller() {
        this.socket.emit('load-caller', { deskripsi: 'Load Data Di Caller Antrian' });
    }

    lihatDetail() {
        let loket = []
        for (let i = 0; i < this.listLoket.length; i++) {
            const element = this.listLoket[i];
            loket.push(element.id)
        }
        this.apiService.get(`viewer/get-list-datalast-panggil?loket=${loket.join(",")}`).subscribe(e => {
            this.dataTableDetail = e
        })
    }

    changePage(pageNumber: number) {
        // Set the current page to the desired page number
        this.currentPage = pageNumber;
        // Load data or perform any action needed for the new page
    }

    ngOnInit(): void {
        // let audio = new Audio();
        // audio.src = "assets/sound/nomorantrian.mp3"
        // audio.load();
        // audio.play();
        this.showConfirm = true

        setTimeout(() => {
            this.changePage(this.currentPage + 1); // Change to the desired page number
        }, 5000);

        this.columndetail = [
            { field: 'namaruangan', header: 'Nama Poli', width: "50%", align: "left", fontsize: "16pt" },
            { field: 'antrian', header: 'Sudah dipanggil', width: "50%", align: "center", fontsize: "16pt" },
        ];

        this.loadAwal()
        if ('speechSynthesis' in window) {
            console.log('Text-to-speech on.');
        } else {
            console.log('Text-to-speech not supported.');
        }
        if (localStorage.getItem('urlVideo') != null) {
            // for (let i = 0; i < this.videoSource.length; i++) {
            //     const element = this.videoSource[i];
            //     if (element.url == localStorage.getItem('urlVideo')) {
            //         this.pilihVideo = localStorage.getItem('urlVideo')
            //     } else {
            //         this.item.url = localStorage.getItem('urlVideo')
            //         this.pilihVideo = localStorage.getItem('urlVideo')
            //     }
            // }

            this.videoURL = localStorage.getItem('urlVideo') + '?autoplay=1&mute=1'
            this.safeURL = this._sanitizer.bypassSecurityTrustResourceUrl(this.videoURL);
        } else {
            // this.pilihVideo = this.videoSource[0].url
        }
        for (let i = 0; i < this.videoSource.length; i++) {
            const element = this.videoSource[i];
            if (element.url == this.pilihVideo) {
                this.item.channel = element
                break
            }
        }

    }
    setNumberStr(nomer) {
        var str = "" + nomer
        var pad = "000"
        var ans = pad.substring(0, pad.length - str.length) + str
        return ans;
    }
    loadAwal() {
        let namaloket = ''
        this.apiService.get('viewer/get-data-viewer').subscribe(e => {
            if (e.length > 0) {
                for (let i = 0; i < e.length; i++) {
                    const element = e[i];
                    if(this.bahasa == 'Indonesian'){
                        if (i == 0) {
                            this.item.antriTerakhir = element.jenis + '-' + this.setNumberStr(element.noantrian)
                            this.item.loketPanggil = 'Loket ' + element.tempatlahir
                        }
                    } else{
                        if (i == 0) {
                            this.item.antriTerakhir = element.jenis + '-' + this.setNumberStr(element.noantrian)
                            this.item.loketPanggil = 'Counter ' + element.tempatlahir
                        }
                    }
                    
                    for (let x = 0; x < this.listLoket.length; x++) {
                        const element2 = this.listLoket[x];
                        if (element2.id == element.tempatlahir) {
                            const antriannya = element.jenis + '-' + this.setNumberStr(element.noantrian)
                            const indexToRemove = element2.value.findIndex((item) => item === antriannya);
                            if (indexToRemove !== -1) {
                                element2.value.splice(indexToRemove, 1);
                            }
                            element2.value.unshift(antriannya)
                        }
                    }

                    // if (element.tempatlahir == 1) {
                    //   this.item.antriLoket1 = element.jenis + '-' + this.setNumberStr(element.noantrian)
                    //   namaloket = 'Satu'
                    // }
                    // if (element.tempatlahir == 2) {
                    //   this.item.antriLoket2 = element.jenis + '-' + this.setNumberStr(element.noantrian)
                    //   namaloket = 'Dua'
                    // }
                    // if (element.tempatlahir == 3) {
                    //   this.item.antriLoket3 = element.jenis + '-' + this.setNumberStr(element.noantrian)
                    //   namaloket = 'Tiga'
                    // }
                    // if (element.tempatlahir == 4) {
                    //   this.item.antriLoket4 = element.jenis + '-' + this.setNumberStr(element.noantrian)
                    //   namaloket = 'Empat'
                    // }
                    // if (element.tempatlahir == 5) {
                    //   this.item.antriLoket5 = element.jenis + '-' + this.setNumberStr(element.noantrian)
                    //   namaloket = 'Lima'
                    // }
                    // if (element.tempatlahir == 6) {
                    //   this.item.antriLoket6 = element.jenis + '-' + this.setNumberStr(element.noantrian)
                    //   namaloket = 'Enam'
                    // }
                    // if (element.tempatlahir == 7) {
                    //   this.item.antriLoket7 = element.jenis + '-' + this.setNumberStr(element.noantrian)
                    //   namaloket = 'Tujuh'
                    // }
                    // if (element.tempatlahir == 8) {
                    //   this.item.antriLoket8 = element.jenis + '-' + this.setNumberStr(element.noantrian)
                    //   namaloket = 'Delapan'
                    // }
                }
                // console.log(this.listLoket)
            }
        })
    }
    // update() {
    //   this.apiService.get('viewer/get-list-antrian').subscribe(e => {
    //     // this.dataTable = e.data
    //     this.item.no = e.last
    //   })
    // }
    playAudio(nomor) {

        setTimeout(() => {

            var synthesis = window.speechSynthesis;
            // Get the first `en` language voice in the list
            var voice = synthesis.getVoices().filter(function (voice) {
                return voice.lang === 'id-ID' && voice.name.toLowerCase().indexOf('Female') > -1;
            })[0];
            // Create an utterance object
            var utterance = new SpeechSynthesisUtterance(nomor);
            // Set utterance properties
            utterance.voice = voice;
            utterance.lang = 'id-ID';
            // utterance.text = document.querySelector("textarea").value;
            utterance.pitch = 1;
            // utterance.rate = 0.8;
            utterance.rate = 1;
            utterance.volume = 1;
            // Speak the utterance
            synthesis.speak(utterance);
            this.audio.pause();
        }, (1500)); //4 detik
        setTimeout(() => {
            this.dipanggil = false
        }, (5000));

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
        var myDays: any = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        var date: any = new Date();
        var day: any = date.getDate();
        var month: any = date.getMonth();
        var thisDay: any = date.getDay(),
            thisDay = myDays[thisDay];
        var yy: any = date.getYear();
        var year = (yy < 1000) ? yy + 1900 : yy;

        var tgl = (thisDay + ', ' + day + ' ' + months[month] + ' ' + year);
        var jam = (h + ":" + m + ":" + s + " WIB");
        // $("#timer").html(tgl + ' ' + jam);
        // setTimeout(function () {this.getdate() }, 1000);
        var el: HTMLElement = document.getElementById('timer');

        this.jamSekarang = jam
        this.tgl = tgl
        // console.log(this.jamSekarang)
    }
    // videoPlayerInit(api: VgApiService) {
    //   this.api = api;
    //   this.api.getDefaultMedia().subscriptions.loadedMetadata.subscribe(
    //     this.playVideo.bind(this)
    //   );
    // }

    // playVideo() {
    //   this.api.play();
    // }
    // nextVideo() {
    //   this.activeIndex++;

    //   if (this.activeIndex === this.videoItems.length) {
    //     this.activeIndex = 0;
    //   }

    //   this.currentVideo = this.videoItems[this.activeIndex];
    // }

    // initVdo() {
    //   this.data.play();
    // }

    // startPlaylistVdo(item, index: number) {
    //   this.activeIndex = index;
    //   this.currentVideo = item;
    // }
    // toggleVideo() {
    //   this.videoplayer.nativeElement.play();
    // }
    onResize() {
        console.log('resize');
    }
    private clickTimeout = null;
    public showPop(): void {
        if (this.clickTimeout) {
            this.setClickTimeout(() => {
                this.handleDoubleClick()
            });
        } else {
            // if timeout doesn't exist, we know it's first click
            // treat as single click until further notice
            this.setClickTimeout(() =>
                this.handleSingleClick()
            );
        }
    }
    // sets the click timeout and takes a callback
    // for what operations you want to complete when
    // the click timeout completes
    public setClickTimeout(callback) {
        // clear any existing timeout
        clearTimeout(this.clickTimeout);
        this.clickTimeout = setTimeout(() => {
            this.clickTimeout = null;
            callback();
        }, 200);
    }
    public handleSingleClick() {
        //  alert('sa')
    }
    public handleDoubleClick() {
        this.popUp = true
    }
    save(player) {
        // if (this.item.url != undefined && this.item.url != '') {
        //     this.pilihVideo = this.item.url
        // } else {
        //     this.pilihVideo = this.item.channel.url
        // }

        this.popUp = false
        localStorage.setItem('urlVideo', this.videoURL)

        var isss = ''
        if (this.isVoiceBrowser) {
            isss = 'true'
        } else {
            isss = 'false'
        }
        localStorage.setItem('isVoiceBrowser', isss)
        window.location.reload()
        // this.DPService._dp[0].dp.options.video.url= this.item.channel.url
        // console.log(this.DPService)
        // this.onloadStart(player)
    }
    // onloadStart(player){
    //   player.pause();
    //   console.log(player)
    // }
    enqueue(data) {
        this.queue.push(data);
        this.processQueue();
    }

    dequeue(): string | undefined {
        return this.queue.shift();
    }

    processQueue() {
        if (!this.dipanggil && this.queue.length > 0) {
            const nextCall = this.queue[0];
            // console.log(`Processing call: ${nextCall}`);
            this.lanjutpanggil(nextCall)
        }
    }

    startAutoScrollS() {
        var element = document.querySelector(".scrollPanelS .p-scrollpanel-content");
        var Height = (element.scrollHeight - element.clientHeight) + 100;
        if (this.scrolltopS == true) {
            this.scrollHeightS = this.scrollHeightS + 1;
            element.scrollTop += 1;
            if (this.scrollHeightS >= Height) {
                this.scrolltopS = false
            }

            // console.log('toBottom');
            // console.log(this.scrollHeightS+' - '+Height);

        } else {
            this.scrollHeightS = this.scrollHeightS - 1;
            element.scrollTop -= 1;
            if (this.scrollHeightS <= 0) {
                this.scrolltopS = true
            }
            // console.log('toTop');
            // console.log(this.scrollHeightS+' - '+Height);
        }
    }

    
}
