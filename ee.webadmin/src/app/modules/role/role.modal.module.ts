import {NgModule} from '@angular/core';
import {ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {RoleCreateComponent} from './role-create/role-create.component';
import {RoleEditComponent} from './role-edit/role-edit.component';
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
    AngularMultiSelectModule,
    CKEditorModule
  ],
  declarations: [
    RoleCreateComponent, RoleEditComponent
  ],
  entryComponents: [
    RoleCreateComponent, RoleEditComponent
  ],
  exports: [
    RoleCreateComponent, RoleEditComponent
  ]
})
export class RoleModalModule {
}
