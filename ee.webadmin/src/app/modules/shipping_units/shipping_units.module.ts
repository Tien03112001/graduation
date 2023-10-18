import {NgModule} from '@angular/core';
import {ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {RouterModule, Routes} from '@angular/router';
import {ShippingUnitsListComponent} from './shipping_units-list/shipping_units-list.component';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {ShippingUnitsModalModule} from './shipping_units.modal.module';

const routing: Routes = [
  {
    path: '',
    component: ShippingUnitsListComponent
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
    ShippingUnitsModalModule
  ],
  declarations: [
    ShippingUnitsListComponent
  ],
  entryComponents: [],
  exports: []
})
export class ShippingUnitsModule {
}
