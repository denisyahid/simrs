import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { SelfRegisBpjsRoutingModule } from './self-regis-bpjs-routing.module';
import { SelfRegisBpjsComponent } from './self-regis-bpjs.component';
import { CoreCommonModule } from '@core/common.module';
import { ContentHeaderModule } from 'app/layout/components/content-header/content-header.module';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';
import { NgSelectModule } from '@ng-select/ng-select';
import { FormsModule } from '@angular/forms';
import { CardSnippetModule } from '@core/components/card-snippet/card-snippet.module';
import { ToastrModule } from 'ngx-toastr';
import { AlertService } from '../../module/alert.service';


@NgModule({
  declarations: [SelfRegisBpjsComponent],
  imports: [
    CommonModule,
    SelfRegisBpjsRoutingModule,
    CoreCommonModule,
    ContentHeaderModule,
    NgbModule,
    NgSelectModule,
    FormsModule,
    CardSnippetModule,
    ToastrModule.forRoot(),
  ],
  providers: [
    AlertService
  ]
})
export class SelfRegisBpjsModule { }
