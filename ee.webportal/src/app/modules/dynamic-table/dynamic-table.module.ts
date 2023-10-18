import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {RouterModule, Routes} from '@angular/router';
import {MenuModule} from '../menu/menu.module';
import {DynamicTableListComponent} from './dynamic-table-list/dynamic-table-list.component';
import {DynamicTableCreateComponent} from './dynamic-table-create/dynamic-table-create.component';
import {DynamicTableEditComponent} from './dynamic-table-edit/dynamic-table-edit.component';
import {DynamicTableRowModule} from '../dynamic-table-row/dynamic-table-row.module';
import {DynamicTableColumnModule} from '../dynamic-table-column/dynamic-table-column.module';
import {CKEditorModule} from 'ng2-ckeditor';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';

const routing: Routes = [
  {
    path: '',
    component: DynamicTableListComponent
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
    MenuModule,
    DynamicTableRowModule,
    DynamicTableColumnModule,
    CKEditorModule,
    AngularMultiSelectModule
  ],
  declarations: [DynamicTableListComponent, DynamicTableCreateComponent, DynamicTableEditComponent],
  entryComponents: [DynamicTableCreateComponent, DynamicTableEditComponent]
})
export class DynamicTableModule {
}
