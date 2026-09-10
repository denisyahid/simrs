import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { ViewerOperasiComponent } from './viewer-operasi.component';

const routes: Routes = [{ path: '', component: ViewerOperasiComponent }];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class ViewerOperasiRoutingModule { }
