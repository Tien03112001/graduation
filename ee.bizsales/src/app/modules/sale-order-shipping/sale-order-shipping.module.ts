import {NgModule} from '@angular/core';
import {CollapseModule, ModalModule, PaginationModule, PopoverModule, TypeaheadModule} from 'ngx-bootstrap';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {MomentModule} from 'ngx-moment';
import {SaleOrderShippingInfoComponent} from './sale-order-shipping-info/sale-order-shipping-info.component';


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
    MomentModule,
    CollapseModule,
    TypeaheadModule.forRoot()
  ],
  declarations: [
    SaleOrderShippingInfoComponent
  ],
  entryComponents: [
    SaleOrderShippingInfoComponent
  ],
  exports: [
    SaleOrderShippingInfoComponent
  ]
})
export class SaleOrderShippingModule {
}
