import {Component} from '@angular/core';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {ExportingNoteService} from '../exporting-note.service';
import {ExportingNoteMeta} from '../exporting-note.meta';
import { AbstractModalComponent, FieldForm } from '../../../core';

@Component({
  selector: 'app-exporting-note-create',
  templateUrl: './exporting-note-create.component.html',
  styleUrls: ['./exporting-note-create.component.css'],
  providers: [ExportingNoteService]
})
export class ExportingNoteCreateComponent extends AbstractModalComponent<ExportingNoteMeta> {

  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      name: new FormControl(null, [Validators.required]),
      details: new FormControl(null, [Validators.required]),
      description: new FormControl(null),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      {
        label: 'Tên phiếu',
        type: 'input',
        typeof: 'text',
        formControl: 'name',
        placeHolder: 'Nhập tên'
      },
      {
        label: 'Mô tả',
        type: 'textarea',
        typeof: 'text',
        formControl: 'description',
        placeHolder: 'Nhập mô tar',
        config: {
          rows: 2
        }
      },
      {
        label: 'Dữ liệu xuất kho',
        type: 'textarea',
        typeof: 'text',
        formControl: 'details',
        placeHolder: 'Mã_sp Size Số_lượng',
        config: {
          rows: 20
        }
      },
    ];
  }

  loaded(): void {
  }

  constructor(
    service: ExportingNoteService,
    modal: BsModalRef,
    builder: FormBuilder,
  ) {
    super(service, modal, builder);
  }

}
