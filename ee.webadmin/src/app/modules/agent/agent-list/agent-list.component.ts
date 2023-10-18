import {Component} from '@angular/core';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import { AgentMeta } from '../agent.meta';
import {AgentService} from '../agent.service';
import {AgentCreateComponent} from '../agent-create/agent-create.component';
import {AgentEditComponent} from '../agent-edit/agent-edit.component';
import { AbstractCRUDComponent } from '../../../core/crud';
import { FieldForm } from '../../../core/common';
import { TitleService } from '../../../core/services';

@Component({
  selector: 'app-agent-list',
  templateUrl: './agent-list.component.html',
  styleUrls: ['./agent-list.component.css'],
  providers: [AgentService]
})
export class AgentListComponent extends AbstractCRUDComponent<AgentMeta> {

  onInit(): void {
    this.load();
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Quản lý tư vấn viên';
  }

  getCreateModalComponent(): any {
    return AgentCreateComponent;
  }

  getEditModalComponent(): any {
    return AgentEditComponent;
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
    ]
  }

  initNewModel(): AgentMeta {
    return new AgentMeta();
  }

  constructor(
    service: AgentService,
    modal: BsModalService,
    title: TitleService,
    builder: FormBuilder,
  ) {
    super(service, modal, builder,);
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
