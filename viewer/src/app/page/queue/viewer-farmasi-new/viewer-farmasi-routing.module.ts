import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { ViewerFarmasiNewComponent } from './viewer-farmasi.component';

const routes: Routes = [{path:'',component:ViewerFarmasiNewComponent}];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class ViewerFarmasiNewRoutingModule { }
