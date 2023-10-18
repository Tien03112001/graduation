import {Component} from '@angular/core';
import {AbstractCRUDComponent} from '../../../core/crud';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {TitleService} from '../../../core/services';
import {WidgetMeta} from '../widget.meta';
import {WidgetCreateComponent} from '../widget-create/widget-create.component';
import {WidgetEditComponent} from '../widget-edit/widget-edit.component';
import {WidgetService} from '../widget.service';
import {SettingService} from '../../setting/setting.service';

@Component({
  selector: 'app-block-type',
  templateUrl: './widget-list.component.html',
  styleUrls: ['./widget-list.component.css'],
  providers: [WidgetService, SettingService]
})
export class WidgetListComponent extends AbstractCRUDComponent<WidgetMeta> {

  onInit(): void {
    this.load();
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Quản lý widget';
  }

  getCreateModalComponent(): any {
    return WidgetCreateComponent;
  }

  getEditModalComponent(): any {
    return WidgetEditComponent;
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
    });
  }

  initNewModel(): WidgetMeta {
    return new WidgetMeta();
  }

  clearCache() {
    this.settingService.clearCachePage().subscribe(() => {
        this.service.toastSuccess('Xóa cache page', 'Thành công');
      }, () => {
        this.service.toastFailed('Xóa cache page', 'Thất bại');
      }
    );
  }

  copy(containerid) {
    const el = document.createElement('textarea');
    el.value = '<div class="widget" id="' + containerid + '"></div>';
    document.body.appendChild(el);
    el.select();
    document.execCommand('copy');
    document.body.removeChild(el);
    alert('text copied: ' + el.value);
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

  constructor(
    service: WidgetService,
    modal: BsModalService,
    title: TitleService,
    builder: FormBuilder,
    private settingService: SettingService
  ) {
    super(service, modal, builder);
  }

}
