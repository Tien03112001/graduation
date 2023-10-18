import {NgModule} from '@angular/core';
import {ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {CKEditorModule} from 'ng2-ckeditor';
import {GalleryCreateComponent} from './gallery-create/gallery-create.component';
import {GalleryEditComponent} from './gallery-edit/gallery-edit.component';

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
    CKEditorModule,
  ],
  declarations: [
    GalleryCreateComponent, GalleryEditComponent
  ],
  entryComponents: [
    GalleryCreateComponent, GalleryEditComponent
  ],
  exports: [
    GalleryCreateComponent, GalleryEditComponent
  ]
})
export class GalleryModalModule {
}
