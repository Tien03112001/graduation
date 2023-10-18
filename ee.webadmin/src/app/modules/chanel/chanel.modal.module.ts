import {NgModule} from '@angular/core';
import {ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {ChanelCreateComponent} from './chanel-create/chanel-create.component';
import {ChanelEditComponent} from './chanel-edit/chanel-edit.component';
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
    ChanelCreateComponent, ChanelEditComponent
  ],
  entryComponents: [
    ChanelCreateComponent, ChanelEditComponent
  ],
  exports: [
    ChanelCreateComponent, ChanelEditComponent
  ]
})
export class ChanelModalModule {
}
