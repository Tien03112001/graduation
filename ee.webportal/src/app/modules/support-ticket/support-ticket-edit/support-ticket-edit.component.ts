import {Component} from '@angular/core';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {SupportTicketMeta} from '../support-ticket.meta';
import {SupportTicketService} from '../support-ticket.service';
import {AbstractModalComponent} from '../../../core/crud';
import {FieldForm} from '../../../core/common';

@Component({
  selector: 'app-support-ticket-edit',
  templateUrl: './support-ticket-edit.component.html',
  styleUrls: ['./support-ticket-edit.component.css'],
  providers: [SupportTicketService]
})
export class SupportTicketEditComponent extends AbstractModalComponent<SupportTicketMeta> {

  ckEditorConfig: any = {
    height: '350px'
  };

  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      title: new FormControl(null, Validators.required),
      content: new FormControl(null, Validators.required),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tiêu đề *', 'title', 'Nhập tiêu đề'),
      FieldForm.createHtmlInput('Nội dung *', 'content', 'Nhập nội dung'),
    ];
  }

  loaded(): void {
  }

  constructor(
    service: SupportTicketService,
    modal: BsModalRef,
    builder: FormBuilder,
  ) {
    super(service, modal, builder);
  }
}
