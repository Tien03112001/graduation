import {NgModule} from '@angular/core';
import {ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {RouterModule, Routes} from '@angular/router';
import {ProductListComponent} from './product-list.component';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {MomentModule} from 'ngx-moment';
import {InventoryProductModule} from '../inventory-product/inventory-product.module';
import {ProductCreateComponent} from './product-create/product-create.component';

const routing: Routes = [
  {
    path: '',
    component: ProductListComponent
  }
];

@NgModule({
  imports: [
    RouterModule.forChild(routing),
    CommonModule,
    FormsModule,
    PaginationModule.forRoot(),
    ReactiveFormsModule,
    PopoverModule.forRoot(),
    ConfirmationPopoverModule.forRoot(),
    ModalModule.forRoot(),
    AngularMultiSelectModule,
    MomentModule,
    InventoryProductModule
  ],
  declarations: [
    ProductListComponent,
    ProductCreateComponent
  ],
  entryComponents: [
    ProductCreateComponent
  ],
  exports: []
})
export class ProductModule {
}
