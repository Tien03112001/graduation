import {NgModule} from '@angular/core';
import {ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {RouterModule, Routes} from '@angular/router';
import {FanpageListComponent} from './fanpage-list/fanpage-list.component';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {FanpageModalModule} from './fanpage.modal.module';
import { AgentPageModalModule } from '../agent-page/agent-page.modal.module';

const routing: Routes = [
  {
    path: '',
    component: FanpageListComponent
  }
];

@NgModule({
  imports: [
    RouterModule.forChild(routing),
    CommonModule,
    FormsModule,
    PaginationModule.forRoot(),
    ReactiveFormsModule,
    PopoverModule.forRoot(),
    ConfirmationPopoverModule.forRoot(),
    ModalModule.forRoot(),
    AngularMultiSelectModule,
    FanpageModalModule,
    AgentPageModalModule,
  ],
  declarations: [
    FanpageListComponent
  ],
  entryComponents: [],
  exports: []
})
export class FanpageModule {
}
