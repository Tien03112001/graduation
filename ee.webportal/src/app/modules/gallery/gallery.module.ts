import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {RouterModule, Routes} from '@angular/router';
import {PhotoCrudModule} from '../photo-CRUD/photo-crud.module';
import {UiSwitchModule} from 'ngx-toggle-switch';
import {GalleryCRUDModule} from '../gallery-CRUD/gallery-crud.module';
import {GalleryListComponent} from './gallery-list/gallery-list.component';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {GalleryModalModule} from './gallery.modal.module';

const routing: Routes = [
  {
    path: '',
    component: GalleryListComponent
  }
];

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    ReactiveFormsModule,
    PaginationModule.forRoot(),
    PopoverModule.forRoot(),
    ModalModule.forRoot(),
    ConfirmationPopoverModule.forRoot(),
    RouterModule.forChild(routing),
    PhotoCrudModule,
    UiSwitchModule,
    GalleryCRUDModule,
    AngularMultiSelectModule,
    GalleryModalModule
  ],
  declarations: [GalleryListComponent],
  entryComponents: [],
  exports: []
})
export class GalleryModule {
}
