import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { ViewerOperasiRoutingModule } from './viewer-operasi-routing.module';
import { primeNgModule } from 'src/app/shared/shared.module';
import { ViewerOperasiComponent } from './viewer-operasi.component';
import { VgCoreModule } from '@videogular/ngx-videogular/core';
import { VgControlsModule } from '@videogular/ngx-videogular/controls';
import { VgOverlayPlayModule } from '@videogular/ngx-videogular/overlay-play';
import { VgBufferingModule } from '@videogular/ngx-videogular/buffering';
import { DPlayerModule } from 'angular-dplayer';


@NgModule({
  declarations: [
    ViewerOperasiComponent
  ],
  imports: [
    CommonModule,
    ViewerOperasiRoutingModule,
    primeNgModule,
    VgCoreModule,
    VgControlsModule,
    VgOverlayPlayModule,
    VgBufferingModule,
    DPlayerModule
  ]
})
export class ViewerOperasiModule { }
