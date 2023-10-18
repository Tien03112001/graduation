import {NgModule} from '@angular/core';
import {CollapseModule, ModalModule, PaginationModule, PopoverModule, TypeaheadModule} from 'ngx-bootstrap';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';;
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {MomentModule} from 'ngx-moment';
import {OrderVerifyComponent} from './order-verify/order-verify.component';
import { OrderCancelComponent } from './order-cancel/order-cancel.component';
import { OrderEditComponent } from './order-edit/order-edit.component';

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
  ],
  declarations: [
    OrderVerifyComponent,
    OrderCancelComponent,
    OrderEditComponent
  ],
  entryComponents: [
    OrderVerifyComponent,
    OrderCancelComponent,
    OrderEditComponent
  ],
  exports: []
})
export class OrderCrudModule {
}
