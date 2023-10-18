import {NgModule} from '@angular/core';
import {CollapseModule, ModalModule, PaginationModule, PopoverModule, TypeaheadModule} from 'ngx-bootstrap';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {RouterModule, Routes} from '@angular/router';
import {SaleOrderListComponent} from './sale-order-list.component';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {MomentModule} from 'ngx-moment';
import {SaleOrderShippingModule} from '../sale-order-shipping/sale-order-shipping.module';
import {SaleOrderCrudModule} from './sale-order.crud.module';

const routing: Routes = [
  {
    path: '',
    component: SaleOrderListComponent
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
    MomentModule,
    CollapseModule,
    TypeaheadModule.forRoot(),
    SaleOrderShippingModule,
    SaleOrderCrudModule
  ],
  declarations: [
    SaleOrderListComponent,
  ],
  entryComponents: [
  ],
  exports: []
})
export class SaleOrderModule {
}
