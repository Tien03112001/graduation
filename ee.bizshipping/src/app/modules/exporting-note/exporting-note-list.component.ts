import {Component} from '@angular/core';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
// import {TitleService} from '../../services';
// import {AbstractCRUDComponent, FieldForm} from '../../classes';
import {ExportingNoteMeta} from './exporting-note.meta';
import {ExportingNoteService} from './exporting-note.service';
import { AbstractCRUDComponent, FieldForm, TitleService } from '../../core';
import { DefinitionService } from '../definition/definition.service';

@Component({
  selector: 'app-exporting-note-list',
  templateUrl: './exporting-note-list.component.html',
  styleUrls: ['./exporting-note-list.component.css'],
  providers: [ExportingNoteService, DefinitionService],
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
    return null;
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
      this.load();
    }, () => this.service.toastFailed('In phiếu xuất kho'));
  }
}
