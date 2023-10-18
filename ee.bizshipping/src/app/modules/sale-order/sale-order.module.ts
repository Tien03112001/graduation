import {NgModule} from '@angular/core';
import {ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {RouterModule, Routes} from '@angular/router';
import {MomentModule} from 'ngx-moment';
import {SaleOrderListComponent} from './sale-order-list/sale-order-list.component';
import {SaleOrderShippingModule} from '../sale-order-shipping/sale-order-shipping.module';
import {SaleOrderShippingListComponent} from '../sale-order-shipping/sale-order-shipping-list/sale-order-shipping-list.component';
import { SaleOrderNoteComponent } from './sale-order-note/sale-order-note.component';
import { SaleOrderRefundComponent } from './sale-order-refund/sale-order-refund.component';


const routing: Routes = [
  {
    path: 'list',
    component: SaleOrderListComponent
  },
  {
    path: 'ship',
    component: SaleOrderShippingListComponent
  },

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
    MomentModule,
    SaleOrderShippingModule,
  ],
  declarations: [
    SaleOrderListComponent,SaleOrderNoteComponent,SaleOrderRefundComponent
  ],
  entryComponents: [SaleOrderListComponent,SaleOrderNoteComponent,SaleOrderRefundComponent],
  exports: [SaleOrderListComponent,SaleOrderNoteComponent,SaleOrderRefundComponent]
})
export class SaleOrderModule {
}
