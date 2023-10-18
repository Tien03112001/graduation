import {NgModule} from '@angular/core';
import {ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {CustomerListComponent} from './customer-list/customer-list.component';
import {RouterModule, Routes} from '@angular/router';
import {CustomerCreateExportModule} from './customer-create/customer-create-export.module';
import {CustomerEditComponent} from './customer-edit/customer-edit.component';

const routing: Routes = [
  {
    path: '',
    component: CustomerListComponent
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
    CustomerCreateExportModule
  ],
  declarations: [
    CustomerListComponent,
    CustomerEditComponent
  ],
  entryComponents: [
    CustomerEditComponent
  ],
  exports: []
})
export class CustomerModule {
}
