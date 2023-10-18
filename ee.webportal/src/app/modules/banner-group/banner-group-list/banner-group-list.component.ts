import {Component} from '@angular/core';
import {AbstractCRUDComponent, AbstractModalComponent,} from '../../../core/crud';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {TitleService} from '../../../core/services';
import {BannerGroupMeta} from '../banner-group.meta';
import {BannerGroupService} from '../banner-group.service';
import {BannerGroupCreateComponent} from '../banner-group-create/banner-group-create.component';
import {BannerGroupEditComponent} from '../banner-group-edit/banner-group-edit.component';
import {ObjectUtil} from '../../../core/utils';
import {ModalResult} from '../../../core/common';
import {BannerService} from '../../banner/banner.service';
import {BannerMeta} from '../../banner/banner.meta';
import {BannerCreateComponent} from '../../banner/banner-create/banner-create.component';
import {BannerEditComponent} from '../../banner/banner-edit/banner-edit.component';

@Component({
  selector: 'app-banner',
  templateUrl: './banner-group-list.component.html',
  styleUrls: ['./banner-group-list.component.css'],
  providers: [BannerGroupService, BannerService]
})
export class BannerGroupListComponent extends AbstractCRUDComponent<BannerGroupMeta> {

  onInit(): void {
    this.loadAll();
  }

  loadAll() {
    const params: any = ObjectUtil.combineValue({}, ObjectUtil.ignoreNullValue(this.searchForm.value));
    this.service.loadByParams(params).subscribe((res: BannerGroupMeta[]) => {
        this.list = res;
      }, () => {
        this.list = [];
      }
    );
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Quản lý nhóm banner';
  }

  getCreateModalComponent(): any {
    return BannerGroupCreateComponent;
  }

  getEditModalComponent(): any {
    return BannerGroupEditComponent;
  }

  getCreateModalComponentOptions(): ModalOptions {
    return null;
  }

  getEditModalComponentOptions(): ModalOptions {
    return null;
  }

  buildSearchForm(): FormGroup {
    return this.formBuilder.group({
      search: new FormControl(null),
    });
  }

  initNewModel(): BannerGroupMeta {
    return new BannerGroupMeta();
  }

  constructor(
    service: BannerGroupService,
    modal: BsModalService,
    title: TitleService,
    builder: FormBuilder,
    private bannerService: BannerService,
  ) {
    super(service, modal, builder);
  }

  open_update(key: BannerMeta, index: number, j: number) {
    const order = prompt('Nhập thay đổi ưu tiên:', key.order.toString());
    if (order == null || order === '') {
    } else {
      key.order = +order;
      this.bannerService.updatePriority(key).subscribe(() => {
        this.list[index].banners[j].order = +order;
        this.service.toastSuccessfully('Thay đổi ưu tiên', 'Thành công');
      }, () => this.service.toastFailedDeleted());
    }
  }

  createBanner(item: BannerGroupMeta) {
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, this.getCreateModalComponentOptions());
    const modalRef = this.modalService.show(BannerCreateComponent, config);
    const modal: AbstractModalComponent<BannerMeta> = <AbstractModalComponent<BannerMeta>>modalRef.content;
    const banner = new BannerMeta();
    banner.group = item;
    banner.group_id = item.id;
    modal.setModel(banner);
    const sub = modal.onHidden.subscribe((result: ModalResult<BannerMeta>) => {
      if (result.success) {
        this.load();
      }
      sub.unsubscribe();
    });
  }

  editBanner(item: BannerMeta) {
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, this.getEditModalComponentOptions());
    const modalRef = this.modalService.show(BannerEditComponent, config);
    const modal: AbstractModalComponent<BannerMeta> = <AbstractModalComponent<BannerMeta>>modalRef.content;
    modal.setModel(ObjectUtil.clone(item));
    const sub = modal.onHidden.subscribe((result: ModalResult<BannerMeta>) => {
      if (result.success) {
        this.load();
      }
      sub.unsubscribe();
    });
  }

  removeBanner(item: BannerMeta) {
    this.bannerService.destroy(item['id']).subscribe(() => {
      this.load();
      this.service.toastSuccessfullyDeleted();
    }, () => this.service.toastFailedDeleted());

  }

  upOrder(item: BannerMeta, i: number) {
    this.bannerService.up(item.id).subscribe(res => {
      this.service.toastSuccessfully('Tăng thứ tự');
      this.load();
    }, () => this.service.toastFailedEdited());
  }

  downOrder(item: BannerMeta, i: number) {
    this.bannerService.down(item.id).subscribe(res => {
      this.service.toastSuccessfully('Giảm thứ tự');
      this.load();
    }, () => this.service.toastFailedEdited());
  }

  public goToPageNumber() {
    if (this.nextPage <= 0) {
      this.nextPage = 1;
    }
    if (this.nextPage > this.pagination.numPages) {
      this.nextPage = this.pagination.numPages;
    }
    this.pagination.currentPage = this.nextPage;
    this.load();
  }
}
