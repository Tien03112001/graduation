import {NgModule} from '@angular/core';
import {ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {ShippingUnitsCreateComponent} from './shipping_units-create/shipping_units-create.component';
import {ShippingUnitsEditComponent} from './shipping_units-edit/shipping_units-edit.component';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {CKEditorModule} from 'ng2-ckeditor';
import {Shipping_unitsPartnerComponent} from './shipping_units-partner/shipping_units-partner.component';

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
    CKEditorModule
  ],
  declarations: [
    ShippingUnitsCreateComponent, ShippingUnitsEditComponent, Shipping_unitsPartnerComponent
  ],
  entryComponents: [
    ShippingUnitsCreateComponent, ShippingUnitsEditComponent, Shipping_unitsPartnerComponent
  ],
  exports: [
    ShippingUnitsCreateComponent, ShippingUnitsEditComponent, Shipping_unitsPartnerComponent
  ]
})
export class ShippingUnitsModalModule {
}
