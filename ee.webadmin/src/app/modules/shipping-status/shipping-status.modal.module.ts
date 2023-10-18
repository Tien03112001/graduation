import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {ShippingStatusCreateComponent} from './shipping-status-create/shipping-status-create.component';
import {ShippingStatusEditComponent} from './shipping-status-edit/shipping-status-edit.component';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {NgSelectModule} from '@ng-select/ng-select';

import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {CKEditorModule} from 'ng2-ckeditor';
import { ShippingStatusEditVnpayComponent } from './shipping-status-vnpay-edit/shipping-status-vnpay-edit.component';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    PaginationModule.forRoot(),
    ReactiveFormsModule,
    PopoverModule.forRoot(),
    ConfirmationPopoverModule.forRoot(),
    ModalModule.forRoot(),
    NgSelectModule,
    AngularMultiSelectModule,
    CKEditorModule
  ],
  declarations: [ShippingStatusCreateComponent, ShippingStatusEditComponent, ShippingStatusEditVnpayComponent],
  entryComponents: [ShippingStatusCreateComponent, ShippingStatusEditComponent, ShippingStatusEditVnpayComponent],
  exports: [ShippingStatusCreateComponent, ShippingStatusEditComponent, ShippingStatusEditVnpayComponent],
})
export class ShippingStatusModalModule {
}
