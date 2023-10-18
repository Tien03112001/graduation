import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {PostTagListComponent} from './post-tag-list/post-tag-list.component';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {RouterModule, Routes} from '@angular/router';
import {MetaDataModule} from '../meta-data/meta-data.module';
import {NgSelectModule} from '@ng-select/ng-select';
import {PhotoCrudModule} from '../photo-CRUD/photo-crud.module';
import {PostTagModalModule} from './post-tag.modal.module';

const routing: Routes = [
  {
    path: '',
    component: PostTagListComponent
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
    PostTagModalModule
  ],
  declarations: [
    PostTagListComponent,
  ],
  entryComponents: []
})
export class PostTagModule {
}
