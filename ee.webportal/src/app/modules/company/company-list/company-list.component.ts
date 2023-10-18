import {Component} from '@angular/core';
import {AbstractCRUDComponent} from '../../../core/crud';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {TitleService} from '../../../core/services';
import {CompanyMeta} from '../company.meta';
import {CompanyService} from '../company.service';
import {CompanyCreateComponent} from '../company-create/company-create.component';
import {CompanyEditComponent} from '../company-edit/company-edit.component';
import {ToasterService} from 'angular2-toaster';

@Component({
  selector: 'app-sale',
  templateUrl: './company-list.component.html',
  styleUrls: ['./company-list.component.css'],
  providers: [CompanyService]
})
export class CompanyListComponent extends AbstractCRUDComponent<CompanyMeta> {

  onInit(): void {
    this.load();
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Quản lý thông tin công ty';
  }

  getCreateModalComponent(): any {
    return CompanyCreateComponent;
  }

  getEditModalComponent(): any {
    return CompanyEditComponent;
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


  initNewModel(): CompanyMeta {
    return new CompanyMeta();
  }

  constructor(
    service: CompanyService,
    modal: BsModalService,
    title: TitleService,
    builder: FormBuilder,
    private toast: ToasterService,
  ) {
    super(service, modal, builder);
  }

}
