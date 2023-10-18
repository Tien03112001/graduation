import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {ProductTagListComponent} from './product-tag-list/product-tag-list.component';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {RouterModule, Routes} from '@angular/router';
import {MetaDataModule} from '../meta-data/meta-data.module';
import {NgSelectModule} from '@ng-select/ng-select';
import {PhotoCrudModule} from '../photo-CRUD/photo-crud.module';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {CKEditorModule} from 'ng2-ckeditor';
import {ProductTagModalModule} from './product-tag.modal.module';

const routing: Routes = [
  {
    path: '',
    component: ProductTagListComponent
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
    MetaDataModule,
    PhotoCrudModule,
    AngularMultiSelectModule,
    CKEditorModule,
    ProductTagModalModule
  ],
  declarations: [
    ProductTagListComponent,

  ],
  entryComponents: [],
  exports: []
})
export class ProductTagModule {
}
