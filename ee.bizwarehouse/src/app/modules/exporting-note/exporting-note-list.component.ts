import {Component} from '@angular/core';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {ExportingNoteMeta} from './exporting-note.meta';
import {ExportingNoteService} from './exporting-note.service';
import {ExportingNoteCreateComponent} from './exporting-note-create/exporting-note-create.component';
import { AbstractCRUDComponent, FieldForm, TitleService } from '../../core';

@Component({
  selector: 'app-exporting-note-list',
  templateUrl: './exporting-note-list.component.html',
  styleUrls: ['./exporting-note-list.component.css'],
  providers: [ExportingNoteService],
})
export class ExportingNoteListComponent extends AbstractCRUDComponent<ExportingNoteMeta> {

  onInit(): void {
    this.load();
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Quản lý phiếu xuất kho';
  }

  getCreateModalComponent(): any {
    return ExportingNoteCreateComponent;
  }

  getEditModalComponent(): any {
    return null;
  }

  getCreateModalComponentOptions(): ModalOptions {
    return {class: 'modal-huge', backdrop: 'static', keyboard: false};
  }

  getEditModalComponentOptions(): ModalOptions {
    return {backdrop: 'static', keyboard: false};
  }

  initSearchForm(): FieldForm[] {
    return [
      {
        label: 'Tìm kiếm theo tên phiếu',
        type: 'input',
        typeof: 'text',
        formControl: 'search',
        placeHolder: 'Nhập tên cần tìm kiếm',
        data: [],
        class: 'col-md-6'
      },
    ];
  }

  buildSearchForm(): FormGroup {
    return this.formBuilder.group({
      search: new FormControl(null),
    });
  }

  initNewModel(): ExportingNoteMeta {
    return new ExportingNoteMeta();
  }

  constructor(
    service: ExportingNoteService,
    modal: BsModalService,
    title: TitleService,
    builder: FormBuilder
  ) {
    super(service, modal, builder);
  }

  printBill(item: ExportingNoteMeta) {
    (<ExportingNoteService>this.service).printBill(item.id).subscribe(res => {
      this.service.toastSuccessfully('In phiếu xuất kho');
      if (res['link']) {
        window.open(res['link']);
      }
      if (res['src']) {
        var win = window.open('', 'In phiếu xuất kho', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=780,height=200,top=' + (screen.height - 400) + ',left=' + (screen.width - 840));
        win.document.body.innerHTML = res['src'];
      }
    }, () => this.service.toastFailed('In phiếu xuất kho'));
  }

  approve(item: ExportingNoteMeta, i: number) {
    let confirmed = window.confirm('Xác nhận duyệt phiếu này?');
    if (confirmed) {
      (<ExportingNoteService>this.service).approve(item.id).subscribe((val) => {
        this.list[i] = val;
        this.service.toastSuccessfully('Duyệt');
      }, () => {
        this.service.toastFailed('Duyệt');
      });
    }

  }

  disapprove(item: ExportingNoteMeta, i: number) {
    let confirmed = window.confirm('Xác nhận hủy duyệt phiếu này?');
    if (confirmed) {
      (<ExportingNoteService>this.service).disapprove(item.id).subscribe((val) => {
        this.list[i] = val;
        this.service.toastSuccessfully('Hủy duyệt');
      }, () => {
        this.service.toastFailed('Hủy duyệt');
      });
    }
  }
}
