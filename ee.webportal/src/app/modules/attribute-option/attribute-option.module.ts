import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {UiSwitchModule} from 'ngx-toggle-switch';
import {CKEditorModule} from 'ng2-ckeditor';
import {AttributeOptionListComponent} from './attribute-option-list/attribute-option-list.component';
import {AttributeOptionCreateComponent} from './attribute-option-create/attribute-option-create.component';
import {AttributeOptionEditComponent} from './attribute-option-edit/attribute-option-edit.component';

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
    CKEditorModule
  ],
  declarations: [AttributeOptionListComponent, AttributeOptionCreateComponent, AttributeOptionEditComponent],
  entryComponents: [AttributeOptionListComponent, AttributeOptionCreateComponent, AttributeOptionEditComponent],
  exports: [AttributeOptionListComponent, AttributeOptionCreateComponent, AttributeOptionEditComponent]
})
export class AttributeOptionModule {
}
