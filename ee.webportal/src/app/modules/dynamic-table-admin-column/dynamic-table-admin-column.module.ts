import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {MenuModule} from '../menu/menu.module';
import {DynamicTableAdminColumnCreateComponent} from './dynamic-table-admin-column-create/dynamic-table-admin-column-create.component';
import {DynamicTableAdminColumnEditComponent} from './dynamic-table-admin-column-edit/dynamic-table-admin-column-edit.component';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {CKEditorModule} from 'ng2-ckeditor';


@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    PaginationModule.forRoot(),
    ReactiveFormsModule,
    PopoverModule.forRoot(),
    ConfirmationPopoverModule.forRoot(),
    ModalModule.forRoot(),
    MenuModule,
    AngularMultiSelectModule,
    CKEditorModule
  ],
  declarations: [DynamicTableAdminColumnCreateComponent, DynamicTableAdminColumnEditComponent],
  entryComponents: [DynamicTableAdminColumnCreateComponent, DynamicTableAdminColumnEditComponent],
  exports: [DynamicTableAdminColumnCreateComponent, DynamicTableAdminColumnEditComponent]

})
export class DynamicTableAdminColumnModule {
}
