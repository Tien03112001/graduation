import {NgModule} from '@angular/core';
import {ModalModule, PaginationModule, PopoverModule, TabsModule} from 'ngx-bootstrap';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {CKEditorModule} from 'ng2-ckeditor';
import {ProductCreateComponent} from './product-create/product-create.component';
import {ProductEditComponent} from './product-edit/product-edit.component';
import {ProductCreateCategoryComponent} from './product-create-category/product-create-category.component';
import {MetaDataModule} from '../meta-data/meta-data.module';
import {PhotoCrudModule} from '../photo-CRUD/photo-crud.module';
import {ArticleModule} from '../article/article.module';
import {ArticleCommentModule} from '../article-comment/article-comment.module';
import {NgSelectModule} from '@ng-select/ng-select';
import {UiSwitchModule} from 'ngx-toggle-switch';
import {StructuredDataModule} from '../structured-data/structured-data.module';
import {GalleryCRUDModule} from '../gallery-CRUD/gallery-crud.module';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    PaginationModule.forRoot(),
    ReactiveFormsModule,
    PopoverModule.forRoot(),
    ConfirmationPopoverModule.forRoot(),
    ModalModule.forRoot(),
    MetaDataModule,
    PhotoCrudModule,
    ArticleModule,
    ArticleCommentModule,
    NgSelectModule,
    CKEditorModule,
    UiSwitchModule,
    StructuredDataModule,
    GalleryCRUDModule,
    TabsModule.forRoot(),
    AngularMultiSelectModule,
  ],
  declarations: [
    ProductCreateComponent, ProductEditComponent, ProductCreateCategoryComponent
  ],
  entryComponents: [
    ProductCreateComponent, ProductEditComponent, ProductCreateCategoryComponent
  ],
  exports: [
    ProductCreateComponent, ProductEditComponent, ProductCreateCategoryComponent
  ]
})
export class ProductModalModule {
}
