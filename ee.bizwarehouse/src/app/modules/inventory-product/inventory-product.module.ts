import {NgModule} from '@angular/core';
import {ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {InventoryProductCreateComponent} from './inventory-product-create/inventory-product-create.component';
import {InventoryProductImportComponent} from './inventory-product-import/inventory-product-import.component';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {MomentModule} from 'ngx-moment';
import {InventoryProductEditComponent} from './inventory-product-edit/inventory-product-edit.component';
import { InventoryProductListComponent } from './inventory-product-list/inventory-product-list.component';
import { InventoryProductAddComponent } from './inventory-product-add/inventory-product-add.component';


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
    MomentModule
  ],
  declarations: [
    InventoryProductListComponent,
    InventoryProductCreateComponent,
    InventoryProductImportComponent,
    InventoryProductAddComponent,
    InventoryProductEditComponent
  ],
  entryComponents: [
    InventoryProductListComponent,
    InventoryProductCreateComponent,
    InventoryProductImportComponent,
    InventoryProductAddComponent,
    InventoryProductEditComponent
  ],
  exports: [
   InventoryProductListComponent,
    InventoryProductCreateComponent,
    InventoryProductImportComponent,
    InventoryProductAddComponent,
    InventoryProductEditComponent
  ]
})
export class InventoryProductModule {
}
