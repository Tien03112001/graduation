import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { StructuredDataPropertyListComponent } from './structured-data-property-list/structured-data-property-list.component';
import { StructuredDataPropertyCreateComponent } from './structured-data-property-create/structured-data-property-create.component';
import { StructuredDataPropertyEditComponent } from './structured-data-property-edit/structured-data-property-edit.component';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { ModalModule, PaginationModule, PopoverModule } from 'ngx-bootstrap';
import { ConfirmationPopoverModule } from 'angular-confirmation-popover';
import { NgSelectModule } from '@ng-select/ng-select';
import { CKEditorModule } from 'ng2-ckeditor';
import { UiSwitchModule } from 'ngx-toggle-switch';
import { AngularMultiSelectModule } from 'angular2-multiselect-dropdown';
import { StructuredDatapropertyModalModule } from './structured-data-property.modal.module';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    PaginationModule.forRoot(),
    ReactiveFormsModule,
    PopoverModule.forRoot(),
    ConfirmationPopoverModule.forRoot(),
    ModalModule.forRoot(),
    NgSelectModule,
    CKEditorModule,
    UiSwitchModule,
    AngularMultiSelectModule,
    StructuredDatapropertyModalModule
  ],
  declarations: [StructuredDataPropertyListComponent, StructuredDataPropertyCreateComponent, StructuredDataPropertyEditComponent],
  entryComponents: [StructuredDataPropertyListComponent, StructuredDataPropertyCreateComponent, StructuredDataPropertyEditComponent],
  exports: [StructuredDataPropertyListComponent, StructuredDataPropertyCreateComponent, StructuredDataPropertyEditComponent]
})
export class StructuredDataPropertyModule {
}
