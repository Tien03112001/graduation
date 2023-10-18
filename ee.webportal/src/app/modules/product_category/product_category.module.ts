import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {Product_categoryListComponent} from './product_category-list/product_category-list.component';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ModalModule, PaginationModule, PopoverModule, TabsModule} from 'ngx-bootstrap';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {RouterModule, Routes} from '@angular/router';
import {MetaDataModule} from '../meta-data/meta-data.module';
import {NgSelectModule} from '@ng-select/ng-select';
import {PhotoCrudModule} from '../photo-CRUD/photo-crud.module';
import {UiSwitchModule} from 'ngx-toggle-switch';
import {CKEditorModule} from 'ng2-ckeditor';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {ProductCategoryModalModule} from './product_category.modal.module';
import {ArticleModule} from '../article/article.module';
import {ArticleCommentModule} from '../article-comment/article-comment.module';

const routing: Routes = [
  {
    path: '',
    component: Product_categoryListComponent
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
    MetaDataModule,
    ArticleModule,
    ArticleCommentModule,
    PhotoCrudModule,
    NgSelectModule,
    CKEditorModule,
    UiSwitchModule,
    AngularMultiSelectModule,
    TabsModule.forRoot(),
    ProductCategoryModalModule,
  ],
  declarations: [Product_categoryListComponent],
  entryComponents: [ ]
})
export class Product_categoryModule {
}
