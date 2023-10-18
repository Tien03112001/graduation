import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {AttributeOptionService} from '../attribute-option.service';
import {AttributeOptionMeta} from '../attribute-option.meta';

@Component({
  selector: 'app-attribute-option-edit',
  templateUrl: './attribute-option-edit.component.html',
  styleUrls: ['./attribute-option-edit.component.css'],
  providers: [AttributeOptionService]
})
export class AttributeOptionEditComponent extends AbstractModalComponent<AttributeOptionMeta> {

  ckEditorConfig: any = {
    height: '200px'
  };

  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      option: new FormControl(null, Validators.required),
      color_code: new FormControl(null)
    });
  }


  loaded(): void {
    this.formGroup.setValue({
      option: this.model.option,
      color_code: this.model.color_code
    });
  }

  constructor(
    service: AttributeOptionService,
    modal: BsModalRef,
    builder: FormBuilder
  ) {
    super(service, modal, builder);
  }

}
