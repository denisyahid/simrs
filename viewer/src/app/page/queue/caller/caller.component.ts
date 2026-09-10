
import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { ApiService } from 'src/app/service';
import { AlertService } from 'src/app/service/component/alert/alert.service';
import { SocketService } from 'src/app/service/socket.service';

@Component({
    selector: 'app-caller',
    templateUrl: './caller.component.html',
    styleUrls: ['./caller.component.scss']
})
export class CallerComponent implements OnInit {
    item: any = {}
    dataTable: any[];
    dataTableSudah: any[];
    column: any[];
    columnsudah: any[];
    dataPanggil: any[] = []
    isPanggil: boolean = false;
    sedangPanggil: boolean = false;
    jmlLoket: any = []
    antrian: any = []
    array: any = []
    listLoket: any[] = []
    listCaller: any[] = [];
    listJenis =['A','B','C','D']
    listAntrian = [{id: '0', label: '--Pilih Antrian--'},{id: '1', label: 'Loket 1 : Lama BPJS (WNA WNI)'},{id : '2', label: 'Loket 2: Pasien Baru (WNA WNI ), Reservasi, Lansia dan Anak'},{id : '3', label: 'Loket 3: Pasien Lansia Lama BPJS'},{id : '4', label: 'Loket 4: Pasien Lama Umum WNA'}]
    showDetailAntrian: boolean = false;
    sub: any;
    namaPanggil: any;
    isfinish: boolean = false;
    columndetail: any[];
    dataTableDetail:any
    constructor(
        private route: ActivatedRoute,
        private socket: SocketService,
        private apiService: ApiService,
        private alertService: AlertService
    ) {
        this.socket.on('get-list-antrian', (data: any) => {
            let result = JSON.parse(data)
            this.isfinish = false
            this.load()
        });
    }

    ngOnInit(): void {
        this.sub = this.route
        .queryParams
        .subscribe(params => {
            if(this.item.loketparam != undefined){
                this.item.loket = this.item.loketparam.id;
            } else{
                this.item.loket = params['loket'];
            }

            if(this.item.antrianparam != undefined){
                this.antrian = this.item.antrianparam.id;
            } else{
                this.antrian = params['antrian'];
            }
            // this.item.loketparam = params['loket'];
            // this.item.antrianparam = params['antrian'];

            let arr = this.antrian.split(',')
            let loketFilter = []
            for (var z = 0; z < arr.length; z++) {
                loketFilter.push("'" + arr[z] + "'")
            }
            this.array = loketFilter

            this.apiService.get('sysadmin/settingdatafixed/get/jumlahLoket').subscribe(e => {

                let jmlLoket = parseInt(e)
                for (let x = 0; x < jmlLoket; x++) {
                    const element = jmlLoket[x];
                    this.listLoket.push({
                        label: 'Loket ' + (x + 1),
                        namapoli: '',
                        value: '-',
                        id: x + 1
                    })
                }

                for (var x = 0; x < this.listAntrian.length; x++) {
                    if(this.listAntrian[x].id == params['antrian'])
                    this.item.antrianparam = this.listAntrian[x];
                }
                for (var y = 0; y < this.listLoket.length; y++) {
                    if(this.listLoket[y].id == params['loket'])
                    this.item.loketparam = this.listLoket[y];
                }
            })

            
            console.log(this.array)
        });


        this.columndetail = [
            { field: 'noantrian', header: 'No Antrian', width: "100px" },
            { field: 'loket', header: 'Yang Memanggil', width: "150px" },
            { field: 'tglinput', header: 'Tanggal Panggil', width: "150px" },
            { field: 'devicememanggil', header: 'Nama Komputer', width: "250px" },

          ];
        this.apiService.get('sysadmin/settingdatafixed/get/jumlahLoket').subscribe(e => {
            let jml = parseInt(e)
            for (let x = 0; x < jml; x++) {
                // sorcut button
                this.jmlLoket.push({ name: `L-${x + 1}`, id: x + 1 })
                // sorcut dropdown
                this.listLoket.push({
                    label: 'Loket ' + (x + 1),
                    id: x + 1
                })
            }

        })
        this.column = [
            { field: 'namaruangan', header: 'Keterangan', width: "180px", align: "left" },
            { field: 'name', header: 'Kebangsaan', width: "100px", align: "left" },
            { field: 'ruanganreservasi', header: 'Ruangan', width: "100px", align: "left" },
            { field: 'jenis', header: 'Jenis', width: "100px", align: "left" },
            { field: 'antrian', header: 'Antrian', width: "100px", align: "center" },
            { field: 'user', header: 'SEP', width: "100px", align: "center" },
        ];
        this.columnsudah = [
            { field: 'namaruangan', header: 'Keterangan', width: "180px", align: "left" },
            { field: 'name', header: 'Kebangsaan', width: "100px", align: "left" },
            { field: 'ruanganreservasi', header: 'Ruangan', width: "100px", align: "left" },
            { field: 'jenis', header: 'Jenis', width: "100px", align: "left" },
            { field: 'antrian', header: 'Antrian', width: "100px", align: "center" },
            { field: 'loket', header: 'Loket', width: "100px", align: "left" },
            { field: 'user', header: 'SEP', width: "100px", align: "center" },
        ];
        this.apiService.get(`viewer/get-list-antrian?antrian=${this.array}`).subscribe(e => {
            this.dataTable = e.data
            this.dataTableSudah = e.datasudah
            for (let i = 0; i < e.data.length; i++) {
                const element = e.data[i];
                this.listCaller.push({
                    label: element.jenis,
                    id: i + 1
                })
            }
            this.isPanggil = true
            this.item.jenis = e.data[0].jenis
            this.item.noantri = e.data[0].antrian
            this.item.user = e.data[0].user
            this.item.namaruangan = e.data[0].namaruangan
            this.item.norec = e.data[0].norec
            this.item.objectkebangsaanfk = e.data[0].objectkebangsaanfk 
            this.item.name = e.data[0].name 
            this.item.ruanganreservasi = e.data[0].ruanganreservasi 
            this.item.currentIndex = 0;
            this.item.noantri = this.dataTable[this.item.currentIndex].antrian;
            this.item.user = this.dataTable[this.item.currentIndex].user;
            this.item.jenis = this.dataTable[this.item.currentIndex].jenis;
            this.item.name = this.dataTable[this.item.currentIndex].name;
            this.item.ruanganreservasi = this.dataTable[this.item.currentIndex].ruanganreservasi;
            this.item.norec = this.dataTable[this.item.currentIndex].norec;

            

            if(this.sedangPanggil == true){
                this.panggil()
            }
        })
        // this.item.loket = 1
        // this.item.jenis = 'A'

        
    }
    load() {
        this.isPanggil = false
        this.apiService.get(`viewer/get-list-antrian?antrian=${this.array}`).subscribe(e => {
            this.dataTable = e.data
            this.dataTableSudah = e.datasudah
            // this.dataPanggil = e.last
            this.isPanggil = true
            // Jangan Dihapus.......
            // this.item.jenis = e.data[0].jenis
            // this.item.noantri = e.data[0].antrian
            // this.item.namaruangan = e.data[0].namaruangan
            // this.item.norec = e.data[0].norec
            // this.item.objectkebangsaanfk = e.data[0].objectkebangsaanfk 
            // this.item.currentIndex = 0;
            if(this.isfinish == true && this.namaPanggil == 'notspecial'){
                this.item.currentIndex = 0;
                this.item.noantri = this.dataTable[this.item.currentIndex].antrian;
                this.item.user = this.dataTable[this.item.currentIndex].user;
                this.item.jenis = this.dataTable[this.item.currentIndex].jenis;
                this.item.name = this.dataTable[this.item.currentIndex].name;
                this.item.ruanganreservasi = this.dataTable[this.item.currentIndex].ruanganreservasi;
                this.item.norec = this.dataTable[this.item.currentIndex].norec;
            } else if(this.isfinish == true && this.namaPanggil == 'special'){
                this.item.currentIndex = 0;
                this.item.noantri = this.dataTableSudah[this.item.currentIndex].antrian;
                this.item.user = this.dataTableSudah[this.item.currentIndex].user;
                this.item.jenis = this.dataTableSudah[this.item.currentIndex].jenis;
                this.item.name = this.dataTableSudah[this.item.currentIndex].name;
                this.item.ruanganreservasi = this.dataTableSudah[this.item.currentIndex].ruanganreservasi;
                this.item.norec = this.dataTableSudah[this.item.currentIndex].norec;
            }
        })
    }

    panggildulu(type = "norefresh") {
        this.panggil(type)
    }

    panggil(type = "norefresh") {

        console.log('Halo WNI')
        console.log('THIS ITEM', this.item)

        if(!this.item.loket) {
            this.alertService.warn('Informasi', 'Harap set Loket terbelih dahulu dengan memilih shortcut dibawah !')
            return
        }
        if(!this.item.jenis) {
            this.alertService.warn('Informasi', 'Harap set Jenis terbelih dahulu dengan memilih shortcut dibawah !')
            return
        }

        // this.updatePanggil(this.item.jenis, this.item.loket, this.item.norec)

        let json = {
            jenis: this.item.jenis,
            loket: this.item.loket,
            norec: this.item.norec
        }
        this.apiService.post('viewer/update-antrian', json).subscribe(e => {
            if(e.msg == 'Antrian Sedang Dipanggil'){
                this.ngOnInit()
                this.alertService.info('Informasi', e.msg)
                this.sedangPanggil = true
                return
            } else{
                if(this.item.name == 'WNI'){
                    this.socket.emit('caller', { no: this.item.jenis + '-' + this.setNumberStr(this.item.noantri), loket: this.item.loket, jenis: 'Indo' });
                } else{
                    this.socket.emit('caller', { no: this.item.jenis + '-' + this.setNumberStr(this.item.noantri), loket: this.item.loket, jenis: 'English' });
                }
                this.sedangPanggil = false
                // this.panggilwna()
                // this.apiService.get(`viewer/get-list-antrian?antrian=${this.array}`).subscribe(e => {
                //     this.dataTable = e.data
                //     let aya = false
                //     for (let i = 0; i < this.dataTable.length; i++) {
                //         const element = this.dataTable[i];
                //         if (element.jenis == this.item.jenis && element.sisa != 0) {
                //             aya = true
                //             break
                //         }
                //     }
                //     //this.item.no = element.sekarang + 1
                    
                //     // this.item.noantri = this.item.no
                //     // if(type == "refresh") {
                //     //     this.load();
                //     // }
                // })
            }
        })
    }
    panggilwna() {

        console.log('Halo WNA')

        if(!this.item.loket) {
            this.alertService.warn('Informasi', 'Harap set Loket terbelih dahulu dengan memilih shortcut dibawah !')
            return
        }
        if(!this.item.jenis) {
            this.alertService.warn('Informasi', 'Harap set Jenis terbelih dahulu dengan memilih shortcut dibawah !')
            return
        }

        this.socket.emit('caller', { no: this.item.jenis + '-' + this.setNumberStr(this.item.noantri), loket: this.item.loket, jenis: 'English' });

        // let json = {
        //     jenis: this.item.jenis,
        //     loket: this.item.loket,
        //     norec: this.item.norec
        // }
        // this.apiService.post('viewer/update-antrian', json).subscribe(e => {
        //     if(e.msg == 'Antrian Sedang Dipanggil'){
        //         this.alertService.info('Informasi', e.msg)
        //         return
        //     } else{
                
        //     }
        // })

        // this.apiService.get(`viewer/get-list-antrian?antrian=${this.array}`).subscribe(e => {
        //     this.dataTable = e.data
        //     let aya = false
        //     for (let i = 0; i < this.dataTable.length; i++) {
        //         const element = this.dataTable[i];
        //         if (element.jenis == this.item.jenis && element.sisa != 0) {
        //             //this.item.no = element.sekarang + 1
        //             aya = true
        //             break
        //         }
        //     }
            
        //     // this.item.noantri = this.item.no
        //     this.updatePanggil(this.item.jenis, this.item.loket, this.item.norec)
        // })

    }
    panggilUlang() {
        if (this.item.noantri == undefined) {
            this.alertService.info('Informasi', 'Isi No Antrian yang mau dipanggil')
            return
        }
        // for (let i = 0; i < this.dataTable.length; i++) {
        //   const element = this.dataTable[i];
        //   if (parseFloat(this.item.noantri) > element.sekarang && this.item.jenis == element.jenis) {
        //     this.alertService.info('Informasi', 'Mohon panggil ulang nomor yang belum di panggil')
        //     return
        //   }
        // }
        this.socket.emit('caller', { no: this.item.jenis + '-' + this.setNumberStr(this.item.noantri), loket: this.item.loket, jenis: 'Indo' });
        // this.updatePanggil(this.item.jenis, this.item.loket)
    }
    panggilUlangWNA() {
        if (this.item.noantri == undefined) {
            this.alertService.info('Informasi', 'Isi No Antrian yang mau dipanggil')
            return
        }
        // for (let i = 0; i < this.dataTable.length; i++) {
        //   const element = this.dataTable[i];
        //   if (parseFloat(this.item.noantri) > element.sekarang && this.item.jenis == element.jenis) {
        //     this.alertService.info('Informasi', 'Mohon panggil ulang nomor yang belum di panggil')
        //     return
        //   }
        // }
        this.socket.emit('caller', { no: this.item.jenis + '-' + this.setNumberStr(this.item.noantri), loket: this.item.loket, jenis: 'English' });
        // this.updatePanggil(this.item.jenis, this.item.loket)
    }
    setNumberStr(nomer) {
        var str = "" + nomer
        var pad = "000"
        var ans = pad.substring(0, pad.length - str.length) + str
        return ans;
    }
    updatePanggil(jenis, loket, norec) {
        let json = {
            jenis: jenis,
            loket: loket,
            norec: norec
        }
        this.apiService.post('viewer/update-antrian', json).subscribe(e => {
            // this.alertService.info('Informasi', e.msg)
            
            if(e.msg == 'Antrian Sedang Dipanggil'){
                console.log('Halo sama')
                this.alertService.info('Informasi', e.msg)
                this.sedangPanggil = true
                this.ngOnInit()
                return
            } else{
                this.sedangPanggil = false
            }
            
            
            // if (e.msg != 'Antrian Ada') {
            //   this.alertService.info('Informasi', e.msg)
            //   return
            // } else {
            //   this.load()
            // }
        })
    }
    changeKelompok(event) {
        console.log(event)
        this.refresh()
    }
    finish() {
        let json = {
            norec: this.item.norec,
        }
        this.apiService.post('viewer/update-finish', json).subscribe(e => {
            if(e == 'Mohon Panggil Antrian Terlebih Dahulu'){
                console.log('Halo belum panggil')
                this.alertService.info('Informasi', e)
            } else{
                this.isfinish = true
            }
            this.load()
        })
    }
    refresh() {
        console.log(this.item.loketparam)
        console.log(this.item.antrianparam)
        this.item.loket = this.item.loketparam.id;
        this.antrian = this.item.antrianparam.id;

        let arr = this.antrian.split(',')
        let loketFilter = []
        for (var z = 0; z < arr.length; z++) {
            loketFilter.push("'" + arr[z] + "'")
        }
        this.array = loketFilter
        console.log(this.array)
        this.isfinish = true
        this.namaPanggil = 'notspecial'
        this.load()
    }
    shortcutLoket(loket) {
        this.item.loket = loket

    }
    shortcutJenis(jenis) {
        this.item.jenis = jenis
    }
    lihatDetail(jenis) {
        this.apiService.get('viewer/get-data-detail-panggil?jenis=' + jenis).subscribe(e => {
          this.dataTableDetail = e
          this.showDetailAntrian = true
        })
      }
    onChangeSCL(event){
        this.item.loket = event.value.id
    }
    onChangeSCJ(event){
        this.item.jenis = event.value.label
    }
    onSelectAntrianList(data) {
        console.log(data)
        if(data != undefined) {
            this.item.noantri = data.antrian;
            this.item.user = data.user;
            this.item.jenis = data.jenis;
            this.item.name = data.name;
            this.item.ruanganreservasi = data.ruanganreservasi;
            this.item.norec = data.norec;
        }
    }

    async nextAntrian(index) {
        this.isPanggil = true;
        this.item.currentIndex = 0;
        this.item.jenis = this.dataTableSudah[this.item.currentIndex].jenis;
        this.item.name = this.dataTableSudah[this.item.currentIndex].name;
        this.item.ruanganreservasi = this.dataTableSudah[this.item.currentIndex].ruanganreservasi;
        this.item.noantri = this.dataTableSudah[this.item.currentIndex].antrian;
        this.item.user = this.dataTableSudah[this.item.currentIndex].user;
        this.item.norec = this.dataTableSudah[this.item.currentIndex].norec
        this.namaPanggil = 'special'
        await this.panggildulu("refresh");
        
    }

    skipAntrian() {
        this.apiService.get('viewer/update-skip?norec=' + this.item.norec).subscribe(e => {
            this.isfinish = true
            this.namaPanggil = 'notspecial'
            this.load()
        })
    }

    async panggilCurrent(index) {
        this.isPanggil = true;
        // this.item.jenis = this.dataTable[index].jenis;
        // this.item.noantri = this.dataTable[index].antrian;
        // this.item.namaruangan = this.dataTable[index].namaruangan
        // this.item.norec = this.dataTable[index].norec
        // this.item.objectkebangsaanfk = this.dataTable[index].objectkebangsaanfk 
        let json = {
            norec: this.item.norec
        }
        this.namaPanggil = 'notspecial'
        this.panggildulu();
        // this.apiService.post('viewer/update-sedangdipanggil', json).subscribe(e => {
        //     this.panggildulu();
        // })
    }

    toBottom() {
        document.getElementById("inispecial").scrollIntoView({behavior: 'smooth'});
      }
}
