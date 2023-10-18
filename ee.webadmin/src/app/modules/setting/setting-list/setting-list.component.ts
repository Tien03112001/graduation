import {Component} from '@angular/core';
import {AbstractCRUDComponent} from '../../../core/crud';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {TitleService} from '../../../core/services';
import {SettingMeta} from '../setting.meta';
import {SettingService} from '../setting.service';
import {SettingCreateComponent} from '../setting-create/setting-create.component';
import {SettingEditComponent} from '../setting-edit/setting-edit.component';

@Component({
  selector: 'app-setting',
  templateUrl: './setting-list.component.html',
  styleUrls: ['./setting-list.component.css'],
  providers: [SettingService]
})
export class SettingListComponent extends AbstractCRUDComponent<SettingMeta> {

  onInit(): void {
    this.load();
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Quản lý cấu hình chung';
  }

  getCreateModalComponent(): any {
    return SettingCreateComponent;
  }

  getEditModalComponent(): any {
    return SettingEditComponent;
  }

  getCreateModalComponentOptions(): ModalOptions {
    return {'class': 'modal-lg'};
  }

  getEditModalComponentOptions(): ModalOptions {
    return {'class': 'modal-lg'};
  }

  buildSearchForm(): FormGroup {
    return this.formBuilder.group({
      search: new FormControl(null),
    });
  }

  initNewModel(): SettingMeta {
    return new SettingMeta();
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

  clearCache() {
    this.settingService.clearCachePage().subscribe(() => {
        this.service.toastSuccess('Xóa cache page', 'Thành công');
      }, () => {
        this.service.toastFailed('Xóa cache page', 'Thất bại');
      }
    );
  }

  constructor(
    service: SettingService,
    modal: BsModalService,
    title: TitleService,
    builder: FormBuilder,
    private settingService: SettingService
  ) {
    super(service, modal, builder);
  }

}
