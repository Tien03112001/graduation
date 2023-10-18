import {NgModule} from '@angular/core';
import {ModalModule, PaginationModule, PopoverModule, TabsModule} from 'ngx-bootstrap';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {CKEditorModule} from 'ng2-ckeditor';
import {MetaDataModule} from '../meta-data/meta-data.module';
import {PhotoCrudModule} from '../photo-CRUD/photo-crud.module';
import {NgSelectModule} from '@ng-select/ng-select';
import {UiSwitchModule} from 'ngx-toggle-switch';
import {Product_categoryCreateComponent} from './product_category-create/product_category-create.component';
import {Product_categoryEditComponent} from './product_category-edit/product_category-edit.component';
import {ArticleModule} from '../article/article.module';
import {ArticleCommentModule} from '../article-comment/article-comment.module';

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
    ArticleModule,
    ArticleCommentModule,
    PhotoCrudModule,
    NgSelectModule,
    CKEditorModule,
    UiSwitchModule,
    AngularMultiSelectModule,
    TabsModule.forRoot(),
  ],
  declarations: [
    Product_categoryCreateComponent,Product_categoryEditComponent
  ],
  entryComponents: [
    Product_categoryCreateComponent,Product_categoryEditComponent
  ],
  exports: [
    Product_categoryCreateComponent,Product_categoryEditComponent
  ]
})
export class ProductCategoryModalModule {
}
