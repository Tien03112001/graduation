import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {AttributeTypeListComponent} from './attribute-type-list/attribute-type-list.component';
import {AttributeTypeCreateComponent} from './attribute-type-create/attribute-type-create.component';
import {AttributeTypeEditComponent} from './attribute-type-edit/attribute-type-edit.component';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    PaginationModule.forRoot(),
    ReactiveFormsModule,
    PopoverModule.forRoot(),
    ConfirmationPopoverModule.forRoot(),
    ModalModule.forRoot(),
  ],
  declarations: [AttributeTypeListComponent, AttributeTypeCreateComponent, AttributeTypeEditComponent],
  entryComponents: [AttributeTypeListComponent, AttributeTypeCreateComponent, AttributeTypeEditComponent],
  exports: [AttributeTypeListComponent, AttributeTypeCreateComponent, AttributeTypeEditComponent]
})
export class AttributeTypeModule {
}
