import {NgModule} from '@angular/core';
import {ModalModule, PaginationModule, PopoverModule, TabsModule} from 'ngx-bootstrap';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {CKEditorModule} from 'ng2-ckeditor';
import {ShippingFeeTableCreateComponent} from './shipping-fee-table-create/shipping-fee-table-create.component';
import {ShippingFeeTableEditComponent} from './shipping-fee-table-edit/shipping-fee-table-edit.component';
import {UiSwitchModule} from 'ngx-toggle-switch';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    PaginationModule.forRoot(),
    ReactiveFormsModule,
    PopoverModule.forRoot(),
    ConfirmationPopoverModule.forRoot(),
    ModalModule.forRoot(),
    CKEditorModule,
    UiSwitchModule,
    TabsModule.forRoot(),
    AngularMultiSelectModule,
  ],
  declarations: [
    ShippingFeeTableCreateComponent, ShippingFeeTableEditComponent,
  ],
  entryComponents: [
    ShippingFeeTableCreateComponent, ShippingFeeTableEditComponent,
  ],
  exports: [
    ShippingFeeTableCreateComponent, ShippingFeeTableEditComponent,
  ]
})
export class ShippingFeeTableModalModule {
}
