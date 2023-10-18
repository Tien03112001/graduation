import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {VideoMeta} from '../video.meta';
import {VideoService} from '../video.service';

@Component({
  selector: 'app-video-create',
  templateUrl: './video-create.component.html',
  styleUrls: ['./video-create.component.css'],
  providers: [VideoService]
})
export class VideoCreateComponent extends AbstractModalComponent<VideoMeta> {
  onDestroy(): void {
  }

  onInit(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      url: new FormControl(null, Validators.required),
    });
  }

  loaded(): void {

  }


  constructor(
    service: VideoService,
    modal: BsModalRef,
    builder: FormBuilder
  ) {
    super(service, modal, builder);
  }

}
