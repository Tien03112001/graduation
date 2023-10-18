import {NgModule} from '@angular/core';
import {CollapseModule, ModalModule, PaginationModule, PopoverModule, TypeaheadModule} from 'ngx-bootstrap';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {MomentModule} from 'ngx-moment';
import {SaleOrderShippingInfoComponent} from './sale-order-shipping-info/sale-order-shipping-info.component';
import {SaleOrderShippingCreateComponent} from './sale-order-shipping-create/sale-order-shipping-create.component';
import {SaleOrderShippingListComponent} from './sale-order-shipping-list/sale-order-shipping-list.component';
import {SaleOrderShippingCreateResultComponent} from './sale-order-shipping-create-result/sale-order-shipping-create-result.component';


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
    SaleOrderShippingListComponent,
    SaleOrderShippingInfoComponent,
    SaleOrderShippingCreateComponent,
    SaleOrderShippingCreateResultComponent
  ],
  entryComponents: [
    SaleOrderShippingListComponent,
    SaleOrderShippingInfoComponent,
    SaleOrderShippingCreateComponent,
    SaleOrderShippingCreateResultComponent
  ],
  exports: [
    SaleOrderShippingListComponent,
    SaleOrderShippingInfoComponent,
    SaleOrderShippingCreateComponent,
  ]
})
export class SaleOrderShippingModule {
}
