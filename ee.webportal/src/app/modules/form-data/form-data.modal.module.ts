import {NgModule} from '@angular/core';
import {ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {CKEditorModule} from 'ng2-ckeditor';
import {FormDataCreateComponent} from './form-data-create/form-data-create.component';
import {FormDataEditComponent} from './form-data-edit/form-data-edit.component';

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
    FormDataCreateComponent,FormDataEditComponent
  ],
  entryComponents: [
    FormDataCreateComponent,FormDataEditComponent
  ],
  exports: [
    FormDataCreateComponent,FormDataEditComponent
  ]
})
export class FormDataModalModule {
}
