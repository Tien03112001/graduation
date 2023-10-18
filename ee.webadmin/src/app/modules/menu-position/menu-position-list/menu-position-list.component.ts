import {Component} from '@angular/core';
import {AbstractCRUDComponent, AbstractModalComponent} from '../../../core/crud';
import {ModalResult} from '../../../core/common';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {TitleService} from '../../../core/services';
import {MenuPositionMeta} from '../menu-position.meta';
import {MenuPositionCreateComponent} from '../menu-position-create/menu-position-create.component';
import {MenuPositionEditComponent} from '../menu-position-edit/menu-position-edit.component';
import {MenuPositionService} from '../menu-position.service';
import {ObjectUtil} from '../../../core/utils';
import {MenuMeta} from '../../menu/menu.meta';
import {MenuCreateComponent} from '../../menu/menu-create/menu-create.component';
import {MenuService} from '../../menu/menu.service';
import {MenuEditComponent} from '../../menu/menu-edit/menu-edit.component';

@Component({
  selector: 'app-menu-position',
  templateUrl: './menu-position-list.component.html',
  styleUrls: ['./menu-position-list.component.css'],
  providers: [MenuPositionService, MenuService]
})
export class MenuPositionListComponent extends AbstractCRUDComponent<MenuPositionMeta> {
  cache_data: any = null;

  onInit(): void {
    this.load();
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Quản lý menu';
  }

  getCreateModalComponent(): any {

    return MenuPositionCreateComponent;
  }

  getEditModalComponent(): any {
    return MenuPositionEditComponent;
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

  initNewModel(): MenuPositionMeta {
    return new MenuPositionMeta();
  }

  upOrderMenu(item: MenuMeta, i: number) {
    (<MenuService>this.menuService).up(item.id).subscribe(res => {
      this.service.toastSuccessfully('Tăng thứ tự');
      this.load();
    }, () => this.service.toastFailedEdited());
  }

  downOrderMenu(item: MenuMeta, i: number) {
    (<MenuService>this.menuService).down(item.id).subscribe(res => {
      this.service.toastSuccessfully('Giảm thứ tự');
      this.load();
    }, () => this.service.toastFailedEdited());
  }

  public createMenu(item: MenuPositionMeta, index: number) {
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, MenuCreateComponent);
    const modalRef = this.modalService.show(MenuCreateComponent, config);
    const modal: AbstractModalComponent<MenuMeta> = <AbstractModalComponent<MenuMeta>>modalRef.content;
    const menuMeta = new MenuMeta();
    menuMeta.menu_position_id = item.id;
    menuMeta.parent_id = 0;
    modal.setModel(menuMeta);
    const sub = modal.onHidden.subscribe((result: ModalResult<any>) => {
      if (result.success) {
        const itemCreated: MenuMeta = result.data;
        this.list[index].menus.unshift(itemCreated);
      }
      sub.unsubscribe();
    });
  }

  editMenu(item: MenuMeta, i: number, j: number) {
    const modalRef = this.modalService.show(MenuEditComponent, {ignoreBackdropClick: true});
    const modal: AbstractModalComponent<MenuMeta> = <AbstractModalComponent<MenuMeta>>modalRef.content;
    modal.setModel(ObjectUtil.clone(item));
    const sub = modal.onHidden.subscribe((result: ModalResult<MenuMeta>) => {
      if (result.success) {
        const itemUpdated: MenuMeta = result.data;
        this.list[i].menus.splice(j, 1, itemUpdated);
      }
      sub.unsubscribe();
    });
  }

  editOrder(item: MenuMeta, i: number, j: number) {
    const priority = prompt('Nhập thay đổi vị trí:', item.order.toString());
    if (priority == null || priority === '') {
    } else {
      item.order = +priority;
      this.menuService.update(item).subscribe(() => {
        this.list[i].menus[j].order = +priority;
        this.service.toastSuccessfully('Thay đổi vị trí', 'Thành công');
      }, () => this.service.toastFailedDeleted());
    }
  }

  removeMenu(item: MenuMeta, i: number, j: number) {
    this.menuService.destroy(item['id']).subscribe(() => {
      this.list[i].menus.splice(j, 1);
      this.service.toastSuccessfullyDeleted();
    }, () => this.service.toastFailedDeleted());
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
    service: MenuPositionService,
    modal: BsModalService,
    title: TitleService,
    builder: FormBuilder,
    private menuService: MenuService
  ) {
    super(service, modal, builder);
  }

}
