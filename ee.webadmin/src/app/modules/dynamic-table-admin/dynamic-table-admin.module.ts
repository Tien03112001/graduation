import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {RouterModule, Routes} from '@angular/router';
import {MenuModule} from '../menu/menu.module';
import {DynamicTableAdminListComponent} from './dynamic-table-admin-list/dynamic-table-admin-list.component';
import {DynamicTableAdminCreateComponent} from './dynamic-table-admin-create/dynamic-table-admin-create.component';
import {DynamicTableAdminEditComponent} from './dynamic-table-admin-edit/dynamic-table-admin-edit.component';
import {DynamicTableAdminRowModule} from '../dynamic-table-admin-row/dynamic-table-admin-row.module';
import {DynamicTableAdminColumnModule} from '../dynamic-table-admin-column/dynamic-table-admin-column.module';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {CKEditorModule} from 'ng2-ckeditor';

const routing: Routes = [
  {
    path: '',
    component: DynamicTableAdminListComponent
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
    DynamicTableAdminRowModule,
    DynamicTableAdminColumnModule,
    AngularMultiSelectModule,
    CKEditorModule
  ],
  declarations: [DynamicTableAdminListComponent, DynamicTableAdminCreateComponent, DynamicTableAdminEditComponent],
  entryComponents: [DynamicTableAdminCreateComponent, DynamicTableAdminEditComponent]
})
export class DynamicTableAdminModule {
}
