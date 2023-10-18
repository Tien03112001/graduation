import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {EmbedCodeMeta} from '../embed-code.meta';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {EmbedCodeService} from '../embed-code.service';

@Component({
  selector: 'app-embed-code-create',
  templateUrl: './embed-code-create.component.html',
  styleUrls: ['./embed-code-create.component.css'],
  providers: [EmbedCodeService]
})
export class EmbedCodeCreateComponent extends AbstractModalComponent<EmbedCodeMeta> {

  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      name: new FormControl(null, Validators.required),
      type: new FormControl(1, Validators.required),
      code: new FormControl(null, Validators.required),
    });
  }

  loaded(): void {
  }


  constructor(
    service: EmbedCodeService,
    modal: BsModalRef,
    builder: FormBuilder
  ) {
    super(service, modal, builder);
  }

}
