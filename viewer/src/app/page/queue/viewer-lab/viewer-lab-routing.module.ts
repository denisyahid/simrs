import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { ViewerLabComponent } from './viewer-lab.component';

const routes: Routes = [{path:'',component:ViewerLabComponent}];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class ViewerLabRoutingModule { }
