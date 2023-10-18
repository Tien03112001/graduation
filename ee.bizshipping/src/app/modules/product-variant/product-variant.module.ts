import {NgModule} from '@angular/core';
import {ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {RouterModule, Routes} from '@angular/router';
import {ProductVariantListComponent} from './product-variant-list.component';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {MomentModule} from 'ngx-moment';
import { ProductVariantEditComponent } from './product-variant-edit/product-variant-edit.component';

const routing: Routes = [
  {
    path: '',
    component: ProductVariantListComponent
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
    MomentModule
  ],
  declarations: [
    ProductVariantListComponent,
    ProductVariantEditComponent
  ],
  entryComponents: [
    ProductVariantEditComponent
  ],
  exports: []
})
export class ProductVariantModule {
}
