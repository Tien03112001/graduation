import {NgModule} from '@angular/core';
import {ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {OrderListComponent} from './order-list/order-list.component';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {OrderModalModule} from './order.modal.module';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    PaginationModule.forRoot(),
    ReactiveFormsModule,
    PopoverModule.forRoot(),
    ConfirmationPopoverModule.forRoot(),
    ModalModule.forRoot(),
    AngularMultiSelectModule,
    OrderModalModule
  ],
  declarations: [
    OrderListComponent
  ],
  entryComponents: [],
  exports: [
    OrderListComponent
  ]
})
export class OrderModule {
}
