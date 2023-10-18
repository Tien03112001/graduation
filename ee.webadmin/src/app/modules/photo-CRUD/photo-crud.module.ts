import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ModalModule} from 'ngx-bootstrap';
import {PhotoCreateComponent} from './photo-create/photo-create.component';
import {PhotoEditComponent} from './photo-edit/photo-edit.component';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {CKEditorModule} from 'ng2-ckeditor';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    ReactiveFormsModule,
    ModalModule.forRoot(),
    AngularMultiSelectModule,
    CKEditorModule,
  ],
  declarations: [PhotoCreateComponent, PhotoEditComponent],
  entryComponents: [PhotoCreateComponent, PhotoEditComponent],
  exports: [PhotoCreateComponent, PhotoEditComponent]
})
export class PhotoCrudModule {
}
