import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { ViewerKamarComponent } from './viewer-kamar.component';

const routes: Routes = [{ path: '', component: ViewerKamarComponent }];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class ViewerKamarRoutingModule { }
