import {NgModule} from '@angular/core';
import {ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {RouterModule, Routes} from '@angular/router';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {StatusModalModule} from './status.modal.module';
import { StatusListComponent } from './status-list/status-list.component';

const routing: Routes = [
  {
    path: '',
    component: StatusListComponent
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
    StatusModalModule
  ],
  declarations: [
    StatusListComponent
  ],
  entryComponents: [],
  exports: []
})
export class StatusModule {
}
