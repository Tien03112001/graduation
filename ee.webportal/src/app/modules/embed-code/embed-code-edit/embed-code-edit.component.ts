import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {EmbedCodeMeta} from '../embed-code.meta';
import {EmbedCodeService} from '../embed-code.service';

@Component({
  selector: 'app-embed-code-edit',
  templateUrl: './embed-code-edit.component.html',
  styleUrls: ['./embed-code-edit.component.css'],
  providers: [EmbedCodeService]
})
export class EmbedCodeEditComponent extends AbstractModalComponent<EmbedCodeMeta> {

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
    this.formGroup.setValue({
      name: this.model.name,
      type: this.model.type,
      code: this.model.code,
    });
  }

  constructor(
    service: EmbedCodeService,
    modal: BsModalRef,
    builder: FormBuilder
  ) {
    super(service, modal, builder);
  }


}
