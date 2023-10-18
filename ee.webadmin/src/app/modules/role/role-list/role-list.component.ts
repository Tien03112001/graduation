import {Component} from '@angular/core';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {RoleMeta} from '../role.meta';
import {RoleService} from '../role.service';
import {RoleCreateComponent} from '../role-create/role-create.component';
import {RoleEditComponent} from '../role-edit/role-edit.component';
import { AbstractCRUDComponent, AbstractModalComponent } from '../../../core/crud';
import { FieldForm, ModalResult } from '../../../core/common';
import { TitleService } from '../../../core/services';
import { ObjectUtil } from '../../../core/utils';

@Component({
  selector: 'app-role-list',
  templateUrl: './role-list.component.html',
  styleUrls: ['./role-list.component.css'],
  providers: [RoleService]
})
export class RoleListComponent extends AbstractCRUDComponent<RoleMeta> {

  onInit(): void {
    this.load();
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Quản lý role';
  }

  getCreateModalComponent(): any {
    return RoleCreateComponent;
  }

  getEditModalComponent(): any {
    return RoleEditComponent;
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

  initSearchForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tìm kiếm theo tên', 'search', 'Nhập từ khóa')
    ];
  }

  initNewModel(): RoleMeta {
    return new RoleMeta();
  }

  constructor(
    service: RoleService,
    modal: BsModalService,
    title: TitleService,
    builder: FormBuilder,
  ) {
    super(service, modal, builder,);
  }

  public createRole() {
    let modalOptions = Object.assign(this.defaultModalOptions(), this.getCreateModalComponentOptions());
    const config = ObjectUtil.combineValue({ ignoreBackdropClick: true }, modalOptions);
    const modalRef = this.modalService.show(this.getCreateModalComponent(), config);
    let modal: AbstractModalComponent<RoleMeta> = <AbstractModalComponent<RoleMeta>>modalRef.content;
    modal.setModel(this.initNewModel());
    let sub = modal.onHidden.subscribe((result: ModalResult<RoleMeta>) => {
      if (result.success) {
        this.load();
      }
    });
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
