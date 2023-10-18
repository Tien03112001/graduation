import {NgModule} from '@angular/core';
import {ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {RouterModule, Routes} from '@angular/router';
import {PaymentListComponent} from './payment-list/payment-list.component';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {PaymentModalModule} from './payment.modal.module';

const routing: Routes = [
  {
    path: '',
    component: PaymentListComponent
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
    PaymentModalModule
  ],
  declarations: [
    PaymentListComponent
  ],
  entryComponents: [],
  exports: []
})
export class PaymentModule {
}
