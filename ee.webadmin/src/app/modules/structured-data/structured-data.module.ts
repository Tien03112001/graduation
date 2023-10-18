import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {StructuredDataListComponent} from './structured-data-list/structured-data-list.component';
import {StructuredDataCreateComponent} from './structured-data-create/structured-data-create.component';
import {StructuredDataEditComponent} from './structured-data-edit/structured-data-edit.component';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {NgSelectModule} from '@ng-select/ng-select';
import {CKEditorModule} from 'ng2-ckeditor';
import {UiSwitchModule} from 'ngx-toggle-switch';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';

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
    ],
  declarations: [StructuredDataListComponent, StructuredDataCreateComponent, StructuredDataEditComponent],
  entryComponents: [StructuredDataListComponent, StructuredDataCreateComponent, StructuredDataEditComponent],
  exports: [StructuredDataListComponent, StructuredDataCreateComponent, StructuredDataEditComponent]
})
export class StructuredDataModule {
}
