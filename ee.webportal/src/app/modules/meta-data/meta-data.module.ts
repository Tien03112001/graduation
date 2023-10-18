import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ModalModule} from 'ngx-bootstrap';
import {MetaDataCreateComponent} from './meta-data-create/meta-data-create.component';
import {MetaDataEditComponent} from './meta-data-edit/meta-data-edit.component';
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
  declarations: [MetaDataCreateComponent, MetaDataEditComponent],
  entryComponents: [MetaDataCreateComponent, MetaDataEditComponent],
  exports: [MetaDataCreateComponent, MetaDataEditComponent]
})
export class MetaDataModule {
}
