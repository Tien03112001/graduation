import {NgModule} from '@angular/core';
import {ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {FanpageCreateComponent} from './fanpage-create/fanpage-create.component';
import {FanpageEditComponent} from './fanpage-edit/fanpage-edit.component';
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
    FanpageCreateComponent, FanpageEditComponent
  ],
  entryComponents: [
    FanpageCreateComponent, FanpageEditComponent
  ],
  exports: [
    FanpageCreateComponent, FanpageEditComponent
  ]
})
export class FanpageModalModule {
}
