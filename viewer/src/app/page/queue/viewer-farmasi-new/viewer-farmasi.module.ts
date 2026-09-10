import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { ViewerFarmasiNewRoutingModule } from './viewer-farmasi-routing.module';
import { primeNgModule } from 'src/app/shared/shared.module';
import { ViewerFarmasiNewComponent } from './viewer-farmasi.component';
import { TableModule } from 'primeng/table';


@NgModule({
  declarations: [ViewerFarmasiNewComponent],
  imports: [
    CommonModule,
    ViewerFarmasiNewRoutingModule,
    primeNgModule,
    TableModule
  ]
})
export class ViewerFarmasiNewModule { }
