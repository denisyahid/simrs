import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { SettingLoketComponent } from './setting-loket.component';

const routes: Routes = [{ path: '', component: SettingLoketComponent }];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class SettingLoketRoutingModule { }
