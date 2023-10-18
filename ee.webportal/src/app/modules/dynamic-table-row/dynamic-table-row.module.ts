import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {MenuModule} from '../menu/menu.module';
import {DynamicTableRowCreateComponent} from './dynamic-table-row-create/dynamic-table-row-create.component';
import {DynamicTableRowEditComponent} from './dynamic-table-row-edit/dynamic-table-row-edit.component';
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
  declarations: [DynamicTableRowCreateComponent, DynamicTableRowEditComponent],
  entryComponents: [DynamicTableRowCreateComponent, DynamicTableRowEditComponent],
  exports: [DynamicTableRowCreateComponent, DynamicTableRowEditComponent]

})
export class DynamicTableRowModule {
}
