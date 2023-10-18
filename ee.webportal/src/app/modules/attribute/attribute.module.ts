import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {BsDatepickerModule, ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {RouterModule, Routes} from '@angular/router';
import {NgSelectModule} from '@ng-select/ng-select';
import {CKEditorModule} from 'ng2-ckeditor';
import {UiSwitchModule} from 'ngx-toggle-switch';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {AttributeComponent} from './attribute-list/attribute-list.component';
import {AttributeCreateComponent} from './attribute-create/attribute-create.component';
import {AttributeEditComponent} from './attribute-edit/attribute-edit.component';
import {AttributeOptionModule} from '../attribute-option/attribute-option.module';

const routing: Routes = [
  {
    path: '',
    component: AttributeComponent
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
    NgSelectModule,
    CKEditorModule,
    UiSwitchModule,
    AngularMultiSelectModule,
    BsDatepickerModule.forRoot(),
    AttributeOptionModule
  ],
  declarations: [AttributeComponent, AttributeCreateComponent, AttributeEditComponent],
  entryComponents: [AttributeCreateComponent, AttributeEditComponent]
})
export class AttributeModule {
}
