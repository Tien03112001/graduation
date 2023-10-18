import { Component } from '@angular/core';
import { FormBuilder, FormControl, FormGroup, Validators } from '@angular/forms';
import { BsModalRef } from 'ngx-bootstrap';
import { FieldForm } from '../../../core/common';
import { AbstractModalComponent } from '../../../core/crud';
import { TitleService } from '../../../core/services';
import { AgentPageMeta } from '../agent-page.meta';
import { AgentPageService } from '../agent-page.service';

@Component({
  selector: 'app-agent-page-edit',
  templateUrl: './agent-page-edit.component.html',
  styleUrls: ['./agent-page-edit.component.css'],
  providers: [AgentPageService]
})
export class AgentPageEditComponent extends AbstractModalComponent<AgentPageMeta> {

  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      name: new FormControl(null, Validators.required),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tên đầy đủ', 'name', 'Nhập ký tự'),
    ];
  }

  loaded(): void {
  }

  constructor(
    service: AgentPageService,
    modal: BsModalRef,
    title: TitleService,
    builder: FormBuilder,
  ) {
    super(service, modal, builder);
  }

}
