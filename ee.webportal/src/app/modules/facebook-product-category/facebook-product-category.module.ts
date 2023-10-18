import {NgModule} from '@angular/core';
import {ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {RouterModule, Routes} from '@angular/router';
import {FacebookProductCategoryListComponent} from './facebook-product-category-list.component';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {MomentModule} from 'ngx-moment';
import {FacebookProductCategoryImportComponent} from './facebook-product-category-import/facebook-product-category-import.component';

const routing: Routes = [
  {
    path: '',
    component: FacebookProductCategoryListComponent
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
    MomentModule,
  ],
  declarations: [
    FacebookProductCategoryListComponent,
    FacebookProductCategoryImportComponent
  ],
  entryComponents: [
    FacebookProductCategoryImportComponent
  ],
  exports: []
})
export class FacebookProductCategoryModule {
}
