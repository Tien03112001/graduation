import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {RouterModule, Routes} from '@angular/router';
import {NgSelectModule} from '@ng-select/ng-select';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {PhotoListComponent} from './photo-list/photo-list.component';
import {PhotoCrudModule} from '../photo-CRUD/photo-crud.module';

const routing: Routes = [
  {
    path: '',
    component: PhotoListComponent
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
    AngularMultiSelectModule,
    PhotoCrudModule
  ],
  declarations: [PhotoListComponent]
})
export class PhotoModule {
}
