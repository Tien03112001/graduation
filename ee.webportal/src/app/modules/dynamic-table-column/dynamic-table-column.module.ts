import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {MenuModule} from '../menu/menu.module';
import {DynamicTableColumnCreateComponent} from './dynamic-table-column-create/dynamic-table-column-create.component';
import {DynamicTableColumnEditComponent} from './dynamic-table-column-edit/dynamic-table-column-edit.component';


@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    PaginationModule.forRoot(),
    ReactiveFormsModule,
    PopoverModule.forRoot(),
    ConfirmationPopoverModule.forRoot(),
    ModalModule.forRoot(),
    MenuModule
  ],
  declarations: [DynamicTableColumnCreateComponent, DynamicTableColumnEditComponent],
  entryComponents: [DynamicTableColumnCreateComponent, DynamicTableColumnEditComponent],
  exports: [DynamicTableColumnCreateComponent, DynamicTableColumnEditComponent]

})
export class DynamicTableColumnModule {
}
