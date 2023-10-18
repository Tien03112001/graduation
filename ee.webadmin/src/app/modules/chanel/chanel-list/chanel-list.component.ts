import {Component} from '@angular/core';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {ChanelMeta} from '../chanel.meta';
import {ChanelService} from '../chanel.service';
import {ChanelCreateComponent} from '../chanel-create/chanel-create.component';
import {ChanelEditComponent} from '../chanel-edit/chanel-edit.component';
import { AbstractCRUDComponent, AbstractModalComponent } from '../../../core/crud';
import { FieldForm, ModalResult } from '../../../core/common';
import { TitleService } from '../../../core/services';
import { ObjectUtil } from '../../../core/utils';

@Component({
  selector: 'app-chanel-list',
  templateUrl: './chanel-list.component.html',
  styleUrls: ['./chanel-list.component.css'],
  providers: [ChanelService]
})
export class ChanelListComponent extends AbstractCRUDComponent<ChanelMeta> {

  onInit(): void {
    this.load();
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Danh sách kênh';
  }

  getCreateModalComponent(): any {
    return ChanelCreateComponent;
  }

  getEditModalComponent(): any {
    return ChanelEditComponent;
  }

  getCreateModalComponentOptions(): ModalOptions {
    return null;
  }

  getEditModalComponentOptions(): ModalOptions {
    return null;
  }

  buildSearchForm(): FormGroup {
    return this.formBuilder.group({
      search: new FormControl(null, Validators.maxLength(255)),
    });
  }

  initSearchForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tìm kiếm theo tên', 'search', 'Nhập từ khóa')
    ];
  }

  initNewModel(): ChanelMeta {
    return new ChanelMeta();
  }

  constructor(
    service: ChanelService,
    modal: BsModalService,
    title: TitleService,
    builder: FormBuilder,
  ) {
    super(service, modal, builder,);
  }

  public createChanel() {
    let modalOptions = Object.assign(this.defaultModalOptions(), this.getCreateModalComponentOptions());
    const config = ObjectUtil.combineValue({ ignoreBackdropClick: true }, modalOptions);
    const modalRef = this.modalService.show(this.getCreateModalComponent(), config);
    let modal: AbstractModalComponent<ChanelMeta> = <AbstractModalComponent<ChanelMeta>>modalRef.content;
    modal.setModel(this.initNewModel());
    let sub = modal.onHidden.subscribe((result: ModalResult<ChanelMeta>) => {
      if (result.success) {
        this.load();
      }
    });
  }

  public goToPageNumber() {
    this.nextPage = Math.round(this.nextPage);
    if (this.nextPage <= 0) {
      this.nextPage = 1;
    }
    if (Math.round(this.nextPage) > this.pagination.numPages) {
      this.nextPage = this.pagination.numPages;
    }
    this.pagination.currentPage = this.nextPage;
    this.load();
  }
}
