import { Component } from '@angular/core';
import { AppComponent } from './app.component';
import { AppMainComponent } from './app.main.component';
import { AuthService } from './service';
import { Config } from './guard';

@Component({
    selector: 'app-topbar',
    templateUrl: './app.topbar.component.html'
})
export class AppTopBarComponent {
    tgl: any
    jamSekarang: any
    namaProfile = Config.getProfile().namaProfile
    brand = Config.getProfile().brand
    constructor(public app: AppComponent, public appMain: AppMainComponent, public authService: AuthService) { }
}
