import {Component} from '@angular/core';
import {AbstractCRUDComponent} from '../../../core/crud';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {TitleService} from '../../../core/services';
import {VoucherMeta} from '../voucher.meta';
import {VoucherService} from '../voucher.service';
import {VoucherCreateComponent} from '../voucher-create/voucher-create.component';
import {VoucherEditComponent} from '../voucher-edit/voucher-edit.component';
import {FieldForm} from '../../../core/common';
import {ArticleCommentMeta} from '../../article-comment/article-comment.meta';

@Component({
  selector: 'app-voucher-list',
  templateUrl: './voucher-list.component.html',
  styleUrls: ['./voucher-list.component.css'],
  providers: [VoucherService]
})
export class VoucherListComponent extends AbstractCRUDComponent<VoucherMeta> {

  onInit(): void {
    this.load();
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Quản lý voucher';
  }

  getCreateModalComponent(): any {
    return VoucherCreateComponent;
  }

  getEditModalComponent(): any {
    return VoucherEditComponent;
  }

  getCreateModalComponentOptions(): ModalOptions {
    return {'class': 'modal-huge'};
  }

  getEditModalComponentOptions(): ModalOptions {
    return {'class': 'modal-huge'};
  }

  buildSearchForm(): FormGroup {
    return this.formBuilder.group({
      search: new FormControl(null),
      published: new FormControl(''),
    });
  }

  public initSearchForm(): FieldForm[] {
    return [
      {
        label: 'Tìm kiếm theo tên trang',
        type: 'input',
        typeof: 'text',
        formControl: 'search',
        placeHolder: 'Tìm kiếm theo tên trang',
      },
      {
        label: 'Tìm kiếm theo trạng thái',
        type: 'select',
        typeof: 'text',
        formControl: 'published',
        placeHolder: 'Từ khóa',
        data: [
          {
            id: '',
            name: 'Tất cả'
          },
          {
            id: 1,
            name: 'Hoạt động'
          },
          {
            id: 0,
            name: 'Không hoạt động'
          },
        ]
      },
    ];
  }

  initNewModel(): VoucherMeta {
    return new VoucherMeta();
  }

  constructor(
    service: VoucherService,
    modal: BsModalService,
    title: TitleService,
    builder: FormBuilder,
  ) {
    super(service, modal, builder);
  }


  onPublishedChange(item: VoucherMeta, index: number, enable: boolean) {
    let methodAsync = null;
    let titleMsg = 'Hoạt động';
    if (enable) {
      methodAsync = this.service.enable(item.id);
    } else {
      methodAsync = this.service.disable(item.id);
      titleMsg = 'Không hoạt động';
    }

    methodAsync.subscribe((res: ArticleCommentMeta) => {
      this.list[index].enable = res.published;
      this.service.toastSuccessfully(titleMsg);
    }, () => this.service.toastFailed(titleMsg));
  }
}
