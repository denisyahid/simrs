import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { VerifPasienBpjsMandiriComponent } from './verif-pasien-bpjs-mandiri.component';


const routes: Routes = [{ path: '', component: VerifPasienBpjsMandiriComponent }];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class VerifPasienBpjsMandiriRoutingModule { }
