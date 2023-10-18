import {NgModule} from '@angular/core';
import {ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {RouterModule, Routes} from '@angular/router';
import {PaymentmtListComponent} from './paymentmt-list/paymentmt-list.component';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {PaymentmtModalModule} from './paymentmt.modal.module';

const routing: Routes = [
  {
    path: '',
    component: PaymentmtListComponent
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
    PaymentmtModalModule
  ],
  declarations: [
    PaymentmtListComponent
  ],
  entryComponents: [],
  exports: []
})
export class PaymentmtModule {
}
