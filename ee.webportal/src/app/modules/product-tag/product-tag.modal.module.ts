import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {ProductTagCreateComponent} from './product-tag-create/product-tag-create.component';
import {ProductTagEditComponent} from './product-tag-edit/product-tag-edit.component';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {MetaDataModule} from '../meta-data/meta-data.module';
import {NgSelectModule} from '@ng-select/ng-select';
import {PhotoCrudModule} from '../photo-CRUD/photo-crud.module';
import {ProductTagAssignComponent} from './product-tag-assign/product-tag-assign.component';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {CKEditorModule} from 'ng2-ckeditor';


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
    MetaDataModule,
    PhotoCrudModule,
    AngularMultiSelectModule,
    CKEditorModule,
  ],
  declarations: [
    ProductTagCreateComponent,
    ProductTagEditComponent,
    ProductTagAssignComponent
  ],
  entryComponents: [
    ProductTagCreateComponent,
    ProductTagEditComponent,
    ProductTagAssignComponent
  ],
  exports: [
    ProductTagCreateComponent,
    ProductTagEditComponent,
    ProductTagAssignComponent
  ]
})
export class ProductTagModalModule {
}
