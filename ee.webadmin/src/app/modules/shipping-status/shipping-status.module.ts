import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {RouterModule, Routes} from '@angular/router';
import {NgSelectModule} from '@ng-select/ng-select';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {CKEditorModule} from 'ng2-ckeditor';
import {ShippingStatusModalModule} from './shipping-status.modal.module';
import {ShippingStatusListComponent} from './shipping-status-list/shipping-status-list.component';

const routing: Routes = [
  {
    path: '',
    component: ShippingStatusListComponent
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
    AngularMultiSelectModule,
    CKEditorModule,
    ShippingStatusModalModule
  ],
  declarations: [ShippingStatusListComponent],
  entryComponents: []
})
export class ShippingStatusModule {
}
