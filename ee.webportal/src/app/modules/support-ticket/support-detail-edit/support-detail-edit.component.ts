import {Component} from '@angular/core';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {SupportTicketMeta} from '../support-ticket.meta';
import {SupportTicketService} from '../support-ticket.service';
import {AbstractModalComponent} from '../../../core/crud';
import {FieldForm} from '../../../core/common';

@Component({
  selector: 'app-support-ticket-edit',
  templateUrl: './support-detail-edit.component.html',
  styleUrls: ['./support-detail-edit.component.css'],
  providers: [SupportTicketService]
})
export class SupportDetailEditComponent extends AbstractModalComponent<SupportTicketMeta> {

  ckEditorConfig: any = {
    height: '350px'
  };

  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      content: new FormControl(null, Validators.required),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
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
