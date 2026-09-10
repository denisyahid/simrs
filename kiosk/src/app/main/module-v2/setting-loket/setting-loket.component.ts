import { Component, OnInit, ViewEncapsulation } from '@angular/core';

import { takeUntil } from 'rxjs/operators';
import { Subject } from 'rxjs';
import { CoreConfigService } from '@core/services/config.service';
import { Router } from '@angular/router';
import { HttpService } from '../../module/httpService';


@Component({
  selector: 'app-setting-loket',
  templateUrl: './setting-loket.component.html',
  styleUrls: ['./setting-loket.component.scss']
})
export class SettingLoketComponent implements OnInit {
  // Public
  public userNameVar;
  public emailVar;
  public passwordVar;
  public coreConfig: any;
  public passwordTextType: boolean;
  listLoket: any = [];

  // Private
  private _unsubscribeAll: Subject<any>;
  constructor(
    private _coreConfigService: CoreConfigService,
    private router: Router,
    private httpService: HttpService,
  ) {
    this._unsubscribeAll = new Subject();

    // Configure the layout
    this._coreConfigService.config = {
      layout: {
        navbar: {
          hidden: true
        },
        menu: {
          hidden: true
        },
        footer: {
          hidden: true
        },
        customizer: false,
        enableLocalStorage: false
      }
    };
  }

  ngOnInit(): void {
    this._coreConfigService.config.pipe(takeUntil(this._unsubscribeAll)).subscribe(config => {
      this.coreConfig = config;
    });

    this.httpService.get('medifirst2000/kiosk/get-jumlah-loket').subscribe(e => {
      for (let i = 0; i < parseInt(e.loket); i++) {
        const element = e.loket[i];
        const id = i + 1
        const namanya = (id == 1) ? "Lama" : (id == 2) ? "Baru" : id
        this.listLoket.push({
          id: id,
          name: `Rajal. ${namanya}`,
          subname: `Kiosk`
        })
      }
      console.log(this.listLoket)
    })

  }

  goto(id: any) {
    localStorage.setItem('isLoket', id)
    this.router.navigate(['v2/touchscreen']);
  }

  togglePasswordTextType() {
    this.passwordTextType = !this.passwordTextType;
  }

}
