import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {OrderProductCreateComponent} from './order-product-create/order-product-create.component';
import {OrderProductEditComponent} from './order-product-edit/order-product-edit.component';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {CollapseModule, ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {MetaDataModule} from '../meta-data/meta-data.module';
import {NgSelectModule} from '@ng-select/ng-select';
import {CKEditorModule} from 'ng2-ckeditor';
import {ArticleModule} from '../article/article.module';
import {UiSwitchModule} from 'ngx-toggle-switch';
import {ArticleCommentModule} from '../article-comment/article-comment.module';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {StructuredDataModule} from '../structured-data/structured-data.module';
import {OrderProductListComponent} from './order-product-list/order-product-list.component';


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
    MetaDataModule,
    ArticleModule,
    ArticleCommentModule,
    StructuredDataModule,
    NgSelectModule,
    CKEditorModule,
    UiSwitchModule,
    CollapseModule,
  ],
  declarations: [
    OrderProductCreateComponent,
    OrderProductEditComponent,
  ],
  entryComponents: [
    OrderProductCreateComponent,
    OrderProductEditComponent,
  ],
  exports: [
    OrderProductCreateComponent,
    OrderProductEditComponent,
  ]
})
export class OrderProductModalModule {
}
