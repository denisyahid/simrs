import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { ViewerLabRoutingModule } from './viewer-lab-routing.module';
import { primeNgModule } from 'src/app/shared/shared.module';
import { ViewerLabComponent } from './viewer-lab.component';
import { TableModule } from 'primeng/table';
import { VgCoreModule } from '@videogular/ngx-videogular/core';
import { VgControlsModule } from '@videogular/ngx-videogular/controls';
import { VgOverlayPlayModule } from '@videogular/ngx-videogular/overlay-play';
import { VgBufferingModule } from '@videogular/ngx-videogular/buffering';
import { DPlayerModule } from 'angular-dplayer';


@NgModule({
  declarations: [ViewerLabComponent],
  imports: [
    CommonModule,
    ViewerLabRoutingModule,
    primeNgModule,
    TableModule,
    VgCoreModule,
    VgControlsModule,
    VgOverlayPlayModule,
    VgBufferingModule,
    DPlayerModule

    // CommonModule,
    // ViewerLabRoutingModule,
    // primeNgModule,
    // ViewerLabComponent,
    // TableModule
  ]
})
export class ViewerLabModule { }
