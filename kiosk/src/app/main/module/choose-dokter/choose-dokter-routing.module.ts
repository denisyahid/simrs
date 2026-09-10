import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { ChooseDokterComponent } from './choose-dokter.component';

const routes: Routes = [{ path: '', component: ChooseDokterComponent }];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class ChooseDokterRoutingModule { }
