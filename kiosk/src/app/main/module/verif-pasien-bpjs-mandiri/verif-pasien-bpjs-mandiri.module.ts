import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { VerifPasienBpjsMandiriRoutingModule } from './verif-pasien-bpjs-mandiri-routing.module';
import { VerifPasienBpjsMandiriComponent } from './verif-pasien-bpjs-mandiri.component';
import { CoreCommonModule } from '@core/common.module';
import { ContentHeaderModule } from 'app/layout/components/content-header/content-header.module';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';
import { NgSelectModule } from '@ng-select/ng-select';
import { FormsModule } from '@angular/forms';
import { CardSnippetModule } from '@core/components/card-snippet/card-snippet.module';
import { ToastrModule } from 'ngx-toastr';
import { AlertService } from '../alert.service';
import { SkeletonModule } from 'primeng/skeleton';

@NgModule({
  declarations: [VerifPasienBpjsMandiriComponent],
  imports: [
    CommonModule,
    VerifPasienBpjsMandiriRoutingModule,
    CoreCommonModule,
    ContentHeaderModule,
    NgbModule,
    NgSelectModule,
    FormsModule,
    CardSnippetModule,
    ToastrModule.forRoot(),
    SkeletonModule,
  ],
  providers:[
    AlertService
  ]
})
export class VerifPasienBpjsMandiriModule { }
