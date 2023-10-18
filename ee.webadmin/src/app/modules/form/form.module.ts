import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {FormListComponent} from './form-list/form-list.component';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {RouterModule, Routes} from '@angular/router';
import {UiSwitchModule} from 'ngx-toggle-switch';
import {FormCreateComponent} from './form-create/form-create.component';
import {FormEditComponent} from './form-edit/form-edit.component';
import {FormAttributeCreateComponent} from '../form-attribute/form-attribute-create/form-attribute-create.component';

const routing: Routes = [
  {
    path: '',
    component: FormListComponent
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
    UiSwitchModule,
  ],
  declarations: [FormListComponent, FormCreateComponent, FormEditComponent, FormAttributeCreateComponent],
  entryComponents: [FormCreateComponent, FormEditComponent, FormAttributeCreateComponent]
})
export class FormModule {
}
