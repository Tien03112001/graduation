import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ProductVariantListComponent } from './product-variant-list/product-variant-list.component';
import { ProductVariantCreateComponent } from './product-variant-create/product-variant-create.component';
import { ProductVariantEditComponent } from './product-variant-edit/product-variant-edit.component';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { ModalModule, PaginationModule, PopoverModule } from 'ngx-bootstrap';
import { ConfirmationPopoverModule } from 'angular-confirmation-popover';
import { NgSelectModule } from '@ng-select/ng-select';
import { CKEditorModule } from 'ng2-ckeditor';
import { UiSwitchModule } from 'ngx-toggle-switch';
import { AngularMultiSelectModule } from 'angular2-multiselect-dropdown';
import { ProductVariantModalModule } from './product-variant.modal.module';

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
    ProductVariantModalModule
  ],
  declarations: [ProductVariantListComponent, ProductVariantCreateComponent, ProductVariantEditComponent],
  entryComponents: [ProductVariantListComponent, ProductVariantCreateComponent, ProductVariantEditComponent],
  exports: [ProductVariantListComponent, ProductVariantCreateComponent, ProductVariantEditComponent]
})
export class ProductVariantModule {
}
