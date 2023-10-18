import {NgModule} from '@angular/core';
import {CollapseModule, ModalModule, PaginationModule, PopoverModule, TypeaheadModule} from 'ngx-bootstrap';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {SaleOrderCreateComponent} from './sale-order-create/sale-order-create.component';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {MomentModule} from 'ngx-moment';
import {SaleOrderShippingModule} from '../sale-order-shipping/sale-order-shipping.module';
import {SaleOrderEditComponent} from './sale-order-edit/sale-order-edit.component';
import {SaleOrderVerifyComponent} from './sale-order-verify/sale-order-verify.component';
import {SaleOrderNoteComponent} from './sale-order-note/sale-order-note.component';
import {SaleOrderCancelComponent} from './sale-order-cancel/sale-order-cancel.component';
import {SaleOrderSwapComponent} from './sale-order-swap/sale-order-swap.component';
import { SaleOrderDraftVerifyComponent } from './sale-order-draft-verify/sale-order-draft-verify.component';
import { SaleOrderDraftEditComponent } from './sale-order-draft-edit/sale-order-draft-edit.component';

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
    TypeaheadModule.forRoot(),
    SaleOrderShippingModule
  ],
  declarations: [
    SaleOrderCreateComponent,
    SaleOrderEditComponent,
    SaleOrderVerifyComponent,
    SaleOrderNoteComponent,
    SaleOrderCancelComponent,
    SaleOrderDraftVerifyComponent,
    SaleOrderDraftEditComponent,
    SaleOrderSwapComponent
  ],
  entryComponents: [
    SaleOrderCreateComponent,
    SaleOrderEditComponent,
    SaleOrderVerifyComponent,
    SaleOrderNoteComponent,
    SaleOrderCancelComponent,
    SaleOrderDraftVerifyComponent,
    SaleOrderDraftEditComponent,
    SaleOrderSwapComponent
  ],
  exports: []
})
export class SaleOrderCrudModule {
}
