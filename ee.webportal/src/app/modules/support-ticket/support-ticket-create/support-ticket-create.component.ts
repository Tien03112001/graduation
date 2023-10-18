import {Component} from '@angular/core';
import {SupportTicketMeta} from '../support-ticket.meta';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef, BsModalService} from 'ngx-bootstrap';
import {SupportTicketService} from '../support-ticket.service';
import {AbstractModalComponent} from '../../../core/crud';
import {FieldForm} from '../../../core/common';

@Component({
  selector: 'app-support-ticket-create',
  templateUrl: './support-ticket-create.component.html',
  styleUrls: ['./support-ticket-create.component.css'],
  providers: [SupportTicketService, BsModalService]
})
export class SupportTicketCreateComponent extends AbstractModalComponent<SupportTicketMeta> {
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
      ended_at: new FormControl(null, Validators.required)
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tiêu đề *', 'title', 'Nhập tiêu đề'),
      FieldForm.createHtmlInput('Nội dung *', 'content', 'Nhập nội dung'),
      FieldForm.createDateInput('Ngày hết hạn *', 'ended_at', 'Chọn ngày hết hạn'),
    ];
  }

  loaded(): void {
  }

  constructor(
    service: SupportTicketService,
    modal: BsModalRef,
    builder: FormBuilder,
    private modalService: BsModalService,
  ) {
    super(service, modal, builder);
  }
}
