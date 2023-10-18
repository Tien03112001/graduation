import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {MenuModule} from '../menu/menu.module';
import {DynamicTableAdminRowCreateComponent} from './dynamic-table-admin-row-create/dynamic-table-admin-row-create.component';
import {DynamicTableAdminRowEditComponent} from './dynamic-table-admin-row-edit/dynamic-table-admin-row-edit.component';


@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    PaginationModule.forRoot(),
    ReactiveFormsModule,
    PopoverModule.forRoot(),
    ConfirmationPopoverModule.forRoot(),
    ModalModule.forRoot(),
    MenuModule
  ],
  declarations: [DynamicTableAdminRowCreateComponent, DynamicTableAdminRowEditComponent],
  entryComponents: [DynamicTableAdminRowCreateComponent, DynamicTableAdminRowEditComponent],
  exports: [DynamicTableAdminRowCreateComponent, DynamicTableAdminRowEditComponent]

})
export class DynamicTableAdminRowModule {
}
