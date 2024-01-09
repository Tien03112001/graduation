import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {OrderListComponent} from './order-list/order-list.component';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {CollapseModule, ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {RouterModule, Routes} from '@angular/router';
import {MetaDataModule} from '../meta-data/meta-data.module';
import {NgSelectModule} from '@ng-select/ng-select';
import {CKEditorModule} from 'ng2-ckeditor';
import {ArticleModule} from '../article/article.module';
import {UiSwitchModule} from 'ngx-toggle-switch';
import {ArticleCommentModule} from '../article-comment/article-comment.module';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {StructuredDataModule} from '../structured-data/structured-data.module';
import {OrderModalModule} from './order.modal.module';

const routing: Routes = [
  {
    path: '',
    component: OrderListComponent
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
    AngularMultiSelectModule,
    MetaDataModule,
    ArticleModule,
    ArticleCommentModule,
    StructuredDataModule,
    NgSelectModule,
    CKEditorModule,
    UiSwitchModule,
    CollapseModule,
    OrderModalModule,
  ],
  declarations: [
    OrderListComponent,
  ],
  entryComponents: [],
  exports: []
})
export class OrderModule {
}
