import {NgModule} from '@angular/core';
import {ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {RouterModule, Routes} from '@angular/router';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {SeoMetaModalModule} from './seo-meta.modal.module';
import {PostSeometaComponent} from './post-seometa/post-seometa.component';
import {StructuredDataModule} from '../structured-data/structured-data.module';
import {PostCategorySeometaComponent} from './post-category-seometa/post-category-seometa.component';
import {MetaDataModule} from '../meta-data/meta-data.module';
import {PostTagSeometaComponent} from './post-tag-seometa/post-tag-seometa.component';
import {ProductSeometaComponent} from './product-seometa/product-seometa.component';
import {ProductCategorySeometaComponent} from './product-category-seometa/product-category-seometa.component';
import {ProductTagSeometaComponent} from './product-tag-seometa/product-tag-seometa.component';
import {PageSeometaComponent} from './page-seometa/page-seometa.component';
import { RelatedProductModule } from '../related-product/related-product.module';
import { RelatedPostModule } from '../related-post/related-post.module';

const routing: Routes = [
  {
    path: 'post',
    component: PostSeometaComponent
  },
  {
    path: 'post-categories',
    component: PostCategorySeometaComponent
  },
  {
    path: 'post-tag',
    component: PostTagSeometaComponent
  },
  {
    path: 'product',
    component: ProductSeometaComponent
  },
  {
    path: 'product-categories',
    component: ProductCategorySeometaComponent
  },
  {
    path: 'product-tag',
    component: ProductTagSeometaComponent
  },
  {
    path: 'pages',
    component: PageSeometaComponent
  },
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
    SeoMetaModalModule,
    StructuredDataModule,
    MetaDataModule,
    RelatedProductModule,
    RelatedPostModule
  ],
  declarations: [
    PostSeometaComponent,PostCategorySeometaComponent,PostTagSeometaComponent,ProductSeometaComponent,
    ProductCategorySeometaComponent,ProductTagSeometaComponent,PageSeometaComponent
  ],
  entryComponents: [],
  exports: []
})
export class SeoMetaModule {
}
