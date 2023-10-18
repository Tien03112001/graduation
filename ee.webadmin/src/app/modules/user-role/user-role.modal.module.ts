import { NgModule } from '@angular/core';
import { ModalModule, PaginationModule, PopoverModule } from 'ngx-bootstrap';
import { CommonModule } from '@angular/common';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { ConfirmationPopoverModule } from 'angular-confirmation-popover';
import { UiSwitchModule } from 'ngx-toggle-switch';
import { AngularMultiSelectModule } from 'angular2-multiselect-dropdown';
import { CKEditorModule } from 'ng2-ckeditor';
import { UserRoleCreateComponent } from './user-role-create/user-role-create.component';
import { UserRoleEditComponent } from './user-role-edit/user-role-edit.component';
import { UserRoleListComponent } from './user-role-list/user-role-list.component';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    PaginationModule.forRoot(),
    ReactiveFormsModule,
    PopoverModule.forRoot(),
    ConfirmationPopoverModule.forRoot(),
    ModalModule.forRoot(),
    UiSwitchModule,
    AngularMultiSelectModule,
    CKEditorModule
  ],
  declarations: [
    UserRoleListComponent, UserRoleCreateComponent, UserRoleEditComponent
  ],
  entryComponents: [
    UserRoleListComponent, UserRoleCreateComponent, UserRoleEditComponent
  ],
  exports: [
    UserRoleListComponent, UserRoleCreateComponent, UserRoleEditComponent
  ]
})
export class UserRoleModalModule {
}
