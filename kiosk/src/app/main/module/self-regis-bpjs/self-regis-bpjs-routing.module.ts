import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { SelfRegisBpjsComponent } from './self-regis-bpjs.component';

const routes: Routes = [{ path: '', component: SelfRegisBpjsComponent }];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class SelfRegisBpjsRoutingModule { }
