import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {StructuredDataTypeListComponent} from './structured-data-type-list/structured-data-type-list.component';
import {StructuredDataTypeCreateComponent} from './structured-data-type-create/structured-data-type-create.component';
import {StructuredDataTypeEditComponent} from './structured-data-type-edit/structured-data-type-edit.component';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {RouterModule, Routes} from '@angular/router';
import {StructuredDataPropertyListComponent} from '../structured-data-property/structured-data-property-list/structured-data-property-list.component';
import { StructuredDataTypeModalModule } from './structured-data-type.modal.module';
import { StructuredDataPropertyModule } from '../structured-data-property/structured-data-property.module';

const routing: Routes = [
  {
    path: '',
    component: StructuredDataTypeListComponent
  }
];

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    ReactiveFormsModule,
    PaginationModule.forRoot(),
    PopoverModule.forRoot(),
    ModalModule.forRoot(),
    ConfirmationPopoverModule.forRoot(),
    RouterModule.forChild(routing),
    StructuredDataTypeModalModule,
    StructuredDataPropertyModule,
  ],
  declarations: [StructuredDataTypeListComponent, StructuredDataTypeCreateComponent, StructuredDataTypeEditComponent],
  entryComponents: [StructuredDataTypeCreateComponent, StructuredDataTypeEditComponent]
})
export class StructuredDataTypeModule {
}
