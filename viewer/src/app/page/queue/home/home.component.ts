import { Component, ElementRef, OnInit, ViewChild } from '@angular/core';

import { ApiService } from 'src/app/service';


import { ActivatedRoute, Router } from '@angular/router';
import { Config } from 'src/app/guard';
@Component({
    selector: 'app-home',
    templateUrl: './home.component.html',
    styleUrls: ['./home.component.scss']
})
export class HomeComponent implements OnInit {
    poli: any
    loket: any
    farmasi: any
    showPoli: boolean
    showFarmasi: boolean
    showLoket: boolean
    showCaller: boolean
    listPoli: any;
    listFarmasi: any;
    listLab: any;
    listLoket: any = []
    listCaller: any = []
    listBahasa: any = [] 
    bahasa: any 
    listAntrian = [{id: '1', label: 'Loket 1 : Lama BPJS (WNA WNI)'},{id : '2', label: 'Loket 2: Pasien Baru (WNA WNI ), Reservasi, Lansia dan Anak'},{id : '3', label: 'Loket 3: Pasien Lansia Lama BPJS'},{id : '4', label: 'Loket 4: Pasien Lama Umum WNA'}]
    namaProfile = Config.getProfile().namaProfile
    brand = Config.getProfile().brand
    constructor(

        private apiService: ApiService,
        private route: Router,
    ) { }

    ngOnInit(): void {
        this.listBahasa.push({id: 0, label: 'Pilih Bahasa'}, {id: 1, label: 'Indonesian'},{id : 2, label: 'English'})
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
        })
        this.apiService.get('sysadmin/settingdatafixed/get/jumlahLoketKiosk').subscribe(e => {
            let jmlLantai = parseInt(e)
            for (let x = 0; x < jmlLantai; x++) {
                const element = jmlLantai[x];
                const id = x + 1
                const namanya = (id == 1) ? "Lama" : (id == 2) ? "Baru" : id
                this.listCaller.push({
                    label: `Rajal ${namanya}`,
                    namapoli: '',
                    value: '-',
                    id: x + 1
                })
            }
        })
        this.apiService.get('viewer/get-setting-viewer').subscribe(e => {
            this.listPoli = e.ruangan;
            this.listFarmasi = e.farmasi;
            this.listLab = e.laborat;
            this.poli = []
            for (let x = 0; x < this.listPoli.length; x++) {
                const element = this.listPoli[x];
                // if ([698, 662, 688, 693, 694, 676].includes(element.id)) {
                this.poli.push(element)
                // }
            }
            this.farmasi = [];
            for (let x = 0; x < this.listFarmasi.length; x++) {
                const element = this.listFarmasi[x];
                // if ([698, 662, 688, 693, 694, 676].includes(element.id)) {
                this.farmasi.push(element)
                // }
            }
        })
    }
    save(e) {

        if (e == undefined) return
        let data = ''
        for (let x = 0; x < e.length; x++) {
            const element = e[x];
            data = data + ',' + element.id
        }

        data = data.substring(1, data.length)
        this.route.navigate(['/viewer-poli/' + data])
    }

    saveFarma(e) {
        if (e == undefined) return
        let data = ''
        for (let x = 0; x < e.length; x++) {
            const element = e[x];
            data = data + ',' + element.id
        }

        data = data.substring(1, data.length)
        this.route.navigate(['/viewer-farmasi/' + data])
    }

    saveLab(e) {
        if (e == undefined) return
        let data = ''
        for (let x = 0; x < e.length; x++) {
            const element = e[x];
            data = data + ',' + element.id
        }

        data = data.substring(1, data.length)
        this.route.navigate(['/viewer-lab/' + data])
    }
    saveLoket(e, bahasa) {

        console.log(bahasa)
        if (e == undefined) return
        let data = ''
        if(bahasa.label == 'Indonesian'){
            for (let x = 0; x < e.length; x++) {
                const element = e[x];
                data = data + ',' + element.label
            }
        } else{
            for (let x = 0; x < e.length; x++) {
                const element = e[x];
                data = data + ',' + element.label.replace('Loket', 'Counter')
            }
        }
        
        data = data.substring(1, data.length)
        console.log(data)
        this.route.navigate(['/viewer'], { queryParams: { loket: data, bahasa: bahasa.label} })
    }
    saveCaller(loket, antrian) {
        if (loket == undefined) return
        let data = ''
        for (let x = 0; x < antrian.length; x++) {
            const element = antrian[x];
            data = data + ',' + element.id
        }
        
        data = data.substring(1, data.length)
        this.route.navigate(['/caller'], { queryParams: { loket: loket.id, antrian: data } })
    }
}
