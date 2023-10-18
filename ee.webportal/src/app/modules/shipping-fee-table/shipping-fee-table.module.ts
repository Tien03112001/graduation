import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {ShippingFeeTableListComponent} from './shipping-fee-table-list/shipping-fee-table-list.component';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ModalModule, PaginationModule, PopoverModule, TabsModule} from 'ngx-bootstrap';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {RouterModule, Routes} from '@angular/router';
import {NgSelectModule} from '@ng-select/ng-select';
import {CKEditorModule} from 'ng2-ckeditor';
import {UiSwitchModule} from 'ngx-toggle-switch';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {ShippingFeeTableModalModule} from './shipping-fee-table.modal.module';

const routing: Routes = [
  {
    path: '',
    component: ShippingFeeTableListComponent
  }
];

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    PaginationModule.forRoot(),
    ReactiveFormsModule,
    PopoverModule.forRoot(),
    ConfirmationPopoverModule.forRoot(),
    RouterModule.forChild(routing),
    ModalModule.forRoot(),
    NgSelectModule,
    CKEditorModule,
    UiSwitchModule,
    TabsModule.forRoot(),
    AngularMultiSelectModule,
    ShippingFeeTableModalModule,
  ],
  declarations: [ShippingFeeTableListComponent],
  entryComponents: [],
  exports: []
})
export class ShippingFeeTableModule {
}
