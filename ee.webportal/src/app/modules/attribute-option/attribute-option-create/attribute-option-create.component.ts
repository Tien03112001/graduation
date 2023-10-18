import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {AttributeOptionMeta} from '../attribute-option.meta';
import {AttributeOptionService} from '../attribute-option.service';

@Component({
  selector: 'app-attribute-option-create',
  templateUrl: './attribute-option-create.component.html',
  styleUrls: ['./attribute-option-create.component.css'],
  providers: [AttributeOptionService]
})
export class AttributeOptionCreateComponent extends AbstractModalComponent<AttributeOptionMeta> {

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
      content_attribute_id: new FormControl(null, Validators.required),
      color_code: new FormControl(null)
    });
  }

  loaded(): void {
    this.formGroup.controls['content_attribute_id'].setValue(this.model.content_attribute_id);
  }


  constructor(
    service: AttributeOptionService,
    modal: BsModalRef,
    builder: FormBuilder
  ) {
    super(service, modal, builder);
  }


}
