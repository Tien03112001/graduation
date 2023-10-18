import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ModalModule} from 'ngx-bootstrap';
import {VideoCreateComponent} from './video-create/video-create.component';
import {VideoEditComponent} from './video-edit/video-edit.component';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    ReactiveFormsModule,
    ModalModule.forRoot(),
  ],
  declarations: [VideoCreateComponent, VideoEditComponent],
  entryComponents: [VideoCreateComponent, VideoEditComponent],
  exports: [VideoCreateComponent, VideoEditComponent]
})
export class VideoModule {
}
