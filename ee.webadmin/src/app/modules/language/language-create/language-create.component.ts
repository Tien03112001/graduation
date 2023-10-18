import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {LanguageMeta} from '../language.meta';
import {LanguageService} from '../language.service';

@Component({
  selector: 'app-language-create',
  templateUrl: './language-create.component.html',
  styleUrls: ['./language-create.component.css'],
  providers: [LanguageService]
})
export class LanguageCreateComponent extends AbstractModalComponent<LanguageMeta> {

  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      name: new FormControl(null, Validators.required),
      value: new FormControl(null, Validators.required),
    });
  }

  loaded(): void {
  }


  constructor(
    service: LanguageService,
    modal: BsModalRef,
    builder: FormBuilder
  ) {
    super(service, modal, builder);
  }

}
