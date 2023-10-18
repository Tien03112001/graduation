import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {VideoMeta} from '../video.meta';
import {VideoService} from '../video.service';


@Component({
  selector: 'app-video-edit',
  templateUrl: './video-edit.component.html',
  styleUrls: ['./video-edit.component.css'],
  providers: [VideoService]
})
export class VideoEditComponent extends AbstractModalComponent<VideoMeta> {

  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      url: new FormControl(null, Validators.required),
    });
  }

  loaded(): void {
    this.formGroup.setValue({
      url: this.model.url,
    });
  }


  constructor(
    service: VideoService,
    modal: BsModalRef,
    builder: FormBuilder
  ) {
    super(service, modal, builder);
  }

}
